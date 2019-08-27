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
                    $role->id == 1 and !in_array( $permission->id, [5, 6, 7, 9, 33, 34, 35] )
                     
                    or // for admin
                    $role->id == 2 and !in_array( $permission->id, [1, 2, 3, 5, 6, 7, 9, 29, 30, 31, 33, 34, 35, 37, 38, 39] )
                    
                    or // for manager
                    $role->id == 3 and in_array( $permission->id, [4, 8, 12, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 28, 36] )
                    
                    or // for user
                    $role->id == 4 and in_array( $permission->id, [13, 16, 24, 25] )
                    
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
