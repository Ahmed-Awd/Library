<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrder;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Notification;
use App\Jobs\SendNewOrder;
use App\Notifications\CanceledOrder;
use App\Notifications\UpdateOrder;
use Exception;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {
    }

    public function createOrder($store, $orderData)
    {
        if (isset($orderData['distance'])) {
            if (number_format($orderData['distance'], 2) > 1.50) {
                $distance = $orderData['distance'];
            } else {
                $distance = resolve(GeoDistance::class)->calculate(
                    [$store->lat, $store->lng],
                    [$orderData['location']['lat'], $orderData['location']['lng']]
                );
                if ($distance == 0) {
                    $response["message"] = Lang::get('messages.place.out_of_range');
                    $response["code"] = 422;
                    return $response;
                }
            }
        } else {
            $distance = resolve(GeoDistance::class)->calculate(
                [$store->lat, $store->lng],
                [$orderData['location']['lat'], $orderData['location']['lng']]
            );
            if ($distance == 0) {
                $response["message"] = Lang::get('messages.place.out_of_range');
                $response["code"] = 422;
                return $response;
            }
        }

        $deliveryPrice = $this->calculateDeliveryPrice($distance);
        $storePayed = $deliveryPrice * ($store->setting->delivery_perice_percentage / 100);
        $storeFee = $this->calculateStoreFee($deliveryPrice, $store);

        $status = $storeFee > $store->owner->balance ? 6 : 1;
        if (!isset($orderData['all_distances'])) {
            $orderData['all_distances'] = null;
        } else {
            $orderData['all_distances'] = json_encode($orderData['all_distances']);
        }
        $order = $this->orderRepository
            ->store($store, $orderData, $status, $deliveryPrice, $storePayed, $distance, $storeFee);

        if ($status == 1) {
            resolve(UserRepositoryInterface::class)->reserveBalance($store->owner_id, $storeFee);
            // $this->notifyDrivers($order);
            SendNewOrder::dispatch($order);
            $response["message"] = Lang::get('messages.process.create');
            $response["code"] = 200;
            return $response;
        }
        $response["message"] = Lang::get('messages.store_order.suspended');
        $response["code"] = 400;

        return $response;
    }

    public function updateOrder($order, $orderData)
    {
        $this->orderRepository
            ->update($order, $orderData);
        if ($order->driver) {
            Notification::send($order->driver, new UpdateOrder($order));
        }
            $response["message"] = Lang::get('messages.process.update');
            $response["code"] = 200;
            return $response;
    }

    public function estimate($store, $orderData): array
    {
        if (isset($orderData['distance'])) {
            if (number_format($orderData['distance'], 2) > 1.50) {
                $distance = $orderData['distance'];
            } else {
                $distance = resolve(GeoDistance::class)->calculate(
                    [$store->lat, $store->lng],
                    [$orderData['location']['lat'], $orderData['location']['lng']]
                );
                if ($distance == 0) {
                    $response["message"] = Lang::get('messages.place.out_of_range');
                    $response["code"] = 422;
                    return $response;
                }
            }
        } else {
            $distance = resolve(GeoDistance::class)->calculate(
                [$store->lat, $store->lng],
                [$orderData['location']['lat'], $orderData['location']['lng']]
            );
            if ($distance == 0) {
                $response["message"] = Lang::get('messages.place.out_of_range');
                $response["code"] = 422;
                return $response;
            }
        }
        $data['deliveryPrice'] = $this->calculateDeliveryPrice($distance);
        $data['storeFee'] = $this->calculateStoreFee($data['deliveryPrice'], $store);
        $data['originalPrice'] = $orderData['total_price'] * 100;
        $data['order_price'] = $data['deliveryPrice'] + $data['originalPrice'];
        $data['delivery_fee_payed_by_store'] = $data['deliveryPrice']
            * ($store->setting->delivery_perice_percentage / 100);
        $data['customer_payed'] = $data['order_price'] - $data['delivery_fee_payed_by_store'];
        $data['customer_payed_for_delivery'] = $data['deliveryPrice'] - $data['delivery_fee_payed_by_store'];
        $data['store_profit'] = $data['originalPrice'] - $data['delivery_fee_payed_by_store'];
        $data['distance_store_order'] = $distance;
        $data['code'] = 200;
        return $data;
    }

    public function reorder($order)
    {
        $store = $order->store ?? null;
        if (is_null($store)) {
            $response["message"] = Lang::get('messages.store_order.no_store');
            $response["code"] = 422;
            return $response;
        }
        $deliveryPrice = $this->calculateDeliveryPrice($order->distance_store_order);
        $storePayed = $deliveryPrice * ($store->setting->delivery_perice_percentage / 100);
        $storeFee = $this->calculateStoreFee($deliveryPrice, $store);
        $status = $storeFee > $store->owner->balance ? 6 : 1;

        if ($status == 6) {
            $response["message"] = Lang::get('messages.store_order.suspended');
            $response["code"] = 400;
            return $response;
        }

        $order = $this->orderRepository
        ->reorder($store, $order, $status, $deliveryPrice, $storePayed, $storeFee);

        if ($status == 1) {
            resolve(UserRepositoryInterface::class)->reserveBalance($store->owner_id, $storeFee);
            // $this->notifyDrivers($order);
            SendNewOrder::dispatch($order);
            $response["message"] = Lang::get('messages.process.create');
            $response["code"] = 200;
            return $response;
        }
        $response["message"] = Lang::get('messages.store_order.suspended');
        $response["code"] = 400;

        return $response;
    }

    public function cancel($order)
    {
        if (!in_array($order->status, [1,2,3,6])) {
            $response["message"] = Lang::get('messages.can_not_cancel', ['status' => $order->orderStatus->name]);
            $response["code"] = 400;
            return $response;
        }
        if (in_array($order->status, [2,3])) {
            $order->update(["status" => 4]) ;
            $response["message"] = Lang::get('messages.driver_order.delivered_success');
            $response["code"] = 200;
            return $response;
        }
        $order->update(["status" => 5]) ;
        $user = User::findOrFail($order->store->owner_id);
        // $value = $this->calculateStoreFee($order->delivery_price, $order->store);
        $value = $order->store_fee;
        $user->balance = $user->balance + $value;
        $user->reserved_balance = $user->reserved_balance - $value;
        $user->save();
        try {
            $user->notify(new CanceledOrder($order));
        } catch (\Exception $error) {
        }
        $response["message"] = Lang::get('messages.cancel');
        $response["code"] = 200;
        return $response;
    }

    public function notifyDrivers($order)
    {
        $order = Order::find($order);
        $users = User::role('driver')->has('tokens')->where('is_available', 1)->get();
        if ($order) {
            Notification::send($users, new NewOrder($order));
        }
    }

    public function calculateDeliveryPrice($distance)
    {
        $deliveryPrice = settings('initial_price');
        if ($distance > settings('initial_distance')) {
            $deliveryPrice += (($distance - settings('initial_distance')) * settings('additional_price'));
        }

        return ceil($deliveryPrice / 100) * 100;
    }

    public function calculateStoreFee($deliveryPrice, $store)
    {
        $store->setting->taken_percentage_from_store == null
            ? $rate = settings("taken_percentage_from_delivery") : $rate = $store->setting->taken_percentage_from_store;
        return floor($deliveryPrice * ($rate / 100));
    }
}
