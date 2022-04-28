<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Payment;
use App\Repositories\PaymentRepositoryInterface;
use App\Services\OrderService;
use Ramsey\Uuid\Uuid;

class OrderObserver
{
    public function __construct(
        private PaymentRepositoryInterface $paymentRepositoryInterface
    ) 
    {
    }
    public function created(Order $order)
    {
       $order->logStatus()->syncWithoutDetaching($order->order_status_id);
    }


    public function updated(Order $order)
    {
        //
        if ($order->isDirty('order_status_id')) 
        {

            $order->logStatus()->syncWithoutDetaching($order->order_status_id);

            if($order->payment_method==2 && $order->order_status_id==9 )
            {
                $payment=$order->payment ;
                if(!is_null($payment) && !$order->refund)
                {
                    $uuid=null;
                    do {
                        $uuid =Uuid::uuid4();
                        $payment_test=Payment::where('uuid', $uuid)->first();
                    } while (!is_null($payment_test));

                  $data_payment = resolve(OrderService::class)
                    ->refundsPaymentRequestV2($uuid,$payment->uuid,$payment->data->amount,$payment->data->currency,$order->refuse_comment);
                    if($data_payment['status']==200)
                    {
                        $paymen_data['data']=json_encode($data_payment['response']) ;
                        $paymen_data['uuid']=$uuid;
                        $paymen_data['type']='refund';
                        $paymen_data['order_id']=$order->id;
                        $paymen_data['method_id']=2;
                        $this->paymentRepositoryInterface->create($paymen_data);
                    }
                }
                
            }else if($order->payment_method==3 && $order->order_status_id==9)
            {
                $payment=$order->payment ;
                if(!is_null($payment) && !$order->refund)
                {   
                  $data_payment = $this->refoundBambora($payment->uuid);
                    if($data_payment->meta->result)
                    {
                        $response = $this->paymentBambora($payment->uuid);
                        if($data_payment->meta->result)
                        {
                        $paymen_data['data']=json_encode($response->transaction) ;
                        $paymen_data['uuid']=$payment->uuid;
                        $paymen_data['type']='refund';
                        $paymen_data['order_id']=$order->id;
                        $paymen_data['method_id']=3;
                        $this->paymentRepositoryInterface->create($paymen_data);
                        }
                    }
                }
            }
        }
    }


    public function deleted(Order $order)
    {
        //
    }


    public function restored(Order $order)
    {
        //
    }


    public function forceDeleted(Order $order)
    {
        //
    }
    public function refoundBambora($transactionId)
    {
        $endpointUrl = "https://transaction-v1.api-eu.bambora.com/transactions/".
        $transactionId."/credit";
        $response=bambora($endpointUrl,"POST",0);
        return $response;
    }
    public function paymentBambora($transactionId)
    {
        $endpointUrl = "https://merchant-v1.api-eu.bambora.com/transactions/".$transactionId;
        $response=bambora($endpointUrl,"GET",0);
        return $response;
    }
}
