<?php

namespace Database\Seeders;

use App\Models\OptionCategory;
use App\Models\OptionSecondary;
use App\Models\OptionValue;
use Illuminate\Database\Seeder;

class OptionSecondarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $options= OptionValue::onlyTrashed()->get();
        foreach($options as $option)
        {
            $option->optionSecondary()->delete();
        }
    }
}
