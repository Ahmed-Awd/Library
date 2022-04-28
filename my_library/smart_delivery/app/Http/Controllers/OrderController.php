<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeDriverRequest;
use App\Jobs\ChangeDriverOrder;
use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use App\Notifications\NewOrder;
use App\Notifications\OrderForDriver;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\RateOrderRequest;
use App\Http\Requests\GetOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\OrderService;
use App\Models\OrderStatus;
use Illuminate\Support\Facades\Lang;

class OrderController extends Controller
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }


    public function index(GetOrderRequest $request, Store $store)
    {
        $store_id = $store->id ?? auth()->user()->store->id;
        $range = $request->get('range', false);
        $OrderStatus = OrderStatus::all();
        $filter['status'] = explode('-', $request->status ?? '1-2-3-4-5');

        $filter['store_id'] = $store_id ;
        $orders = $this->orderRepository->get($filter, 'sent_at', $range);
        $filter2['status'] = [6];
        $filter2['store_id'] = $store_id ;
        $suspondedCount = $this->orderRepository->count($filter2);
        return Inertia::render('Orders/Index', compact('orders', 'suspondedCount', 'OrderStatus', 'store_id'));
    }

    public function all(GetOrderRequest $request)
    {
        $OrderStatus = OrderStatus::all();
        $range = $request->get('range', false);
        $filter['status'] = explode('-', $request->status ?? '1-2-3-4-5');
        $orders = $this->orderRepository->get($filter, 'sent_at', $range);
        $sumOfIncome    = $this->orderRepository->getTotalFee($filter, $range)[0]->smart_total_income;
        $filter2['status'] = [6];
        $suspendedCount = $this->orderRepository->count($filter2);
        return Inertia::render('Orders/All', compact('orders', 'sumOfIncome', 'suspendedCount', 'OrderStatus'));
    }

    public function create(Store $store)
    {
        $store_id = $store->id;
        $default_delivery_time = now()->addMinutes($store->default_delivery_time)->format('Y-m-d H:i:s');
        return Inertia::render('Orders/Create', compact('store_id', 'default_delivery_time'));
    }

    public function store(StoreOrderRequest $request, ?Store $store = null)
    {
        $validated = $request->validated();

        $store = $store ?? auth()->user()->store;

        $response = resolve(OrderService::class)->createOrder($store, $validated);

        session()->flash('flash.banner', $response["message"]);

        if ($response['code'] == 200) {
            session()->flash('flash.bannerStyle', 'success');
        } else {
            session()->flash('flash.bannerStyle', 'danger');
        }

        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('stores.orders.index', $store->id);
        }
        return redirect()->route('orders.index');
    }

    public function show(Order $order)
    {
        $order->load(['orderStatus', 'driver','store:id,name,lat,lng']);
        $order->makeVisible(['qr_code']);
        $order->customer_payed = $order->order_price - $order->delivery_fee_payed_by_store;
        return Inertia::render('Orders/Show', compact('order'));
    }

    public function edit(Order $order)
    {
        return Inertia::render('Orders/Edit', compact('order'));
    }

    public function showSerial($serial)
    {
        [$storeId, $orderNumber] = explode(',', $serial);

        $orderId = Order::where('store_id', $storeId)->where('order_number', $orderNumber)->firstOrFail()->id;

        return redirect()->route('orders.show', [$orderId]);
    }

    public function rate(RateOrderRequest $request, Order $order)
    {
        $validated = $request->validated();
        $this->orderRepository->rate($order, $validated);

        session()->flash('flash.banner', "order $order->customer_name rated successfully");
        session()->flash('flash.bannerStyle', 'success');

        return back();
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {

                $validated = $request->validated();
                $validated['customer_mobile'] = str_replace(' ', '', $validated['customer_mobile']);
                $validated['customer_mobile'] = str_replace('+', '', $validated['customer_mobile']);
                $response = resolve(OrderService::class)->updateOrder($order, $validated);
                session()->flash('flash.banner', $response["message"]);
        if ($response['code'] == 200) {
            session()->flash('flash.bannerStyle', 'success');
        } else {
            session()->flash('flash.bannerStyle', 'danger');
        }
                return back();
    }

    public function reorder(Order $order)
    {
        $response = resolve(OrderService::class)->reorder($order);

        session()->flash('flash.banner', $response["message"]);

        if ($response['code'] == 200) {
            session()->flash('flash.bannerStyle', 'success');
        } else {
            session()->flash('flash.bannerStyle', 'danger');
        }
        return back();
    }

    public function cancel(Order $order)
    {
        $response = resolve(OrderService::class)->cancel($order);

        session()->flash('flash.banner', $response["message"]);

        if ($response['code'] == 200) {
            session()->flash('flash.bannerStyle', 'success');
        } else {
            session()->flash('flash.bannerStyle', 'danger');
        }
        return back();
    }

    public function destroy(Order $order)
    {
        $this->orderRepository->delete($order);
        session()->flash('flash.banner', Lang::get('messages.record.delete'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('stores.orders.index', $order->store_id);
    }

    public function currentActive()
    {
        $user = Auth::user();
        $user->hasRole('admin') ? $type = "admin" : $type = "store";
        if ($type == "store") {
            $filter['store_id'] = $user->store->id;
        }
        $filter['status'] = ["2","3"];
        $OrderStatus = OrderStatus::all();
        $orders = $this->orderRepository->get($filter, 'sent_at', false);
        return Inertia::render('Orders/Active', compact('orders', 'OrderStatus'));
    }

    public function traceOrder(Order $order)
    {
        if (!in_array($order->status, ["2","3"])) {
            session()->flash('flash.banner', "you can not trance this order");
            session()->flash('flash.bannerStyle', 'danger');
            return back();
        }
        $current = $this->orderRepository->show($order->id);
        return Inertia::render('Orders/Trace', compact('current'));
    }

    public function changeDriver(ChangeDriverRequest $request, Order $order)
    {
        $data = $request->validated();
        if (in_array($order->status, ["1","2","3"])) {
            $user = User::findOrFail($data["driver_id"]);
            $order->update(["driver_id" => $data["driver_id"],"status" => 2]);
            $user->notify(new OrderForDriver($order));
            return response()->json(['message' => Lang::get('messages.process.update')]);
        }
        return response()->json(['message' => Lang::get('messages.error.error')], 400);
    }
}
