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
            CategoriesTableSeeder::class,
            ManufacturersTableSeeder::class,
            UsersTableSeeder::class,
            TasksTableSeeder::class,
            TasksprioritiesTableSeeder::class,
            TasksstatusesTableSeeder::class,
            ProductsTableSeeder::class,
            // CategoryProductTableSeeder::class,
            RolesTableSeeder::class,            // for Zizaco/entrust
            PermissionsTableSeeder::class,      // for Zizaco/entrust
            RoleUserTableSeeder::class,         // for Zizaco/entrust
            PermissionRoleTableSeeder::class,   // for Zizaco/entrust
            StatusesTableSeeder::class,         // for confirmations email
            SettingsTableSeeder::class,
            ImagesTableSeeder::class,
        ]);

        // factory(App\Comment::class, (config('custom.num_products_seed') * 2) )->create();
    }
}
