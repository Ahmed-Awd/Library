<?php

namespace Database\Seeders;

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
use App\Models\RestaurantCategory;
use App\Models\RestaurantPhone;
use App\Models\Tax;
use App\Models\User;
use App\Models\Worktime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

;
class Restaurant_3_FromJosnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json_restaurant = file_get_contents(url('database/newest/Restaurant_3.json'));
        $restaurant_olds = json_decode($json_restaurant);

        $cities=City::all();
        $users=User::all();
        $categories=Category::all();
        $taxes=Tax::all();
        foreach ($restaurant_olds as $restaurant_old) {
            $city=$cities->where('name', $restaurant_old->Address->City)->first();
            $db_restaurant_id=$this->objectToArray($restaurant_old->_id);
            $user=$users->where('mongo_db_restaurant_id', $db_restaurant_id['$oid'])->first();

            if (!$user) {
                continue;
            }
            $restaurant_new = Restaurant::create([
                'mongo_db_id' => $db_restaurant_id['$oid'],
                'name'      => $restaurant_old->Name,
                'owner_id' =>$user->id ,
                'address' =>$restaurant_old->Address->FullAddress ,
                'min_order_price' => $restaurant_old->MinimumOrder->Amount ,
                'company_name' => $restaurant_old->CompanyName ,
                'company_number' => $restaurant_old->CompanyNumber ,
                'logo' => $restaurant_old->Logo ,
                'image' => $restaurant_old->Image ,
                'vat' => $restaurant_old->VAT ,
                'lat' => $restaurant_old->Address->Latitude ,
                'lng' => $restaurant_old->Address->Longitude ,
                'status_id' => $restaurant_old->Status ,
                'ZIP_code' => $restaurant_old->Address->ZipCode ,
            ]);
            $menu=Menu::create(["restaurant_id" => $restaurant_new->id]);
            DeliveryPriceOption::factory()->create(['restaurant_id' => $restaurant_new->id]);

            RestaurantPhone::create([
                'restaurant_id' =>$restaurant_new->id ,
                'country_code' => $city?$city->country->calling_code:46,
                'phone' =>$restaurant_old->RestaurantPhone ,
            ]);
            RestaurantPhone::create([
                'restaurant_id' =>$restaurant_new->id ,
                'country_code' => $city?$city->country->calling_code:46,
                'phone' =>$restaurant_old->PhoneNumber ,
            ]);
            foreach ($restaurant_old->Tags as $tag) {
                $tag_array=$this->objectToArray($tag);
                $category=$categories->where('mongo_db_id', $tag_array['$oid'])->first();
                RestaurantCategory::create([
                    'restaurant_id' =>$restaurant_new->id ,
                    'category_id' => $category->id,
                ]);
            }

            foreach ($restaurant_old->Options as $option) {
                $option_id=$this->objectToArray($option->_id);

                $option_category=OptionCategory::create([
                    'restaurant_id' =>$restaurant_new->id ,
                    'name'      => translateToEn($option->Name),
                    'mongo_db_id' => $option_id['$oid'],
                    'is_primary' =>$option->IsPrimary ,
                    'max_selectable' =>$option->MaxSelectable ,
                    'selection' =>$option->Type==1?"single":"multiple",
                    'status' =>$option->Status ,
                ]);
                usleep(500000);
                foreach ($option->OptionItems as $option_item) {
                    $option_item_id=$this->objectToArray($option_item->_id);
                    OptionValue::create([
                    'option_category_id' =>$option_category->id ,
                    'name' =>$option_item->Name ,
                    'mongo_db_id' => $option_item_id['$oid'],
                    'price' =>$option_item->Price ,
                    'min_count' =>$option_item->MinCount ,
                    'max_count' =>$option_item->MaxCount,
                    'status' =>$option_item->Status ,
                    'is_available' =>$option_item->IsAvailable ,
                    ]);
                }
            }

            $option_categories=OptionCategory::all();
            $option_values=OptionValue::all();
            foreach ($restaurant_old->OptionTemplates as $option_template) {
                $option_template_id=$this->objectToArray($option_template->_id);
                $option_category=$option_categories->where('mongo_db_id', $option_template->PrimaryOptionId)->first();
                if ($option_category) {
                    $option_template_new=OptionTemplate::create([
                        'mongo_db_id' => $option_template_id['$oid'],
                        'restaurant_id' =>$restaurant_new->id ,
                        'primary_option_id' =>$option_category->id ,
                        'name'      => translateToEn($option_template->Name),
                        'status' =>$option_template->Status ,
                        'is_available' =>$option_template->IsAvailable ,
                    ]);
                    usleep(500000);
                    foreach ($option_template->SecondryOptions as $secondry_option) {
                        $secondry_option_id=$this->objectToArray($secondry_option->_id);
                        $option_category=$option_categories->where('mongo_db_id', $secondry_option->DefaultSecondaryOptionId)->first();
                        $option_value_primary=$option_values->where('mongo_db_id', $secondry_option->DefaultPrimaryOptionItemId)->first();
                        $option_value=$option_values->where('mongo_db_id', $secondry_option->DefaultSecondaryOptionItemId)->first();
                        OptionSecondary::create([
                            'mongo_db_id' => $secondry_option_id['$oid'],
                            'secondary_option_id' =>$option_category->id ,
                            'primary_option_value_id' =>$option_value_primary->id ,
                            'secondary_option_value_id' =>$option_value->id ,
                            'option_template_id' =>$option_template_new->id ,
                            'price' =>$secondry_option->Price ,
                            'status' =>$secondry_option->Status ,
                            'use_default' =>$secondry_option->UseDefault ,
                        ]);
                    }
                }
            }

            // foreach($restaurant_old->OperationTimings as $operation_timings)
            // {

            //     Worktime::create([
            //         'restaurant_id' =>1,
            //         'day_id' =>$operation_timings->Day==0?7:$operation_timings->Day,
            //         'open_from' =>date('H:i', strtotime($operation_timings->StartTime)) ,
            //         'open_to' =>date('H:i', strtotime($operation_timings->EndTime)) ,
            //         'takeaway' =>1,
            //         'delivery' =>0,
            //         'status' =>$operation_timings->Status ,
            //     ]);
            // }

            // foreach($restaurant_old->DeliveryTimings as $delivery_timing)
            // {
            //     Worktime::create([
            //         'restaurant_id' =>1,
            //         'day_id' =>$delivery_timing->Day==0?7:$delivery_timing->Day,
            //         'open_from' =>date('H:i', strtotime($delivery_timing->StartTime)),
            //         'open_to' =>date('H:i', strtotime($delivery_timing->StartTime)),
            //         'takeaway' =>0,
            //         'delivery' =>1,
            //         'status' =>$delivery_timing->Status ,
            //     ]);
            // }
            $option_templates=OptionTemplate::all();
            foreach ($restaurant_old->MenuCategories as $menu_category) {
                $menu_category_new=MenuCategory::create([
                    'name'      => translateToEn($menu_category->Name),
                    'description' => $menu_category->Descriptions,
                    'menu_id' => $menu->id
                ]);
                usleep(500000);
                foreach ($menu_category->MenuItems as $item) {
                    $tax=$taxes->where('mongo_db_id', $item->TaxId)->first();
                    $option_template=$option_templates->where('mongo_db_id', $item->OptionTemplateId)->first();
                    $item_new=Item::create([
                        'name'      => $item->Name,
                        'description' => $item->Descriptions ,
                        'alcohol_percentage' => $item->AlcoholPercentage  ,
                        'is_available' =>$item->IsAvailable ,
                        'category_id' =>$menu_category_new->id ,
                        'tax_id' =>$tax?$tax->id:null ,
                        'currency' => "SEK",
                        'image' => $item->Image??"min_pizza.png",
                        'option_template_id' => $option_template?$option_template->id:null,
                        'price' => $item->Price  ,
                    ]);

                    foreach ($item->OptionIds as $i) {
                        $option_category=$option_categories->where('mongo_db_id', $i)->first();
                        DB::table('item_option')->insert([
                        'item_id'=>$item_new->id,
                        'option_id'=>$option_category->id,
                        ]);
                    }
                }
            }
        }
    }

    function objectToArray(&$object)
    {
        return @json_decode(json_encode($object), true);
    }
}
