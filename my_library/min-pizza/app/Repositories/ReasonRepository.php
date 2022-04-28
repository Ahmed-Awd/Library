<?php

namespace App\Repositories;

use App\Models\RefuseReason;

class ReasonRepository implements ReasonRepositoryInterface
{
    private RefuseReason $refuseReason;

    public function __construct(RefuseReason $refuseReason)
    {
        $this->refuseReason = $refuseReason;
    }

    public function get()
    {
        return $this->refuseReason->all();
    }

    public function show($refuseReason)
    {
        return $refuseReason;
    }

    public function store($data)
    {
        $this->refuseReason->create($data);
    }

    public function update($refuseReason, $data)
    {
        $refuseReason->update($data);
    }

    public function delete($refuseReason)
    {
        $refuseReason->delete();
    }
}
