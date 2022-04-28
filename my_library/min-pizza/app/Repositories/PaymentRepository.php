<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PaymentRepository implements PaymentRepositoryInterface
{

    private Payment $payment;


    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function show($payment)
    {
        return  $payment;
    }

    public function create($data)
    {
        $this->payment->create($data);
    }

    public function update($data, $uuid)
    {
        $this->payment->where('uuid',$uuid)->update($data);
    }
    
}
