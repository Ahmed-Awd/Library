<?php

/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 4/7/2021
 * Time: 11:17 AM
 */

namespace App\Repositories;

use App\Models\Package;
use App\Models\PackageCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class PackageCodeRepository implements PackageCodeRepositoryInterface
{
    private PackageCode $packageCode;
    private Package $package;

    public function __construct(PackageCode $packageCode, Package $package)
    {
        $this->packageCode = $packageCode;
        $this->package = $package;
    }

    public function get($package_id, $type = "all", $range = false)
    {
        $codes = [];
        if ($type == "all") {
            $codes = $this->packageCode->where('package_id', $package_id)
                ->with('user:id,name')->with('user.store:id,name,owner_id');
        }
        if ($type == "used") {
            $codes = $this->packageCode->where('package_id', $package_id)
                ->with('user:id,name')->with('user.store:id,name,owner_id')
                ->whereNotNull('user_id');
        }
        if ($type == "new") {
            $codes = $this->packageCode->where('package_id', $package_id)
                ->with('user:id,name')->whereNull('user_id');
        }
        $codes = dateFilter($codes, $range, 'created_at');
        return $codes->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());
    }

    public function all($type = "all", $range = false, $userType = "all")
    {
        $data = [];
        $codes = $this->packageCode->has('package');
        if ($userType != "all") {
            $packages = $this->package->where('type', $userType)->get()->pluck('id');
            $codes = $codes->whereIn('package_id', $packages);
        }
        if ($type == "all") {
            $codes = $codes->with('user:id,name')->with('package:id,package_name,type,price')
                ->with('user.store:id,name,owner_id');
        }
        if ($type == "used") {
            $codes = $codes->with('user:id,name')->with('package:id,package_name,type,price')
                ->with('user.store:id,name,owner_id')
                ->whereNotNull('user_id');
        }
        if ($type == "new") {
            $codes = $codes->with('user:id,name')->with('package:id,package_name,type,price')->whereNull('user_id');
        }
        $codes = dateFilter($codes, $range, 'created_at');
        $data['total'] = $codes->get()->sum('package.price');
        $data['codes'] = $codes->orderBy('created_at', 'desc')->paginate(15)->appends(request()->query());
        return $data;
    }

    public function store($package_id)
    {
        do {
            $code = Str::random(config('packages.code_length'));
            $packageCode = PackageCode::where('code', $code)->first();
        } while ($packageCode != null);

        $data['code'] = $code;
        $data['package_id'] = $package_id;
        $data['value'] = $this->package->find($package_id)->price;
        $this->packageCode->create($data);
    }

    public function delete($packageCode)
    {
        $packageCode->delete();
    }

    public function charge($code): array
    {
        $chargeCode = $this->packageCode->where('code', $code)->first();
        if ($this->invalidCode($chargeCode)) {
            $this->failChargeAttempt();
            $response["message"] = Lang::get('messages.charge.invalid');
            $response["code"] = 400;
            return $response ;
        }
        if ($chargeCode->user_id != null) {
            $this->failChargeAttempt();
            $response["message"] = Lang::get('messages.charge.already');
            $response["code"] = 400;
            return $response ;
        }
        $this->charging($chargeCode);
        $response["message"] = Lang::get('messages.charge.success');
        $response["code"] = 200;
        return $response ;
    }

    public function invalidCode($code): bool
    {
        if (!$code) {
            return true;
        }
        $role = $code->package->type;
        if (!Auth::user()->hasRole($role)) {
            return true;
        }
        return false;
    }

    public function failChargeAttempt()
    {
        $user = Auth::user();
        $user->increment('charge_fail_attempts');
        $user->last_fail_charge = Carbon::now();
        $user->save();
    }

    public function charging($chargeCode)
    {
        $chargeCode->user_id = Auth::user()->id;
        $chargeCode->purchased_at = Carbon::now();
        $chargeCode->save();
        $this->chargeBalance($chargeCode->package->value);
    }

    public function chargeBalance($value)
    {
        Auth::user()->increment('balance', $value);
    }

    public function history($range = false, $user = false)
    {
        if ($user == false) {
            $user = Auth::user();
        }
        $codes = $user->package()->join('packages', 'packages.id', '=', 'package_codes.package_id')
            ->select('package_codes.purchased_at as created_at', 'packages.value')
            ->addSelect(DB::raw("'' as store_name"))
            ->addSelect(DB::raw("'ingoing' as status"));

        if ($range) {
            $codes = dateFilter($codes, $range, 'purchased_at');
        }
        return $codes->latest('purchased_at');
    }


    public function getTotalIngoing($range = false, $user = false)
    {
        if ($user == false) {
            $user = Auth::user();
        }
        $codes = $user->package()->join('packages', 'packages.id', '=', 'package_codes.package_id');
        if ($range) {
            $codes = dateFilter($codes, $range, 'purchased_at');
        }
        return $codes->sum('packages.value');
    }
}
