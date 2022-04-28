<?php


namespace App\Repositories;
use App\Models\AcademicSemester;
use App\Models\Academic;

class AcademicYearRepository implements AcademicYearRepositoryInterface
{
    private Academic $academic;

    public function __construct(Academic $academic)
    {
        $this->academic = $academic;
    }

    public function get() {
        return $this->academic->all();
    }

    public function show(Academic $academic) {
        return $academic;
    }

    public function store($data)
    {
        $record['academic_year_title'] = $data['academic_year_title'];
        $record['academic_start_date']     = $data['academic_start_date'];
        $record['academic_end_date']    = $data['academic_start_date'];
        $record['show_in_list']    = $data['show_in_list'];

        $this->academic = $this->academic->create($record);

        foreach ($data['semesters'] as $semester) {
            $semester = new AcademicSemester($semester);
            $this->academic->academicSemesters()->save($semester);

        }

    }

    public function update(Academic $academic, $data)
    {
        $academic->update($data);
    }


}
