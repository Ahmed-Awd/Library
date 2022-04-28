<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Http\Requests\UpdateMyLocationRequest;
use App\Models\Driver;
use App\Repositories\DriverRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class DriverController extends Controller
{

    private UserRepositoryInterface $userRepository;
    private DriverRepositoryInterface $driverRepository;

    public function __construct(UserRepositoryInterface $userRepository, DriverRepositoryInterface $driverRepository)
    {
        $this->driverRepository = $driverRepository;
        $this->userRepository = $userRepository;
        $this->authorizeResource(Driver::class);
    }

    public function index(Request $request): JsonResponse
    {
        $search = $request->get('searchTerm', '');
        $order['by']     = $request->get('sort_by', false);
        $order['type']   = $request->get('sort_type', 'asc');
        $data = $this->driverRepository->get($search, $order);
        return response()->json(['drivers' => $data]);
    }


    public function store(StoreDriverRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user =  $this->userRepository->create(Arr::except($data, ['type','restaurant_id']));
        $driver = Arr::except($data, ['name','email','password','profile_photo_path','country_code','phone','city_id']);
        $this->driverRepository->create($user, $driver);
        return response()->json(['message' => Lang::get('messages.driver.create'),'user' => $user]);
    }


    public function show(Driver $driver): JsonResponse
    {
        $driver = $this->driverRepository->show($driver);
        return response()->json(['data' => $driver]);
    }


    public function update(UpdateDriverRequest $request, Driver $driver): JsonResponse
    {
        $data = $request->validated();
        $this->userRepository->update($driver->user, Arr::except($data, ['type','restaurant_id']));
        $this->driverRepository->update($driver, Arr::except($data, ['name','country_code','phone','city_id']));
        return response()->json(['message' => Lang::get('messages.driver.update')]);
    }

    public function destroy(Driver $driver): JsonResponse
    {
        $this->driverRepository->delete($driver);
        return response()->json(["message" => Lang::get('messages.driver.delete')]);
    }

    public function changeStatus(): JsonResponse
    {
        $status = $this->driverRepository->changeStatus(Auth::user()->driver);
        $status == true ?  $res = Lang::get('messages.driver.active') : $res = Lang::get('messages.driver.in_active');
        return response()->json(['message' => Lang::get('messages.driver.status_changed', ["status" => $res])]);
    }

    public function updateMyLocation(UpdateMyLocationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->driverRepository->updateLocation($data);
        return response()->json(['message' => Lang::get('messages.update')]);
    }

    public function getAllAvl(): JsonResponse
    {
        $drivers = $this->driverRepository->getAllAvl();
        return response()->json(['drivers' => $drivers]);
    }
}
