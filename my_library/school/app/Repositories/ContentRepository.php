<?php


namespace App\Repositories;


use App\Models\Content;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContentRepository implements ContentRepositoryInterface
{
    private Content $content;

    public function __construct(Content $content)
    {
        $this->content    = $content;
    }

    public function get()
    {
        return $this->content->branch()->with('topic','topic.subject')->with('course')->get();
    }

    public function show($content)
    {
        if(Auth::user()->hasRole('student')){
            $this->counter($content);
        }
        return $content->with('topic','topic.subject')->with('course')->first();
    }

    public function delete($content)
    {
        $content->delete();
    }

    public function store($data)
    {
        $record = $data;
        $record['image'] = $this->saveFile($data['image']);
        $record['file_path'] = $this->contentType($data['content_type'],$data['file_path']);
        $record['branch_id']     = Auth::user()->branch_id;
        $this->content->create($record);
    }

    public function update($content,$data)
    {
        if(isset($data['image'])){
            $data['image'] = $this->saveFile($data['image']);
        }
        if(isset($data['content_type']) && isset($data['file_path'])){
            $data['file_path'] = $this->contentType($data['content_type'],$data['file_path']);
        }
        $content->update($data);
    }

    public function saveFile($file): string
    {
        $name = "content_" . rand(10, 99) . time() . '.' . $file->extension();
        $filePath = public_path('/lms_content');
        $file->move($filePath, $name);
        return $name;
    }

    public function contentType($type,$fileOrUrl): string
    {
        if($type == "file" || $type == "video" || $type == "audio" || $type == "image_file"){
            return $this->saveFile($fileOrUrl);
        }
        return $fileOrUrl;
    }

    public function counter($content)
    {
        $current = $content->students()->wherePivot('student_id',Auth::user()->id)->first();
        $current ? DB::table('content_students')->where(['student_id'=>Auth::user()->id,'content_id'=>$content->id])
            ->increment('count') : $content->students()->attach(Auth::user()->id);
    }

}
