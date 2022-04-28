<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\UpdateMyLocationRequest;
use App\Models\Order;
use App\Models\User;
use App\Notifications\OrderAcceptedByDriver;
use App\Notifications\ChangeStatusOrder;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\AttachmentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;
use App\Http\Requests\DeliveryOrderRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DriverOrderController extends Controller
{
    //
    private OrderRepositoryInterface $orderRepository;
    private AttachmentRepositoryInterface $attachmentRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        AttachmentRepositoryInterface $attachmentRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->attachmentRepository = $attachmentRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $filters['driver_id'] = auth()->user()->id;
        $filters['driver_archived'] = false;
        if ($request->status) {
            $filter['status'] = $request->status;
        } else {
            $filter['status'] = [2,3,4,5];
        }
        $range = $request->get('range', false);
        $orders = $this->orderRepository->get($filters, 'sent_at', $range);

        return response()->json([
            'orders' => $orders,
        ]);
    }
    public function newOrder(Request $request): JsonResponse
    {
        $orders = $this->orderRepository->driverNewOrders(
            [
                ['status','=',1],
            ],
            'sent_at'
        );
        return response()->json([
            'orders' => $orders,
        ]);
    }

    public function acceptOrder(Request $request, Order $order): JsonResponse
    {
        Auth::user()->update(["last_activity" => Carbon::now()]);
        if ($order->status == 1) {
            if (auth()->user()->driver_type == 'freelancer') {
                $value = floor($order->delivery_price * (settings('driver_percentage_taken_from_delivery') / 100));
            } else {
                $value = $order->delivery_price;
            }

            if ($value > auth()->user()->balance && auth()->user()->driver_type == 'freelancer') {
                return response()->json(['message' => Lang::get('messages.driver_order.not_enough_balance')], 400);
            }
             $check = Order::where('driver_id', auth()->user()->id)->whereIn('status', [2,3])->first();
            if ($check) {
                return response()->json(['message' => Lang::get('messages.driver_order.have_order')], 400);
            }

            $order->driver_id = auth()->user()->id;
            $order->status = 2;
            $order->driver_fee = $value;
            $order->accepted_by_driver_at = Carbon::now();
            $order->save();

            if (auth()->user()->driver_type == 'freelancer') {
                $driver = User::findOrFail(auth()->user()->id);
                $driver->balance = $driver->balance - ($value);
                throw_if($driver->balance < 0, NotEnoughBalanceException::class);
                $driver->reserved_balance = $driver->reserved_balance + ($value);
                $driver->save();
            }
            if ($order->store->owner->tokens->count()) {
                $order->store->owner->notify(new OrderAcceptedByDriver($order, $driver));
            }
            return response()->json(['message' => Lang::get('messages.driver_order.accepted_success')], 200);
        } elseif ($order->driver_id != auth()->user()->id) {
            return response()->json(['message' => Lang::get('messages.driver_order.already_accepted')], 403);
        }
        return response()->json(['message' => Lang::get('messages.error.error')], 401);
    }

    public function deliveryOrder(DeliveryOrderRequest $request, Order $order): JsonResponse
    {
        if ($order->status == 2 && $order->driver_id == auth()->user()->id) {
            if ($request['qr_code'] == $order->qr_code) {
                $order->status = 3;
                $order->order_taken_from_store_at = Carbon::now();
                $order->save();
                $driver = User::find(auth()->user()->id);
                if ($order->store->owner->tokens->count()) {
                    $order->store->owner->notify(new ChangeStatusOrder($order, $driver));
                }
                Auth::user()->update(["last_activity" => Carbon::now()]);
                return response()->json(['message' => Lang::get('messages.driver_order.taken_success')], 200);
            } else {
                return response()->json(['message' => Lang::get('messages.driver_order.qr_code_wrong')], 400);
            }
        }
        return response()->json(['message' => Lang::get('messages.error.error')], 401);
    }

    public function deliveredOrder(UpdateMyLocationRequest $request, Order $order): JsonResponse
    {
        $data = $request->validated();
        if ($order->status == 3  && $order->driver_id == auth()->user()->id) {
            $distance = getDistanceBetween($data["lat"], $data["lng"], $order->customer_lat, $order->customer_lng);
            $radius = (int)settings('radius_of_deliver');
            if ($distance > $radius) {
                return response()->json(
                    ['message' => Lang::get('messages.driver_order.not_in_range'),"distance" => $distance],
                    400
                );
            }
            $order->status = 4;
            $order->order_delivered_at = Carbon::now();
            $order->save();
            $driver = User::find(auth()->user()->id);
            if ($order->store->owner->tokens->count()) {
                $order->store->owner->notify(new ChangeStatusOrder($order, $driver));
            }
            return response()->json(['message' => Lang::get('messages.driver_order.delivered_success')
                ,"distance" => $distance]);
        }
        return response()->json(['message' => Lang::get('messages.error.error')], 401);
    }

    public function show(Order $order): JsonResponse
    {
        if ($order->driver_id == auth()->user()->id) {
            $order = Order::find($order->id);
            $order->store = $order->store;
            $order->orderStatus = $order->orderStatus;
            $order->customer_payed = $order->order_price - $order->delivery_fee_payed_by_store ;
            return response()->json(['order' => $order]);
        }
        return response()->json(['message' => Lang::get('messages.error.error')], 401);
    }
    public function data(request $request): JsonResponse
    {
        $user = User::where('id', Auth::id())->with('town', 'newPapers')->first();
        $user['personal_photo'] = $this->getUrlPhoto(auth()->user(), 'personal_photo');
        $user['license_photo'] = $this->getUrlPhoto(auth()->user(), 'license_photo');
        $user['vehicle_photo'] = $this->getUrlPhoto(auth()->user(), 'vehicle_photo');
        $user['vehicle_license_photo'] = $this->getUrlPhoto(auth()->user(), 'vehicle_license_photo');
        $user['is_verified'] = $user->mobile_verified_at == null ? false : true ;
        return response()->json([
        'user' => $user,
        ]);
    }

    public function statistics(request $request)
    {
        return Cache::remember(
            'orders_api_statistics.' . auth()->user()->id,
            now()->addHour(),
            fn() => $this->getStatistics()
        );
    }

    public function getStatistics()
    {
        $filter['status'] = [1];
        $data['Pending'] = $this->orderRepository->count($filter);
        $data['On_the_way'] = $this->statusCountOrder(2);
        $data['Delivering'] = $this->statusCountOrder(3);
        $data['Delivered'] = $this->statusCountOrder(4);
        $data['Canceled'] = $this->statusCountOrder(5);
        return response()->json([
            'data' => $data,
            ]);
    }
    public function getUrlPhoto($user, $type)
    {
        $photo = $this->attachmentRepository->get([
            ['description', '=', $type],
            ['fileable_id', '=',$user->id ],
        ]);
        return $photo ?  $photo->path : "";
    }

    public function statusCountOrder($no)
    {
        $filter['status'] = [$no];
        $filter['driver_id'] = auth()->user()->id;
        return  $this->orderRepository->count($filter);
    }
}
