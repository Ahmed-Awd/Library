<?php

namespace App\Repositories;

use App\Models\OptionValue;

class OptionValueRepository implements OptionValueRepositoryInterface
{
    private OptionValue $value;

    public function __construct(OptionValue $value)
    {
        $this->value = $value;
    }

    public function get($category)
    {
        return $category->optionValues;
    }

    public function show($value)
    {
        return $this->value->where('id', $value->id)->with('category')->first();
    }

    public function store($data)
    {
        $this->value->create($data);
    }

    public function update($value, $data)
    {
        $value->update($data);
    }

    public function delete($value)
    {
        $value->delete();
    }
}
