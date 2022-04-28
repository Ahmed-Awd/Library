<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FQASeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('f_q_a_s')->insertOrIgnore([
            "question" => json_encode([
                "en" => "what is your favourite color?",
                "sv" => "vad är din favoritfärg?",
            ]),
            "answer" => json_encode([
                "en" => "blue",
                "sv" => "blå",
            ]),
        ]);

        DB::table('f_q_a_s')->insertOrIgnore([
            "question" => json_encode([
                "en" => "how many days in a week?",
                "sv" => "hur många dagar i veckan?",
            ]),
            "answer" => json_encode([
                "en" => "seven",
                "sv" => "sju",
            ]),
        ]);

        DB::table('f_q_a_s')->insertOrIgnore([
            "question" => json_encode([
                "en" => "how many hours are there in one day?",
                "sv" => "hur många timmar är det på en dag?",
            ]),
            "answer" => json_encode([
                "en" => "24",
                "sv" => "24",
            ]),
        ]);
    }
}
