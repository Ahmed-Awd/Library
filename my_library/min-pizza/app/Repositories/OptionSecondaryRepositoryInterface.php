<?php

namespace App\Repositories;

interface OptionSecondaryRepositoryInterface
{
    public function get($template);
    public function show($secondary);
    public function store($data);
    public function delete($template);
}
