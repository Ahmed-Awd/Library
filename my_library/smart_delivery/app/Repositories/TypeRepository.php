<?php

namespace App\Repositories;

use App\Models\Type;

class TypeRepository implements TypeRepositoryInterface
{
    private Type $type;

    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    public function get()
    {
        return $this->type->all();
    }
}
