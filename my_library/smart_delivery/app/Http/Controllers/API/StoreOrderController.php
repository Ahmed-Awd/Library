<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\User;
use App\Repositories\OrderRepositoryInterface;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\RateOrderRequest;
use App\Http\Requests\GetOrderRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;

class StoreOrderController extends Controller
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $store = auth()->user()->store ? auth()->user()->store : null;
        $range = $request->get('range', false);

        if ($store != null) {
            if ($request->status) {
                $filter['status'] = $request->status;
            } else {
                $filter['status'] = [1,2,3,4,5];
            }
            $filter['store_id']       = $store->id;
            $filter['owner_archived'] = false;
            $orders = $this->orderRepository->get($filter, 'created_at', $range);
            $orders->data = $orders->makeHidden(['order_status','orderStatus']);
            return response()->json([
            'orders' => $orders,
            ]);
        }
        return response()->json(['message' => Lang::get('messages.store_order.no_store')], 401);
    }

    public function indexSusponded(Request $request): JsonResponse
    {
        $store = auth()->user()->store ? auth()->user()->store : null;
        if ($store != null) {
            $orders = $this->orderRepository->get(
                [
                    ['status','=',6],
                    ['store_id' , $store->id],
                    ['owner_archived' , false]
                ],
                'created_at'
            );
            $orders->data = $orders->makeHidden(['order_status','orderStatus']);
            return response()->json([
            'orders' => $orders,
            ]);
        }
        return response()->json(['message' => Lang::get('messages.store_order.no_store')], 401);
    }


    public function store(StoreOrderRequest $request): JsonResponse
    {
        $store = auth()->user()->store ? auth()->user()->store : null;
        if ($store != null) {
            $validated = $request->validated();
            $validated['customer_mobile'] = str_replace(' ', '', $validated['customer_mobile']);
            $validated['customer_mobile'] = str_replace('+', '', $validated['customer_mobile']);
            $response = resolve(OrderService::class)->createOrder($store, $validated);
            return response()->json(['message' => $response['message']], $response['code']);
        }
        return response()->json(['message' => Lang::get('messages.store_order.no_store')], 401);
    }

    public function estimate(StoreOrderRequest $request)
    {
        $store = auth()->user()->store ? auth()->user()->store : null;
        if ($store != null) {
            $validated = $request->validated();
            $prices = resolve(OrderService::class)->estimate($store, $validated);
            if ($prices['code'] == 422) {
                return response()->json(['message' => $prices['message']], 422);
            }
            return response()->json($prices);
        }
        return response()->json(['message' => Lang::get('messages.store_order.no_store')], 401);
    }

    public function show(Order $order): JsonResponse
    {
        $store = auth()->user()->store ? auth()->user()->store : null;
        if ($store != null) {
            if ($order->store_id == $store->id) {
                if ($order->status > 1) {
                    $order->driver = $order->driver;
                    $order->driver->phone = "";
                }
                $order->makeHidden(['order_status'])->makeVisible('qr_code');
                $order->customer_payed = $order->order_price - $order->delivery_fee_payed_by_store ;
                return response()->json(['order' => $order]);
            }
            return response()->json(['message' => Lang::get('messages.store_order.no_order')], 401);
        }
        return response()->json(['message' => Lang::get('messages.store_order.no_store')], 401);
    }

    public function rate(RateOrderRequest $request, Order $order): JsonResponse
    {
        $store = auth()->user()->store ? auth()->user()->store : null;
        if ($store != null) {
            if ($order->store_id == $store->id) {
                $validated = $request->validated();
                $this->orderRepository->rate($order, $validated);
                return response()->json([
                'message' => Lang::get('messages.store_order.rated', ['id' => $order->id]),
                ]);
            }
            return response()->json(['message' => Lang::get('messages.store_order.no_order')], 401);
        }
        return response()->json(['message' => Lang::get('messages.store_order.no_store')], 401);
    }

    public function update(UpdateOrderRequest $request,Order $order)
    {
        $store = auth()->user()->store ? auth()->user()->store : null;//now
        if ($store != null) {
            if ($order->store_id == $store->id) {
                $validated = $request->validated();
                if(isset($validated['customer_mobile']))
                {
                    $validated['customer_mobile'] = str_replace(' ', '', $validated['customer_mobile']);
                    $validated['customer_mobile'] = str_replace('+', '', $validated['customer_mobile']);

                }
                 $response = resolve(OrderService::class)->updateOrder($order,$validated);
                return response()->json(['message' => $response['message']], $response['code']);
            }
            return response()->json(['message' => Lang::get('messages.store_order.no_order')], 401);
        }
        return response()->json(['message' => Lang::get('messages.store_order.no_store')], 401);
    }
    public function reorder(Order $order)
    {
        $store = auth()->user()->store ? auth()->user()->store : null;//now
        if ($store != null) {
            if ($order->store_id == $store->id) {
                $response = resolve(OrderService::class)->reorder($order);
                return response()->json(['message' => $response['message']], $response['code']);
            }
            return response()->json(['message' => Lang::get('messages.store_order.no_order')], 401);
        }
        return response()->json(['message' => Lang::get('messages.store_order.no_store')], 401);
    }

    public function cancel(Order $order)
    {
        $response = resolve(OrderService::class)->cancel($order);
        return response()->json(['message' => $response['message']], $response['code']);
    }

    public function data(request $request): JsonResponse
    {
        $user = User::where('id', Auth::id())->with('town', 'store', 'store.type')->first();
        $user['is_verified'] = $user->mobile_verified_at == null ? false : true ;
        return response()->json([
        'store' => $user->store,
        'user' => $user,
        ]);
    }
    public function statistics(request $request): JsonResponse
    {
        $store = auth()->user()->store ? auth()->user()->store : null;

        if ($store != null) {
            return Cache::remember(
                'orders_api_statistics.' . auth()->user()->id,
                now()->addHour(),
                fn() => $this->getStatistics($store)
            );
        }
            return response()->json(['message' => Lang::get('messages.store_order.no_store')], 401);
    }

    public function getStatistics($store)
    {
        $data['Pending'] = $this->statusCountOrder($store, 1);
        $data['On_the_way'] = $this->statusCountOrder($store, 2);
        $data['Delivering'] = $this->statusCountOrder($store, 3);
        $data['Delivered'] = $this->statusCountOrder($store, 4);
        $data['Canceled'] = $this->statusCountOrder($store, 5);
        $data['Suspond'] = $this->statusCountOrder($store, 6);

        return response()->json([
            'data' => $data,
            ]);
    }

    public function statusCountOrder($store, $no)
    {
        $filter['status'] = [$no];
        $filter['store_id'] = $store->id;

        return  $this->orderRepository->count($filter);
    }
}
