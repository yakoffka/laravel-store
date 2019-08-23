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
            *  ПОРЯДОК СЛЕДОВАНИЯ НЕ НАРУШАТЬ!
            *  используется в database/seeds/PermissionRoleTableSeeder.php
            */

            // permissions for roles
            /*  1 */ ['group' => 'roles', 'name' => 'create_roles',  'display_name' => 'Create Roles',   'description' => 'Create the Role', ],
            /*  2 */ ['group' => 'roles', 'name' => 'edit_roles',    'display_name' => 'Edit Roles',     'description' => 'Edit the Role',   ],
            /*  3 */ ['group' => 'roles', 'name' => 'delete_roles',  'display_name' => 'Delete Roles',   'description' => 'Delete the Role', ],
            /*  4 */ ['group' => 'roles', 'name' => 'view_roles',    'display_name' => 'View Roles',     'description' => 'View all (include invisible, alien and hide) Role',],
            
            // permissions for permissions
            /*  5 */ ['group' => 'permissions', 'name' => 'create_permissions',  'display_name' => 'Create Permissions',   'description' => 'Create the Permission', ],
            /*  6 */ ['group' => 'permissions', 'name' => 'edit_permissions',    'display_name' => 'Edit Permissions',     'description' => 'Edit the Permission',   ],
            /*  7 */ ['group' => 'permissions', 'name' => 'delete_permissions',  'display_name' => 'Delete Permissions',   'description' => 'Delete the Permission', ],
            /*  8 */ ['group' => 'permissions', 'name' => 'view_permissions',    'display_name' => 'View Permissions',     'description'  => 'View all (include invisible, alien and hide) Permission',],
            
            // permissions for users
            /*  9 */ ['group' => 'users', 'name' => 'create_users',  'display_name' => 'Create Users',   'description' => 'Create the User', ],
            /* 10 */ ['group' => 'users', 'name' => 'edit_users',    'display_name' => 'Edit Users',     'description' => 'Edit the User',   ],
            /* 11 */ ['group' => 'users', 'name' => 'delete_users',  'display_name' => 'Delete Users',   'description' => 'Delete the User', ],
            /* 12 */ ['group' => 'users', 'name' => 'view_users',    'display_name' => 'View Users',     'description' => 'View all (include invisible, alien and hide) User',],
            
            // permissions for comments
            /* 13 */ ['group' => 'comments', 'name' => 'create_comments',  'display_name' => 'Create Comments',   'description' => 'Create the Comment', ],
            /* 14 */ ['group' => 'comments', 'name' => 'edit_comments',    'display_name' => 'Edit Comments',     'description' => 'Edit the Comment',   ],
            /* 15 */ ['group' => 'comments', 'name' => 'delete_comments',  'display_name' => 'Delete Comments',   'description' => 'Delete the Comment', ],
            /* 16 */ ['group' => 'comments', 'name' => 'view_comments',    'display_name' => 'View Comments',     'description' => 'View all (include invisible, alien and hide) Comment',],
            
            // permissions for products
            /* 17 */ ['group' => 'products', 'name' => 'create_products',  'display_name' => 'Create Products',   'description' => 'Create the Product', ],
            /* 18 */ ['group' => 'products', 'name' => 'edit_products',    'display_name' => 'Edit Products',     'description' => 'Edit the Product',   ],
            /* 19 */ ['group' => 'products', 'name' => 'delete_products',  'display_name' => 'Delete Products',   'description' => 'Delete the Product', ],
            /* 20 */ ['group' => 'products', 'name' => 'view_products',    'display_name' => 'View Products',     'description' => 'View all (include invisible, alien and hide) Product',],
            
            // permissions for categories
            /* 21 */ ['group' => 'categories', 'name' => 'create_categories',  'display_name' => 'Create Categories',   'description' => 'Create the Product', ],
            /* 22 */ ['group' => 'categories', 'name' => 'edit_categories',    'display_name' => 'Edit Categories',     'description' => 'Edit the Product',   ],
            /* 23 */ ['group' => 'categories', 'name' => 'delete_categories',  'display_name' => 'Delete Categories',   'description' => 'Delete the Product', ],
            /* 24 */ ['group' => 'categories', 'name' => 'view_categories',    'display_name' => 'View Categories',     'description' => 'View all (include invisible, alien and hide) Product',],

            // permissions for orders
            /* 25 */ ['group' => 'orders', 'name' => 'create_orders',  'display_name' => 'Create Orders',   'description' => 'Create the Order', ],
            /* 26 */ ['group' => 'orders', 'name' => 'edit_orders',    'display_name' => 'Edit Orders',     'description' => 'Edit the Order',   ],
            /* 27 */ ['group' => 'orders', 'name' => 'delete_orders',  'display_name' => 'Delete Orders',   'description' => 'Delete the Order', ],
            /* 28 */ ['group' => 'orders', 'name' => 'view_orders',    'display_name' => 'View Orders',     'description' => 'View all (include invisible, alien and hide) Order',],

            // permissions for settings
            /* 29 */ ['group' => 'settings', 'name' => 'create_settings',  'display_name' => 'Create Settings',   'description' => 'Create the Setting', ],
            /* 30 */ ['group' => 'settings', 'name' => 'edit_settings',    'display_name' => 'Edit Settings',     'description' => 'Edit the Setting',   ],
            /* 31 */ ['group' => 'settings', 'name' => 'delete_settings',  'display_name' => 'Delete Settings',   'description' => 'Delete the Setting', ],
            /* 32 */ ['group' => 'settings', 'name' => 'view_settings',    'display_name' => 'View Settings',     'description' => 'View Setting',       ],

            // permissions to access admin panel
            /* 33 */ ['group' => 'adminpanel', 'name' => 'create_adminpanel',  'display_name' => 'Create Adminpanel',   'description' => 'Create the Adminpanel', ],
            /* 34 */ ['group' => 'adminpanel', 'name' => 'edit_adminpanel',    'display_name' => 'Edit Adminpanel',     'description' => 'Edit the Adminpanel',   ],
            /* 35 */ ['group' => 'adminpanel', 'name' => 'delete_adminpanel',  'display_name' => 'Delete Adminpanel',   'description' => 'Delete the Adminpanel', ],
            /* 36 */ ['group' => 'adminpanel', 'name' => 'view_adminpanel',    'display_name' => 'View Adminpanel',     'description' => 'View Adminpanel',       ],

            // permissions to actions (history)
            /* 33 */ ['group' => 'actions', 'name' => 'create_actions',  'display_name' => 'Create actions',   'description' => 'Create the Actions',],
            /* 34 */ ['group' => 'actions', 'name' => 'edit_actions',    'display_name' => 'Edit actions',     'description' => 'Edit the Actions',  ],
            /* 35 */ ['group' => 'actions', 'name' => 'delete_actions',  'display_name' => 'Delete actions',   'description' => 'Delete the Actions',],
            /* 36 */ ['group' => 'actions', 'name' => 'view_actions',    'display_name' => 'View actions',     'description' => 'View Actions',      ],
            
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
