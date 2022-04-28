<?php

namespace App\Repositories;

interface TrackRepositoryInterface
{
    public function get();

    public function show($track);

    public function delete($track);

    public function store($data);

    public function update($track, $data);

    public function setMandatorySubjects($track,$program,$level,$semester,$subjects,$type);

    public function setOptionalSubjects($track, $program, $semester, $subjects, $type);

    public function getMandatorySubjects($track,$program,$level,$semester);

    public function getOptionalSubjects($track, $program, $semester);
}
