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
            'name' => 'unregistered user',
            'email' => 'yagithub+unregistered@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Theodor',
            'email' => 'yagithub+owner@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Travlarnor',
            'email' => 'yagithub+admin@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Lagshmivara',
            'email' => 'yagithub+manager@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Travlarnor I',
            'email' => 'yagithub+user01@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Akaky Akakievich II',
            'email' => 'yagithub+user02@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Ephim III',
            'email' => 'yagithub+user03@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
