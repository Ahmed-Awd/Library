<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class ArchiveController extends Controller
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function archiveAll(Request $request): JsonResponse
    {
        $user = User::where('id', Auth::id())->first();
        if ($user->hasRole('driver')) {
            $this->orderRepository->archive("driver", "all", false);
        }
        if ($user->hasRole('owner')) {
            $this->orderRepository->archive("owner", "all", false);
        }
        return response()->json(['message' => Lang::get('messages.archived')]);
    }

    public function archive(Request $request, Order $order): JsonResponse
    {
        $user = User::where('id', Auth::id())->first();
        if ($user->hasRole('driver')) {
            if ($order->driver_id != $user->id) {
                return response()->json(['message' => "unauthorized"], 403);
            }
            $this->orderRepository->archive("driver", "one", $order->id);
        }
        if ($user->hasRole('owner')) {
            if ($order->store_id != $user->store->id) {
                return response()->json(['message' => "unauthorized"], 403);
            }
            $this->orderRepository->archive("owner", "one", $order->id);
        }
        return response()->json(['message' => Lang::get('messages.archived')]);
    }
}
