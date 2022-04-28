<?php


namespace App\Repositories;


use App\Models\Track;

class TrackRepository implements TrackRepositoryInterface
{
    protected Track $track;

    public function __construct(Track $track)
    {
        $this->track = $track;
    }

    public function get()
    {
        return $this->track->all();
    }

    public function show($track)
    {
        return $track;
    }

    public function delete($track)
    {
        $track->delete();
    }

    public function store($data)
    {
        $record['name'] = $data['name'];
        $this->track->create($record);
    }

    public function update($track, $data)
    {
        $track->update($data);
    }

    public function setMandatorySubjects($track, $program, $level, $semester, $subjects, $type)
    {
        $track->subjects()
            ->wherePivot('program_id', $program->id)
            ->wherePivot('semester_id', $semester)
            ->wherePivot('level_id', $level->id)
            ->wherePivot('mandatory', $type)->detach();

        $track->subjects()->attach($subjects
            , ['program_id' => $program->id,'level_id' => $level->id,'semester_id' => $semester, 'mandatory' => 1]);

    }

    public function setOptionalSubjects($track, $program, $semester, $subjects, $type)
    {
        $track->subjects()
            ->wherePivot('program_id', $program->id)
            ->wherePivot('semester_id', $semester)
            ->wherePivot('mandatory', $type)->detach();
        $levels = $track->levelsOfProgram($program)->get();
        foreach ($levels as $level) {
            $track->subjects()->attach($subjects
                ,['program_id' => $program->id,'level_id' => $level->id,'semester_id' => $semester,'mandatory' => 0]);
        }

    }

    public function getMandatorySubjects($track, $program, $level, $semester)
    {
        return $track->subjects()
            ->wherePivot('program_id',$program->id)
            ->wherePivot('semester_id', $semester->id)
            ->wherePivot('level_id', $level->id)
            ->wherePivot('mandatory',1)->get();
    }

    public function getOptionalSubjects($track, $program, $semester)
    {
        return $track->subjects()
            ->wherePivot('program_id',$program->id)
            ->wherePivot('semester_id', $semester->id)
            ->wherePivot('mandatory',0)->distinct()->get();
    }
}
