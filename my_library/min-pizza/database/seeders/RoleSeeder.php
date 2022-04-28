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
//        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//        DB::table('role_has_permissions')->truncate();
//        DB::table('model_has_permissions')->truncate();
//        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $role1 = Role::findOrCreate('super_admin', 'web');

        $role1->givePermissionTo('suspend user');
        $role1->givePermissionTo('list settings');
        $role1->givePermissionTo('edit settings');
        $role1->givePermissionTo('show settings');

        $role1->givePermissionTo('list addresses');
        $role1->givePermissionTo('edit address');
        $role1->givePermissionTo('delete address');
        $role1->givePermissionTo('add address');


        $role1->givePermissionTo('control drivers');
        $role1->givePermissionTo('control customers');
        $role1->givePermissionTo('control categories');
        $role1->givePermissionTo('control taxes');
        $role1->givePermissionTo('control feedbacks');
        $role1->givePermissionTo('control restaurants');


        $role1->givePermissionTo('list worktimes');
        $role1->givePermissionTo('update worktime');

        $role1->givePermissionTo('list restaurant settings');
        $role1->givePermissionTo('update restaurant setting');
        $role1->givePermissionTo('update restaurant status');


        $role1->givePermissionTo('change menu');
        $role1->givePermissionTo('list settings');
        $role1->givePermissionTo('edit settings');



        $role1->givePermissionTo('control menu item options');

        $role1->givePermissionTo('list restaurant ratings');
        $role1->givePermissionTo('delete restaurant rating');

        $role1->givePermissionTo('edit questions');

        $role1->givePermissionTo('list orders');
        $role1->givePermissionTo('add order');
        $role1->givePermissionTo('edit order');
        $role1->givePermissionTo('delete order');


        $role1->givePermissionTo('send notification');
        $role1->givePermissionTo('see notification');

        $role1->givePermissionTo('control codes');

        $role1->givePermissionTo('view orders');

        $role1->givePermissionTo('control reasons');
        $role1->givePermissionTo('control sliders');
        $role1->givePermissionTo('control owner');
        $role1->givePermissionTo('control offers');
        $role1->givePermissionTo('manage holidays');


        $role2 = Role::findOrCreate('customer', 'web');
        $role2->givePermissionTo('add address');
        $role2->givePermissionTo('list restaurant ratings');
        $role2->givePermissionTo('add restaurant rating');
        $role2->givePermissionTo('edit restaurant rating');
        $role2->givePermissionTo('delete restaurant rating');

        $role2->givePermissionTo('list orders');
        $role2->givePermissionTo('add order');
        $role2->givePermissionTo('edit order');
        $role2->givePermissionTo('delete order');

        $role3= Role::findOrCreate('driver', 'web');
        $role3->givePermissionTo('change status');
        $role3->givePermissionTo('list orders');

        $role4= Role::findOrCreate('owner', 'web');
        $role4->givePermissionTo('control menu item options');
        $role4->givePermissionTo('list orders');
        $role4->givePermissionTo('edit order');
        $role4->givePermissionTo('manage holidays');

        DB::table('roles')->insertOrIgnore([
            ['name' => 'admin','guard_name'=> 'web'],
        ]);
    }
}
