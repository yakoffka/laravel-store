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

        foreach ($roles as $role) {

            foreach ($permissions as $permission) {

                if (
                    $role->id == 1
                    or 
                    $role->id == 2 and !in_array($permission->id, [1, 2, 3, 5, 6, 7])
                    or
                    $role->id == 3 and ( $permission->id > 12 or in_array($permission->id, [4, 8, 9, 10, 12] ) )
                    or
                    in_array($permission->id, [9, 10, 16, 20])
                ) {
                    DB::table('permission_role')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $role->id,
                    ]);
                }        
            }

        }

    }
}
