<?php

namespace App\Http\Controllers;

use App\Models\PackageCode;
use App\Models\Store;
use App\Models\User;
use Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'role:admin|owner']);
    }

    public function index()
    {
        // TODO: refactor later
        $cacheKey = 'dashboard-cards-' . auth()->id();
        $cards = Cache::remember($cacheKey, now()->endOfDay(), function () {

            if (auth()->user()->hasRole('admin')) {
                $packageCode = PackageCode::whereNotNull('user_id')
                ->join('packages', 'package_id', 'packages.id');
                $store = Store::orderBy('id', 'asc');
            } else {
                $store = Store::where('owner_id', auth()->user()->id)->orderBy('id', 'asc');
                $packageCode = PackageCode::where('user_id', auth()->user()->id)
                ->join('packages', 'package_id', 'packages.id');
            }
            return [
                'stores' => [
                    'title' => 'Stores Count',
                    'icon' => 'store',
                    'number' => $store->count(),
                ],
                'drivers' => [
                    'title' => 'Drivers Count',
                    'icon' => 'car',
                    'number' => User::role('driver')->count(),
                ],
                'packages' => [
                    'title' => 'Buyed Packages',
                    'icon' => 'package',
                    'number' => $packageCode->count(),
                ],
                'earnings' => [
                    'title' => 'Earnings',
                    'icon' => 'cash-coin',
                    'number' => $packageCode->sum('price') / 100,
                ]
            ];
        });

        $monthlyEarnings = null;
        if (auth()->user()->hasRole('admin')) {
            $storedEarnings = PackageCode::select([
                    DB::raw('DATE_FORMAT(purchased_at, "%c") as month'),
                    DB::raw('SUM(price) as price')
                ])
                ->join('packages', 'package_id', 'packages.id')
                ->whereNotNull('user_id')
                ->whereYear('purchased_at', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $monthlyEarnings = array_fill(0, 12, 0);

            foreach ($storedEarnings as $record) {
                $monthlyEarnings[$record->month - 1] = $record->price / 100;
            }
        }

        return Inertia::render('Dashboard', compact('cards', 'monthlyEarnings'));
    }

    public function seeder()
    {
        Artisan::call('db:seed --class=OrderStatusSeeder');
        //Artisan::call('db:seed');
        return redirect()->route('dashboard');
    }
}
