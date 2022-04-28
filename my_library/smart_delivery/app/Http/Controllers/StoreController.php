<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\AuthController;
use App\Http\Requests\SaveStoreSettingsRequest;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\UpdateStoreRequest;
use App\Models\Store;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\PackageCodeRepositoryInterface;
use App\Repositories\StoreRepositoryInterface;
use App\Repositories\TownRepositoryInterface;
use App\Repositories\TypeRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;

class StoreController extends Controller
{
    private StoreRepositoryInterface $storeRepository;
    private TypeRepositoryInterface $typeRepository;
    private OrderRepositoryInterface $orderRepository;
    private PackageCodeRepositoryInterface $packageCodeRepository;
    private TownRepositoryInterface $townRepository;

    public function __construct(
        StoreRepositoryInterface $storeRepository,
        TypeRepositoryInterface $typeRepository,
        OrderRepositoryInterface $orderRepository,
        TownRepositoryInterface $townRepository,
        PackageCodeRepositoryInterface $packageCodeRepository
    ) {
        $this->storeRepository = $storeRepository;
        $this->typeRepository = $typeRepository;
        $this->townRepository = $townRepository;
        $this->orderRepository = $orderRepository;
        $this->packageCodeRepository = $packageCodeRepository;
    }


    public function index(Request $request)
    {
        $filter = $request->get('filter', false);
        $data['stores'] = $this->storeRepository->get($filter);
        return Inertia::render('Stores/Index', $data);
    }

    public function create()
    {
        $data['types']  = $this->typeRepository->get();
        $data['towns']  = $this->townRepository->get();
        return Inertia::render('Stores/Create', $data);
    }

    public function store(StoreStoreRequest $request)
    {
        $validated = $request->validated();
        $this->storeRepository->store($validated);

        //Password::sendResetLink(['email' => $request->owner_email]);

        session()->flash('flash.banner',  Lang::get('messages.record.create'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('stores.index');
    }

    public function edit(Store $store)
    {
        $data['store'] = $this->storeRepository->show($store);
        $data['types']  = $this->typeRepository->get();
        $data['towns']  = $this->townRepository->get();
        return Inertia::render('Stores/Create', $data);
    }

    public function update(UpdateStoreRequest $request, Store $store)
    {
        $validated = $request->validated();
        $this->storeRepository->update($store, $validated);
        session()->flash('flash.banner',  Lang::get('messages.record.update'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('stores.index');
    }

    public function destroy(Store $store)
    {
        $this->storeRepository->delete($store);
        session()->flash('flash.banner',  Lang::get('messages.record.delete'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('stores.index');
    }

    public function view(Store $store)
    {
        $data['record'] = $this->storeRepository->show($store);
        return Inertia::render('Stores/Info', $data);
    }

    public function history(Store $store, Request $request)
    {
        $range = $request->get('range', false);
        $type = $request->get('type', "all");
        $user = $store->owner;
        $records = [];
        if ($type == "ingoing") {
            $records = $this->packageCodeRepository->history($range, $user);
        }
        if ($type == "outgoing") {
            $records = $this->orderRepository->history($range, $user);
        }
        if ($type == "all") {
            $records = $this->packageCodeRepository->history($range, $user);
            $records = $this->orderRepository->history($range, $user)->union($records);
        }
        $totals = $this->getTotals($range, $user, $type);
        $records ? $records = $records->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query())
            : $records = [];
        return Inertia::render('Stores/transactions', compact('records', 'store', 'totals'));
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

    public function settings(Store $store)
    {
        $store = $this->storeRepository->show($store);
        return Inertia::render('Stores/Setting', compact('store'));
    }

    public function saveSettings(SaveStoreSettingsRequest $request, Store $store)
    {
        $validated = $request->validated();
        if (!Auth::user()->hasRole('admin')) {
            unset($validated['taken_percentage_from_store']);
        }
        $this->storeRepository->saveSettings($store, $validated);
        return redirect()->back();
    }
}
