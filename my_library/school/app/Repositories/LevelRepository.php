<?php


namespace App\Repositories;


use App\Models\Level;
use App\Models\Track;

class LevelRepository implements LevelRepositoryInterface
{
    protected Level $level;

    public function __construct(Level $level)
    {
        $this->level = $level;
    }


    public function get()
    {
        return $this->level->all();
    }

    public function show($level)
    {
        return $level;
    }

    public function delete($level)
    {
        $level->delete();
    }

    public function store($data)
    {
        $record['name']  = $data['name'];
        $this->level->create($record);
    }

    public function update($level,$data)
    {
        $level->update($data);
    }

    public function fetchLevels($program,$track)
    {
        return $track->levelsOfProgram($program)->get();
    }

    public function setLevels($program,$track,$levels)
    {
        $track->levelsOfProgram($program)->detach();
        $track->levelsOfProgram($program)->attach($levels,['program_id' => $program->id]);
    }


}
