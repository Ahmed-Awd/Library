<?php

namespace App\Repositories;

interface OptionTemplateRepositoryInterface
{
    public function get();
    public function show($template);
    public function store($data);
    public function update($template, $data);
    public function delete($template);
}
