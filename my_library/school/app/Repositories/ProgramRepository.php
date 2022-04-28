<?php


namespace App\Repositories;


use App\Models\Program;

class ProgramRepository implements ProgramRepositoryInterface
{
    protected Program $program;

    public function __construct(Program $program)
    {
        $this->program = $program;
    }

    public function get()
    {
        return $this->program->all();
    }

    public function show($program)
    {
        return $program;
    }

    public function delete($program)
    {
        $program->delete();
    }

    public function store($data)
    {
        $record['name']  = $data['name'];
        $this->program->create($record);
    }

    public function update($program,$data)
    {
        $program->update($data);
    }
}
