<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $arr_perm = [
            'system' => [
                /* roles */         0,0,0,0,
                /* permissions */   0,0,0,0,
                /* users */         0,0,0,0,
                /* comments */      0,0,0,0,
                /* products */      0,0,0,0,
                /* categories */    0,0,0,0,
                /* orders */        0,0,0,0,
                /* settings */      0,0,0,0,
                /* adminpanel */    0,0,0,0,
                /* actions */       0,0,0,0,
                /* tasks */         0,0,0,0,
                /* manufacturers */ 1,1,1,1,
            ],
            'unregistered' => [
                /* roles */         0,0,0,0,
                /* permissions */   0,0,0,0,
                /* users */         0,0,0,0,
                /* comments */      0,0,0,0,
                /* products */      0,0,0,0,
                /* categories */    0,0,0,0,
                /* orders */        0,0,0,0,
                /* settings */      0,0,0,0,
                /* adminpanel */    0,0,0,0,
                /* actions */       0,0,0,0,
                /* tasks */         0,0,0,0,
                /* manufacturers */ 0,0,0,0,
            ],
            'owner' => [
                /* roles */         1,1,1,1,
                /* permissions */   0,0,0,1,
                /* users */         0,1,1,1,
                /* comments */      1,1,1,1,
                /* products */      1,1,1,1,
                /* categories */    1,1,1,1,
                /* orders */        1,1,1,1,
                /* settings */      0,1,0,1,
                /* adminpanel */    0,0,0,1,
                /* actions */       0,0,0,1,
                /* tasks */         1,1,1,1,
                /* manufacturers */ 1,1,1,1,
            ],
            'developer' => [
                /* roles */         1,1,1,1,
                /* permissions */   0,0,0,1,
                /* users */         0,1,1,1,
                /* comments */      1,1,1,1,
                /* products */      1,1,1,1,
                /* categories */    1,1,1,1,
                /* orders */        1,1,1,1,
                /* settings */      0,1,0,1,
                /* adminpanel */    0,0,0,1,
                /* actions */       0,0,0,1,
                /* tasks */         1,1,1,1,
                /* manufacturers */ 1,1,1,1,
            ],
            'admin' => [
                /* roles */         0,0,0,1,
                /* permissions */   0,0,0,1,
                /* users */         0,1,1,1,
                /* comments */      1,1,1,1,
                /* products */      1,1,1,1,
                /* categories */    1,1,1,1,
                /* orders */        1,1,1,1,
                /* settings */      0,1,0,1,
                /* adminpanel */    0,0,0,1,
                /* actions */       0,0,0,1,
                /* tasks */         1,1,1,1,
                /* manufacturers */ 1,1,1,1,
            ],
            'cmanager' => [
                /* roles */         0,0,0,0,
                /* permissions */   0,0,0,0,
                /* users */         0,0,0,0,
                /* comments */      0,0,0,0,
                /* products */      1,1,1,1,
                /* categories */    1,1,1,1,
                /* orders */        0,0,0,1,
                /* settings */      0,1,0,1,
                /* adminpanel */    0,0,0,1,
                /* actions */       0,0,0,1,
                /* tasks */         1,0,0,1,
                /* manufacturers */ 1,1,1,1,
            ],
            'smanager' => [
                /* roles */         0,0,0,0,
                /* permissions */   0,0,0,0,
                /* users */         0,0,0,0,
                /* comments */      0,0,0,0,
                /* products */      0,0,0,1,
                /* categories */    0,0,0,0,
                /* orders */        0,1,1,1,
                /* settings */      0,0,0,0,
                /* adminpanel */    0,0,0,1,
                /* actions */       0,0,0,1,
                /* tasks */         1,0,0,1,
                /* manufacturers */ 0,0,0,0,
            ],
            'user' => [
                /* roles */         0,0,0,0,
                /* permissions */   0,0,0,0,
                /* users */         0,0,0,0,
                /* comments */      0,0,0,0,
                /* products */      0,0,0,0,
                /* categories */    0,0,0,0,
                /* orders */        1,0,0,0,
                /* settings */      0,0,0,0,
                /* adminpanel */    0,0,0,0,
                /* actions */       0,0,0,0,
                /* tasks */         0,0,0,0,
                /* manufacturers */ 0,0,0,0,
            ],
        ];

        foreach ($roles as $role) {
            foreach ($permissions as $permission) {
                if ( $arr_perm[$role->name][$permission->id - 1] ) {
                    DB::table('permission_role')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                    ]);
                }
            }
        }

    }
}
