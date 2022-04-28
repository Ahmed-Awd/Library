<?php


namespace App\Repositories;


use App\Models\Instruction;
use Illuminate\Support\Facades\Auth;

class InstructionRepository implements InstructionRepositoryInterface
{
    protected Instruction $instruction;

    public function __construct(Instruction $instruction)
    {
        $this->instruction = $instruction;
    }

    public function get()
    {
        return $this->instruction->all();
    }

    public function show($instruction)
    {
        return $instruction;
    }

    public function delete($instruction)
    {
        $instruction->delete();
    }

    public function store($data)
    {
        $record = $data;
        $record['branch_id'] = Auth::user()->branch_id;
        $this->instruction->create($record);
    }

    public function update($instruction, $data)
    {
        $instruction->update($data);
    }

}
