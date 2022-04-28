<?php


namespace App\Repositories;

use App\Models\Academic;
use App\Models\Subject;
use App\Models\SubjectColumn;
use App\Models\SubjectConfig;

class SubjectRepository implements SubjectRepositoryInterface
{
    private Subject $subject;

    public function __construct(Subject $subject)
    {
        $this->subject = $subject;
    }

    public function get()
    {
        return $this->subject->all();
    }

    public function show(Subject $subject)
    {
        return $subject;
    }

    public function store($data)
    {
        $record['title']  = $data['title'];
        $record['is_lab']  = $data['is_lab'];
        $record['category_id']  = auth()->user()->category_id;
        $this->subject->create($record);
    }

    public function update($id,$data)
    {
        $record = $this->subject->findOrFail($id);

        $record->update($data);
    }

    public function delete($id)
    {
        $this->subject->where('id',$id)->delete();
    }

    public function showSubjectConfig($id)
    {
        $config = SubjectConfig::with('columns')
                               ->where('subject_id',$id)
                                ->get();
       return $config;
    }

    public function updateSubjectConfig($data, $subject)
    {
        $academic_year = new Academic();
        $subjectConfig = SubjectConfig::where('subject_id', $subject)->first();
        $record['is_published'] = $data['is_published'];
        $record['passing_grade'] = $data['passing_grade'];
        $record['total'] = $data['total'];
        $record['subject_id'] = $subject;
        $record['semester_id'] = $academic_year->defaultSemester();
        if(!$subjectConfig) {
            $subjectConfig = new SubjectConfig($record);
            $subjectConfig->create($record);
        } else {
            $subjectConfig->update($record);
        }
        if($data['columns']){
            $subjectConfig->columns()->delete();
            foreach($data['columns'] as $column){
                $col_array['name'] = $column['name'];
                $col_array['score'] = $column['score'];
                $col_record = new SubjectColumn($col_array);
                $subjectConfig->columns()->save($col_record);
            }
        }
    }

    public function setStaff($semester,$class,$subject,$staff)
    {
        $subject = Subject::findOrFail($subject);
        $class == null ? $class_id = null : $class_id = $class->id;
        $subject->staffSubjects()->wherePivot('class_id',$class_id)->wherePivot('semester_id',$semester)->detach();
        $subject->staffSubjects()->attach($staff,["semester_id" => $semester,"class_id" => $class_id]);
    }

    public function getSchoolStaff($semester,$class)
    {
        $subjects = $semester->schoolSubjects()->wherePivot('course_id',$class->course->id)->distinct()->get();
        foreach ($subjects as $subject){
            $subject->staff = $subject->staffSubjects()
                ->wherePivot('semester_id',$semester->id)->wherePivot('class_id',$class->id)->first();
        }
        return $subjects;
    }

    public function getUniversityStaff($semester):array
    {
        $subjects = $semester->universitySubjects()->distinct()->get();
        foreach ($subjects as $subject){
            $subject->staff = $subject->staffSubjects()->wherePivot('semester_id',$semester->id)->first();
        }
        return $subjects;
    }

    public function getTopics($subject,$semester)
    {
        return $subject->semesterTopics($semester)->with('subTopics','subTopics.contents')->get();
    }

}
