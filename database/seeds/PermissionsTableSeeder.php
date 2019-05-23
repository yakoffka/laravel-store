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
            ['group' => 'roles', 'name' => 'create_roles',  'display_name' => 'Create Roles',   'description' => 'Create the Role', ],
            ['group' => 'roles', 'name' => 'edit_roles',    'display_name' => 'Edit Roles',     'description' => 'Edit the Role',   ],
            ['group' => 'roles', 'name' => 'delete_roles',  'display_name' => 'Delete Roles',   'description' => 'Delete the Role', ],
            ['group' => 'roles', 'name' => 'view_roles',    'display_name' => 'View Roles',     'description'  => 'View the Role',  ],
            
            // permissions for permissions
            ['group' => 'permissions', 'name' => 'create_permissions',  'display_name' => 'Create Permissions',   'description' => 'Create the Permission', ],
            ['group' => 'permissions', 'name' => 'edit_permissions',    'display_name' => 'Edit Permissions',     'description' => 'Edit the Permission',   ],
            ['group' => 'permissions', 'name' => 'delete_permissions',  'display_name' => 'Delete Permissions',   'description' => 'Delete the Permission', ],
            ['group' => 'permissions', 'name' => 'view_permissions',    'display_name' => 'View Permissions',     'description'  => 'View the Permission',  ],
            
            // permissions for users
            ['group' => 'users', 'name' => 'create_users',  'display_name' => 'Create Users',   'description' => 'Create the User', ],
            ['group' => 'users', 'name' => 'edit_users',    'display_name' => 'Edit Users',     'description' => 'Edit the User',   ],
            ['group' => 'users', 'name' => 'delete_users',  'display_name' => 'Delete Users',   'description' => 'Delete the User', ],
            ['group' => 'users', 'name' => 'view_users',    'display_name' => 'View Users',     'description'  => 'View the User',  ],
            
            // permissions for comments
            ['group' => 'comments', 'name' => 'create_comments',  'display_name' => 'Create Comments',   'description' => 'Create the Comment', ],
            ['group' => 'comments', 'name' => 'edit_comments',    'display_name' => 'Edit Comments',     'description' => 'Edit the Comment',   ],
            ['group' => 'comments', 'name' => 'delete_comments',  'display_name' => 'Delete Comments',   'description' => 'Delete the Comment', ],
            ['group' => 'comments', 'name' => 'view_comments',    'display_name' => 'View Comments',     'description'  => 'View the Comment',  ],
            
            // permissions for products
            ['group' => 'products', 'name' => 'create_products',  'display_name' => 'Create Products',   'description' => 'Create the Product', ],
            ['group' => 'products', 'name' => 'edit_products',    'display_name' => 'Edit Products',     'description' => 'Edit the Product',   ],
            ['group' => 'products', 'name' => 'delete_products',  'display_name' => 'Delete Products',   'description' => 'Delete the Product', ],
            ['group' => 'products', 'name' => 'view_products',    'display_name' => 'View Products',     'description'  => 'View the Product',  ],
            
        ];
 
        foreach ($permissions as $permission){
            DB::table('permissions')->insert([
                'group' => $permission['group'],
                'name' => $permission['name'],
                'display_name' => $permission['display_name'],
                'description' => $permission['description'],
            ]);
        }
    }
}
