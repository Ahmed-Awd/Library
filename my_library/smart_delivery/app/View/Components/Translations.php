<?php

namespace App\View\Components;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;

class Translations extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function render()
    {
        $locale = App::getLocale();
        $translations = [];
        if (File::exists(resource_path("lang/$locale/$locale.json"))) {
            $translations = json_decode(File::get(resource_path("lang/$locale/$locale.json")), true);
        }
        return view('components.translations', ["translations" => $translations]);
    }
}
