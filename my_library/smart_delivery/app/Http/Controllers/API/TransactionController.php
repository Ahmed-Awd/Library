<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\PackageCodeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private PackageCodeRepositoryInterface $packageCodeRepository;
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        PackageCodeRepositoryInterface $packageCodeRepository,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->packageCodeRepository = $packageCodeRepository;
        $this->orderRepository = $orderRepository;
    }

    public function history(Request $request, $type = "all"): JsonResponse
    {
        $range = $request->get('range', false);
        $records = [];
        if ($type == "ingoing") {
            $records = $this->packageCodeRepository->history($range)->orderBy('created_at', 'desc');
        }
        if ($type == "outgoing") {
            $records = $this->orderRepository->history($range)->orderBy('created_at', 'desc');
        }
        if ($type == "all") {
            $records = $this->packageCodeRepository->history($range);
            $records = $this->orderRepository->history($range)->union($records);
        }
        $records = $records->orderBy('created_at', 'desc')->paginate(15);
        return response()->json($records);
    }
}
