<?php

namespace App\Repositories;

use App\Models\FQA;

class FQARepository implements FQARepositoryInterface
{
    private FQA $FQA;

    public function __construct(FQA $FQA)
    {
        $this->FQA = $FQA;
    }

    public function get($search , $order)
    {
        $query = $this->FQA;
        if ($search != '') {
            $query = searchIt($query, ['question','answer'], $search);
        }
        if ($order['by'] != false && $order['type'] != "none") {
            $query = $query->orderBy($order['by'], $order['type']);
        }
        return $query->paginate(15);
    }

    public function show($FQA)
    {
        return $FQA;
    }

    public function update($FQA, $data)
    {
        $FQA->update($data);
    }

    public function store($data)
    {
        $this->FQA->create($data);
    }

    public function destroy($FQA)
    {
        $FQA->delete();
    }

    public function all()
    {
        return $this->FQA->all();
    }
}
