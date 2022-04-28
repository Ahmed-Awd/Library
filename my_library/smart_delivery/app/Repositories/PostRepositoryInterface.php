<?php

namespace App\Repositories;

interface PostRepositoryInterface
{
    public function get();

    public function show($post);

    public function store();

    public function delete($post);
}
