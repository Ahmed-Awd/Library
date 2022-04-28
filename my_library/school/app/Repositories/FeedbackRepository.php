<?php


namespace App\Repositories;


use App\Models\Feedback;
use App\Models\FeedbackFile;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FeedbackRepository implements FeedbackRepositoryInterface
{
    private Feedback $feedback;

    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    public function get()
    {
        return $this->feedback->with('files')->get();
    }

    public function show($feedback)
    {
        if (Auth::user()->id != $feedback->user_id) {
            $feedback->update(["new" => 0]);
        }
        return $feedback->with('files')->first();
    }


    public function store($data)
    {
        $record['title'] = $data['title'];
        $record['description'] = $data['description'];
        $record['subject'] = $data['subject'];
        $record['user_id'] = Auth::user()->id;
        $record['branch_id'] = Auth::user()->branch_id;
        $files = $this->saveFiles($data['files']);
        $saved = $this->feedback->create($record);
        $saved->files()->saveMany($files);
    }

    public function update($feedback, $data)
    {
        if (isset($data['files'])) {
            $files = $this->saveFiles($data['files']);
            $feedback->files()->saveMany($files);
        }
        $feedback->update($data);
    }

    public function delete($feedback)
    {
        $feedback->delete();
    }

    public function saveFiles($files): array
    {
        $saved = [];
        foreach ($files as $file) {
            $name = "feedback_" . rand(10, 99) . time() . '.' . $file->extension();
            $record = new FeedbackFile(['file_name' => $name]);
            $filePath = public_path('/feedback');
            $file->move($filePath, $name);
            $saved[] = $record;
        }
        return $saved;
    }

    public function removeFile($feedback, $file)
    {
        FeedbackFile::where('file_name', $file)->where('feedback_id', $feedback->id)->delete();
        try {
            $file_path = public_path() . '/feedback/' . $file;
            unlink($file_path);
        } catch (\Exception $exception) {
            //do nothing
        }
    }
}
