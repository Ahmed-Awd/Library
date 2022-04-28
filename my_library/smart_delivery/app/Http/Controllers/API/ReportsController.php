<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function driverReport(Request $request)
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $status = $request->get('order_status', false);
        $rate = $request->get('rate', false);
        $withoutPagination = $request->get('without_pagination', false);
        $filters = $this->prepareFilters($status, $rate);
        $filters['driver_id'] = Auth::id();
        $range = $this->prepareRange($from, $to);
        $orders = $this->orderRepository->get($filters, 'sent_at', $range, true, $withoutPagination);
        if ($withoutPagination == true) {
            return response()->json(['orders' => $orders['orders'] ,'totals' => $orders['totals']]);
        }
        return response()->json(['orders' => $orders]);
    }

    public function ownerReport(Request $request)
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);
        $status = $request->get('order_status', false);
        $filters = $this->prepareFilters($status, false);
        $withoutPagination = $request->get('without_pagination', false);
        $range = $this->prepareRange($from, $to);
        $filters['store_id'] = Auth::user()->store->id;
        $orders = $this->orderRepository->get($filters, 'sent_at', $range, true, $withoutPagination);
        if ($withoutPagination == true) {
            return response()->json(['orders' => $orders['orders'] ,'totals' => $orders['totals']]);
        }
        return response()->json(['orders' => $orders]);
    }

    public function prepareFilters($status, $rate): array
    {
        $filter = [];
        if ($status != false) {
            $filter['status'] = explode(',', $status);
        }
        if ($rate != false) {
            $filter['rate'] = $rate;
        }
        return $filter;
    }

    public function prepareRange($from, $to)
    {
        if ($from == false || $to == false) {
            return false;
        }
        return "$from,$to";
    }
}
