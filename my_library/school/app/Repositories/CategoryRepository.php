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
       return $this->category->all();
    }

    public function show(Category $category)
    {
        return $this->category->findOrFail($category->id);
    }

    public function store($data)
    {
        $record['name']  = $data['name'];
        $this->category->create($record);
    }

    public function update($id,$data)
    {
        $record = $this->category->findOrFail($id);

        $record->update($data);
    }

    public function delete($id)
    {
        $this->category->where('id',$id)->delete();
    }
}
