<?php


namespace App\Repositories;


use App\Models\TimingSet;

class TimingSetRepository implements TimingSetRepositoryInterface
{
    protected TimingSet $timingSet;

    public function __construct(TimingSet $timingSet)
    {
        $this->timingSet = $timingSet;
    }

    public function get()
    {
        return $this->timingSet->all();
    }

    public function show($timingSet)
    {
        $timingSet->holidays = $this->getRestDays($timingSet->rest_days);
        return $timingSet;
    }

    public function delete($timingSet)
    {
        $timingSet->delete();
    }

    public function store($data)
    {
        $record['name']        = $data['name'];
        $record['description'] = $data['description'];
        $record['rest_days'] =  array_sum($data['rest_days']);

        $this->timingSet->create($record);
    }

    public function update($timingSet, $data)
    {
        $data['rest_days'] =  array_sum($data['rest_days']);
        $timingSet->update($data);
    }

    public function getRestDays($days): array
    {
        $binary = array_reverse(str_split(decbin($days))); //convert the number to binary array
        $rest = [];
        foreach ($binary as $key => $value){
            $temp = $value * pow(2,$key);
            $temp != 0 ? $rest[] = $temp : $temp = 0;
        }
        return array_filter($rest);
    }

}
