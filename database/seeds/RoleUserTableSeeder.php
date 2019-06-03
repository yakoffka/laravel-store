<?php

use Illuminate\Database\Seeder;
use App\User;

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

            if ($user->id == 1) {
                $role_id = 1;
            } elseif ($user->id == 2) {
                $role_id = 2;
            } elseif ($user->id == 3) {
                $role_id = 3;
            } else {
                $role_id = 4;
            }

            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $role_id,
            ]);

        }
    }
}