<?php


namespace App\Repositories;


use App\Models\PaymentMethod;

class PaymentMethodsRepository implements PaymentMethodsRepositoryInterface
{
    private PaymentMethod $paymentMethod;

    public function __construct(PaymentMethod $paymentMethod)
    {
            $this->paymentMethod = $paymentMethod;
    }

    public function get()
    {
        return $this->paymentMethod->all();
    }
}
