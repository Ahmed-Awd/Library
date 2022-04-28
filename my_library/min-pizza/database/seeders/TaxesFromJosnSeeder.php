<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\City;
use App\Models\DeliveryPriceOption;
use App\Models\Item;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\OptionCategory;
use App\Models\OptionSecondary;
use App\Models\OptionTemplate;
use App\Models\OptionValue;
use App\Models\Restaurant;
use App\Models\RestaurantPhone;
use App\Models\RestaurantRating;
use App\Models\Tax;
use App\Models\User;
use App\Models\Worktime;
use Database\Factories\MenuCategoryFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
class TaxesFromJosnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void0
     */
    public function run()
    {
        $json_tax = file_get_contents(url('database/newest/Tax.json'));
        $tax_olds = json_decode($json_tax);

        foreach($tax_olds as $tax_old)
        {
            $mongo_db_id=$this->objectToArray($tax_old->_id);
            Tax::create([
                'name' => $tax_old->Name  ,
                'percentage' => $tax_old->Percentage ,
                'mongo_db_id' => $mongo_db_id['$oid'] ,
            ]);
        }
    }
    function objectToArray(&$object)
    {
        return @json_decode(json_encode($object), true);
    }
}
