<?php

namespace App\Services;

use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrder;
use App\Repositories\DiscountCodeRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Jobs\SendNewOrder;
use App\Models\Address;
use App\Models\Item;
use App\Models\OptionSecondary;
use App\Models\OptionValue;
use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\Setting;
use App\Repositories\OrderItemRepositoryInterface;
use App\Repositories\PaymentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;
use Ramsey\Uuid\Uuid;

use function PHPUnit\Framework\isEmpty;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderItemRepositoryInterface $orderItemRepository,
        private PaymentRepositoryInterface $paymentRepositoryInterface
    ) {
    }

    public function createOrder($orderData)
    {
        $restaurant = Restaurant::find($orderData['restaurant_id']);
        $orderData['service_info_type'] == null ? $orderData['service_info_type'] = 1 :
            $orderData['service_info_type'] = $orderData['service_info_type'];

        if (isset($orderData['scheduling_time'])) {
            $schedul_order = $this->schedulingOrder($orderData, $restaurant);
            if ($schedul_order['code'] == 200) {
                $data['scheduling'] = true;
                $data['scheduling_to'] = $schedul_order['timestamp'];
            } else {
                return $schedul_order;
            }
        } else {
            $status = $this->isOpenRestaurant(
                $restaurant,
                getCurrentDayNumber(Carbon::now()->format('l')),
                Carbon::now()->toTimeString(),
                $orderData['service_info_type']
            );
            if (!$status) {
                if ($orderData['service_info_type']) {
                    $response["message"] = Lang::get('messages.restaurant.restaurant_is_close_for_delivery');
                } else {
                    $response["message"] = Lang::get('messages.restaurant.restaurant_is_close_for_takeaway');
                }
                $response["code"] = 400;
                $response["order"] = null;
                return $response;
            }
        }


        $data['preparation_time'] = $restaurant->prepare_order_time;
        $data['delivery_time'] = 0;
        $orderItems = $orderData['order_items'] ?? null;
        $data['sub_total'] = 0;
        $data['total_discount'] = 0;
        $data['total_tax'] = 0;
        if (!is_null($orderItems)) {
            $data_resault = $this->calculateSubTotal($orderItems);
            $data['sub_total'] = $data_resault['subtotal'];
            $data['total_discount'] += $data_resault['discount'];
            $data['total_tax'] = $data_resault['total_tax'];
        }
        $data['delivery_fee'] = 0;


        if ($orderData['service_info_type'] == 1) {
            if (isset($orderData['address_id'])) {
                $address = Address::find($orderData['address_id']);
                $result = resolve(GeoDistance::class)->calculate(
                    [$restaurant->lat, $restaurant->lng],
                    [$address->lat, $address->lng]
                );
              
                if ($result['distance'] == -1) {
                    $response["message"] = Lang::get('messages.order.distance__too_far');
                    $response["code"] = 400;
                    $response["order"] = null;
                    return $response;
                }
                if ($restaurant->setting &&
                    $restaurant->setting->max_delivery_distance < $result['distance']
                ) {
                    $response["message"] = Lang::get('messages.order.restaurant_is_not_available_in_your_location');
                    $response["code"] = 400;
                    $response["order"] = null;
                    return $response;
                }
               
                $data['delivery_time'] = $result['duration'];
                $data['address'] = $address;
                $data['delivery_fee'] = $this->calculateDeliveryFee(
                    $restaurant,
                    $result['distance'],
                    $data['sub_total']
                );
            }
        }
        $data['minimum_order_adjustment_amount'] = $restaurant->min_order_price < $data['sub_total']
        ? 0 : ($restaurant->min_order_price - $data['sub_total']);
        $data['total_amount'] = $restaurant->min_order_price < $data['sub_total']
        ? $data['sub_total'] : $restaurant->min_order_price;
        if ($restaurant->currentOffer()->exists()) {
            $offer = $restaurant->currentOffer;
            $restDiscount = $data['total_amount'] * ($offer->rate / 100);
            $data['total_discount'] += $restDiscount;
            $data['total_amount']    = $data['total_amount'] - $restDiscount;
        }
        $data['total_amount'] += $data['delivery_fee'];
        $data['restaurant_id'] = $restaurant->id;
        if (isset($orderData['discount_code_id'])) {
            $discount = $this->useDiscountCode($data['total_amount'], $restaurant->id, $orderData['discount_code_id']);
            if ($discount['code'] != 200) {
                $response["message"] = $discount['message'];
                $response["code"] = $discount['code'];
                $response["order"] = [];
                return $response;
            }
            $numbers = $discount['data'];
            $data['total_amount'] = $numbers['new_price'];
            $data['total_discount'] += $numbers['discount'];
            $data['discount_code_id'] = $orderData['discount_code_id'];
        }
        if (auth()->user()->hasRole('customer')) {
            $data['user_id'] = auth()->user()->id;
            $data['order_status_id'] = 1;
        } else {
            $data['user_id'] = $orderData['user_id'];
            $data['order_status_id'] = 2;
            $data['is_created_by_admin'] = 1;
        }
        $lastOrder = $restaurant->orders()->latest()->first();
        $data['order_number'] =$lastOrder ? $lastOrder->order_number + 1 : 1;
        $payeePaymentReference=str_pad($restaurant->id, 4, '0', STR_PAD_LEFT).''.str_pad($data['order_number'], 6, '0', STR_PAD_LEFT);
        $data['order_type'] = isset($orderData['order_type']) ? $orderData['order_type'] : 1;
        $data['payment_method'] = isset($orderData['payment_method']) ? $orderData['payment_method'] : 1;
        $data['platform'] = isset($orderData['platform']) ? $orderData['platform'] : 'web';
        $data['service_info_type'] = $orderData['service_info_type'];
        $data['comment'] = isset($orderData['comment']) ? $orderData['comment'] : "";

    //check payment
        $uuid=null;
        $token='';
        if ($data['payment_method']==2) {
            do {
                $uuid =Uuid::uuid4();
                $uuid=strtoupper(str_replace('-','',$uuid));
                $payment=Payment::where('uuid', $uuid)->first();
            } while (!is_null($payment));
            $phone=isset($orderData['phone']) ? $orderData['phone'] : auth()->user()->phone;
            $response_payment= $this->retrievePaymentRequestV2($uuid, $phone, $data['total_amount'], 'SEK',$data['platform'],$payeePaymentReference);
            if ($response_payment['status']!=200) {
                $response["message"] = $response_payment['response'];
                $response["code"] = $response_payment['status'];
                $response["order"] = [];
                return $response;
            } else {
                $paymen_data['data']=json_encode($response_payment['response']) ;
                $paymen_data['uuid']=$response_payment['uuid'];
                $token=$response_payment['token'];
                $paymen_data['type']='paid';
                $paymen_data['method_id']=2;
                $this->paymentRepositoryInterface->create($paymen_data);
            }
        }else if ($data['payment_method']==1) {
            $data['paid'] = 1;
        }

        if($restaurant->setting)
        {
        if($orderData['service_info_type'])
            $data['comission_percentage']=$restaurant->setting->taken_percentage_from_delivery;
        else
           $data['comission_percentage']=$restaurant->setting->taken_percentage_from_takeaway;
           
           $data['comission_amount']=$data['total_amount']*($data['comission_percentage']/100);
        }

        $order_id = $this->orderRepository->store($data);

        if ($data['payment_method']==2) {
            $paymen_data2['order_id']=$order_id;
            $this->paymentRepositoryInterface->update($paymen_data2, $uuid);
        }

        if (!is_null($orderItems)) {
            $all_ids = array();
            foreach ($orderItems as $item) {
                array_push($all_ids, $item['item_id']);
            }
              $items = Item::whereIn('id', $all_ids)->get();
            foreach ($orderItems as $orderItem) {
                $item_order = array();
                $item = $items->where('id', $orderItem['item_id'])->first();
                $item_order['item'] = $item;
                $item_order['name'] = $item->name;
                $item_order['tax'] = $item->tax ?? null;
                if ($item->currentOffer()->exists()) {
                    $offer = $item->currentOffer;
                    $item_price = $offer->new_price;
                } else {
                    $item_price = $item->price ;
                }
                $item_order['price'] = $orderItem['quantity'] * $item_price ;
                $item_order['unit_price'] = $item_price;
                
                if($item->tax)
                    $item_order['unit_price_without_tax'] =$item_price/(1+($item->tax->percentage / 100));
                else
                    $item_order['unit_price_without_tax'] =$item_price;

                    $item_order['total_without_tax'] =$item_order['unit_price_without_tax'] * $orderItem['quantity'];
                
                $total_option_price=0;
                $item_order['quantity'] = $orderItem['quantity'];
                $item_order['note'] = isset($orderItem['note']) ? $orderItem['note'] : '';
                if ($item_order['note'] == "null" || $item_order['note'] == null) {
                    $item_order['note'] = "";
                }
                $item_order['total'] = $item_order['price'];
                $TemplateSecondaryOptionsDetails = array();
                if (isset($orderItem['template_selected_options'])) 
                {
                    if(!is_null($orderItem['template_selected_options']) && !empty($orderItem['template_selected_options']))
                    {
                                $all_secondary_id = array();
                                $all_secondary_quantity = array();
                            foreach ($orderItem['template_selected_options'] as $selected_options) {
                                array_push($all_secondary_id, $selected_options['option_secondary_id']);
                                array_push($all_secondary_quantity, $selected_options['quantity']);
                            }
                            $item_order['template_secondary_options'] = json_encode($all_secondary_id) ;
                            $item_order['template_secondary_options_quantity'] = json_encode($all_secondary_quantity);
                            $option_secondaries = OptionSecondary::whereIn('id', $all_secondary_id)->get();
                            foreach ($orderItem['template_selected_options'] as $option) {
                                $default_secondary_option_item = array();
                                $option_secondary = $option_secondaries->where(
                                    'id',
                                    $option['option_secondary_id']
                                )->first();
                                $price = $option_secondary->price;
                                if ($option_secondary->use_default) {
                                    $price = $option_secondary->secondaryOptionValue->price;
                                }

                                $default_secondary_option_item['default_secondary_option_item'] = $option_secondary;
                                $default_secondary_option_item['total_price'] = $option['quantity'] * $price;
                                $total_option_price+=$option['quantity'] * $price;
                                $default_secondary_option_item['quantity'] = $option['quantity'];
                                $TemplateSecondaryOptionsDetails[] = $default_secondary_option_item;
                            }
                           
                    }
                } 
                if(isset($orderItem['primary_option_value_id']))
                {
                    if(!is_null($orderItem['primary_option_value_id']) && !empty($orderItem['primary_option_value_id']) )
                  {
                    $option_primary = OptionValue::where('id', $orderItem['primary_option_value_id'])->first();
                    $item_order['primary_option_value_id'] = $option_primary->id;
                    $template_selected_options['default_primary_option_item'] = $option_primary;
                    $template_selected_options['quantity'] = $orderItem['primary_option_quantity'];
                    $template_selected_options['total_price'] = $orderItem['primary_option_quantity']
                    * $option_primary->price;
                    $total_option_price+=$orderItem['primary_option_quantity']
                    * $option_primary->price;
                    $template_selected_options['template_secondary_sptions_details'] = $TemplateSecondaryOptionsDetails;
                    $item_order['template_selected_options'] = json_encode($template_selected_options);
                  }
                }
                
                if (isset($orderItem['selected_options'])) 
                {
                    $all_option_vlaue_ids = array();
                    foreach ($orderItem['selected_options'] as $selected_option) {
                        array_push($all_option_vlaue_ids, $selected_option['option_vlaue_id']);
                    }
                     $option_values = OptionValue::whereIn('id', $all_option_vlaue_ids)->get();
                     $selected_options = array();
                    foreach ($orderItem['selected_options'] as $option) {
                        $selected_options_val = array();
                        $option_value = $option_values->where('id', $option['option_vlaue_id'])->first();
                        $selected_options_val['option_value'] = $option_value;
                        $selected_options_val['quantity'] = $option['quantity'];
                        $selected_options_val['total_price'] = $option_value->price * $option['quantity'];
                        $total_option_price+=$option_value->price * $option['quantity'];
                        $selected_options[] = $selected_options_val;
                    }
                    $item_order['selected_options'] = json_encode($selected_options);
                }

                $item_order['order_id'] = $order_id;
                $item_order['total_options'] =$total_option_price;
                    $this->orderItemRepository->store($item_order);
            }
        }
        $order = Order::find($order_id);
        
        if ($restaurant->owner->tokens->count() && $order->payment_method==1) {
            $restaurant->owner->notify(new NewOrder($order));
        }
        if (isset($orderData['discount_code_id'])) {
            $this->codeUsed($orderData['discount_code_id']);
        }
        $response["message"] = Lang::get('messages.order.create');
        $response["code"] = 200;
        $response["order"] = $order;
        $response["token"] = $token;
        return $response;
    }

    public function reorder($request, $order)
    {
        $restaurant = Restaurant::find($order->restaurant_id);
        $request["service_info_type"] == null ? $request["service_info_type"] = 1 : 1 == 1;
        if (isset($request['scheduling_time'])) {
            $schedul_order = $this->schedulingOrder($request, $restaurant);
            if ($schedul_order['code'] == 200) {
                $data['scheduling'] = true;
                $data['scheduling_to'] = $schedul_order['timestamp'];
            } else {
                return $schedul_order;
            }
        } else {
            $status = $this->isOpenRestaurant(
                $restaurant,
                getCurrentDayNumber(Carbon::now()->format('l')),
                Carbon::now()->toTimeString(),
                $request['service_info_type']
            );
            if (!$status) {
                if ($request['service_info_type']) {
                    $response["message"] = Lang::get('messages.restaurant.restaurant_is_close_for_delivery');
                } else {
                    $response["message"] = Lang::get('messages.restaurant.restaurant_is_close_for_takeaway');
                }
                $response["code"] = 400;
                $response["order"] = null;
                return $response;
            }
        }

        $orderItems = $order->orderItem ?? null;
        $data['sub_total'] = 0;
        $data['delivery_fee'] = 0;
        $data['delivery_time'] = 0;
        $data['total_discount'] = 0;
        $data['total_tax'] = 0;

        if (!is_null($orderItems)) {
            $response = $this->calculateSubTotalForReorder($orderItems);
            if ($response['status']) {
                $data_resault = $response['data'];
                $data['sub_total'] = $data_resault['subtotal'];
                $data['total_discount'] += $data_resault['discount'];
                $data['total_tax'] = $data_resault['total_tax'];
            } else {
                $response["message"] = $response['message'];
                $response["code"] = 400;
                $response["order"] = null;
                return $response;
            }
        }
        if ($request['service_info_type'] == 1) {
            if (isset($request['address_id'])) {
                $address = Address::find($request['address_id']);
            } else {
                $address = Address::find($order->address->id);
            }

            $result = resolve(GeoDistance::class)->calculate(
                [$restaurant->lat, $restaurant->lng],
                [$address->lat, $address->lng]
            );


            if ($result['distance'] == -1) {
                $response["message"] = Lang::get('messages.order.distance__too_far');
                $response["code"] = 400;
                $response["order"] = null;
                return $response;
            }
            if ($restaurant->setting &&
                $restaurant->setting->max_delivery_distance < $result['distance']
            ) {
                $response["message"] = Lang::get('messages.order.restaurant_is_not_available_in_your_location');
                $response["code"] = 400;
                $response["order"] = null;
                return $response;
            }
            $data['delivery_time'] = $result['duration'];
            $data['address'] = $address;
            $data['delivery_fee'] = $this->calculateDeliveryFee(
                $restaurant,
                $result['distance'],
                $data['sub_total']
            );
        }
        $data['preparation_time'] = $restaurant->prepare_order_time;
        $data['minimum_order_adjustment_amount'] = $restaurant->min_order_price < $data['sub_total']
        ? 0 : ($restaurant->min_order_price - $data['sub_total']);
        $data['total_amount'] = $restaurant->min_order_price < $data['sub_total']
        ? $data['sub_total'] : $restaurant->min_order_price;
        if ($restaurant->currentOffer()->exists()) {
            $offer = $restaurant->currentOffer;
            $restDiscount = $data['total_amount'] * ($offer->rate / 100);
            $data['total_discount'] += $restDiscount;
            $data['total_amount']    = $data['total_amount'] - $restDiscount;
        }
        $data['total_amount'] += $data['delivery_fee'];
        $data['restaurant_id'] = $restaurant->id;
        if (isset($request['discount_code_id'])) {
            $discount = resolve(OrderService::class)
                ->useDiscountCode($data['total_amount'], $restaurant->id, $request['discount_code_id']);
            if ($discount['code'] != 200) {
                $response["message"] = $discount['message'];
                $response["code"] = $discount['code'];
                $response["order"] = [];
                return $response;
            }
            $numbers = $discount['data'];
            $data['total_amount'] = $numbers['new_price'];
            $data['total_discount'] += $numbers['discount'];
            $data['discount_code_id'] = $request['discount_code_id'];
        }
        if (auth()->user()->hasRole('customer')) {
            $data['user_id'] = auth()->user()->id;
            $data['order_status_id'] = 1;
        } else {
            $data['user_id'] = isset($request['user_id']) ? $request['user_id'] : $order->user_id;
            $data['order_status_id'] = 2;
            $data['is_created_by_admin'] = 1;
        }
        $lastOrder = $restaurant->orders()->latest()->first();
        $data['order_number'] =$lastOrder ? $lastOrder->order_number + 1 : 1;   
        $payeePaymentReference=str_pad($restaurant->id, 4, '0', STR_PAD_LEFT).''.str_pad($data['order_number'], 6, '0', STR_PAD_LEFT);
        $data['order_type'] = $order->order_type;
        $data['payment_method'] =isset($request['payment_method'])?$request['payment_method']: $order->payment_method;
        $data['platform'] =isset($request['platform'])?$request['platform']: $order->platform;
        $data['service_info_type'] = $request['service_info_type'];

        $uuid=null;
        $token='';
        if ($data['payment_method']==2) {
            do {
                $uuid =Uuid::uuid4();
                $payment=Payment::where('uuid', $uuid)->first();
            } while (!is_null($payment));
            $phone=isset($request['phone']) ? $request['phone'] : auth()->user()->phone;
            $response_payment= $this->retrievePaymentRequestV2($uuid, $phone, $data['total_amount'], 'SEK',$data['platform'],$payeePaymentReference);
            if ($response_payment['status']!=200) {
                $response["message"] = $response_payment['response'];
                $response["code"] = $response_payment['status'];
                $response["order"] = [];
                return $response;
            } else {
                $paymen_data['data']=json_encode($response_payment['response']) ;
                $paymen_data['uuid']=$response_payment['uuid'];
                $token=$response_payment['token'];
                $paymen_data['type']='paid';
                $paymen_data['method_id']=2;
                $this->paymentRepositoryInterface->create($paymen_data);
            }
        }else if ($data['payment_method']==1) {
            $data['paid'] = 1;
        }
        if($restaurant->setting)
        {
        if($request['service_info_type'] )
            $data['comission_percentage']=$restaurant->setting->taken_percentage_from_delivery;
        else
           $data['comission_percentage']=$restaurant->setting->taken_percentage_from_takeaway;
           
           $data['comission_amount']=$data['total_amount']*($data['comission_percentage']/100);
        }
        $order_id = $this->orderRepository->store($data);

        if ($data['payment_method']==2) {
            $paymen_data2['order_id']=$order_id;
            $this->paymentRepositoryInterface->update($paymen_data2, $uuid);
        }

        if (!is_null($orderItems)) {
            $all_ids = array();
            foreach ($orderItems as $order_item) {
                array_push($all_ids, $order_item->item->id);
            }
              $items = Item::whereIn('id', $all_ids)->get();
            foreach ($orderItems as $orderItem) {
                $item_order = array();
                $item = $items->where('id', $orderItem->item->id)->first();
                $item_order['item'] = $item;
                $item_order['name'] = $item->name;
                $item_order['tax'] = $item->tax ?? null;
                if ($item->currentOffer()->exists()) {
                    $offer = $item->currentOffer;
                    $item_price = $offer->new_price;
                } else {
                    $item_price = $item->price ;
                }
                $item_order['price'] = $orderItem->quantity * $item_price ;
                $item_order['unit_price'] = $item_price;
                if($item->tax)
                $item_order['unit_price_without_tax'] =$item_price/(1+($item->tax->percentage / 100));
            else
                $item_order['unit_price_without_tax'] =$item_price;

                $item_order['total_without_tax'] =$item_order['unit_price_without_tax'] *  $orderItem->quantity;
            
                $item_order['note'] = $orderItem->note;
                $item_order['quantity'] = $orderItem->quantity;
                $item_order['total'] = $item_order['price'] ;
                $total_option_price=0;

                if ($orderItem->template_selected_options && $orderItem->template_secondary_options) {
                    $item_order['template_secondary_options'] =
                    json_encode($orderItem->template_secondary_options) ;
                    $item_order['template_secondary_options_quantity'] =
                    json_encode($orderItem->template_secondary_options_quantity);
                    $TemplateSecondaryOptionsDetails = array();
                     $option_secondaries =
                     OptionSecondary::whereIn('id', $orderItem->template_secondary_options)
                     ->get();
                    foreach ($orderItem->template_secondary_options as $key => $value) {
                        $default_secondary_option_item = array();
                        $option_secondary = $option_secondaries->where(
                            'id',
                            $value
                        )->first();
                        $price = $option_secondary->price;
                        if ($option_secondary->use_default) {
                            $price = $option_secondary->secondaryOptionValue->price;
                        }

                        $default_secondary_option_item['default_secondary_option_item'] = $option_secondary;
                        $default_secondary_option_item['total_price'] =
                        $orderItem->template_secondary_options_quantity[$key] * $price;
                        $total_option_price+= $orderItem->template_secondary_options_quantity[$key] * $price;

                        $default_secondary_option_item['quantity'] =
                        $orderItem->template_secondary_options_quantity[$key];
                        $TemplateSecondaryOptionsDetails[] = $default_secondary_option_item;
                    }
                    $option_primary = OptionValue::where('id', $orderItem->primary_option_value_id)->first();
                    $item_order['primary_option_value_id'] = $option_primary->id;
                    $template_selected_options['default_primary_option_item'] = $option_primary;
                    $template_selected_options['quantity'] = $orderItem->template_selected_options->quantity;
                    $template_selected_options['total_price'] = $orderItem->template_selected_options->quantity
                    * $option_primary->price;
                    $total_option_price+=$orderItem->template_selected_options->quantity
                    * $option_primary->price;
                    $template_selected_options['template_secondary_sptions_details'] =
                    $TemplateSecondaryOptionsDetails;
                    $item_order['template_selected_options'] = json_encode($template_selected_options);
                } elseif ($orderItem->selected_options) {
                    $all_option_vlaue_ids = array();
                    foreach ($orderItem->selected_options as $selected_option) {
                        array_push($all_option_vlaue_ids, $selected_option->option_value->id);
                    }
                     $option_values = OptionValue::whereIn('id', $all_option_vlaue_ids)->get();
                     $selected_options = array();
                    foreach ($orderItem->selected_options as $option) {
                        $selected_options_val = array();
                        $option_value = $option_values->where('id', $option->option_value->id)->first();
                        $selected_options_val['option_value'] = $option_value;
                        $selected_options_val['quantity'] = $option->quantity;
                        $selected_options_val['total_price'] = $option_value->price * $option->quantity;
                        $selected_options[] = $selected_options_val;
                        $total_option_price+= $option_value->price * $option->quantity;
                    }
                    $item_order['selected_options'] = json_encode($selected_options);
                }else if(!is_null($orderItem->primary_option_value_id))
                {
                    $option_primary = OptionValue::where('id', $orderItem->primary_option_value_id)->first();
                    $item_order['primary_option_value_id'] = $option_primary->id;
                    $template_selected_options['default_primary_option_item'] = $option_primary;
                    $template_selected_options['quantity'] = $orderItem->template_selected_options->quantity;
                    $template_selected_options['total_price'] = $orderItem->template_selected_options->quantity
                    * $option_primary->price;
                    $total_option_price+=$orderItem->template_selected_options->quantity
                    * $option_primary->price;
                    $template_selected_options['template_secondary_sptions_details'] =[];
                    $item_order['template_selected_options'] = json_encode($template_selected_options);
                }
                $item_order['order_id'] = $order_id;
                $item_order['total_options'] = $total_option_price;
                    $this->orderItemRepository->store($item_order);
            }
        }
        $order = Order::find($order_id);
        if ($restaurant->owner->tokens->count() && $order->payment_method==1) {
            $restaurant->owner->notify(new NewOrder($order));
        }
        if (isset($request['discount_code_id'])) {
            $this->codeUsed($request['discount_code_id']);
        }
        $response["message"] = Lang::get('messages.order.create');
        $response["code"] = 200;
        $response["order"] = $order;
        $response["token"] = $token;

        return $response;
    }
    public function calculateSubTotal($orderItems)
    {
         $subtotal = 0;
         $discount = 0;
         $total_tax = 0;
         $all_ids = array();
        foreach ($orderItems as $item) {
            array_push($all_ids, $item['item_id']);
        }
          $items = Item::whereIn('id', $all_ids)->get();
        foreach ($orderItems as $orderItem) {
            $item = $items->where('id', $orderItem['item_id'])->first();
            $tax_item=0;
            if ($item->currentOffer()->exists()) {
                $offer = $item->currentOffer;
                $sum = $offer->new_price * $orderItem['quantity'];
                $discount += ($item->price - $offer->new_price)*$orderItem['quantity'];
                if ($item->tax) {
                    $old_price=$offer->new_price/(1+($item->tax->percentage / 100));
                    $total_tax+=($offer->new_price - $old_price)*$orderItem['quantity'];
                }
            } else {
                $sum = $item->price * $orderItem['quantity'];
                if ($item->tax) {
                    $old_price=$item->price/(1+($item->tax->percentage / 100));
                    $total_tax+=($item->price - $old_price)*$orderItem['quantity'];
                }
            }
            if (isset($orderItem['template_selected_options'])) {
                if(!is_null($orderItem['template_selected_options']) && !empty($orderItem['template_selected_options']))
                {
                    $all_secondary_id = array();
                    foreach ($orderItem['template_selected_options'] as $selected_options) {
                        array_push($all_secondary_id, $selected_options['option_secondary_id']);
                    }
                    $option_secondaries = OptionSecondary::whereIn('id', $all_secondary_id)->get();
                    foreach ($orderItem['template_selected_options'] as $option) {
                        $option_secondary = $option_secondaries->where('id', $option['option_secondary_id'])
                        ->first();
                        $price = $option_secondary->price;
                        if ($option_secondary->use_default) {
                            $price = $option_secondary->secondaryOptionValue->price;
                        }
                        $sum += $option['quantity'] * $price;
                    }
               }
            }
            if(isset($orderItem['primary_option_value_id']))
            {
                if(!is_null($orderItem['primary_option_value_id']) && !empty($orderItem['primary_option_value_id']) )
                {
                    $option_primary = OptionValue::where('id', $orderItem['primary_option_value_id'])->first();
                    $sum += $orderItem['primary_option_quantity'] * $option_primary->price;
                }
            }
            if (isset($orderItem['selected_options'])) {
                $all_option_vlaue_ids = array();
                foreach ($orderItem['selected_options'] as $selected_option) {
                    array_push($all_option_vlaue_ids, $selected_option['option_vlaue_id']);
                }
                 $option_values = OptionValue::whereIn('id', $all_option_vlaue_ids)->get();

                foreach ($orderItem['selected_options'] as $option) {
                    $option_value = $option_values->where('id', $option['option_vlaue_id'])->first();
                    $sum += $option_value->price * $option['quantity'];
                }
            }
            $subtotal += $sum;
            $sum = 0;
        }
         $data['subtotal'] = $subtotal;
         $data['discount'] = $discount;
         $data['total_tax'] = $total_tax;
        return  $data;
    }

    public function calculateSubTotalForReorder($orderItems)
    {
         $subtotal = 0;
         $discount = 0;
         $total_tax=0;
         $all_ids = array();
        foreach ($orderItems as $order_item) {
            array_push($all_ids, $order_item->item->id);
        }
          $items = Item::whereIn('id', $all_ids)->get();
        foreach ($orderItems as $orderItem) {
            $tax_item=0;
            $item = $items->where('id', $orderItem->item->id)->first();
            if (is_null($item)) {
                $response['status'] = false;
                $response["code"] = 400;
                $response['message'] = Lang::get('messages.you_can_not_reorder');
                return $response;
            }
            if ($item->currentOffer()->exists()) {
                $offer = $item->currentOffer;
                $sum = $offer->new_price * $orderItem->quantity;
                $discount += ($item->price - $offer->new_price);
                if ($item->tax) {
                    $old_price=$offer->new_price/(1+($item->tax->percentage / 100));
                    $total_tax+=($offer->new_price - $old_price)*$orderItem['quantity'];
                }
            } else {
                $sum = $item->price * $orderItem->quantity;
                if ($item->tax) {
                    $old_price=$item->price/(1+($item->tax->percentage / 100));
                    $total_tax+=($item->price - $old_price)*$orderItem['quantity'];
                }
            }

            if ($orderItem->template_secondary_options) {
                if(!$item->optionTemplate)
                {
                    $response['status'] = false;
                    $response["code"] = 400;
                    $response['message'] = Lang::get('messages.you_can_not_reorder');
                    return $response;
                }
                 $option_secondaries = OptionSecondary::whereIn('id', $orderItem->template_secondary_options)
                 ->get();
                foreach ($orderItem->template_secondary_options as $key => $value) {
                    $option_secondary = $option_secondaries->where('option_template_id', $item->option_template_id )
                    ->where('id', $value)->first();
                    if (is_null($option_secondary)) {
                        $response['status'] = false;
                        $response["code"] = 400;
                        $response['message'] = Lang::get('messages.you_can_not_reorder');
                        return $response;
                    }
                    $price = $option_secondary->price;
                    if ($option_secondary->use_default) {
                        if (is_null($option_secondary->secondaryOptionValue)) {
                            $response['status'] = false;
                            $response["code"] = 400;
                            $response['message'] = Lang::get('messages.you_can_not_reorder');
                            return $response;
                        }
                        $price = $option_secondary->secondaryOptionValue->price;
                    }
                    $sum += $orderItem->template_secondary_options_quantity[$key] * $price;
                }

            }

            if(!is_null($orderItem->primary_option_value_id))
            {
                $option_primary = OptionValue::where('id', $orderItem->primary_option_value_id)->first();
                if (is_null($option_primary)) {
                        $response['status'] = false;
                        $response["code"] = 400;
                        $response['message'] = Lang::get('messages.you_can_not_reorder');
                        return $response;
                }
                $sum += $orderItem->template_selected_options->quantity  * $option_primary->price;
            }
            if ($orderItem->selected_options) {
                $all_option_vlaue_ids = array();
                foreach ($orderItem->selected_options as $selected_option) {
                    array_push($all_option_vlaue_ids, $selected_option->option_value->id);
                }
                 $option_values = OptionValue::whereIn('id', $all_option_vlaue_ids)->get();

                foreach ($orderItem->selected_options as $option) {
                    $option_value = $option_values->where('id', $option->option_value->id)->first();
                    if (is_null($option_value)) {
                        $response['status'] = false;
                        $response["code"] = 400;
                        $response['message'] = Lang::get('messages.you_can_not_reorder');
                        return $response;
                    }
                    $sum += $option_value->price * $option->quantity;
                }
            }
            $subtotal += $sum;
            $sum = 0;
        }
        $data['subtotal'] = $subtotal;
        $data['discount'] = $discount;
        $data['total_tax'] = $total_tax;
        $response['status'] = true;
        $response['message'] = "seccess";
        $response['data'] = $data;
        return $response;
    }
    public function calculateDeliveryFee($restaurant, $distance, $subtotal)
    {
        $delivery_fee = 0;
        $value = Setting::firstWhere('key', 'free_delivery_for_all')->value;

        if (boolval($value == 'true') ) {
            return  $delivery_fee;
        }

        $delivery_price = $restaurant->deliveryPrice ?? null;
        if (!is_null($delivery_price)) {
            if ($delivery_price->type == 'per_kilometer') {
                $delivery_fee = $this->calcUpToPrice($restaurant, $distance);
            } elseif ($delivery_price->type == 'value') {
                $delivery_fee = $delivery_price->value;
            } elseif ($delivery_price->type == 'percent') {
                $delivery_fee = $subtotal * ($delivery_price->value / 100);
            } elseif ($delivery_price->type == 'free') {
                $delivery_fee = 0;
            }
        }
        return  $delivery_fee;
    }

    public function calcUpToPrice($restaurant, $distance)
    {
        $flag = 0;
        $delivery_fee = 0;
        $prices = $restaurant->deliveryDistances()->orderBy('up_to')->get();
        if (count($prices) == 0) {
            return 0;
        }
        foreach ($prices as $price) {
            if ($price->up_to >= $distance) {
                $delivery_fee = $price->price;
                $flag = 1;
                break;
            }
        }
        if ($flag == 0) {
            $delivery_fee = $restaurant->deliveryDistances()->orderBy('up_to', 'desc')->first()->price;
        }
        return $delivery_fee;
    }

    public function useDiscountCode($amount, $restaurant, $code)
    {
        $record = DiscountCode::where('id', $code)->where('usage_left', '>', 0)
            ->whereDate('start_date', '<=', date("Y-m-d"))
            ->whereDate('end_date', '>=', date("Y-m-d"))->first();
        if (!$record) {
            $response["message"] = Lang::get('messages.discount_code.invalid_code');
            $response["code"] = 403;
            return $response;
        }
        if (!$this->checkCodeAvl($record, $restaurant)) {
            $response["message"] = Lang::get('messages.discount_code.invalid_for_this_order');
            $response["code"] = 403;
            return $response;
        }
        if ($amount < $record->min_order_price) {
            $response["message"] = Lang::get('messages.discount_code.min_order_price');
            $response["code"] = 403;
            return $response;
        }
        $data = $this->calcDiscount($amount, $record);
        $response["code"] = 200;
        $response['data'] = $data;
        return $response;
    }

    public function checkCodeAvl($code, $restaurant): bool
    {
        if ($code->restaurant_id == null) {
            if ($code->user_id == Auth::user()->id) {
                return true;
            }
            return false;
        }
        if ($code->restaurant_id == $restaurant && $code->user_id == Auth::user()->id) {
            return true;
        }
        return false;
    }

    public function calcDiscount($amount, $code): array
    {
        $data = [];
        if ($code->type == "rate") {
            $data['discount'] = $amount * ($code->amount / 100);
            $data['new_price'] = $amount - $data['discount'];
        } else {
            $data['discount'] =  $code->amount;
            $data['new_price'] = $amount - $data['discount'];
        }
        if ($data['new_price'] < 0) {
            $data['new_price'] = 0;
        }
        return $data;
    }

    public function codeUsed($code)
    {
        DiscountCode::where('id', $code)->decrement('usage_left');
    }

    public function codeRefunded($code)
    {
        DiscountCode::where('id', $code)->increment('usage_left');
    }

    public function schedulingOrder($orderData, $restaurant)
    {
        if ($restaurant->scheduling_order) {
            $date = Carbon::now()->format('Y-m-d');
            if (isset($orderData['scheduling_date'])) {
                if ($orderData['scheduling_date'] >= Carbon::now()->format('Y-m-d')) {
                    $date = $orderData['scheduling_date'];
                } else {
                    $response["message"] = Lang::get('messages.restaurant.date_must_be_after_today');
                    $response["code"] = 400;
                    $response["order"] = null;
                    return $response;
                }
            }

            $timestamp = $date . ' ' . $orderData['scheduling_time'];
            $status = $this->isOpenRestaurant(
                $restaurant,
                getCurrentDayNumber(Carbon::parse($date)->format('l')),
                $orderData['scheduling_time'],
                $orderData['service_info_type']
            );
            if ($status) {
                $response["code"] = 200;
                $response["timestamp"] = $timestamp;
                return $response;
            } else {
                $response["message"] = Lang::get('messages.restaurant.restaurant_is_close');
                $response["code"] = 400;
                $response["order"] = null;
                return $response;
            }
        } else {
            $response["message"] = Lang::get('messages.restaurant.restaurant_not_support_schudeling');
                $response["code"] = 400;
                $response["order"] = null;
                return $response;
        }
    }
    public function isOpenRestaurant($restaurant, $day, $hour, $service_info_type)
    {
              $open = $restaurant->workTime()
              ->where('day_id', '=', $day)
              ->where('open_from', '<', $hour)
              ->where('open_to', '>', $hour)
              ->where('status', '=', 1);
        if ($service_info_type) {
            $open = $open->where('delivery', 1);
        } else {
            $open = $open->where('takeaway', 1);
        }
        $open=$open->first();
        if ($open) {
            return true;
        } else {
            return false;
        }
    }

    public function retrievePaymentRequestV2($instructionUUID, $phone, $amount, $currency,$platform,$payeePaymentReference, $message = null)
    {
            $phone='46'.substr(trim($phone),-9,9);
            
            $url='https://backend.minpizza.online/api/check/swish/'.$platform;
            $data = array(
                "uuid" =>$instructionUUID,
                "platform_type" =>$platform,
                "payeePaymentReference" => $payeePaymentReference,
                "callbackUrl" => $url,
                "payerAlias" => $phone,
                "payeeAlias" => 1236607444,
                "amount" =>intval(ceil($amount)),
                "currency" => $currency,
                "message" => "Paid order");
            //  $response =swish($url, $data, 'PUT');
               $response=$this->test($data);
               info("response return form swish");
               info($response);
               info($data);
               $result=array();
            if ($response['status']==201) {
                $url='https://cpc.getswish.net/swish-cpcapi/api/v1/paymentrequests/'.$instructionUUID;
                 $response_siwsh =swish($url, array(), 'GET');
                if ($response_siwsh['status']==200) 
                {
                    $result["status"] = $response_siwsh['status'];
                    $result["uuid"] = $instructionUUID;
                    $result["token"] = isset($response['paymentrequesttoken'])?$response['paymentrequesttoken']:'';
                    $result["response"] = $response_siwsh['response'];
                    return $result;
                }else
                {
                    $result["status"] = $response_siwsh['status'];
                    $result["response"] = $response_siwsh['response'];
                    return $result;
                }
            } else if($response['status']==422) 
            {
                $result["status"] = $response['status'];
                $result["response"] = $response['response'][0]['errorMessage'];
                return $result;
            } else {
                $result["status"] = $response['status'];
                $result["response"] = $response['response'];
                return $result;
            }
    }

      function test($data)
      {

        $url='https://swish.minpizza.online';
        
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            ));
        $response=curl_exec($ch);
        $response = json_decode($response,true);
        return$response;
    }

    public  function refundsPaymentRequestV2($instructionUUID, $original_payment, $amount, $currency, $message = null)
    {
        $url='https://cpc.getswish.net/swish-cpcapi/api/v2/refunds/'.$instructionUUID;
            $data = array(
                "originalPaymentReference" => $original_payment,
                 "callbackUrl" => "https://example.com/api/swishcb/paymentrequests",
                  "payerAlias" => "1236607444‏",//It needs to match with Merchant Swish number
                    "amount" => $amount,
                    "currency" =>$currency, "message" =>$message??"cancel order");
            $response =swish($url, $data, 'PUT');
            if ($response['status']==201) {
                $url='https://cpc.getswish.net/swish-cpcapi/api/v1/refunds/'.$instructionUUID;
                $response2 =swish($url, array(), 'GET');
                if ($response2['status']==200) {
                    $response["status"] = $response2['status'];
                    $response["response"] = $response2['response'];
                    return $response;
                }
            } else {
                $response["status"] = $response['status'];
                $response["response"] = $response['response'];
                return $response;
            }
    }
}
