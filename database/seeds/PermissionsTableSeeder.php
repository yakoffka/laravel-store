<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [

            /*
            *   ПОРЯДОК СЛЕДОВАНИЯ НЕ НАРУШАТЬ!
            *  используется в database/seeds/PermissionRoleTableSeeder.php
            */

            // permissions for roles
            /*  1 */ ['group' => 'roles', 'name' => 'create_roles',  'display_name' => 'Create Roles',   'description' => 'Create the Role', ],
            /*  2 */ ['group' => 'roles', 'name' => 'edit_roles',    'display_name' => 'Edit Roles',     'description' => 'Edit the Role',   ],
            /*  3 */ ['group' => 'roles', 'name' => 'delete_roles',  'display_name' => 'Delete Roles',   'description' => 'Delete the Role', ],
            /*  4 */ ['group' => 'roles', 'name' => 'view_roles',    'display_name' => 'View Roles',     'description'  => 'View the Role',  ],
            
            // permissions for permissions
            /*  5 */ ['group' => 'permissions', 'name' => 'create_permissions',  'display_name' => 'Create Permissions',   'description' => 'Create the Permission', ],
            /*  6 */ ['group' => 'permissions', 'name' => 'edit_permissions',    'display_name' => 'Edit Permissions',     'description' => 'Edit the Permission',   ],
            /*  7 */ ['group' => 'permissions', 'name' => 'delete_permissions',  'display_name' => 'Delete Permissions',   'description' => 'Delete the Permission', ],
            /*  8 */ ['group' => 'permissions', 'name' => 'view_permissions',    'display_name' => 'View Permissions',     'description'  => 'View the Permission',  ],
            
            // permissions for users
            /*  9 */ ['group' => 'users', 'name' => 'create_users',  'display_name' => 'Create Users',   'description' => 'Create the User', ],
            /* 10 */ ['group' => 'users', 'name' => 'edit_users',    'display_name' => 'Edit Users',     'description' => 'Edit the User',   ],
            /* 11 */ ['group' => 'users', 'name' => 'delete_users',  'display_name' => 'Delete Users',   'description' => 'Delete the User', ],
            /* 12 */ ['group' => 'users', 'name' => 'view_users',    'display_name' => 'View Users',     'description'  => 'View the User',  ],
            
            // permissions for comments
            /* 13 */ ['group' => 'comments', 'name' => 'create_comments',  'display_name' => 'Create Comments',   'description' => 'Create the Comment', ],
            /* 14 */ ['group' => 'comments', 'name' => 'edit_comments',    'display_name' => 'Edit Comments',     'description' => 'Edit the Comment',   ],
            /* 15 */ ['group' => 'comments', 'name' => 'delete_comments',  'display_name' => 'Delete Comments',   'description' => 'Delete the Comment', ],
            /* 16 */ ['group' => 'comments', 'name' => 'view_comments',    'display_name' => 'View Comments',     'description'  => 'View the Comment',  ],
            
            // permissions for products
            /* 17 */ ['group' => 'products', 'name' => 'create_products',  'display_name' => 'Create Products',   'description' => 'Create the Product', ],
            /* 18 */ ['group' => 'products', 'name' => 'edit_products',    'display_name' => 'Edit Products',     'description' => 'Edit the Product',   ],
            /* 19 */ ['group' => 'products', 'name' => 'delete_products',  'display_name' => 'Delete Products',   'description' => 'Delete the Product', ],
            /* 20 */ ['group' => 'products', 'name' => 'view_products',    'display_name' => 'View Products',     'description'  => 'View the Product',  ],
            
            // permissions for categories
            /* 21 */ ['group' => 'categories', 'name' => 'create_categories',  'display_name' => 'Create Categories',   'description' => 'Create the Product', ],
            /* 22 */ ['group' => 'categories', 'name' => 'edit_categories',    'display_name' => 'Edit Categories',     'description' => 'Edit the Product',   ],
            /* 23 */ ['group' => 'categories', 'name' => 'delete_categories',  'display_name' => 'Delete Categories',   'description' => 'Delete the Product', ],
            /* 24 */ ['group' => 'categories', 'name' => 'view_categories',    'display_name' => 'View Categories',     'description'  => 'View the Product',  ],
            
        ];
 
        foreach ($permissions as $permission){
            DB::table('permissions')->insert([
                'group' => $permission['group'],
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'description' => $permission['description'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
