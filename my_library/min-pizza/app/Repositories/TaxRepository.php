<?php

namespace App\Repositories;

use App\Models\Tax;

class TaxRepository implements TaxRepositoryInterface
{
    private Tax $tax;

    public function __construct(Tax $tax)
    {
        $this->tax = $tax;
    }

    public function get()
    {
        return $this->tax->all();
    }

    public function show($tax)
    {
        return $tax;
    }

    public function store($data)
    {
        $this->tax->create($data);
    }

    public function update($tax, $data)
    {
        $tax->update($data);
    }

    public function delete($tax)
    {
        $tax->delete();
    }
}
