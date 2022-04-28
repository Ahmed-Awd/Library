<?php

namespace App\Repositories;

interface PostTranslationRepositoryInterface
{

    public function get();

    public function store($post, $data);

    public function update($post, $data, $lang);

    public function delete($PostTranslation);
}
