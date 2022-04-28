<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignItemOptionsRequest;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\StoreMenuCategoryRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Requests\UpdateMenuCategoryRequest;
use App\Models\Item;
use App\Models\MenuCategory;
use App\Models\Restaurant;
use App\Repositories\MenuRepositoryInterface;
use App\Repositories\OptionCategoryRepositoryInterface;
use App\Repositories\OptionSecondaryRepositoryInterface;
use App\Repositories\OptionTemplateRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;

class MenuController extends Controller
{
    private MenuRepositoryInterface $menuRepository;
    private OptionTemplateRepositoryInterface $optionTemplateRepository;
    private OptionSecondaryRepositoryInterface $optionSecondaryRepository;
    private OptionCategoryRepositoryInterface $optionCategoryRepository;

    public function __construct(
        MenuRepositoryInterface $menuRepository,
        OptionCategoryRepositoryInterface $optionCategoryRepository,
        OptionTemplateRepositoryInterface $optionTemplateRepository,
        OptionSecondaryRepositoryInterface $optionSecondaryRepository
    ) {
        $this->menuRepository = $menuRepository;
        $this->optionCategoryRepository = $optionCategoryRepository;
        $this->optionTemplateRepository = $optionTemplateRepository;
        $this->optionSecondaryRepository = $optionSecondaryRepository;
    }

    public function index(Restaurant $restaurant): JsonResponse
    {
        $token = request()->bearerToken();
        $currency = $restaurant->city->country->currency_code;
        $menu = $this->menuRepository->menu($restaurant, $token);
        return response()->json(['data' => $menu->makeHidden(['followers']),'currency' => $currency]);
    }

    public function categories(Restaurant $restaurant): JsonResponse
    {
        $categories =  $this->menuRepository->categories($restaurant);
        return response()->json(['categories' => $categories]);
    }

    public function allItems(Restaurant $restaurant): JsonResponse
    {
        $items =  $this->menuRepository->allItems($restaurant);
        return response()->json(['items' => $items]);
    }

    public function storeCategory(Restaurant $restaurant, StoreMenuCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['name'] = translate($data['name']);
        $this->menuRepository->createCategory($restaurant, $data);
        return response()->json(["message" => Lang::get('messages.menu_category.create')]);
    }

    public function showCategory(MenuCategory $category): JsonResponse
    {
        $category = $this->menuRepository->showCategory($category);
        return response()->json(['category' => $category]);
    }

    public function updateCategory(MenuCategory $category, UpdateMenuCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        if (! Gate::allows('change-menu', $category->menu->restaurant)) {
            abort(403);
        }
        $data['name'] = translate($data['name']);
        $this->menuRepository->updateCategory($category, $data);
        return response()->json(["message" => Lang::get('messages.menu_category.update')]);
    }

    public function deleteCategory(MenuCategory $category): JsonResponse
    {
        if (! Gate::allows('change-menu', $category->menu->restaurant)) {
            abort(403);
        }
        $this->menuRepository->deleteCategory($category);
        return response()->json(["message" => Lang::get('messages.menu_category.delete')]);
    }

    public function items(MenuCategory $category): JsonResponse
    {
        $items =  $this->menuRepository->items($category);
        $currency = $category->menu->restaurant->city->country->currency_code;
        return response()->json(['items' => $items,'currency' => $currency]);
    }

    public function storeItem(MenuCategory $category, StoreItemRequest $request): JsonResponse
    {
        if (! Gate::allows('change-menu', $category->menu->restaurant)) {
            abort(403);
        }
        $data = $request->validated();
        $option_id=null;
        if (isset($data['option_id'])) {
            $option_id=$data['option_id'];
            unset($data['option_id']);
        }
        $item=$this->menuRepository->createItem($category, $data);
        if (!is_null($option_id)) {
            $this->menuRepository->assignItem($item, $option_id);
        }

        return response()->json(["message" => Lang::get('messages.menu_item.create')]);
    }

    public function showItem(Item $item): JsonResponse
    {
        $token = request()->bearerToken();
        $item = $this->menuRepository->showItem($item, $token);
        if (!is_null($item->option_template_id) && $item->optionTemplate) {
            $item->option_templates = $this->optionTemplateRepository->show($item->optionTemplate);
            $item->option_secondaries = $this->optionSecondaryRepository->get($item->optionTemplate);
        } else {
            if (count($item->options) > 0) {
                $item->option_categories = $this->optionCategoryRepository
                ->showWereIn($item->options->pluck('id')->toArray());
            }
        }
        return response()->json(['item' => $item->makeHidden(['category','options','optionTemplate'])]);
    }

    public function updateItem(Item $item, UpdateItemRequest $request): JsonResponse
    {
        if (! Gate::allows('change-menu', $item->category->menu->restaurant)) {
            abort(403);
        }
        $data = $request->validated();
        if (isset($data['option_template_id'])) {
            $this->menuRepository->unAssignItemAll($item);
        } else {
            $data['option_template_id'] = null;
        }

        $this->menuRepository->updateItem($item, $data);
        return response()->json(["message" => Lang::get('messages.menu_item.update')]);
    }

    public function deleteItem(Item $item): JsonResponse
    {
        if (! Gate::allows('change-menu', $item->category->menu->restaurant)) {
            abort(403);
        }
        $this->menuRepository->deleteItem($item);
        return response()->json(["message" => Lang::get('messages.menu_item.delete')]);
    }

    public function attachItem(AssignItemOptionsRequest $request, Item $item): JsonResponse
    {
        if (! Gate::allows('change-menu', $item->category->menu->restaurant)) {
            abort(403);
        }
        $data = $request->validated();
        $this->menuRepository->assignItem($item, $data['option_id']);
        return response()->json(["message" => Lang::get('messages.menu_item.attached')]);
    }

    public function detachItem(AssignItemOptionsRequest $request, Item $item): JsonResponse
    {
        if (! Gate::allows('change-menu', $item->category->menu->restaurant)) {
            abort(403);
        }
        $data = $request->validated();
        $this->menuRepository->unAssignItem($item, $data['option_id']);
        return response()->json(["message" => Lang::get('messages.menu_item.detached')]);
    }

    public function changeItemStatus(Item $item): JsonResponse
    {
        if (! Gate::allows('change-menu', $item->category->menu->restaurant)) {
            abort(403);
        }
        $status = $this->menuRepository->changeAvailability($item);
        return response()->json(["status" => $status]);
    }

    public function favouriteItem(Item $item): JsonResponse
    {
        $status = $this->menuRepository->favOrUnFavItem($item);
        return response()->json(["message" => Lang::get('messages.item_follow.update'),'is_favourite' => $status]);
    }
}
