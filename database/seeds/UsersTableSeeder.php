<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Admin Name',
            'email' => 'admin@gmail.com',
            // 'role_id' => 1,
            'password' => bcrypt('111111'),
        ]);
    }
}
