<?php

    return [
        'store_theme' => env('STORE_THEME', 'MUSIC'),
        'products_paginate' => env('PRODUCTS_PAGINATE', 6),
        'orders_paginate' => env('ORDERS_PAGINATE', 20),
        'tasks_paginate' => env('TASKS_PAGINATE', 30),
        'actions_paginate' => env('ACTIONS_PAGINATE', 100),
        'num_products_seed' => env('NUM_PRODUCTS_SEED', 20),
        'num_last_actions' => env('NUM_LAST_ACTIONS', 50),

        'main_title_append' => env('MAIN_TITLE_APPEND', config('app.name', 'Laravel')),
        'main_description' => env('MAIN_DESCRIPTION', config('app.name', 'Laravel')),
        'product_title_append' => env('PRODUCT_TITLE_APPEND', config('app.name', 'Laravel')),
        'category_title_append' => env('CATEGORY_TITLE_APPEND', config('app.name', 'Laravel')),
        'product_description_append' => env('PRODUCT_DESCRIPTION_APPEND', config('app.name', 'Laravel')),
        'category_description_append' => env('CATEGORY_DESCRIPTION_APPEND', config('app.name', 'Laravel')),
    ];
