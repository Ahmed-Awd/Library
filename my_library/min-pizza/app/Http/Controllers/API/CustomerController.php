<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class CustomerController extends Controller
{

    private UserRepositoryInterface $userRepository;


    public function __construct(UserRepositoryInterface $userRepository)
    {

        $this->userRepository = $userRepository;
        $this->authorizeResource(User::class);
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->get('searchTerm', '');
        $order['by']     = $request->get('sort_by', false);
        $order['type']   = $request->get('sort_type', 'asc');
        $data = $this->userRepository->get($search, $order, 'customer');
        return response()->json(['users' => $data]);
    }


    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user =  $this->userRepository->create($data);
        $user->assignRole('customer');
        return response()->json(['message' => Lang::get('messages.customer.create')]);
    }


    public function show(User $user): JsonResponse
    {
        $user = $this->userRepository->show($user);
        return response()->json(['data' => $user]);
    }


    public function update(UpdateCustomerRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();
        $this->userRepository->update($user, $data);
        return response()->json(['message' => Lang::get('messages.customer.update')]);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->userRepository->delete($user);
        return response()->json(["message" => Lang::get('messages.customer.delete')]);
    }
}
