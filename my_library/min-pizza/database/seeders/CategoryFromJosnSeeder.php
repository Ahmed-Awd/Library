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
class CategoryFromJosnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void0
     */
    public function run()
    {
        $json_category = file_get_contents(url('database/newest/Tag.json'));
        $category_olds = json_decode($json_category);

        foreach($category_olds as $category_old)
        {
            $mongo_db_id=$this->objectToArray($category_old->_id);

            Category::create([
                'name'      => translateToEn($category_old->Name),
                'is_active' => $category_old->Status ,
                'mongo_db_id' => $mongo_db_id['$oid'] ,
                'image' => $category_old->Icon ,
            ]);
        }
    }
    function objectToArray(&$object)
    {
        return @json_decode(json_encode($object), true);
    }
}
