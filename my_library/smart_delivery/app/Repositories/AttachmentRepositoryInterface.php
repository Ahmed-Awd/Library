<?php

namespace App\Repositories;

interface AttachmentRepositoryInterface
{
    public function get($filter);
    public function store($file);
    public function update($item, $type);
    public function linkAttachment($item, $attachment, $type);
    public function delete($attachment);
}
