<?php

namespace App\Http\Controllers;

use App\Repositories\TypeRepositoryInterface;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    private TypeRepositoryInterface $typeRepository;

    public function __construct(TypeRepositoryInterface $typeRepository)
    {
        $this->typeRepository = $typeRepository;
    }
}
