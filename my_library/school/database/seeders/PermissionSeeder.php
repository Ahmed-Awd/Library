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
        DB::table('permissions')->insertOrIgnore([
            ['name' => 'add user','guard_name'=> 'web'],
            ['name' => 'edit user','guard_name'=> 'web'],
            ['name' => 'delete user','guard_name'=> 'web'],
            ['name' => 'list users','guard_name'=> 'web'],
            ['name' => 'suspend user','guard_name'=> 'web'],
            ['name' => 'list settings','guard_name'=> 'web'],
            ['name' => 'edit settings','guard_name'=> 'web'],


            ['name' => 'list academic years','guard_name'=> 'web'],
            ['name' => 'add academic year','guard_name'=> 'web'],
            ['name' => 'edit academic year','guard_name'=> 'web'],
            ['name' => 'add branch','guard_name'=> 'web'],
            ['name' => 'edit branch','guard_name'=> 'web'],
            ['name' => 'delete branch','guard_name'=> 'web'],
            ['name' => 'list branch','guard_name'=> 'web'],
            ['name' => 'list categories','guard_name'=> 'web'],
            ['name' => 'add category','guard_name'=> 'web'],
            ['name' => 'edit category','guard_name'=> 'web'],
            ['name' => 'delete category','guard_name'=> 'web'],

            ['name' => 'list programs','guard_name'=> 'web'],
            ['name' => 'add program','guard_name'=> 'web'],
            ['name' => 'edit program','guard_name'=> 'web'],
            ['name' => 'delete program','guard_name'=> 'web'],

            ['name' => 'add track','guard_name'=> 'web'],
            ['name' => 'edit track','guard_name'=> 'web'],
            ['name' => 'delete track','guard_name'=> 'web'],
            ['name' => 'list tracks','guard_name'=> 'web'],

            ['name' => 'import users','guard_name'=> 'web'],

            ['name' => 'show settings','guard_name'=> 'web'],
            ['name' => 'edit settings','guard_name'=> 'web'],

            ['name' => 'add subject','guard_name'=> 'web'],
            ['name' => 'list subjects','guard_name'=> 'web'],
            ['name' => 'add subject','guard_name'=> 'web'],
            ['name' => 'edit subject','guard_name'=> 'web'],
            ['name' => 'delete subject','guard_name'=> 'web'],
            ['name' => 'assign subjects','guard_name'=> 'web'],

            ['name' => 'assign subjects to staff','guard_name'=> 'web'],

            ['name' => 'add level','guard_name'=> 'web'],
            ['name' => 'edit level','guard_name'=> 'web'],
            ['name' => 'delete level','guard_name'=> 'web'],
            ['name' => 'list levels','guard_name'=> 'web'],
            ['name' => 'assign level','guard_name'=> 'web'],


            ['name' => 'add course','guard_name'=> 'web'],
            ['name' => 'edit course','guard_name'=> 'web'],
            ['name' => 'delete course','guard_name'=> 'web'],
            ['name' => 'list courses','guard_name'=> 'web'],

            ['name' => 'add class','guard_name'=> 'web'],
            ['name' => 'edit class','guard_name'=> 'web'],
            ['name' => 'delete class','guard_name'=> 'web'],
            ['name' => 'list classes','guard_name'=> 'web'],

            ['name' => 'add topic','guard_name'=> 'web'],
            ['name' => 'edit topic','guard_name'=> 'web'],
            ['name' => 'delete topic','guard_name'=> 'web'],
            ['name' => 'list topics','guard_name'=> 'web'],
            ['name' => 'view topics','guard_name'=> 'web'],

            ['name' => 'add content','guard_name'=> 'web'],
            ['name' => 'edit content','guard_name'=> 'web'],
            ['name' => 'delete content','guard_name'=> 'web'],
            ['name' => 'list contents','guard_name'=> 'web'],
            ['name' => 'view contents','guard_name'=> 'web'],


            ['name' => 'show settings','guard_name'=> 'web'],
            ['name' => 'edit settings','guard_name'=> 'web'],

            ['name' => 'set academic settings','guard_name'=> 'web'],

            ['name' => 'add timing set','guard_name'=> 'web'],
            ['name' => 'edit timing set','guard_name'=> 'web'],
            ['name' => 'delete timing set','guard_name'=> 'web'],
            ['name' => 'list timing sets','guard_name'=> 'web'],

            ['name' => 'add feedback','guard_name'=> 'web'],
            ['name' => 'edit feedback','guard_name'=> 'web'],
            ['name' => 'delete feedback','guard_name'=> 'web'],
            ['name' => 'view feedbacks','guard_name'=> 'web'],
            ['name' => 'list feedbacks','guard_name'=> 'web'],

            ['name' => 'add instruction','guard_name'=> 'web'],
            ['name' => 'edit instruction','guard_name'=> 'web'],
            ['name' => 'delete instruction','guard_name'=> 'web'],
            ['name' => 'view instructions','guard_name'=> 'web'],
            ['name' => 'list instructions','guard_name'=> 'web'],

            ['name' => 'add quiz','guard_name'=> 'web'],
            ['name' => 'edit quiz','guard_name'=> 'web'],
            ['name' => 'delete quiz','guard_name'=> 'web'],
            ['name' => 'view quizzes','guard_name'=> 'web'],
            ['name' => 'list quizzes','guard_name'=> 'web'],

            ['name' => 'view timetable','guard_name'=> 'web'],
            ['name' => 'list timetable','guard_name'=> 'web'],
            ['name' => 'update timetable','guard_name'=> 'web'],

            ['name' => 'check lessons','guard_name'=> 'web'],
        ]);
    }
}
