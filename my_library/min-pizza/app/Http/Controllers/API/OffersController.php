<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MakeItemOfferRequest;
use App\Http\Requests\MakeRestaurantOfferRequest;
use App\Models\ItemOffer;
use App\Models\RestaurantOffer;
use App\Repositories\ItemOfferRepositoryInterface;
use App\Repositories\MenuRepositoryInterface;
use App\Repositories\RestaurantOfferRepositoryInterface;
use App\Repositories\RestaurantRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;

class OffersController extends Controller
{
    private RestaurantOfferRepositoryInterface $restaurantOfferRepository;
    private RestaurantRepositoryInterface $restaurantRepository;
    private ItemOfferRepositoryInterface $itemOfferRepository;
    private MenuRepositoryInterface $menuRepository;

    public function __construct(
        RestaurantOfferRepositoryInterface $restaurantOfferRepository,
        RestaurantRepositoryInterface $restaurantRepository,
        ItemOfferRepositoryInterface $itemOfferRepository,
        MenuRepositoryInterface $menuRepository
    ) {
        $this->restaurantOfferRepository = $restaurantOfferRepository;
        $this->restaurantRepository = $restaurantRepository;
        $this->itemOfferRepository = $itemOfferRepository;
        $this->menuRepository = $menuRepository;
    }


    public function getRestaurantOffers(Request $request): JsonResponse
    {
        $search = $request->get('searchTerm', '');
        $order['by']     = $request->get('sort_by', false);
        $order['type']   = $request->get('sort_type', 'asc');
        $offers = $this->restaurantOfferRepository->get($search, $order);
        return response()->json(['offers' => $offers]);
    }

    public function updateRestaurantOffer(MakeRestaurantOfferRequest $request): JsonResponse
    {
        $data = $request->validated();
        $offer = Arr::except($data, ['rank']);
        $restaurant = $data['restaurant_id'];
        $this->restaurantOfferRepository->store($offer, $restaurant);
        $this->restaurantRepository->updateRank($restaurant, $data['rank']);
        return response()->json(["message" => Lang::get('messages.update')]);
    }

    public function showRestaurantOffer(RestaurantOffer $restaurantOffer): JsonResponse
    {
        $offer = $this->restaurantOfferRepository->show($restaurantOffer);
        return response()->json(['offer' => $offer]);
    }

    public function destroyRestaurantOffer(RestaurantOffer $restaurantOffer): JsonResponse
    {
        $this->restaurantOfferRepository->delete($restaurantOffer);
        return response()->json(["message" => Lang::get('messages.delete')]);
    }


    public function getItemOffers(Request $request): JsonResponse
    {
        $search = $request->get('searchTerm', '');
        $order['by']     = $request->get('sort_by', false);
        $order['type']   = $request->get('sort_type', 'asc');
        $offers = $this->itemOfferRepository->get($search, $order);
        return response()->json(['offers' => $offers]);
    }

    public function updateItemOffer(MakeItemOfferRequest $request): JsonResponse
    {
        $data = $request->validated();
        $offer = Arr::except($data, ['rank']);
        $item = $data['item_id'];
        $this->itemOfferRepository->store($offer, $item);
        $this->menuRepository->updateRank($item, $data['rank']);
        return response()->json(["message" => Lang::get('messages.update')]);
    }

    public function showItemOffer(ItemOffer $itemOffer): JsonResponse
    {
        $offer = $this->itemOfferRepository->show($itemOffer);
        return response()->json(['offer' => $offer]);
    }

    public function destroyItemOffer(ItemOffer $itemOffer): JsonResponse
    {
        $this->itemOfferRepository->delete($itemOffer);
        return response()->json(["message" => Lang::get('messages.delete')]);
    }
}
