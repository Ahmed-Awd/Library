<?php


namespace App\Repositories;


use App\Models\ClassRoom;

class ClassRepository implements ClassRepositoryInterface
{
    protected ClassRoom $class;

    public function __construct(ClassRoom $class)
    {
        $this->class = $class;
    }

    public function get()
    {
        return $this->class->all();
    }

    public function show($class,$withCourse)
    {
        if($withCourse == true){
            return $class->with('course')->first();
        }
        return $class;
    }

    public function delete($class)
    {
        $class->delete();
    }

    public function store($data)
    {
        $record['title']      = $data['title'];
        $record['course_id']  = $data['course_id'];
        $this->class->create($record);
    }

    public function update($class,$data)
    {
        $class->update($data);
    }
}
