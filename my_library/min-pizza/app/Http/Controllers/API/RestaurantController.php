<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignMethodRequest;
use App\Http\Requests\StoreRestaurantRequest;
use App\Http\Requests\UpdateRestaurantDistancesRequest;
use App\Http\Requests\UpdateRestaurantPaymentRequest;
use App\Http\Requests\UpdateRestaurantPhonesRequest;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Http\Requests\UpdateRestaurantStatusRequest;
use App\Models\Restaurant;
use App\Repositories\DeliveryPriceOptionRepositoryInterface;
use App\Repositories\RestaurantCategoryRepositoryInterface;
use App\Repositories\RestaurantDistanceRepositoryInterface;
use App\Repositories\RestaurantPhoneRepositoryInterface;
use App\Repositories\RestaurantRepositoryInterface;
use App\Repositories\RestaurantStatusRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class RestaurantController extends Controller
{

    private RestaurantRepositoryInterface $restaurantRepository;
    private RestaurantCategoryRepositoryInterface $restaurantCategoryRepository;
    private RestaurantPhoneRepositoryInterface $restaurantPhoneRepository;
    private RestaurantStatusRepositoryInterface $restaurantStatusRepository;
    private DeliveryPriceOptionRepositoryInterface $deliveryPriceOptionRepository;
    private UserRepositoryInterface $userRepository;
    private RestaurantDistanceRepositoryInterface $distanceRepository;

    public function __construct(
        RestaurantRepositoryInterface $restaurantRepository,
        RestaurantCategoryRepositoryInterface $restaurantCategoryRepository,
        RestaurantPhoneRepositoryInterface $restaurantPhoneRepository,
        RestaurantStatusRepositoryInterface $restaurantStatusRepository,
        DeliveryPriceOptionRepositoryInterface $deliveryPriceOptionRepository,
        RestaurantDistanceRepositoryInterface $distanceRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->authorizeResource(Restaurant::class);
        $this->restaurantRepository = $restaurantRepository;
        $this->restaurantCategoryRepository = $restaurantCategoryRepository;
        $this->restaurantPhoneRepository = $restaurantPhoneRepository;
        $this->restaurantStatusRepository = $restaurantStatusRepository;
        $this->deliveryPriceOptionRepository = $deliveryPriceOptionRepository;
        $this->distanceRepository = $distanceRepository;
        $this->userRepository = $userRepository;
    }


    public function index(Request $request): JsonResponse
    {
        $restaurants = [];
        return response()->json(['restaurants' => $restaurants], 200);
    }

    public function all(Request $request): JsonResponse
    {
        $filters = $this->getFilters($request);
        $location['lat'] = $request->get('lat', false);
        $location['lng'] = $request->get('lng', false);
        $order['by']     = $request->get('orderBy', false);
        $order['type']   = $request->get('orderType', 'asc');
        $token = request()->bearerToken();
        $restaurants = $this->restaurantRepository->get($filters, $token, $location, $order);
        return response()->json(['restaurants' => $restaurants], 200);
    }

    public function getForAdmin(Request $request): JsonResponse
    {
        $filters = $this->getFilters($request);
        $order['by']     = $request->get('orderBy', false);
        $order['type']   = $request->get('orderType', 'asc');
        $restaurants = $this->restaurantRepository->getForAdmin($filters, $order);
        return response()->json(['restaurants' => $restaurants], 200);
    }

    public function getFilters($request): array
    {
        $filters['category'] = $request->get('category', false);
        $filters['offers'] = $request->get('offers', false);
        $filters['rate'] = $request->get('rate', false);
        $filters['distance'] = $request->get('distance', false);
        $filters['name'] = $request->get('name', false);
        $filters['free_delivery'] = $request->get('free_delivery', false);
        $filters['rest_type'] = $request->get('rest_type', false);
        return $filters;
    }

    public function lite(): JsonResponse
    {
        $restaurants = $this->restaurantRepository->all();
        return response()->json(['restaurants' => $restaurants], 200);
    }

    public function store(StoreRestaurantRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $rest_id = $this->restaurantRepository->store($validated);
        $this->deliveryPriceOptionRepository->store($rest_id, $validated);
        if (isset($validated['categories'])) {
            foreach ($validated['categories'] as $category) {
                $this->restaurantCategoryRepository->store($rest_id, $category);
            }
        }

        // Password::sendResetLink(['email' => $request->owner_email]);
        return response()->json(["message" => Lang::get('messages.restaurant.create'),"restaurant" => $rest_id], 200);
    }


    public function view(Request $request, Restaurant $restaurant): JsonResponse
    {
        $token = request()->bearerToken();
        $location['lat'] = $request->get('lat', false);
        $location['lng'] = $request->get('lng', false);
        $currency = $restaurant->city->country->currency_code;
        $restaurant = $this->restaurantRepository->show($restaurant, $token , $location);
        return response()->json(['restaurant' => $restaurant,'currency' => $currency], 200);
    }

    public function status(): JsonResponse
    {
        $status = $this->restaurantStatusRepository->get();
        return response()->json(['status' => $status], 200);
    }

    public function updateStatus(UpdateRestaurantStatusRequest $request, Restaurant $restaurant): JsonResponse
    {
        $validated = $request->validated();
        $this->restaurantRepository->updateStatus($restaurant->id, $validated);
        return response()->json(["message" => Lang::get('messages.restaurant_status.update')]);
    }

    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant): JsonResponse
    {
        $validated = $request->validated();
        $this->restaurantRepository->update($restaurant->id, Arr::except(
            $validated,
            ['password','owner_name','owner_email','delivery_type','delivery_value','delivery_kilometer','categories','country_code','phone']
        ));
        $validated['name'] = $validated['owner_name'];
        $validated['email'] = $validated['owner_email'];
        $this->userRepository->update($restaurant->owner, Arr::only($validated, ['password','name','email']));
        if (isset($validated['delivery_type'])) {
            $this->deliveryPriceOptionRepository->delete($restaurant->id);
            $this->deliveryPriceOptionRepository->store($restaurant->id, $validated);
        }
        if (isset($validated['categories'])) {
            $this->restaurantCategoryRepository->delete($restaurant->id);
            foreach ($validated['categories'] as $category) {
                $this->restaurantCategoryRepository->store($restaurant->id, $category);
            }
        }
        return response()->json(["message" => Lang::get('messages.restaurant.update')]);
    }


    public function destroy(Restaurant $restaurant): JsonResponse
    {
        $this->restaurantRepository->delete($restaurant->id);
        return response()->json(["message" => Lang::get('messages.restaurant.delete')]);
    }

    public function myFollowers(): JsonResponse
    {
        $myFollowers = $this->restaurantRepository->showFollowers();
        return response()->json(['myFollowers' => $myFollowers]);
    }

    public function updatePhones(UpdateRestaurantPhonesRequest $request, Restaurant $restaurant): JsonResponse
    {
        $validated = $request->validated();
        $phones = [];
        $country_code = $restaurant->city->country->calling_code;
        foreach ($validated['phones'] as $phone) {
            $current = [];
            $current['phone'] = $phone;
            $current['country_code'] = $country_code;
            $phones[] = $current;
        }
        $this->restaurantPhoneRepository->update($phones, $restaurant);
        return response()->json(["message" => Lang::get('messages.restaurant.update')]);
    }

    public function updateDistances(UpdateRestaurantDistancesRequest $request, Restaurant $restaurant): JsonResponse
    {
        $validated = $request->validated();
        $this->distanceRepository->update($validated['distances'], $restaurant);
        return response()->json(["message" => Lang::get('messages.restaurant.update')]);
    }

    public function updatePayments(UpdateRestaurantPaymentRequest $request, Restaurant $restaurant): JsonResponse
    {
        $validated = $request->validated();
        $this->restaurantRepository->assignMethod($restaurant, $validated['payments']);
        return response()->json(["message" => Lang::get('messages.restaurant.update')]);
    }

    public function switchToOnline(Request $request): JsonResponse
    {
        $user = Auth::user();
        $user->update(["last_seen" => Carbon::now()]);
        $user->restaurant()->update(["mode" => "online"]);
        return response()->json(["message" => Lang::get('messages.restaurant.update')
            ,"extra" => "restaurant of name {$user->restaurant->name} has been updated to online"]);
    }

    public function switchToOffline(Request $request): JsonResponse
    {
        $user = Auth::user();
        $user->restaurant()->update(["mode" => "offline"]);
        return response()->json(["message" => Lang::get('messages.restaurant.update'),
            "extra" => "restaurant of name {$user->restaurant->name} has been updated to offline"]);
    }
}
