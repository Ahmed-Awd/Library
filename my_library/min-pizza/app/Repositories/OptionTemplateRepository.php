<?php

namespace App\Repositories;

use App\Models\OptionTemplate;

class OptionTemplateRepository implements OptionTemplateRepositoryInterface
{
    private OptionTemplate $template;

    public function __construct(OptionTemplate $template)
    {
        $this->template = $template;
    }

    public function get()
    {
        return $this->template->where('restaurant_id', auth()->user()->restaurant->id)->get();
    }

    public function show($template)
    {
        return OptionTemplate::where('id', $template->id)->with('primaryOption:id,name')->first();
    }

    public function store($data)
    {
      return  $this->template->create($data);
    }

    public function update($template, $data)
    {
        $template->update($data);
    }

    public function delete($template)
    {
        $template->delete();
    }
}
