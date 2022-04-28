<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\assignDriverRequest;
use App\Http\Requests\CancelOrderRequest;
use App\Http\Requests\ChangeOrderStatusRequest;
use App\Http\Requests\RateOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StoreReorderRequest;
use App\Models\Address;
use App\Models\Item;
use App\Models\OptionCategory;
use App\Models\OptionSecondary;
use App\Models\OptionTemplate;
use App\Models\OptionValue;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Notifications\NewOrder;
use App\Notifications\NewOrderDriver;
use App\Notifications\OrderAcceptedByRestaurant;
use App\Notifications\OrderCanceledByRestaurant;
use App\Repositories\DriverRepositoryInterface;
use App\Repositories\OrderItemRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Services\GeoDistance;
use App\Services\OrderService;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class AdminOrderController extends Controller
{

    private OrderRepositoryInterface $orderRepository;
    private OrderItemRepositoryInterface $orderItemRepository;
    private DriverRepositoryInterface $driverRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderItemRepositoryInterface $orderItemRepository,
        DriverRepositoryInterface $driverRepository,
    ) {
        // $this->authorizeResource(Order::class);
        $this->orderRepository = $orderRepository;
        $this->orderItemRepository = $orderItemRepository;
        $this->driverRepository = $driverRepository;
    }


    public function index(Request $request): JsonResponse
    {
        $filters = [];
        $range = $request->get('range', false);
        $orders = $this->orderRepository->get($filters, $range);
        $stats = $this->orderRepository->getStats($filters, $range);
        return response()->json(['orders' => $orders, 'statistics' => $stats], 200);
    }

    public function customerOrders(User $user, Request $request): JsonResponse
    {
        $filters['user_id'] = $user->id;
        $range = $request->get('range', false);
        $orders = $this->orderRepository->get($filters, $range);
        return response()->json(['orders' => $orders], 200);
    }

    public function canceledOrders(Request $request): JsonResponse
    {
        $filters['order_status_id'] = [9];
        $range = $request->get('range', false);
        $orders = $this->orderRepository->getCanceled($filters, $range);
        return response()->json(['orders' => $orders], 200);
    }

    public function cancelOrder(CancelOrderRequest $request, Order $order): JsonResponse
    {
        $validated = $request->validated();
        if ($order->order_status_id == 2) {
            $this->orderRepository->cancelOrder($order, $validated);
            return response()->json(['message' => Lang::get('messages.success')], 200);
        }
        return response()->json(['message' => Lang::get('messages.failed')], 400);
    }
    public function changeOrderToPaid(Request $request, Order $order): JsonResponse
    {
        $status=false; 
        $message= Lang::get('messages.failed');  
                if($order->paid==0)
                {
                    $order->update(["paid" => 1]);
                    if ($order->restaurant->owner->tokens->count()) 
                    {
                        $order->restaurant->owner->notify(new NewOrder($order));
                    }
                    $status=true;
                    $message= Lang::get('messages.success');  

                }

        return response()->json(['status' => $status,'message' =>$message ],200);     
    }
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $response = resolve(OrderService::class)->createOrder($validated);
        return response()->json([
            'message' => $response['message'],
            'order' => $response['order']
        ], $response['code']);
    }

    public function calcOrder(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $data['discount'] = 0;
        $data['coupon_discount']=0;
        $restDiscount=0;
        $restaurant = Restaurant::find($validated['restaurant_id']);
        $data_resault = resolve(OrderService::class)->calculateSubTotal($validated['order_items']);
        $data['sub_total'] = $data_resault['subtotal'];
        $data['discount'] += $data_resault['discount'];
        $data['total_tax'] = $data_resault['total_tax'];

       
        if ($restaurant->currentOffer()->exists()) {
            $offer = $restaurant->currentOffer;
            $restDiscount = $data['sub_total'] * ($offer->rate / 100);
            // $data['discount'] += $restDiscount;
            $data['sub_total'] = $data['sub_total'] - $restDiscount;
        }

        if (isset($validated['discount_code_id'])) {
            $discount = resolve(OrderService::class)
                ->useDiscountCode($data['sub_total'], $restaurant->id, $validated['discount_code_id']);
            if ($discount['code'] != 200) {
                return response()->json(['message' => $discount['message'], 'data' => $data], $discount['code']);
            }
            $numbers = $discount['data'];
            $data['sub_total'] = $numbers['new_price'];
            $data['coupon_discount'] += $numbers['discount'];
        }

        $data['minimum_order_adjustment_amount'] = $restaurant->min_order_price < $data['sub_total']
        ? 0 : ($restaurant->min_order_price - $data['sub_total']);
    $data['total_amount'] = $restaurant->min_order_price < $data['sub_total']
        ? $data['sub_total'] : $restaurant->min_order_price;

        $data['delivery_fee'] = 0;
        if ($validated['service_info_type'] == 1) {
            $address = Address::find($validated['address_id']);
            $res = calcDeliveryFee($restaurant, $address, $data);
            if ($res['code'] == 400) {
                return response()->json(['message' => $res['message'], 'order' => $res['order']], $res['code']);
            }
            $data['delivery_fee'] = $res['value'];
            $data['total_amount'] += $data['delivery_fee'];
        }
        $data['min_order_price'] = $restaurant->min_order_price;
        $data['rest_discount'] = $restDiscount;
        
        return response()->json([
            'data' => $data
        ], 200);
    }

    public function reorder(StoreReorderRequest $request, order $order): JsonResponse
    {
        $validated = $request->validated();
        $response = resolve(OrderService::class)->reorder($validated, $order);
        return response()->json([
            'message' => $response['message'],
            'order' => $response['order']
        ], $response['code']);
    }

    public function show(Order $order): JsonResponse
    {
        $order = $this->orderRepository->show($order);
        $order=order($order);
        return response()->json(['order' => $order], 200);
    }

    public function calcReorderWithOrder(StoreReorderRequest $request, Order $order): JsonResponse
    {
        $restDiscount=0;
        $data['coupon_discount']=0;
        $validated = $request->validated();
        $data['discount'] = 0;
        $restaurant = Restaurant::find($order->restaurant_id);
        if (!$restaurant) {
            return response()->json([
                'message' => 'restaurant is not found',
            ], 400);
        }


        $response = resolve(OrderService::class)
            ->calculateSubTotalForReorder($order->orderItem);
        if ($response['status']) {
            $data_resault = $response['data'];
            $data['sub_total'] = $data_resault['subtotal'];
            $data['discount'] += $data_resault['discount'];
            $data['total_tax'] = $data_resault['total_tax'];
        } else {
            return response()->json([
                'message' => $response['message'],
            ], $response['code']);
        }

        if ($restaurant->currentOffer()->exists()) {
            $offer = $restaurant->currentOffer;
            $restDiscount = $data['sub_total'] * ($offer->rate / 100);
            // $data['discount'] += $restDiscount;
            $data['sub_total'] = $data['sub_total'] - $restDiscount;
        }

        if (isset($request['discount_code_id'])) {
            $discount = resolve(OrderService::class)
                ->useDiscountCode($data['sub_total'], $restaurant->id, $request['discount_code_id']);
            if ($discount['code'] != 200) {
                $response["message"] = $discount['message'];
                $response["code"] = $discount['code'];
                $response["order"] = [];
                return $response;
            }
            $numbers = $discount['data'];
            $data['sub_total'] = $numbers['new_price'];
            $data['coupon_discount'] += $numbers['discount'];
        }

        
        $data['minimum_order_adjustment_amount'] = $restaurant->min_order_price < $data['sub_total']
            ? 0 : ($restaurant->min_order_price - $data['sub_total']);
        $data['total_amount'] = $restaurant->min_order_price < $data['sub_total']
            ? $data['sub_total'] : $restaurant->min_order_price;

        $data['delivery_fee'] = 0;
        if (isset($validated['service_info_type']) && $validated['service_info_type'] == 1) {
            if (isset($request['address_id'])) {
                $address = Address::find($request['address_id']);
            } elseif ($order->service_info_type) {
                $address = $order->address;
            } else {
                return response()->json([
                    'message' => 'must select address',
                ], 400);
            }

            $res = calcDeliveryFee($restaurant, $address, $data);
            if ($res['code'] == 400) {
                return response()->json(['message' => $res['message'], 'order' => $res['order']], $res['code']);
            }
            $data['delivery_fee'] = $res['value'];
            $data['total_amount'] += $data['delivery_fee'];
        }
        

        $data['min_order_price'] = $restaurant->min_order_price;

        if (isset($request['address_id'])) {
            $order->address = Address::find($request['address_id']);
        }
        $order=  reoder($order);
        $data['rest_discount'] = $restDiscount;
        return response()->json([
            'data' => $data,
            'order' => $order
        ], 200);
    }

    public function assignDriver(assignDriverRequest $request, Order $order): JsonResponse
    {
        $validated = $request->validated();
        if ($order->order_status_id == 2) {
            $validated['order_status_id'] = 4;
            $this->orderRepository->assignDriver($order, $validated);
            $user = User::find($request->driver_id);
            if ($user->tokens->count()) {
                $user->notify(new NewOrderDriver($order));
            }
            return response()->json(['message' => Lang::get('messages.success')], 200);
        }
        return response()->json(['message' => Lang::get('messages.failed')], 400);
    }

    public function changeStatus(ChangeOrderStatusRequest $request, Order $order): JsonResponse
    {
        $validated = $request->validated();
        if ($validated['status'] == "7") {
            $this->finishOrder($order);
            return response()->json(['message' => Lang::get('messages.order.change_status', ["status" => "complete"])]);
        }
        if ($validated['status'] == "2") {
            if ($order->order_status_id == 1) {
                $this->acceptOrder($order);
                return response()->json(['message' => Lang::get('messages.order.accepted')]);
            }
        }
        if ($validated['status'] == "9") {
            if ($order->order_status_id == 1) {
                $this->rejectOrder($order);
                return response()->json(['message' => Lang::get('messages.order.reject')]);
            }
        }
        return response()->json(['message' => Lang::get('messages.update')]);
    }

    public function notifyDrivers(Order $order)
    {
        if ($order->service_info_type == 1 && !$order->scheduling) {
            $filter['restaurant_id'] = $order->restaurant_id ;
            $filter['is_active'] = 1;
            $filter['order'] = $order;
            $drivers = $this->driverRepository->getByFilter($filter);
            if (count($drivers) != 0) {
                $this->sendNotifications($drivers, $order);
            } else {
                $filter2['is_active'] = 1;
                $filter2['type'] = 'app';
                $filter2['order'] = $order;
                $filter2['restaurant'] = Restaurant::find($order->restaurant_id);
                $drivers = $this->driverRepository->getByFilter($filter2);
                $this->sendNotifications($drivers, $order);
            }
        }
    }

    public function sendNotifications($drivers, $order)
    {
        foreach ($drivers as $driver) {
            if ($driver->user->tokens->count()) {
                try {
                    $driver->user->notify(new NewOrderDriver($order));
                } catch (Exception $ex) {
                }
            }
        }
    }

    public function finishOrder(Order $order)
    {
        $order->order_status_id = 7;
        $order->delivered_at = now();
        $order->save();
    }

    public function rejectOrder(Order $order)
    {
        $order->order_status_id = 9;
        $order->save();
        if ($order->user->tokens->count()) {
            $order->user->notify(new OrderCanceledByRestaurant($order));
        }
    }

    public function acceptOrder(Order $order)
    {
        $order->order_status_id = 2;
        $order->save();
        if ($order->user->tokens->count()) {
            $order->user->notify(new OrderAcceptedByRestaurant($order));
        }
        $this->notifyDrivers($order);
    }
}
