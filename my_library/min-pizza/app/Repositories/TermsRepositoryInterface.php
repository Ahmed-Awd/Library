<?php

namespace App\Repositories;

interface TermsRepositoryInterface
{
    public function show($key);
    public function update($key, $data);
}
