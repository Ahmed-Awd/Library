<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use App\Repositories\CountryRepositoryInterface;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{

    public function __construct()
    {
    }

    public function index(): JsonResponse
    {
        $order_status = OrderStatus::all();
        return response()->json(['order_status' => $order_status]);
    }

    public function getAdminStatus(): JsonResponse
    {
        $order_status = OrderStatus::whereIn('id', ['2','7','9'])->get();
        return response()->json(['order_status' => $order_status]);
    }

    public function generatePDF()
    {
        $data = [
            'title' => 'Welcome to awd.com',
            'date' => date('m/d/Y')
        ];
        $pdf = PDF::loadView('test_pdf', $data);

        return $pdf->download('itsolutionstuff.pdf');
    }
}
