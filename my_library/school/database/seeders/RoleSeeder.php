<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::findOrCreate('owner','web');
        $role1->givePermissionTo('add user');
        $role1->givePermissionTo('edit user');
        $role1->givePermissionTo('delete user');
        $role1->givePermissionTo('list users');
        $role1->givePermissionTo('suspend user');
        $role1->givePermissionTo('list settings');
        $role1->givePermissionTo('edit settings');
        $role1->givePermissionTo('list academic years');
        $role1->givePermissionTo('add academic year');
        $role1->givePermissionTo('edit academic year');
        $role1->givePermissionTo('add branch');
        $role1->givePermissionTo('edit branch');
        $role1->givePermissionTo('delete branch');
        $role1->givePermissionTo('list branch');
        $role1->givePermissionTo('add category');
        $role1->givePermissionTo('edit category');
        $role1->givePermissionTo('delete category');
        $role1->givePermissionTo('list categories');
        $role1->givePermissionTo('add track');
        $role1->givePermissionTo('edit track');
        $role1->givePermissionTo('delete track');
        $role1->givePermissionTo('list tracks');
        $role1->givePermissionTo('import users');


        $role1->givePermissionTo('add program');
        $role1->givePermissionTo('edit program');
        $role1->givePermissionTo('delete program');
        $role1->givePermissionTo('list programs');


        $role1->givePermissionTo('add level');
        $role1->givePermissionTo('edit level');
        $role1->givePermissionTo('delete level');
        $role1->givePermissionTo('list levels');

        $role1->givePermissionTo('add course');
        $role1->givePermissionTo('edit course');
        $role1->givePermissionTo('delete course');
        $role1->givePermissionTo('list courses');

        $role1->givePermissionTo('add class');
        $role1->givePermissionTo('edit class');
        $role1->givePermissionTo('delete class');
        $role1->givePermissionTo('list classes');

        $role1->givePermissionTo('show settings');
        $role1->givePermissionTo('edit settings');
        $role1->givePermissionTo('assign level');

        $role1->givePermissionTo('list subjects');
        $role1->givePermissionTo('add subject');
        $role1->givePermissionTo('edit subject');
        $role1->givePermissionTo('delete subject');
        $role1->givePermissionTo('assign subjects');

        $role1->givePermissionTo('list timing sets');
        $role1->givePermissionTo('add timing set');
        $role1->givePermissionTo('edit timing set');
        $role1->givePermissionTo('delete timing set');

        $role1->givePermissionTo('assign subjects to staff');

        $role1->givePermissionTo('add feedback');
        $role1->givePermissionTo('edit feedback');
        $role1->givePermissionTo('delete feedback');
        $role1->givePermissionTo('view feedbacks');
        $role1->givePermissionTo('list feedbacks');
        $role1->givePermissionTo('view timetable');
        $role1->givePermissionTo('list timetable');
        $role1->givePermissionTo('update timetable');

        $role1->givePermissionTo('list topics');
        $role1->givePermissionTo('add topic');
        $role1->givePermissionTo('edit topic');
        $role1->givePermissionTo('delete topic');
        $role1->givePermissionTo('view topics');

        $role1->givePermissionTo('list contents');
        $role1->givePermissionTo('view contents');
        $role1->givePermissionTo('add content');
        $role1->givePermissionTo('edit content');
        $role1->givePermissionTo('delete content');

        $role1->givePermissionTo('list instructions');
        $role1->givePermissionTo('view instructions');
        $role1->givePermissionTo('add instruction');
        $role1->givePermissionTo('edit instruction');
        $role1->givePermissionTo('delete instruction');

        $role1->givePermissionTo('list quizzes');
        $role1->givePermissionTo('view quizzes');
        $role1->givePermissionTo('add quiz');
        $role1->givePermissionTo('edit quiz');
        $role1->givePermissionTo('delete quiz');


        $role1->givePermissionTo('check lessons');


        $role2 = Role::findOrCreate('admin', 'web');
        $role2->givePermissionTo('add user');
        $role2->givePermissionTo('edit user');
        $role2->givePermissionTo('delete user');
        $role2->givePermissionTo('list users');
        $role2->givePermissionTo('suspend user');
        $role2->givePermissionTo('list settings');
        $role2->givePermissionTo('edit settings');
        $role2->givePermissionTo('add branch');
        $role2->givePermissionTo('edit branch');
        $role2->givePermissionTo('delete branch');
        $role2->givePermissionTo('list branch');
        $role2->givePermissionTo('import users');
        $role2->givePermissionTo('list subjects');
        $role2->givePermissionTo('add subject');
        $role2->givePermissionTo('edit subject');
        $role2->givePermissionTo('delete subject');
        $role2->givePermissionTo('add branch');
        $role2->givePermissionTo('edit branch');
        $role2->givePermissionTo('delete branch');
        $role2->givePermissionTo('list branch');
        $role2->givePermissionTo('import users');
        $role2->givePermissionTo('set academic settings');
        $role2->givePermissionTo('assign subjects');
        $role1->givePermissionTo('view timetable');
        $role1->givePermissionTo('list timetable');
        $role1->givePermissionTo('update timetable');


        DB::table('roles')->insertOrIgnore([
            ['name' => 'admin','guard_name'=> 'web'],
            ['name' => 'staff','guard_name'=> 'web'],
            ['name' => 'student','guard_name'=> 'web'],
            ['name' => 'parent','guard_name'=> 'web'],
            ['name' => 'librarian','guard_name'=> 'web'],
            ['name' => 'assistant_librarian','guard_name'=> 'web'],
            ['name' => 'educational_supervisor','guard_name'=> 'web'],
            ['name' => 'secondary_parent','guard_name'=> 'web'],
            ['name' => 'student_guide','guard_name'=> 'web'],
            ['name' => 'level_agent','guard_name'=> 'web'],
            ['name' => 'school_stage_manager','guard_name'=> 'web'],
            ]);
    }
}
