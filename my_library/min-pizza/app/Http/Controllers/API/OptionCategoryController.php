<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOptionCategoryRequest;
use App\Http\Requests\UpdateOptionCategoryRequest;
use App\Models\OptionCategory;
use App\Repositories\OptionCategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Lang;

class OptionCategoryController extends Controller
{

    private OptionCategoryRepositoryInterface $categoryRepository;

    public function __construct(OptionCategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->authorizeResource(OptionCategory::class);
    }

    public function index(): JsonResponse
    {
        $data = $this->categoryRepository->get();
        return response()->json(['categories' => $data]);
    }


    public function store(StoreOptionCategoryRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['name'] = translate($data['name']);
        $restaurant = auth()->user()->restaurant ?? null;
        if ($restaurant != null) {
            $data['restaurant_id'] = $restaurant->id;
            $category = $this->categoryRepository->store($data);
            return response()->json([
                "message" => Lang::get('messages.menu_category.create'),
                'category' => $category]);
        }
        return response()->json(
            ['message' => Lang::get('messages.restaurant.have_not_restaurant')],
            401
        );
    }


    public function show(OptionCategory $optionCategory): JsonResponse
    {
        $category = $this->categoryRepository->show($optionCategory);
        return response()->json(['category' => $category]);
    }


    public function update(UpdateOptionCategoryRequest $request, OptionCategory $optionCategory): JsonResponse
    {
        $data = $request->validated();
        if (isset($data['name'])) {
            $data['name'] = translate($data['name']);
        }
        $this->categoryRepository->update($optionCategory, $data);
        return response()->json(["message" => Lang::get('messages.menu_category.update')]);
    }


    public function destroy(OptionCategory $optionCategory): JsonResponse
    {
        $this->categoryRepository->delete($optionCategory);
        return response()->json(["message" => Lang::get('messages.menu_category.delete')]);
    }
}
