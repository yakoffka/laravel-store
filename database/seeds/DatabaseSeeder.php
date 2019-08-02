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
            ProductsTableSeeder::class,
            UsersTableSeeder::class,
            // CategoryProductTableSeeder::class,
            // Zizaco/entrust
            RolesTableSeeder::class,
            PermissionsTableSeeder::class,
            RoleUserTableSeeder::class,
            PermissionRoleTableSeeder::class,
            StatusesTableSeeder::class,
        ]);
        factory(App\Comment::class, (config('custom.num_products_seed') * 5) )->create();
    }
}
