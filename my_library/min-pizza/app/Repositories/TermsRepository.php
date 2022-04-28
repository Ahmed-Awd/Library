<?php

namespace App\Repositories;

use App\Models\Term;

class TermsRepository implements TermsRepositoryInterface
{
    private Term $term;

    public function __construct(Term $term)
    {
        $this->term = $term;
    }

    public function show($key)
    {
        return $this->term->where('type', $key)->first();
    }

    public function update($key, $data)
    {
        $this->term->where('type', $key)->update($data);
    }
}
