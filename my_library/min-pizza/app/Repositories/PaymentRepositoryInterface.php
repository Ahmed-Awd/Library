<?php

namespace App\Repositories;

use App\Models\Payment;

interface PaymentRepositoryInterface
{
    public function show($payment);
    public function create($data);
    public function update($data, $payment);
}
