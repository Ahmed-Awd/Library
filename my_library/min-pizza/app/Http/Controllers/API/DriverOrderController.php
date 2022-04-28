<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\OptionCategory;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\User;
use App\Notifications\DriverOnTheWay;
use App\Notifications\OrderAcceptedByDriver;
use App\Notifications\OrderReadyToDelivered;
use App\Notifications\OrderReadyToTakeawayFromDriver;
use App\Repositories\DriverRepositoryInterface;
use App\Repositories\OrderItemRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Notification;

class DriverOrderController extends Controller
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

    public function index(Request $request): JsonResponse
    {
        $filter['order_status_id'] = [2];
        $filters['paid'] = 1;
        $filter['service_info_type'] = 1;
        $filter['new_order'] = true;


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

    public function myOrder(Request $request): JsonResponse
    {
        $filter['driver_id'] = auth()->user()->id;
        $range = $request->get('range', false);
        if (isset($request['order_status_id'])) {
            if (!is_null($request['order_status_id'])) {
                $filter['order_status_id'] = [$request['order_status_id']];
            }
        }
        $orders = $this->orderRepository->get($filter, $range);
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

    public function acceptOrder(Order $order): JsonResponse
    {
        $driver = auth()->user()->driver ? auth()->user()->driver : null;
        if ($driver != null) {
            if (!$driver->is_active) {
                return response()->json(['message' => Lang::get('messages.order.your_account_not_available')], 400);
            }
            if (is_null($order->driver_id)) {
                $order->driver_id = auth()->user()->id;
                $order->order_status_id = 4;
                $order->save();
            } elseif ($order->driver_id == auth()->user()->id) {
                $order->order_status_id = 4;
                $order->save();
            } else {
                return response()->json(['message' => Lang::get('messages.order.accepted_by_another')], 200);
            }
            if ($order->restaurant->owner->tokens->count()) {
                try {
                    $order->restaurant->owner->notify(new OrderAcceptedByDriver($order));
                } catch (Exception $ex) {
                }
            }
            return response()->json(['message' => Lang::get('messages.order.accepted')], 200);
        }
        return response()->json(['message' => Lang::get('messages.driver.have_not_driver')], 401);
    }

    public function onTheWay(Order $order): JsonResponse
    {
        $driver = auth()->user()->driver ? auth()->user()->driver : null;
        if ($driver != null) {
            if ($order->driver_id == auth()->user()->id) {
                $order->order_status_id = 5;
                $order->save();
            } else {
                return response()->json(['message' => Lang::get('messages.order.accepted_by_another')], 200);
            }
            if ($order->user->tokens->count()) {
                try {
                    $order->user->notify(new DriverOnTheWay($order));
                } catch (Exception $ex) {
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
        return response()->json(['message' => Lang::get('messages.driver.have_not_driver')], 401);
    }

    public function delivered(Order $order): JsonResponse
    {
        if ($order->driver_id == Auth::id()) {
            $order->order_status_id = 7;
            $order->delivered_at = now();
            $order->save();
        } else {
            return response()->json(['message' => Lang::get('messages.order.accepted_by_another')]);
        }
        return response()->json(['message' => Lang::get('messages.order.change_status',
            ["status" => $order->orderStatus->name])],      );
    }

    public function readyForTakeaway(Order $order): JsonResponse
    {
        $driver = auth()->user()->driver ? auth()->user()->driver : null;
        if ($driver != null) {
            if ($order->driver_id == auth()->user()->id) {
                $order->order_status_id = 6;
                $order->save();
            } else {
                return response()->json(['message' => Lang::get('messages.order.accepted_by_another')], 200);
            }
            if ($order->user->tokens->count()) {
                try {
                    $order->user->notify(new OrderReadyToTakeawayFromDriver($order));
                } catch (Exception $ex) {
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
        return response()->json(['message' => Lang::get('messages.driver.have_not_driver')], 401);
    }

    public function show(Order $order): JsonResponse
    {
        $order = $this->orderRepository->show($order);
        $order->currency_code = '';


        if ($order->orderItem) {
            foreach ($order->orderItem as $orderItem) {
                if (!is_null($orderItem->template_selected_options)) {
                    $template_selected_option_category = OptionCategory::withTrashed()
                        ->where('id', $orderItem
                            ->template_selected_options->default_primary_option_item
                            ->option_category_id)->first();
                    $template_selected_option_category->option_values = $orderItem
                        ->template_selected_options;
                    $template_selected_option_category->option_values
                        ->template_secondary_sptions_details = array();
                    $option_category_ids = array();
                    foreach ($orderItem->template_selected_options
                                 ->template_secondary_sptions_details as $selected_option
                    ) {
                        if (array_search(
                                $selected_option->default_secondary_option_item->secondary_option_id,
                                $option_category_ids
                            ) === false
                        ) {
                            array_push(
                                $option_category_ids,
                                $selected_option->default_secondary_option_item->secondary_option_id
                            );
                        }
                    }
                    $option_categories = OptionCategory::withTrashed()
                        ->whereIn('id', $option_category_ids)->get();
                    $selected_option_categories = array();
                    foreach ($option_categories as $option_category) {
                        $selected_option_category = $option_category;
                        $option_values = array();
                        foreach ($orderItem->template_selected_options
                                     ->template_secondary_sptions_details as $selected_option
                        ) {
                            if ($selected_option->default_secondary_option_item
                                    ->secondary_option_id == $option_category->id
                            ) {
                                $option_values[] = $selected_option;
                            }
                        }
                        $selected_option_category->option_values = $option_values;
                        $selected_option_categories[] = $selected_option_category;
                        $option_values = null;
                    }
                    $template_selected_option_category->option_values->template_secondary_sptions_details =
                        $selected_option_categories;
                    $orderItem->template_selected_options = array();
                    $orderItem->template_selected_options = $template_selected_option_category;
                } elseif (!is_null($orderItem->selected_options)) {
                    $selected_options = $orderItem->selected_options;
                    $option_category_ids = array();
                    foreach ($selected_options as $selected_option) {
                        if (array_search(
                                $selected_option->option_value->option_category_id,
                                $option_category_ids
                            ) === false
                        ) {
                            array_push(
                                $option_category_ids,
                                $selected_option->option_value->option_category_id
                            );
                        }
                    }
                    $option_categories = OptionCategory::withTrashed()
                        ->whereIn('id', $option_category_ids)->get();
                    $selected_option_categories = array();
                    foreach ($option_categories as $option_category) {
                        $selected_option_category = null;
                        $selected_option_category = $option_category;
                        $option_values = array();
                        foreach ($orderItem->selected_options as $selected_option) {
                            if ($selected_option->option_value->option_category_id == $option_category->id) {
                                $option_values[] = $selected_option;
                            }
                        }
                        $selected_option_category->option_values = $option_values;
                        $selected_option_categories[] = $selected_option_category;
                        $option_values = null;
                    }
                    $orderItem->selected_option_categories = $selected_option_categories;
                }
            }
        }

        if ($order->restaurant) {
            if ($order->restaurant->owner) {
                if ($order->restaurant->owner->city) {
                    if ($order->restaurant->owner->city->country) {
                        $order->currency_code = $order->restaurant->owner->city->country->currency_code;
                    }
                }
            }
        }
        return response()->json(['order' => $order], 200);
    }
}
