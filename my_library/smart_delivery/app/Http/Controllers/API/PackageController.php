<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\PackageRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{

    private PackageRepositoryInterface $packageRepository;

    public function __construct(PackageRepositoryInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function index()
    {
        $type = Auth::user()->roles->pluck('name')->toArray()[0];
        $packages = $this->packageRepository->get($type);
        return response()->json(['packages' => $packages->makeHidden(['type'])]);
    }
}
