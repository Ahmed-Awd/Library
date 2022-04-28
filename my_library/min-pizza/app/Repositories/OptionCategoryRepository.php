<?php

namespace App\Repositories;

use App\Models\OptionCategory;

class OptionCategoryRepository implements OptionCategoryRepositoryInterface
{
    private OptionCategory $category;

    public function __construct(OptionCategory $category)
    {
        $this->category = $category;
    }

    public function get()
    {

        return $this->category->where('restaurant_id', auth()->user()->restaurant->id)->get();
    }

    public function show($category)
    {
        return OptionCategory::where('id', $category->id)->with(['optionValues'])->first();
    }

    public function showWereIn($category)
    {
        return OptionCategory::whereIn('id', $category)->with(['optionValues'])->get();
    }

    public function store($data)
    {
        return  $this->category->create($data);
    }

    public function update($category, $data)
    {
        $category->update($data);
    }

    public function delete($category)
    {
        $category->delete();
    }
}
