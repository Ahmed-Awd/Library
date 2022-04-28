<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CancelOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Models\OptionCategory;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\User;
use App\Notifications\NewOrderDriver;
use App\Notifications\OrderAcceptedByRestaurant;
use App\Notifications\OrderCanceledByRestaurant;
use App\Notifications\OrderReadyToDelivered;
use App\Notifications\OrderReadyToTakeaway;
use App\Repositories\DriverRepositoryInterface;
use App\Repositories\OrderItemRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Notification;

class RestaurantOrderController extends Controller
{

    private OrderRepositoryInterface $orderRepository;
    private DriverRepositoryInterface $driverRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        DriverRepositoryInterface $driverRepository
    )
    {
        // $this->authorizeResource(Order::class);
        $this->orderRepository = $orderRepository;
        $this->driverRepository = $driverRepository;
    }


    public function index(Request $request, Restaurant $restaurant = null): JsonResponse
    {
        $restaurant = $restaurant == null ? auth()->user()->restaurant : $restaurant;
        $range = $request->get('range', false);
        if ($restaurant != null) {
            $filter['restaurant_id'] = $restaurant->id;
            $filter['paid'] = 1;
            if (isset($request['order_status_id'])) {
                if (!is_null($request['order_status_id'])) {
                    $filter['order_status_id'] = [$request['order_status_id']];
                }
            }
            $orders = $this->orderRepository->get($filter, $range);
            $stats = $this->orderRepository->getStats($filter, $range);
            $currency = $restaurant->city->country->currency_code;
            foreach ($orders as $order) {
                $order->currency_code = '';
                if ($order->restaurant) {
                    if ($order->restaurant->owner) {
                        if ($order->restaurant->owner->city) {
                            if ($order->restaurant->owner->city->country) {
                                $order->currency_code = $order->restaurant->owner->city->country->currency_code;
                            }
                        }
                    }
                }
            }
            return response()->json([
                'orders' => $orders,
                'statistics' => $stats,
                'currency' => $currency,
            ], 200);
        }
        return response()->json(['message' => Lang::get('messages.restaurant.have_not_restaurant')], 401);
    }

    public function statistics(request $request): JsonResponse
    {
        $restaurant = auth()->user()->restaurant ? auth()->user()->restaurant : null;

        if ($restaurant != null) {
            return Cache::remember(
                'orders_api_statistics.' . auth()->user()->id,
                now()->addHour(),
                fn() => $this->getStatistics($restaurant)
            );
        }
        return response()->json(['message' => Lang::get('messages.restaurant.have_not_restaurant')], 401);
    }

    public function getStatistics($restaurant)
    {
        $data['order_count'] = $this->statusCountOrder($restaurant, 0);
        $data['Pending'] = $this->statusCountOrder($restaurant, 1);
        $data['Accepted'] = $this->statusCountOrder($restaurant, 2);
        $data['Ready_for_delivery'] = $this->statusCountOrder($restaurant, 3);
        $data['Accepted_by_driver'] = $this->statusCountOrder($restaurant, 4);
        $data['On_the_way'] = $this->statusCountOrder($restaurant, 5);
        $data['Ready_for_takeaway'] = $this->statusCountOrder($restaurant, 6);
        $data['Delivered'] = $this->statusCountOrder($restaurant, 7);
        $data['Completed'] = $this->statusCountOrder($restaurant, 8);
        $data['Rejected'] = $this->statusCountOrder($restaurant, 9);
        $data['total_amonts'] = Order::where('restaurant_id', $restaurant->id)->sum('total_amount');

        return response()->json([
            'data' => $data,
        ]);
    }

    public function statusCountOrder($restaurant, $no)
    {
        if ($no == 0) {
            $filter['order_status_id'] = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        } else {
            $filter['order_status_id'] = [$no];
        }
        $filter['restaurant_id'] = $restaurant->id;
        return $this->orderRepository->count($filter);
    }

    public function getCancelOrder(Request $request): JsonResponse
    {
        $restaurant = auth()->user()->restaurant ? auth()->user()->restaurant : null;
        if ($restaurant != null) {
            $filter['restaurant_id'] = $restaurant->id;
            $filter['order_status_id'] = [9];
            $orders = $this->orderRepository->get($filter);
            foreach ($orders as $order) {
                $order->currency_code = '';
                if ($order->restaurant) {
                    if ($order->restaurant->owner) {
                        if ($order->restaurant->owner->city) {
                            if ($order->restaurant->owner->city->country) {
                                $order->currency_code = $order->restaurant->owner->city->country->currency_code;
                            }
                        }
                    }
                }
            }
            return response()->json([
                'orders' => $orders,
            ], 200);
        }
        return response()->json(['message' => Lang::get('messages.restaurant.have_not_restaurant')], 401);
    }

    public function acceptOrder(Request $request, Order $order): JsonResponse
    {
        if($order->paid == 0)
        {
            return response()->json(
                ['message' => Lang::get('messages.order.not_paid')],
                401
            );
        }
        if (isset($request['preparation_time'])) {
            $order->preparation_time = $request['preparation_time'];
        }
        $order->order_status_id = 2;
        $order->save();
        if ($order->user->tokens->count()) {
            try {
                $order->user->notify(new OrderAcceptedByRestaurant($order));
            } catch (Exception $ex) {
            }
        }
        if ($order->service_info_type == 1 && !$order->scheduling) {
            $restaurant = auth()->user()->restaurant ? auth()->user()->restaurant : null;
            if ($restaurant != null) {
                $filter['restaurant_id'] = $restaurant->id;
                $filter['is_active'] = 1;
                $filter['order'] = $order;
                $drivers = $this->driverRepository->getByFilter($filter);
                if (count($drivers) != 0) {
                    foreach ($drivers as $driver) {
                        if ($driver->user->tokens->count()) {
                            try {
                                $driver->user->notify(new NewOrderDriver($order));
                            } catch (Exception $ex) {
                            }
                        }
                    }
                } else {
                    $filter2['is_active'] = 1;
                    $filter2['type'] = 'app';
                    $filter2['order'] = $order;
                    $filter2['restaurant'] = $restaurant;
                    $drivers = $this->driverRepository->getByFilter($filter2);
                    foreach ($drivers as $drive) {
                        if ($drive->user->tokens->count()) {
                            try {
                                $drive->user->notify(new NewOrderDriver($order));
                            } catch (Exception $ex) {
                            }
                        }
                    }
                }
            } else {
                return response()->json(
                    ['message' => Lang::get('messages.restaurant.have_not_restaurant')],
                    401
                );
            }
        }
        return response()->json(['message' => Lang::get('messages.order.accepted')], 200);
    }

    public function readyToDelivered(Order $order): JsonResponse
    {
        if ($order->service_info_type == 1) {
            $restaurant = auth()->user()->restaurant ? auth()->user()->restaurant : null;
            if ($restaurant != null) {
                $filter['restaurant_id'] = $restaurant->id;
                $filter['is_active'] = 1;
                $driver = $order->driver;
                if ($driver) {
                    $order->order_status_id = 3;
                    $order->save();
                    if ($driver->tokens->count()) {
                        try {
                            $driver->notify(new OrderReadyToDelivered($order));
                        } catch (Exception $ex) {
                        }
                    }
                } else {
                    return response()->json(
                        ['message' => Lang::get('messages.restaurant.have_not_driver')],
                        401
                    );
                }
            } else {
                return response()->json(
                    ['message' => Lang::get('messages.restaurant.have_not_restaurant')],
                    401
                );
            }
        } else {
            $order->order_status_id = 6;
            $order->save();
            if ($order->user->tokens->count()) {
                try {
                    $order->user->notify(new OrderReadyToTakeaway($order));
                } catch (Exception $ex) {
                }
            }
        }
        return response()->json(
            ['message' => Lang::get(
                'messages.order.change_status',
                ["status" => $order->orderStatus->name]
            )],
            200
        );
    }

    public function cancelOrder(CancelOrderRequest $request, Order $order): JsonResponse
    {
        if ($order->order_status_id == 1) {
            $order->order_status_id = 9;
            if (isset($request['refuse_comment'])) {
                $order->refuse_comment = $request['refuse_comment'];
            }
            $order->refuse_reason_id = $request['refuse_reason'];
            $order->save();
            $admins = User::permission('view orders')->has('tokens')->get();
            if ($order->user->tokens->count()) {
                $order->user->notify(new OrderCanceledByRestaurant($order));
            }
            Notification::send($admins, new OrderCanceledByRestaurant($order));
            return response()->json(['message' => Lang::get('messages.order.reject')], 200);
        } else {
            return response()->json(['message' => Lang::get('messages.order.can_not_reject')], 200);
        }
    }

    public function delivered(Order $order): JsonResponse
    {
        $restaurant = auth()->user()->restaurant ? auth()->user()->restaurant : null;
        if ($restaurant != null) {
            if ($order->service_info_type==0) {
                $order->order_status_id = 7;
                $order->delivered_at = now();
                $order->save();
            } else {
                return response()->json(['message' => Lang::get('messages.failed')]);
            }
        } else {
            return response()->json(
                ['message' => Lang::get('messages.restaurant.have_not_restaurant')],
                401
            );
       }
        return response()->json(['message' => Lang::get('messages.order.change_status',
            ["status" => $order->orderStatus->name])]);
    }

    public function show(Order $order): JsonResponse
    {
        $order = $this->orderRepository->show($order);
        $order=order($order);
        return response()->json(['order' => $order], 200);
    }

    public function newOrders($restaurant = null): JsonResponse
    {
        $rest = $restaurant == null ? auth()->user()->restaurant->id : $restaurant;
        $result = $this->orderRepository->newOrders($rest);
        return response()->json(['count' => $result], 200);
    }
}
