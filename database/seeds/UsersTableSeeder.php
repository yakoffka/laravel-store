<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

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
            'uuid' => Str::uuid(),
            'name' => 'unregistered user',
            'email' => 'yagithub+unregistered@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'uuid' => Str::uuid(),
            'name' => 'Theodor',
            'email' => 'yagithub+owner@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'uuid' => Str::uuid(),
            'name' => 'Travlarnor',
            'email' => 'yagithub+admin@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ( !empty(env('NAME_MANAGER')) and !empty(env('MAIL_MANAGER')) and !empty(env('PASS_MANAGER')) ) {
            DB::table('users')->insert([
                'uuid' => Str::uuid(),
                'name' => env('NAME_MANAGER'),
                'email' => env('MAIL_MANAGER'),
                'password' => bcrypt( env('PASS_MANAGER') ),
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        DB::table('users')->insert([
            'uuid' => Str::uuid(),
            'name' => 'Lagshmivara',
            'email' => 'yagithub+manager@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'uuid' => Str::uuid(),
            'name' => 'Travlarnor I',
            'email' => 'yagithub+user01@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'uuid' => Str::uuid(),
            'name' => 'Akaky Akakievich II',
            'email' => 'yagithub+user02@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'uuid' => Str::uuid(),
            'name' => 'Ephim III',
            'email' => 'yagithub+user03@mail.ru',
            'password' => bcrypt('111111'),
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
