<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Models\Package;
use App\Repositories\PackageRepositoryInterface;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;

class PackageController extends Controller
{
    private PackageRepositoryInterface $packageRepository;

    public function __construct(PackageRepositoryInterface $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function index()
    {
        $packages = $this->packageRepository->get();
        return Inertia::render('Packages/Index', compact('packages'));
    }

    public function create()
    {
        return Inertia::render('Packages/Create');
    }

    public function edit(Package $package)
    {
        $balancePackage = $package;
        $balancePackage->price = $package->price / 100;
        $balancePackage->value = $package->value / 100;
        return Inertia::render('Packages/Create', compact('balancePackage'));
    }

    public function store(StorePackageRequest $request)
    {
        $validated = $request->validated();
        $this->packageRepository->store($validated);
        session()->flash('flash.banner', Lang::get('messages.record.create'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('packages.index');
    }

    public function update(UpdatePackageRequest $request, Package $package)
    {
        $validated = $request->validated();
        $this->packageRepository->update($package, $validated);
        session()->flash('flash.banner', Lang::get('messages.record.update'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('packages.index');
    }

    public function destroy(Package $package)
    {
        $this->packageRepository->delete($package);
        session()->flash('flash.banner',Lang::get('messages.record.delete'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('packages.index');
    }


}
