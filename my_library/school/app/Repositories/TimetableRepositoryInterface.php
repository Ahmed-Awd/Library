<?php


namespace App\Repositories;


interface TimetableRepositoryInterface
{
    public function getSchedule($class = null, $staff = null, $without_break = false, $branch = null, $subjects = []);

    public function getUserSchedule($username);

    public function getClassSchedule($class);

    public function updateSchedule($data);

    public function isStaffAvailable($data);

    public function getLevelSchedule($program, $track, $level);

    public function staffReport($data);

    public function classesReport($data);
}
