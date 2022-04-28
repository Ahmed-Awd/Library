<?php

namespace App\Repositories;

use App\Models\OptionCategory;
use App\Models\OptionSecondary;
use App\Models\OptionValue;
use Illuminate\Support\Arr;

class OptionSecondaryRepository implements OptionSecondaryRepositoryInterface
{
    private OptionSecondary $secondary;

    public function __construct(OptionSecondary $secondary)
    {
        $this->secondary = $secondary;
    }

    public function get($template)
    {
        $secondary_option_value_id = $this->secondary->where('option_template_id', $template->id)
                ->groupBy('secondary_option_value_id')
                ->pluck('secondary_option_value_id')->toArray();

        $primary_options = OptionValue::where('option_category_id', $template->primary_option_id)->get();
        $secondary_option_values_all = OptionValue::whereIn('id', $secondary_option_value_id)->get();
        $secondary_options = $this->secondary->where('option_template_id', $template->id)
        ->groupBy('secondary_option_id')
        ->pluck('secondary_option_id')->toArray();
        $option_categories = OptionCategory::whereIn('id', $secondary_options)->get();
        $optionSecondaries=array();
        foreach ($primary_options as $primary_option) {
            $secondary_option2 = array();
            $option=null;
            foreach ($secondary_options as $secondary_option_id) {
                $secondary_option_values = $this->secondary
                ->where('option_template_id', $template->id)
                ->where('secondary_option_id', $secondary_option_id)
                ->groupBy('secondary_option_value_id')
                ->pluck('secondary_option_value_id')->toArray();
                $secondary_value_array = array();
                $use_default=0;
                foreach ($secondary_option_values as $secondary_option_value) {
                    $secondary_option = $secondary_option_values_all->where('id', $secondary_option_value)->first();
                    $secondary_option_price = null;
                    $secondary_option_price = $this->secondary->where('option_template_id', $template->id)
                    ->where('primary_option_value_id', $primary_option->id)
                    ->where('secondary_option_value_id', $secondary_option->id)->first();
                    if($secondary_option_price)
                    {
                        $secondary_option->option_secondary_id = $secondary_option_price->id;
                        $use_default=$secondary_option_price->use_default;
                        if(!$use_default)
                        $secondary_option->price =$secondary_option_price->price;
                    }
                     $secondary_value_array[] = $secondary_option;
                }
                $secondary_option = $option_categories->where('id', $secondary_option_id)->first();
                $secondary_option->use_default=$use_default;
                $secondary_option->secondary_option_value = $secondary_value_array;

                $secondary_option2[] = $secondary_option;
            }
            $primary_option->secondary_option = $secondary_option2;
            $secondary_option2 = array();
            $option= json_encode($primary_option) ;
            $optionSecondaries[]=json_decode($option,true) ;

        }
        return  $optionSecondaries;
    }

    public function show($secondary)
    {
        return $secondary;
    }

    public function store($data)
    {
        $this->secondary->create($data);
    }

    public function delete($template)
    {
        $this->secondary->where('option_template_id', $template->id)->delete();
    }
}
