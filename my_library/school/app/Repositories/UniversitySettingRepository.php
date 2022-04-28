<?php


namespace App\Repositories;


use App\Models\Track;

class UniversitySettingRepository implements UniversitySettingRepositoryInterface
{

    public function get($program,$track)
    {
        $data = $track->programSettings()->where('program_id',$program->id)->first()->pivot->settings_data;
        return json_decode($data);
    }

    public function set($program,$track,$data)
    {
        $settings = json_encode($data);
        $track->programSettings()->detach($program);
        $track->programSettings()->attach($program,["settings_data" => $settings]);
    }
}
