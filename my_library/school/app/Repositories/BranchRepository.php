<?php


namespace App\Repositories;


use App\Models\Branch;

class BranchRepository implements BranchRepositoryInterface
{
    protected Branch $branch;

    public function __construct(Branch $branch)
    {
        $this->branch = $branch;
    }

    public function get()
    {
        return $this->branch->all();
    }

    public function show($branch)
    {
        return $branch;
    }

    public function delete($branch)
    {
        $branch->delete();
    }

    public function store($data)
    {
        $record['name']  = $data['name'];
        $this->branch->create($record);
    }

    public function update($branch,$data)
    {
        $branch->update($data);
    }

}
