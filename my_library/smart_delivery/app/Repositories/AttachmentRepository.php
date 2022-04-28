<?php

namespace App\Repositories;

use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AttachmentRepository implements AttachmentRepositoryInterface
{
    private Attachment $attachment;

    public function __construct(Attachment $attachment)
    {
        $this->attachment = $attachment;
    }

    public function get($filter)
    {
        return $this->attachment->where($filter)->first();
    }

    public function store($file)
    {
        $image = $file;
        $img = Image::make($image->getRealPath());
        $img->resize(360, 360, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->stream();
        $name = date("Y/m/d") . rand(1000, 9999) . '.' . $image->getClientOriginalExtension();
        Storage::disk('public')->put($name, $img, 'public');
        $record = $this->attachment->create([
            "path" => $name,

        ]);
        return $record;
    }

    public function linkAttachment($item, $attachment, $type)
    {
        $this->attachment->where('path', $attachment)->update([
            "fileable_id"   => $item->id,
            "fileable_type" => $item::class,
            "description" => $type
        ]);
    }

    public function update($item, $driver)
    {
        $this->delete($item);
        $this->attachment->where('fileable_id', $driver->id)
        ->where('fileable_type', $driver::class)->update([
            "fileable_id"   => $item->id,
            "fileable_type"   => $item::class,
        ]);
    }
    public function delete($item)
    {
        $this->attachment->where('fileable_id', $item->id)
        ->where('fileable_type', $item::class)->delete();
    }
}
