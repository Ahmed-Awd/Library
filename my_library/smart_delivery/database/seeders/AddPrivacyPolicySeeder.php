<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddPrivacyPolicySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('posts')->truncate();
        DB::table('post_translations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $path = 'public/SQL/policy.sql';
        DB::unprepared(file_get_contents($path));

    }
}
