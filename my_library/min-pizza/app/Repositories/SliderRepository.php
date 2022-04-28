<?php

namespace App\Repositories;

use App\Models\Slider;

class SliderRepository implements SliderRepositoryInterface
{
    private Slider $slider;

    public function __construct(Slider $slider)
    {
        $this->slider = $slider;
    }

    public function get($type = "all")
    {
        if ($type == "all") {
            return $this->slider->all();
        }
        return $this->slider->where('is_active', 1)->get();
    }

    public function show($slider)
    {
        return $slider;
    }

    public function store($data)
    {
        if (isset($data['image'])) {
            $data['image'] = imageStore($data['image']);
        }

        $this->slider->create($data);
    }

    public function update($slider, $data)
    {
        if (isset($data['image'])) {
            $data['image'] = imageStore($data['image']);
        }
        $slider->update($data);
    }

    public function changeStatus($slider)
    {
        $slider->is_active = !$slider->is_active;
        $slider->save();
    }

    public function delete($slider)
    {
        $slider->delete();
    }
}
