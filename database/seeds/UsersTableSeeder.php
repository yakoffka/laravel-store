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
            'name' => 'Owner Name',
            'email' => 'owner@gmail.com',
            'password' => bcrypt('111111'),
        ]);

        DB::table('users')->insert([
            'name' => 'Admin Name',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('111111'),
        ]);

        DB::table('users')->insert([
            'name' => 'Manager Name',
            'email' => 'manager@gmail.com',
            'password' => bcrypt('111111'),
        ]);

        DB::table('users')->insert([
            'name' => 'User First Name',
            'email' => 'user01@gmail.com',
            'password' => bcrypt('111111'),
        ]);

        DB::table('users')->insert([
            'name' => 'User Second Name',
            'email' => 'user02@gmail.com',
            'password' => bcrypt('111111'),
        ]);

        DB::table('users')->insert([
            'name' => 'User Third Name',
            'email' => 'user03@gmail.com',
            'password' => bcrypt('111111'),
        ]);
    }
}
