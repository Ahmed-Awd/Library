<?php


namespace App\Repositories;


use App\Models\AcademicSemester;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;

class TopicRepository implements TopicRepositoryInterface
{
    protected Topic $topic;
    protected AcademicSemester $semester;

    public function __construct(Topic $topic,AcademicSemester $semester)
    {
        $this->topic    = $topic;
        $this->semester = $semester;
    }

    public function get($withSubTopics)
    {
        if($withSubTopics == true){
            return $this->topic->Branch()->with('subject')->with('subTopics')->get();
        }
        return $this->topic->Branch()->with('subject')->get();
    }

    public function show($topic)
    {
        if($topic->parent_id == null){
            return $topic->with('subTopics')->with('subject')->first();
        }
        return $topic;
    }

    public function getParentTopic($topic)
    {
        return $topic->parentTopic;
    }

    public function getSubTopics($topic)
    {
        return $topic->subTopics;
    }

    public function delete($topic)
    {
        $topic->delete();
    }

    public function getContents($topic)
    {
        return $topic->contents;
    }

    public function store($data)
    {
        $record['name']          = $data['name'];
        $record['description']   = $data['description'];
        $record['semester_id']   = $this->semester->defaultSemester();
        $record['multi_branch']  = $data['multi_branch'];
        $record['subject_id']    = $data['subject_id'];
        $record['branch_id']     = Auth::user()->branch_id;
        if(isset($data['parent_id'])){
            $record['parent_id'] = $data['parent_id'];
        }
        $this->topic->create($record);
    }

    public function update($topic,$data)
    {
        $topic->update($data);
    }


}
