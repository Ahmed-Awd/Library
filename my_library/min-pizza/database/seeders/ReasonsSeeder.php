<?php

namespace Database\Seeders;

use App\Models\RefuseReason;
use Illuminate\Database\Seeder;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ReasonsSeeder extends Seeder
{
    public function run()
    {
        $reasons = [
            "the order list is full",
            "all our drivers are unavailable",
            "we are temporary closed",
            "other reason"
        ];
        $swedish_tr = new GoogleTranslate('sv');
        foreach ($reasons as $reason) {
            $data['reason'] = json_encode([
                "en" => $reason,
                "sv" => $swedish_tr->translate($reason)
            ]);
            RefuseReason::create($data);
        }
    }
}
