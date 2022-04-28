<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountCodeRequest;
use App\Http\Requests\UpdateDiscountCodeRequest;
use App\Models\DiscountCode;
use App\Repositories\DiscountCodeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class DiscountCodeController extends Controller
{

    private DiscountCodeRepositoryInterface $discountCodeRepository;

    public function __construct(DiscountCodeRepositoryInterface $discountCodeRepository)
    {
        $this->authorizeResource(DiscountCode::class);
        $this->discountCodeRepository = $discountCodeRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->get('searchTerm', '');
        $order['by']     = $request->get('sort_by', false);
        $order['type']   = $request->get('sort_type', 'asc');
        $data = $this->discountCodeRepository->get($search, $order);
        return response()->json(['codes' => $data]);
    }

    public function store(StoreDiscountCodeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->discountCodeRepository->store($data);
        return response()->json(['message' => Lang::get('messages.discount_code.create')]);
    }

    public function show(DiscountCode $discountCode): JsonResponse
    {
        $discountCode = $this->discountCodeRepository->show($discountCode);
        return response()->json(['code' => $discountCode]);
    }

    public function update(UpdateDiscountCodeRequest $request, DiscountCode $discountCode): JsonResponse
    {
        $data = $request->validated();
        $this->discountCodeRepository->update($discountCode, $data);
        return response()->json(['message' => Lang::get('messages.discount_code.update')]);
    }


    public function destroy(DiscountCode $discountCode): JsonResponse
    {
        $this->discountCodeRepository->delete($discountCode);
        return response()->json(["message" => Lang::get('messages.discount_code.delete')]);
    }

    public function myCodes(): JsonResponse
    {
        $codes =  $this->discountCodeRepository->myCodes();
        return response()->json(["codes" => $codes]);
    }

    public function myCodeInfo($discountCode): JsonResponse
    {
        $discountCode = $this->discountCodeRepository->getMyCode($discountCode);
        if ($discountCode->user_id != Auth::user()->id) {
            return response()->json(['message' => Lang::get('messages.discount_code.invalid_code')], 404);
        }
        return response()->json(['code' => $discountCode]);
    }
}
