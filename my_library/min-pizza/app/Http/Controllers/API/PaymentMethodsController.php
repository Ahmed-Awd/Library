<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\PaymentMethodsRepositoryInterface;
use Illuminate\Http\Request;

class PaymentMethodsController extends Controller
{
    private PaymentMethodsRepositoryInterface $paymentMethodsRepository;

    public function __construct(PaymentMethodsRepositoryInterface $paymentMethodsRepository)
    {
        $this->paymentMethodsRepository = $paymentMethodsRepository;
    }

    public function get(): \Illuminate\Http\JsonResponse
    {
        $methods = $this->paymentMethodsRepository->get();
        return response()->json(['methods' => $methods]);
    }
}
