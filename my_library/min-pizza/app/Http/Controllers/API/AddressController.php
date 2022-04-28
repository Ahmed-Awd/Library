<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use App\Models\User;
use App\Repositories\AddressRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class AddressController extends Controller
{

    private AddressRepositoryInterface $AddressRepository;

    public function __construct(AddressRepositoryInterface $AddressRepository)
    {
        $this->AddressRepository = $AddressRepository;
        $this->authorizeResource(Address::class);
    }


    public function index(): JsonResponse
    {
        $data = $this->AddressRepository->get();
        return response()->json(['addresses' => $data]);
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $this->AddressRepository->create($data);
        return response()->json(['message' => Lang::get('messages.Address.create')]);
    }

    public function show(Address $address): JsonResponse
    {
        $address = $this->AddressRepository->show($address);
        return response()->json(['data' => $address]);
    }

    public function update(UpdateAddressRequest $request, Address $address): JsonResponse
    {
        $data = $request->validated();
        $this->AddressRepository->update($data, $address);
        return response()->json(['message' => Lang::get('messages.Address.update')]);
    }


    public function destroy(Address $address): JsonResponse
    {
        $this->AddressRepository->delete($address);
        return response()->json(["message" => Lang::get('messages.Address.delete')]);
    }

    public function ofUser(User $user): JsonResponse
    {
        $data = $this->AddressRepository->userAddresses($user);
        return response()->json(['addresses' => $data]);
    }
}
