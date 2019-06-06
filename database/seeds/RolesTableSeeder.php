<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            /*
            *   ПОРЯДОК СЛЕДОВАНИЯ НЕ НАРУШАТЬ! rank
            *  используется в database/seeds/PermissionRoleTableSeeder.php
            */
            [
                'name' => 'owner',
                'display_name' => 'Project Owner',
                'description'  => 'The user is the owner of this project. Has all possible rights.',
            ],[
                'name' => 'admin',
                'display_name' => 'Project Admin',
                'description'  => 'Admin acts within the framework of the rights granted to it by the owner.',
            ],[
                'name' => 'manager',
                'display_name' => 'Project Manager',
                'description'  => 'Store management.',
            ],[
                'name' => 'user',
                'display_name' => 'Project User',
                'description'  => 'Plays the role of the buyer.',
            ] 
        ];
 
        foreach ($roles as $i => $role){
            DB::table('roles')->insert([
                'name' => $role['name'],
                'display_name' => $role['display_name'],
                'description' => $role['description'],
                'rank' => $i + 1,
                'is_basic' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
