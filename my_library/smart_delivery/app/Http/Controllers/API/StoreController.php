<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\SaveStoreSettingsRequest;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\Store;
use App\Models\Type;
use App\Repositories\StoreOutSourceRepository;
use App\Repositories\StoreRepositoryInterface;
use App\Repositories\TypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    private StoreOutSourceRepository $storeRepository;
    private StoreRepositoryInterface $storeMainRepository;

    public function __construct(
        StoreOutSourceRepository $storeRepository,
        StoreRepositoryInterface $storeMainRepository
    ) {
        $this->storeRepository = $storeRepository;
        $this->storeMainRepository = $storeMainRepository;
    }


    public function index(): JsonResponse
    {
        $filters['outsource_id'] = auth()->user()->id;

        $stores = $this->storeRepository->get($filters);

        return response()->json([
            'stores' => $stores,
        ]);
    }

    public function store(StoreStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->storeRepository->store($validated);

        Password::sendResetLink(['email' => $request->owner_email]);

        return response()->json(['message' => Lang::get('messages.process.create')], 200);
    }
    public function update(UpdateStoreRequest $request, Store $store): JsonResponse
    {
        $validated = $request->validated();
        $this->storeRepository->update($store, $validated);

        return response()->json(['message' => Lang::get('messages.process.update')], 200);
    }

    public function destroy(Store $store): JsonResponse
    {
        $this->storeRepository->delete($store);
        return response()->json(['message' => Lang::get('messages.process.delete')], 200);
    }

    public function show(Store $store)
    {
        $data = $this->storeRepository->show($store);
        return response()->json([
            'store' => $data,
        ], 200);
    }

    public function saveSettings(SaveStoreSettingsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->storeMainRepository->saveSettings(Auth::user()->store, $validated);
        return response()->json(['message' => Lang::get('messages.process.save')], 200);
    }

    public function settings(): JsonResponse
    {
        $data = $this->storeRepository->show(Auth::user()->store);
        return response()->json([
            'setting' => $data->setting,
        ], 200);
    }

    public function types(): JsonResponse
    {
        return response()->json([
            'types' => Type::all(),
        ], 200);
    }
}
