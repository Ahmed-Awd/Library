<?php


namespace App\Repositories;



use Illuminate\Http\Request;

interface ImportRepositoryInterface
{
    public function storeStudents(Request $request);

    public function setStudents($excel);

    public function checkCourse($name);

    public function checkClass($name);

    public function storeStaff(Request $request);

    public function setStaff($excel);

    public function insertStaff($teachers, Request $request);

    public function isExist($id, $email);

}
