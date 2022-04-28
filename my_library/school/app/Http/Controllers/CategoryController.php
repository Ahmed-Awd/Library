<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->authorizeResource(Category::class);
        $this->categoryRepository = $categoryRepository;
    }

    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->get();
        return response()->json($categories);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->categoryRepository->store($validated);
        return response()->json(["message" => "category created successfully"]);
    }


    public function show(Category $category): JsonResponse
    {
        $record = $this->categoryRepository->show($category);
        return response()->json($category);
    }

    public function update(StoreCategoryRequest $request,Category $category): JsonResponse
    {
        $validated = $request->validated();
        $this->categoryRepository->update($category->id,$validated);
        return response()->json(["message" => "category updated successfully"]);
    }


    public function destroy($id): JsonResponse
    {
        $this->categoryRepository->delete($id);
        return response()->json(["message" => "category deleted successfully"]);
    }
}
