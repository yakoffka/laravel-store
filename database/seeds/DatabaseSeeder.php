<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ProductsTableSeeder::class,
            UsersTableSeeder::class,
            // Zizaco/entrust
            RolesTableSeeder::class,
            PermissionsTableSeeder::class,
            RoleUserTableSeeder::class,
            PermissionRoleTableSeeder::class,
        ]);
    }
}
