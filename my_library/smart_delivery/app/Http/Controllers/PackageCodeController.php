<?php

namespace App\Http\Controllers;

use App\Repositories\PackageRepositoryInterface;
use Illuminate\Http\Request;

use App\Models\PackageCode;
use App\Repositories\PackageCodeRepositoryInterface;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;

class PackageCodeController extends Controller
{
    private PackageCodeRepositoryInterface $packageCodeRepository;
    private PackageRepositoryInterface $packageRepository;

    public function __construct(
        PackageCodeRepositoryInterface $packageCodeRepository,
        PackageRepositoryInterface $packageRepository
    ) {
        $this->packageCodeRepository = $packageCodeRepository;
        $this->packageRepository = $packageRepository;
    }

    public function index($package_id, Request $request)
    {
        $package = $this->packageRepository->show($package_id);
        $status = isset($request['status']) ? $request['status'] : null;
        $range = $request->get('range', false);
        $type = $request->get('type', "all");
        $codes = $this->packageCodeRepository->get($package_id, $type, $range);
        $totalPrice = $package->price * $codes->total();
        $type = $package->type;
        return Inertia::render('Packages/Codes', compact('codes', 'package_id', 'status', 'type', 'totalPrice'));
    }

    public function allCodes(Request $request)
    {
        $range = $request->get('range', false);
        $type = $request->get('type', "all");
        $userType = $request->get('userType', "all");
        $codes = $this->packageCodeRepository->all($type, $range, $userType);
        $data['codes'] = $codes['codes'];
        $data['totalPrice'] = $codes['total'];
        return Inertia::render('Packages/AllCodes', $data);
    }

    public function store($package_id)
    {
        $this->packageCodeRepository->store($package_id);
        session()->flash('flash.banner', Lang::get('messages.record.create'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('packages.codes.index', $package_id);
    }

    public function destroy(PackageCode $packageCode)
    {
        $this->packageCodeRepository->delete($packageCode);
        session()->flash('flash.banner', Lang::get('messages.record.delete'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('packages.codes.index', $packageCode->package_id);
    }
}
