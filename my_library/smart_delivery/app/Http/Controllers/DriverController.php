<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Town;
use App\Models\User;
use App\Repositories\DriverRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\PackageCodeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;
use Illuminate\Support\Facades\Password;
use Redirect;

class DriverController extends Controller
{

    private DriverRepositoryInterface $driverRepository;
    private PackageCodeRepositoryInterface $packageCodeRepository;
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        DriverRepositoryInterface $driverRepository,
        PackageCodeRepositoryInterface $packageCodeRepository,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->driverRepository = $driverRepository;
        $this->packageCodeRepository = $packageCodeRepository;
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $searchParam = $request->get('search_value', "");
        $drivers = $this->driverRepository->get(true, $searchParam);
        foreach ($drivers as $driver) {
            if ($driver->tokens()->count() == 0) {
                $driver->current_states = "offline";
            } else {
                if ($driver->is_available) {
                    $driver->current_states = "online and available";
                } else {
                    $driver->current_states = "online but not available";
                }
            }
        }
        if ($request->headers->get('Accept') == "application/json") {
            return response()->json(['drivers' => $drivers]);
        }
        return Inertia::render('Drivers/Index', compact('drivers'));
    }

    public function create()
    {
        abort(404);
        return Inertia::render('Drivers/Create');
    }

    public function show(User $driver)
    {
        $driver = $this->driverRepository->show($driver);
        return Inertia::render('Drivers/show', compact('driver'));
    }

    public function edit(User $driver)
    {
        return Inertia::render('Drivers/Create', compact('driver'));
    }

    public function store(StoreDriverRequest $request)
    {
        $validated = $request->validated();
        $this->driverRepository->store($validated);

        Password::sendResetLink($request->only('email'));

        session()->flash('flash.banner', 'A new driver created');
        session()->flash('flash.bannerStyle', 'success');

        return redirect()->route('drivers.index');
    }

    public function update(UpdateDriverRequest $request, User $driver)
    {
        $res = $request->validated();
        $this->driverRepository->updateMyInfo(
            $driver,
            arr::only($res, ['name','username','country_code','phone','driver_type'])
        );
        $papers = $this->updatePapers($res);
        $this->driverRepository->forceUpdatePapers(
            $driver,
            arr::only($papers, ["personal_photo","license_photo","vehicle_photo","vehicle_license_photo"])
        );

        session()->flash('flash.banner', Lang::get('messages.record.update'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('drivers.index');
    }

    public function updatePapers($res)
    {
        $res['vehicle_license'] = null;
        if ($res['personal_photo'] != null) {
            $res['personal_photo'] = saveImage($res['personal_photo']);
        }
        if ($res['license_photo'] != null) {
            $res['license_photo'] = saveImage($res['license_photo']);
        }
        if ($res['vehicle_photo'] != null) {
            $res['vehicle_photo'] = saveImage($res['vehicle_photo']);
        }
        if ($res['vehicle_license_photo'] != null) {
            $res['vehicle_license_photo'] = saveImage($res['vehicle_license_photo']);
        }
        return $res;
    }

    public function destroy(User $driver)
    {
//        $this->driverRepository->delete($driver);
//        session()->flash('flash.banner', "Driver $driver->name deleted successfully");
//        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('drivers.index');
    }

    public function history(User $driver, Request $request)
    {
        $range = $request->get('range', false);
        $type = $request->get('type', "all");
        $records = [];
        if ($type == "ingoing") {
            $records = $this->packageCodeRepository->history($range, $driver);
        }
        if ($type == "outgoing") {
            $records = $this->orderRepository->history($range, $driver);
        }
        if ($type == "all") {
            $records = $this->packageCodeRepository->history($range, $driver);
            $records = $this->orderRepository->history($range, $driver)->union($records);
        }
        $totals = $this->getTotals($range, $driver, $type);
        $records ? $records = $records->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query())
            : $records = [];
        return Inertia::render('Drivers/transactions', compact('records', 'driver', 'totals'));
    }

    public function getTotals($range, $user, $type)
    {
        $data = [];
        $data['outgoing'] = 0;
        $data['ingoing'] = 0;
        if ($type == "ingoing") {
            $data['ingoing']  = $this->packageCodeRepository->getTotalIngoing($range, $user);
        }
        if ($type == "outgoing") {
            $data['outgoing']  = $this->orderRepository->getTotalOutgoing($range, $user);
        }
        if ($type == "all") {
            $data['ingoing']  = $this->packageCodeRepository->getTotalIngoing($range, $user);
            $data['outgoing']  = $this->orderRepository->getTotalOutgoing($range, $user);
        }
        $data['total'] = floatval($data['ingoing']) + floatval($data['outgoing']);
        return $data;
    }

    public function newPapers(User $user)
    {
        $papers = $user->newPapers;
        $driver = $user;
        if ($papers == null) {
            session()->flash('flash.banner', "Driver $user->name has no new papers");
            session()->flash('flash.bannerStyle', 'danger');
            return redirect()->back();
        }
        return Inertia::render('Drivers/newPapers', compact('papers', 'driver'));
    }

    public function confirmPapers(Request $request, User $user)
    {
        $current = $user->newPapers;
        if ($current != null) {
            $papers['personal_photo'] = $current->personal_photo;
            $papers['license_photo'] = $current->license_photo ;
            $papers['vehicle_photo'] = $current->vehicle_photo ;
            $papers['vehicle_license_photo'] = $current->vehicle_license_photo ;
            $this->driverRepository->forceUpdatePapers($user, $papers);
            $this->driverRepository->cleanPapers($user);
        }
        $this->driverRepository->updateMyInfo($user, ["has_new_papers" => false]);
        session()->flash('flash.banner', Lang::get('messages.record.update'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('drivers.index');
    }

    public function generalMap(Town $town, Request $request)
    {
        $data['available_drivers'] = $this->driverRepository->availableDrivers($town->id, 1);
        $data['unavailable_drivers'] = $this->driverRepository->availableDrivers($town->id, 0);
        $data['town'] = $town;
        if ($request->headers->get('Accept') == "application/json") {
            return response()->json(['data' => $data]);
        }
        return Inertia::render('Drivers/GeneralMap', $data);
    }

    public function getAvailableDrivers()
    {
        $drivers = $this->driverRepository->freeDrivers();
        return response()->json(['data' => $drivers]);
    }
}
