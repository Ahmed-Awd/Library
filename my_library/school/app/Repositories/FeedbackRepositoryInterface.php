<?php

namespace App\Repositories;

interface FeedbackRepositoryInterface
{
    public function get();

    public function show($feedback);

    public function store($data);

    public function update($feedback, $data);

    public function delete($feedback);

    public function saveFiles($files): array;

    public function removeFile($feedback,$file);
}
