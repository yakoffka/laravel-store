<?php

use Illuminate\Database\Seeder;
use App\{User, Role};

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            
            $role_user = preg_replace('~^[^+]+\+~', '', preg_replace('~@.+$~', '', $user->email));

            $role = Role::where('name', $role_user)->first();
            if ( !$role ) {
                $role = Role::where('name', 'user')->first();
            }

            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $role->id,
            ]);

            unset($role);
        }
    }
}
