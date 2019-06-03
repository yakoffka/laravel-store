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
                    // for owner
                    $role->id == 1
                    or 
                    // for admin
                    $role->id == 2 and !in_array( $permission->id, [1, 2, 3, 5, 6, 7, 21, 22, 23] )
                    or
                    // for manager
                    $role->id == 3 and ( $permission->id > 13 and !in_array( $permission->id, [15, 19, 21, 22, 23] ) )
                    or
                    // for user
                    $role->id == 4 and in_array( $permission->id, [16, 20] )
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