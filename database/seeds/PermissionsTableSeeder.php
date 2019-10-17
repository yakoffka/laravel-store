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
            /*  1 */ ['group' => 'roles', 'name' => 'create_roles',  'display_name' => 'создание',  'description' => 'Создание ролей',  ],
            /*  2 */ ['group' => 'roles', 'name' => 'edit_roles',    'display_name' => 'правка',    'description' => 'Изменение ролей', ],
            /*  3 */ ['group' => 'roles', 'name' => 'delete_roles',  'display_name' => 'удаление',  'description' => 'Удаление ролей',  ],
            /*  4 */ ['group' => 'roles', 'name' => 'view_roles',    'display_name' => 'просмотр',  'description' => 'Просмотр ролей, включая чужие, и их события.',],
            
            // permissions for permissions
            /*  5 */ ['group' => 'permissions', 'name' => 'create_permissions',  'display_name' => 'создание',  'description' => 'Создание разрешений',         ],
            /*  6 */ ['group' => 'permissions', 'name' => 'edit_permissions',    'display_name' => 'правка',    'description' => 'Редактирование разрешений',   ],
            /*  7 */ ['group' => 'permissions', 'name' => 'delete_permissions',  'display_name' => 'удаление',  'description' => 'Удаление разрешений',         ],
            /*  8 */ ['group' => 'permissions', 'name' => 'view_permissions',    'display_name' => 'просмотр',  'description'  => 'Просмотр разрешений, включая чужие, и их события.',],
            
            // permissions for users
            /*  9 */ ['group' => 'users', 'name' => 'create_users',  'display_name' => 'создание', 'description' => 'Создание пользователей',          ],
            /* 10 */ ['group' => 'users', 'name' => 'edit_users',    'display_name' => 'правка',   'description' => 'Редактирование пользователей',    ],
            /* 11 */ ['group' => 'users', 'name' => 'delete_users',  'display_name' => 'удаление', 'description' => 'Удаление пользователей',          ],
            /* 12 */ ['group' => 'users', 'name' => 'view_users',    'display_name' => 'просмотр', 'description' => 'Просмотр пользователей, включая удаленных, и их события.',],
            
            // permissions for comments
            /* 13 */ ['group' => 'comments', 'name' => 'create_comments',  'display_name' => 'создание', 'description' => 'Создание комментариев, включая комментарии от чужого имени (не реализовано)',       ],
            /* 14 */ ['group' => 'comments', 'name' => 'edit_comments',    'display_name' => 'правка',	 'description' => 'Редактирование комментариев, включая чужие', ],
            /* 15 */ ['group' => 'comments', 'name' => 'delete_comments',  'display_name' => 'удаление', 'description' => 'Удаление комментариев',       ],
            /* 16 */ ['group' => 'comments', 'name' => 'view_comments',    'display_name' => 'просмотр', 'description' => 'Просмотр комментариев, включая чужие и удалённые(не реализовано), и их события.',],
            
            // permissions for products
            /* 17 */ ['group' => 'products', 'name' => 'create_products',  'display_name' => 'создание',  'description' => 'Создание товаров',        ],
            /* 18 */ ['group' => 'products', 'name' => 'edit_products',    'display_name' => 'правка',    'description' => 'Редактирование товаров',  ],
            /* 19 */ ['group' => 'products', 'name' => 'delete_products',  'display_name' => 'удаление',  'description' => 'Удаление товаров',        ],
            /* 20 */ ['group' => 'products', 'name' => 'view_products',    'display_name' => 'просмотр',  'description' => 'Просмотр товаров, включая скрытые, и их события.',],
            
            // permissions for categories
            /* 21 */ ['group' => 'categories', 'name' => 'create_categories',  'display_name' => 'создание',  'description' => 'Создание категорий',      ],
            /* 22 */ ['group' => 'categories', 'name' => 'edit_categories',    'display_name' => 'правка',    'description' => 'Редактирование категорий',],
            /* 23 */ ['group' => 'categories', 'name' => 'delete_categories',  'display_name' => 'удаление',  'description' => 'Удаление категорий',      ],
            /* 24 */ ['group' => 'categories', 'name' => 'view_categories',    'display_name' => 'просмотр',  'description' => 'Просмотр категорий, включая скрытые, и их события.',],

            // permissions for orders
            /* 25 */ ['group' => 'orders', 'name' => 'create_orders',  'display_name' => 'создание',  'description' => 'Создание заказов (только от своего имени)',],
            /* 26 */ ['group' => 'orders', 'name' => 'edit_orders',    'display_name' => 'правка',    'description' => 'Редактирование заказов',  ],
            /* 27 */ ['group' => 'orders', 'name' => 'delete_orders',  'display_name' => 'удаление',  'description' => 'Удаление заказов',        ],
            /* 28 */ ['group' => 'orders', 'name' => 'view_orders',    'display_name' => 'просмотр',  'description' => 'Просмотр заказов, включая чужие, и их события.',],

            // permissions for settings
            /* 29 */ ['group' => 'settings', 'name' => 'create_settings',  'display_name' => 'создание',  'description' => 'Создание настроек',       ],
            /* 30 */ ['group' => 'settings', 'name' => 'edit_settings',    'display_name' => 'правка',    'description' => 'Редактирование настроек', ],
            /* 31 */ ['group' => 'settings', 'name' => 'delete_settings',  'display_name' => 'удаление',  'description' => 'Удаление настроек',       ],
            /* 32 */ ['group' => 'settings', 'name' => 'view_settings',    'display_name' => 'просмотр',  'description' => 'Просмотр настроек',       ],

            // permissions to access admin panel
            /* 33 */ ['group' => 'adminpanel', 'name' => 'create_adminpanel',  'display_name' => 'создание',  'description' => 'Создание админки',        ],
            /* 34 */ ['group' => 'adminpanel', 'name' => 'edit_adminpanel',    'display_name' => 'правка',    'description' => 'Редактирование админки',  ],
            /* 35 */ ['group' => 'adminpanel', 'name' => 'delete_adminpanel',  'display_name' => 'удаление',  'description' => 'Удаление админки',        ],
            /* 36 */ ['group' => 'adminpanel', 'name' => 'view_adminpanel',    'display_name' => 'просмотр',  'description' => 'Пользование админкой',        ],

            // permissions to events (history)
            /* 37 */ ['group' => 'events', 'name' => 'create_events',  'display_name' => 'создание', 'description' => 'Создание события',        ],
            /* 38 */ ['group' => 'events', 'name' => 'edit_events',    'display_name' => 'правка',   'description' => 'Редактирование события',  ],
            /* 39 */ ['group' => 'events', 'name' => 'delete_events',  'display_name' => 'удаление', 'description' => 'Удаление события',        ],
            /* 40 */ ['group' => 'events', 'name' => 'view_events',    'display_name' => 'просмотр', 'description' => 'Просмотр полной события.',        ],

            // permissions to tasks
            /* 41 */ ['group' => 'tasks', 'name' => 'create_tasks',  'display_name' => 'создание',  'description' => 'Создание задач',      ],
            /* 42 */ ['group' => 'tasks', 'name' => 'edit_tasks',    'display_name' => 'правка',    'description' => 'Редактирование задач',],
            /* 43 */ ['group' => 'tasks', 'name' => 'delete_tasks',  'display_name' => 'удаление',  'description' => 'Удаление задач, включая чужие',],
            /* 44 */ ['group' => 'tasks', 'name' => 'view_tasks',    'display_name' => 'просмотр',  'description' => 'Просмотр задач, включая чужие, и их события.',      ],

            // permissions for manufacturers
            /* 45 */ ['group' => 'manufacturers', 'name' => 'create_manufacturers',  'display_name' => 'создание',  'description' => 'Создание производителей',         ],
            /* 46 */ ['group' => 'manufacturers', 'name' => 'edit_manufacturers',    'display_name' => 'правка',    'description' => 'Редактирование производителей',   ],
            /* 47 */ ['group' => 'manufacturers', 'name' => 'delete_manufacturers',  'display_name' => 'удаление',  'description' => 'Удаление производителей',         ],
            /* 48 */ ['group' => 'manufacturers', 'name' => 'view_manufacturers',    'display_name' => 'просмотр',  'description' => 'Просмотр производителей.',        ],
            
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
