<?php

namespace Database\Seeders;

use App\Models\Category;
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

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => Str::random(25),
            'email' => Str::random(30)."@gmail.com",
            'email_verified_at' => now(),
            'password' => bcrypt("123456"),
            'remember_token' => Str::random(10),
            'city_id' => 1
        ]);
        $user->assignRole('owner');
        $restaurant = Restaurant::factory()->withOwner($user->id)->create();

        $name=array(
            'en' => "size",
            'sv' => "storlek",
        );
        $name = json_encode($name);
        $size = OptionCategory::create([
            "name" => $name,
            'selection' => "single",
            'is_primary' => 1,
            'max_selectable' => 1,
            'restaurant_id' => $restaurant->id
        ]);
        OptionValue::create([
            "name" => "small",
            "option_category_id" => $size->id,
            "price" => 5.00
        ]);
        OptionValue::create([
            "name" => "medium",
            "option_category_id" => $size->id,
            "price" => 7.50
        ]);
        OptionValue::create([
            "name" => "large",
            "option_category_id" => $size->id,
            "price" => 9.00
        ]);

        $name1=array(
            'en' => "extras",
            'sv' => "Tillägg",
        );
        $name1 = json_encode($name1);
        $extra = OptionCategory::create([
            "name" => $name1,
            'selection' => "single",
            'restaurant_id' => $restaurant->id,
            'is_primary' => 0,
            'max_selectable' => 2,
        ]);
        OptionValue::create([
            "name" => "pepper",
            "min_count" => 1,
            "max_count" => 1,
            "option_category_id" => $extra->id,
            "price" => 5.00
        ]);
        OptionValue::create([
            "name" => "cheese",
            "min_count" => 1,
            "max_count" => 2,
            "option_category_id" => $extra->id,
            "price" => 7.50
        ]);
        OptionValue::create([
            "name" => "salad",
            "min_count" => 1,
            "max_count" => 1,
            "option_category_id" => $extra->id,
            "price" => 9.00
        ]);

        $name2=array(
            'en' => "Size with extras",
            'sv' => "Storlek med tillbehör",
        );
        $name2 = json_encode($name2);
        $template = OptionTemplate::create([
            "name" => $name2,
            'primary_option_id' => $size->id,
            'restaurant_id' => $restaurant->id,
        ]);
        foreach ($size->optionValues as $primary) {
            foreach ($extra->optionValues as $secondary) {
                OptionSecondary::create([
                    "secondary_option_id" => $extra->id,
                    "primary_option_value_id" => $primary->id,
                    "secondary_option_value_id" => $secondary->id,
                    "option_template_id" => $template->id,
                    "price" => 0.00,
                    "use_default" => 1
                ]);
            }
        }

        $menu = Menu::create(["restaurant_id" => $restaurant->id]);
        $tax = Tax::inRandomOrder()->first();
        for ($i=0; $i<5; $i++) {
            $category = MenuCategory::factory()->create(["menu_id" => $menu->id]);
            Item::factory()->count(15)->create([
            "category_id" => $category->id ,
            "tax_id" => $tax->id,
            "option_template_id" => $template->id,
            ]);
        }
        $voters = User::role('customer')->inRandomOrder()->limit(5)->get();
        foreach ($voters as $voter) {
            RestaurantRating::factory()->create(["user_id" => $voter->id,"restaurant_id" => $restaurant->id]);
        }
        for ($i=1; $i<5; $i++) {
            Worktime::factory()->create(["restaurant_id" => $restaurant->id,"day_id" => $i
                ,"open_from" => "9:00:00","open_to" => '11:00:00']);
            Worktime::factory()->create(["restaurant_id" => $restaurant->id,"day_id" => $i
                ,"open_from" => "12:00:00","open_to" => '19:00:00']);
        }
        RestaurantPhone::factory()->count(2)->create(["restaurant_id" => $restaurant->id]);
        DeliveryPriceOption::factory()->create(['restaurant_id' => $restaurant->id]);
        $restaurant->categories()->attach(Category::all()->random(2)->pluck('id'));
    }
}
