<?php


namespace App\Repositories;


use App\Models\Subject;

interface SubjectRepositoryInterface
{
    public function get();

    public function show(Subject $subject);

    public function delete($id);

    public function store($data);

    public function update($id, $data);

    public function showSubjectConfig($id);

    public function updateSubjectConfig($data, $subject);

    public function setStaff($semester,$class,$subject,$staff);

    public function getSchoolStaff($semester,$class);

    public function getUniversityStaff($semester);

    public function getTopics($subject,$semester);
}
