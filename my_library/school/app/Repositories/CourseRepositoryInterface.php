<?php

namespace App\Repositories;

use App\Models\Course;

interface CourseRepositoryInterface
{
    public function get();

    public function show($course, $withClasses);

    public function getClasses($course);

    public function delete($course);

    public function store($data);

    public function update($course, $data);

    public function getSubjects( $course);

    public function setSubjects($data, $course);

    public function getClassSubjects($class);

    public function deleteClassSubject($class, $subject);
}
