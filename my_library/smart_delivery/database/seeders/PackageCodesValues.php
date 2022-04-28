<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackageCode;
use Illuminate\Database\Seeder;

class PackageCodesValues extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $codes = PackageCode::all();
        foreach ($codes as $one){
            $package = Package::where('id',$one->package_id)->first();
            if($package){
                $one->update(["value" => $package->price]);
            }else{
                $one->delete();
            }
        }
    }
}
