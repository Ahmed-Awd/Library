<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RateOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\StoreReorderRequest;
use App\Http\Requests\TokenBamboraRequest;
use App\Models\Address;
use App\Models\Item;
use App\Models\OptionCategory;
use App\Models\OptionSecondary;
use App\Models\OptionValue;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Restaurant;
use App\Models\User;
use App\Notifications\NewOrder;
use App\Notifications\OrderIsLate;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\PaymentRepositoryInterface;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Notification;
use Ramsey\Uuid\Uuid;

class CustomerOrderController extends Controller
{

    private OrderRepositoryInterface $orderRepository;
    private PaymentRepositoryInterface $paymentRepository;
    public function __construct(OrderRepositoryInterface $orderRepository,
    PaymentRepositoryInterface $paymentRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->paymentRepository = $paymentRepository;
    }


    public function index(Request $request): JsonResponse
    {
        $filters['user_id'] = Auth::id();
        $filter['paid'] = 1;
        $range = $request->get('range', false);
        $orders = $this->orderRepository->get($filters, $range);
        return response()->json(['orders' => $orders]);
    }

    public function current(Request $request): JsonResponse
    {
        $filters['user_id'] = Auth::id();
        $filters['paid'] = 1;
        $filters['order_status_id'] = [1, 2, 3, 4, 5, 6];
        $range = $request->get('range', false);
        $orders = $this->orderRepository->get($filters, $range);
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
        return response()->json(['orders' => $orders], 200);
    }

    public function previous(Request $request): JsonResponse
    {
        $filters['user_id'] = Auth::id();
        $filters['paid'] = 1;
        $filters['order_status_id'] = [7, 8, 9];
        $range = $request->get('range', false);
        $orders = $this->orderRepository->get($filters, $range);

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
        return response()->json(['orders' => $orders], 200);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();
        // if($validated['restaurant_id']!=1)
        // {
        //     return response()->json([
        //         'message' => Lang::get('messages.server_unavailable'),
        //         'order' =>[],
        //         'url' => null
        //     ], 400);
        // }
        $response = resolve(OrderService::class)->createOrder($validated);
        $url=null;
        if(isset($validated['payment_method']) && $response['code']==200)
        {
            $order=Order::find($response['order']['id']);
            if($validated['payment_method']==3)
            {
                $order_item=$order->orderItem->first();
                $item=Item::find($order_item->item->id);
                $url=$this->tokenBambora($order,$item->currency);
            }else if($validated['payment_method']==2)
            {
                if($order->payment)
                {
                    if($order->platform =='web')
                    $url='swish://paymentrequest?callbackurl=https://minpizza.online/OrderHistory';
                    elseif ($order->platform =="android")
                     $url='swish://paymentrequest?token='.$response['token'].'&callbackurl=https://minpizzaclient.page.link/android';
                    else
                    $url='swish://paymentrequest?token='.$response['token'].'&callbackurl=https://minpizzaclient.page.link/ios';

                     
                }
            }
        }
        return response()->json([
            'message' => $response['message'],
            'order' => $response['order'],
            'url' => $url
        ], $response['code']);
    }

    public function calcOrder(StoreOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $data['discount'] = 0;
        $data['coupon_discount']=0;
        $restDiscount=0;
        $restaurant = Restaurant::find($validated['restaurant_id']);

        $data_result = resolve(OrderService::class)->calculateSubTotal($validated['order_items']);
        $data['sub_total'] = $data_result['subtotal'];
        $data['total_tax'] = $data_result['total_tax'];
        $data['discount'] += $data_result['discount'];

       
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
                return response()->json(['message' => $res['message'],'order' => $res['order']], $res['code']);
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
        // if($order->restaurant_id!=1)
        // {
        //     return response()->json([
        //         'message' => Lang::get('messages.server_unavailable'),
        //         'order' =>[],
        //         'url' => null
        //     ], 400);
        // }

        $response = resolve(OrderService::class)->reorder($validated, $order);
        $url=null;
        if(isset($validated['payment_method']) && $response['code']==200)
        {
            $order=Order::find($response['order']['id']);
            if($validated['payment_method']==3)
            {
                $order_item=$order->orderItem->first();
                $item=Item::find($order_item->item->id);
                $url=$this->tokenBambora($order,$item->currency);
            }else if($validated['payment_method']==2)
            {
                if($order->payment)
                {
                    if($order->platform =='web')
                    $url='swish://paymentrequest?callbackurl=https://minpizza.online/OrderHistory';
                    elseif ($order->platform =="android")
                     $url='swish://paymentrequest?token='.$response['token'].'&callbackurl=https://minpizzaclient.page.link/android';
                    else
                    $url='swish://paymentrequest?token='.$response['token'].'&callbackurl=https://minpizzaclient.page.link/ios';

                     
                }
            }
        }

        return response()->json([
            'message' => $response['message'],
            'order' => $response['order'],
            'url' => $url
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
            } else {
                $address = $order->address;
            }
            $res = calcDeliveryFee($restaurant, $address, $data);
            if ($res['code'] == 400) {
                return response()->json(['message' => $res['message'],'order' => $res['order']], $res['code']);
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

    public function delivered(Order $order): JsonResponse
    {
        if ($order->user_id == Auth::id()) {
            $order->order_status_id = 7;
            $order->delivered_at = now();
            $order->save();
        } else {
            return response()->json(['message' => Lang::get('messages.order.accepted_by_another')], 200);
        }
        return response()->json(
            ['message' => Lang::get(
                'messages.order.change_status',
                ["status" => $order->orderStatus->name]
            )],
            200
        );
    }

    public function rate(Order $order, RateOrderRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $result = $this->orderRepository->rate($order, $validated);
        return response()->json(['order' => $order, 'message' => $result]);
    }

    public function notify(Request $request, Order $order): JsonResponse
    {
        if ($order->user_id == Auth::id()) {
            $status = $order->order_status_id;
            if ($status == 2 || $status == 3 || $status == 4 || $status == 5) {
                $admins = User::permission('view orders')->has('tokens')->get();
                Notification::send($admins, new OrderIsLate($order));
                return response()->json(['message' => Lang::get('messages.notifications.sent')]);
            }
            return response()->json(['message' => ""], 404);
        }
        return response()->json(['message' => Lang::get('messages.order.accepted_by_another')], 401);
    }

    public function cheackIsOrderPaidAfterRetrunFromPaymentWay(Request $request, Order $order): JsonResponse
    {
        $status=false;

            if ($order->payment_method ==2 )
            {
                if($order->payment)
                {
                    if($order->payment->data)
                    {
                        if( $order->payment->data->status == 'PAID')
                        {
                            if( $order->paid)
                            {
                                $order->update(["paid" => 1]);
                                if ($order->restaurant->owner->tokens->count()) 
                                {
                                    $order->restaurant->owner->notify(new NewOrder($order));
                                }
                            }
                          $status=true;
                           
                        }
                        else if( $order->payment->data->status == 'CREATED')
                        {
                            $instructionUUID=$order->payment->uuid;
                            $url='https://cpc.getswish.net/swish-cpcapi/api/v1/paymentrequests/'.$instructionUUID;
                          $response2 =swish($url, array(), 'GET');
                            if ($response2['status']==200) {
                                if($response2['response']->status=='PAID')
                                {
                                    $paymen=$order->payment;
                                    $paymen->data=json_encode($response2['response']);
                                    $paymen->save();
                                    $order->update(["paid" => 1]);
                                    if ($order->restaurant->owner->tokens->count()) 
                                    {
                                        $order->restaurant->owner->notify(new NewOrder($order));
                                    }
                                    $status=true;

                                }
                               
                            }
                        }
                    }
                }
            }else if ($order->payment_method ==3 )
            { 
                if($order->payment && $order->paid)
                {
                    $status=true;
                }else if($order->payment)
                {
                    $order->update(["paid" => 1]);
                    if ($order->restaurant->owner->tokens->count()) 
                    {
                        $order->restaurant->owner->notify(new NewOrder($order));
                    }
                    $status=true;
                }

            }

        return response()->json(['status' => $status],200);     
    }
     
    public function tokenBambora($order,$currency)
    {
        
        $checkoutUrl = "https://api.v1.checkout.bambora.com/sessions";
        $payment_data = array();
        $payment_data["order"] = array();
        $payment_data["order"]["id"] =$order->id;
        $payment_data["order"]["amount"] =100*$order->total_amount;
        //تم تثبيت من اجل الاختبار  $currency
        info(getCurrency($order->restaurant));
        $payment_data["order"]["currency"] =getCurrency($order->restaurant);
        $data_items=array();        
        foreach($order->orderItem as $item )
        {
         $data_item=array();
         $data_item['id']=$item->id;
         $data_item['linenumber']=$item->item->id;
         $data_item['description']=$item->name;
         $data_item['text']=$item->item->description;
         $data_item['quantity']=$item->quantity;
        
         $restaurant=$order->restaurant;
         
         if($item->tax)
         $total_options_without_tax =$item->total_options/(1+($item->tax->percentage / 100));
         else
         $total_options_without_tax=$item->total_options;

         $unitprice=($item->unit_price_without_tax + $total_options_without_tax);
         $unitpriceinclvat=($item->unit_price +$item->total_options);
         $totalprice =($item->total_without_tax + $total_options_without_tax);
         $totalpriceinclvat=($item->total + $item->total_options);

         if ($restaurant->currentOffer()->exists()) {
            $offer = $restaurant->currentOffer;
            $restDiscount = $unitprice* ($offer->rate / 100);
            $unitprice=($unitprice-$restDiscount);
            $restDiscountUnitPriceInclVat = $unitpriceinclvat * ($offer->rate / 100);
            $unitpriceinclvat=($unitpriceinclvat-$restDiscountUnitPriceInclVat);
            $restDiscounttotalprice = $totalprice * ($offer->rate / 100);
            $totalprice =($totalprice-$restDiscounttotalprice);
            $restDiscounttotalpriceinclvat = $totalpriceinclvat * ($offer->rate / 100);
            $totalpriceinclvat=($totalpriceinclvat-$restDiscounttotalpriceinclvat);
        }

        if($order->discountCode)
        {
            $unitpricediscount=resolve(OrderService::class)
            ->calcDiscount($unitprice,$order->discountCode);
            $unitprice=$unitpricediscount['new_price'];
            $unitpriceinclvatdiscount=resolve(OrderService::class)
            ->calcDiscount($unitpriceinclvat,$order->discountCode);
            $unitpriceinclvat=$unitpriceinclvatdiscount['new_price'];
            $totalpricediscount=resolve(OrderService::class)
            ->calcDiscount($totalprice,$order->discountCode);
            $totalprice=$totalpricediscount['new_price'];
            $totalpriceinclvatdiscount=resolve(OrderService::class)
            ->calcDiscount($totalpriceinclvat,$order->discountCode);
            $totalpriceinclvat=$totalpriceinclvatdiscount['new_price'];
        }
        
         $data_item['unit']=$item->name;
         $data_item['unitprice']=round(100*$unitprice);
         $data_item['unitpriceinclvat']=round(100*$unitpriceinclvat);
         $data_item['unitpricevatamount']=round(100*($unitpriceinclvat-$unitprice));
         $data_item['totalprice']=round(100*$totalprice);
         $data_item['totalpricevatamount']=round(100*($totalpriceinclvat-$totalprice));
         $data_item['totalpriceinclvat']=round(100*$totalpriceinclvat);
         $data_item['vat']=$item->tax?$item->tax->percentage:0;
         $data_items[]=$data_item;
        }
        
        if($order->delivery_fee !=0)
        {
            $delivery_fee=$order->delivery_fee;
            if($order->discountCode)
            {
             $delivery_feediscount=resolve(OrderService::class)
             ->calcDiscount($delivery_fee,$order->discountCode);
            $delivery_fee= $delivery_feediscount['new_price'];
            }

            $data_item=array();
            $data_item['id']=$order->id;
            $data_item['linenumber']=$order->id;
            $data_item['description']="delivery fee";
            $data_item['text']="delivery fee ";
            $data_item['quantity']=1;
            $data_item['unit']="delivery fee";
            $data_item['unitprice']=round($delivery_fee *100);
            $data_item['unitpriceinclvat']=round($delivery_fee *100);
            $data_item['unitpricevatamount']=0;
            $data_item['totalprice']=round($delivery_fee *100);
            $data_item['totalpricevatamount']=0;
            $data_item['totalpriceinclvat']=round($delivery_fee *100);
            $data_item['vat']=0;
            $data_items[]=$data_item;
        }
        if($order->minimum_order_adjustment_amount !=0)
        {
            if ($restaurant->currentOffer()->exists()) 
                {
                $offer = $restaurant->currentOffer;
                $restDiscount_minimum_order_adjustment_amount = $order->minimum_order_adjustment_amount* ($offer->rate / 100);
                $minimum_order_adjustment_amount= $order->minimum_order_adjustment_amount-$restDiscount_minimum_order_adjustment_amount ;
                }else
               $minimum_order_adjustment_amount= $order->minimum_order_adjustment_amount;
               
               if($order->discountCode)
               {
                $minimum_order_adjustment_amountdiscount=resolve(OrderService::class)
                ->calcDiscount($minimum_order_adjustment_amount,$order->discountCode);
               $minimum_order_adjustment_amount= $minimum_order_adjustment_amountdiscount['new_price'];
               }


            $data_item=array();
            $data_item['id']="minimum_order_adjustment_".$order->id;
            $data_item['linenumber']=$order->id;
            $data_item['description']="minimum order adjustment";
            $data_item['text']="minimum order adjustment ";
            $data_item['quantity']=1;
            $data_item['unit']="minimum order adjustment";
            $data_item['unitprice']=round($minimum_order_adjustment_amount*100);
            $data_item['unitpriceinclvat']=round($minimum_order_adjustment_amount*100);
            $data_item['unitpricevatamount']=0;
            $data_item['totalprice']=round($minimum_order_adjustment_amount*100);
            $data_item['totalpricevatamount']=0;
            $data_item['totalpriceinclvat']=round($minimum_order_adjustment_amount*100);
            $data_item['vat']=0;

            $data_items[]=$data_item;
        }


        info($data_items);
        $payment_data["order"]["lines"] =$data_items;

        $payment_data["url"] = array();
        $payment_data["url"]["immediateredirecttoaccept"] = 1;
        $payment_data["url"]["accept"] = 'https://backend.minpizza.online/api/chackout/bambora/'.$order->platform;
        $payment_data["url"]["cancel"] = 'https://backend.minpizza.online/api/cancel/bambora/'.$order->id;
        $payment_data["url"]["callbacks"] = array();
        $payment_data["url"]["callbacks"][] = array("url" => 'https://backend.minpizza.online/api/check/bambora/'.$order->platform);
        $payment_data["paymentwindow"] = array();
        $payment_data["paymentwindow"]["id"] = 1;

        if(auth()->user()->default_lang=="sv")
        $payment_data["paymentwindow"]["language"] = "sv-SE";
        else
        $payment_data["paymentwindow"]["language"] = "en-GB";

        $payment_data["paymentwindow"]["paymentmethods"] = array();
        $payment_data["paymentwindow"]["paymentmethods"][0]['id'] ="paymentcard" ;
        $payment_data["paymentwindow"]["paymentmethods"][0]['action'] ="include" ;
        $payment_data["paymentwindow"]["paymentmethods"][1]['id'] ="invoice" ;
        $payment_data["paymentwindow"]["paymentmethods"][1]['action'] ="include" ;
        $payment_data["paymentwindow"]["paymentgroups"] = array();
        $payment_data["paymentwindow"]["paymentgroups"][0]['id'] =3 ;
        $payment_data["paymentwindow"]["paymentgroups"][0]['action'] ="include" ;
        $payment_data["paymentwindow"]["paymentgroups"][1]['id'] =19 ;
        $payment_data["paymentwindow"]["paymentgroups"][1]['action'] ="include" ;
        $payment_data["paymentwindow"]["paymenttypes"] = array();
        $payment_data["paymentwindow"]["paymenttypes"][0]['id'] =8 ;
        $payment_data["paymentwindow"]["paymenttypes"][0]['action'] ="include" ;
        $payment_data["paymentwindow"]["paymenttypes"][1]['id'] =40;
        $payment_data["paymentwindow"]["paymenttypes"][1]['action'] ="include" ;
        

        $payment_data["securitylevel"] = "none";
        $payment_data["securityexemption"] = "LowValuePayment";
        
        $requestJson = json_encode($payment_data);
        $contentLength = isset($requestJson) ? strlen($requestJson) : 0;
        
        $response=bambora($checkoutUrl,"POST",$contentLength,$requestJson);
        return $response->url;
    }

    public function checkCallBack(Request $request,$platform)
    {
        info(' run callback');
        info($request);
      
    }

    public function checkPaymentSwish(Request $request,$platform)
    {
        info('check call back swish');
        $data=$request->all();
        $paymen=Payment::where('uuid',$request['id'])->first();
        if($paymen)
        {
            $paymen->data=json_encode($data);
            $paymen->save();
            $order=$paymen->order;
            if($request['status']=='PAID')
            {
                $order->update(["paid" => 1]);
                if ($order->restaurant->owner->tokens->count()) 
                {
                    $order->restaurant->owner->notify(new NewOrder($order));
                }
            }else if($request['status']=='CANCELLED')
            {
                $order->update([
                    'order_status_id'=>9
                ]);
            }

        }
        
        return;

    }
    public function paymentBambora(Request $request,$platform)
    {
       
        $transactionId = $request->txnid;
        $endpointUrl = "https://merchant-v1.api-eu.bambora.com/transactions/".$transactionId;
        $captureUrl = "https://transaction-v1.api-eu.bambora.com/transactions/".
        $transactionId."/capture";    
        $response=bambora($captureUrl,"POST",0);
        $message="done";
        if($response->meta->result == false) {
            $message=$response->meta->message->merchant;
        }else{
            $response2=bambora($endpointUrl,"GET",0);
            if($response2->meta->result == false) {
                $message=$response2->meta->message->merchant;
            }else{
                $paymen_data['data']=json_encode($response2->transaction) ;
                $paymen_data['uuid']=$request->txnid;
                $paymen_data['type']='paid';
                $paymen_data['method_id']=3;
                $paymen_data['order_id']=intval($request->orderid);
                $this->paymentRepository->create($paymen_data);
                $order=Order::find(intval($request->orderid));
                $order->update(["paid" => 1]);
                if ($order->restaurant->owner->tokens->count()) 
                {
                    $order->restaurant->owner->notify(new NewOrder($order));
                }
                
            }
        }
        if($platform=="web")
        return redirect()->to("https://minpizza.online/order/".$request->orderid);
        else if($platform=="android")
        return redirect()->to("https://minpizzaclient.page.link/android");
        else
        return redirect()->to("https://minpizzaclient.page.link/ios");

    }
    public function cancelBambora($order)
    {
        $order=Order::find($order);
        $order->update([
            'order_status_id'=>9
        ]);
        if($order->platform=="web")
        return redirect()->to("https://minpizza.online/");
        else if($order->platform=="android")
        return redirect()->to("https://minpizzaclient.page.link/android");
        else
        return redirect()->to("https://minpizzaclient.page.link/ios");
        return;
    }
    
    public function solvePayment(Request $request): JsonResponse
    {
        $status=false;
        $orders=Order::whereIn('id',[815,816,817,819,820,822])->get();
            foreach ($orders as $order)
            {
                $instructionUUID=$order->payment->uuid;
                $url='https://cpc.getswish.net/swish-cpcapi/api/v1/paymentrequests/'.$instructionUUID;
                $response2 =swish($url, array(), 'GET');
                if ($response2['status']==200) {
                    if($response2['response']->status=='PAID')
                    {
                        $paymen=$order->payment;
                        $paymen->data=json_encode($response2['response']);
                        $paymen->save();
                        $status=true;
                    }
                }

            }
                            
                     
        return response()->json(['status' => $status],200);     
    }
    
}
