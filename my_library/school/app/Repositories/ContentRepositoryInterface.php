<?php

namespace App\Repositories;

interface ContentRepositoryInterface
{
    public function get();

    public function show($content);

    public function delete($content);

    public function store($data);

    public function update($content, $data);

    public function saveFile($file): string;

    public function contentType($type, $fileOrUrl):string;

    public function counter($content);
}
