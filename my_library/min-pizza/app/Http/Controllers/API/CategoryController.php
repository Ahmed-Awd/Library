<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;

class CategoryController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->authorizeResource(Category::class);
        $this->categoryRepository = $categoryRepository;
    }

    public function all(): JsonResponse
    {
        $categories = $this->categoryRepository->get();
        return response()->json(['categories' => $categories]);
    }

    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->all();
        return response()->json(['categories' => $categories]);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['name'] = translate($validated['name']);
        $this->categoryRepository->store($validated);
        return response()->json(["message" => Lang::get('messages.restaurant_category.create')]);
    }


    public function view(Category $category): JsonResponse
    {
        $category = $this->categoryRepository->show($category);
        return response()->json(['category' => $category]);
    }

    public function update(StoreCategoryRequest $request, Category $category): JsonResponse
    {
        $validated = $request->validated();
        if (isset($validated['name'])) {
            $validated['name'] = translate($validated['name']);
        }
        $this->categoryRepository->update($category->id, $validated);
        return response()->json(["message" => Lang::get('messages.restaurant_category.update')]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $this->categoryRepository->delete($category->id);
        return response()->json(["message" => Lang::get('messages.restaurant_category.delete')]);
    }

    public function getRestaurants(Category $category): JsonResponse
    {
        $restaurants = $this->categoryRepository->restaurants($category);
        return response()->json(['category' => $category,'restaurants' => $restaurants]);
    }
}
