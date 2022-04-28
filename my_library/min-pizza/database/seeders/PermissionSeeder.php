<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//        DB::table('permissions')->truncate();
//        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        DB::table('permissions')->insertOrIgnore([

            ['name' => 'control drivers','guard_name'=> 'web'],
            ['name' => 'control customers','guard_name'=> 'web'],
            ['name' => 'control categories','guard_name'=> 'web'],
            ['name' => 'control taxes','guard_name'=> 'web'],
            ['name' => 'control reasons','guard_name'=> 'web'],
            ['name' => 'control sliders','guard_name'=> 'web'],
            ['name' => 'control owner','guard_name'=> 'web'],
            ['name' => 'control offers','guard_name'=> 'web'],
            ['name' => 'control codes','guard_name'=> 'web'],
            ['name' => 'control feedbacks','guard_name'=> 'web'],
            ['name' => 'control restaurants','guard_name'=> 'web'],

            ['name' => 'manage holidays','guard_name'=> 'web'],
            ['name' => 'suspend user','guard_name'=> 'web'],

            ['name' => 'list settings','guard_name'=> 'web'],
            ['name' => 'edit settings','guard_name'=> 'web'],
            ['name' => 'show settings','guard_name'=> 'web'],




            ['name' => 'list addresses','guard_name'=> 'web'],
            ['name' => 'add address','guard_name'=> 'web'],
            ['name' => 'edit address','guard_name'=> 'web'],

            ['name' => 'delete address','guard_name'=> 'web'],




            ['name' => 'change status','guard_name'=> 'web'],

            ['name' => 'list worktimes','guard_name'=> 'web'],
            ['name' => 'update worktime','guard_name'=> 'web'],

            ['name' => 'list restaurant settings','guard_name'=> 'web'],
            ['name' => 'update restaurant setting','guard_name'=> 'web'],
            ['name' => 'update restaurant status','guard_name'=> 'web'],



            ['name' => 'change menu','guard_name'=> 'web'],

            ['name' => 'list settings','guard_name'=> 'web'],
            ['name' => 'edit settings','guard_name'=> 'web'],



            ['name' => 'control menu item options','guard_name'=> 'web'],
            ['name' => 'edit questions','guard_name'=> 'web'],

            ['name' => 'list restaurant ratings','guard_name'=> 'web'],
            ['name' => 'add restaurant rating','guard_name'=> 'web'],
            ['name' => 'edit restaurant rating','guard_name'=> 'web'],
            ['name' => 'delete restaurant rating','guard_name'=> 'web'],

            ['name' => 'list orders','guard_name'=> 'web'],
            ['name' => 'add order','guard_name'=> 'web'],
            ['name' => 'edit order','guard_name'=> 'web'],
            ['name' => 'delete order','guard_name'=> 'web'],




            ['name' => 'send notification','guard_name'=> 'web'],
            ['name' => 'see notification','guard_name'=> 'web'],



            ['name' => 'view orders','guard_name'=> 'web'],




        ]);
    }
}
