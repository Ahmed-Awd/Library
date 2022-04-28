<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use App\Repositories\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    private Category $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function get()
    {
        return $this->category->where('is_active', 1)->get();
    }

    public function all()
    {
        return $this->category->all();
    }

    public function show(Category $category)
    {
        return $this->category->where('id', $category->id)->first();
    }

    public function restaurants($category)
    {
        return $category->restaurants()->paginate(15);
    }

    public function store($data)
    {
        if (isset($data['image'])) {
            $data['image'] = imageStore($data['image']);
        }
        $this->category->create($data);
    }

    public function update($id, $data)
    {
        if (isset($data['image'])) {
            if ($data['image'] != null) {
                $data['image'] = imageStore($data['image']);
            }
        }
        $record = $this->category->findOrFail($id);
        $record->update($data);
    }

    public function delete($id)
    {
        $this->category->where('id', $id)->delete();
    }
}
