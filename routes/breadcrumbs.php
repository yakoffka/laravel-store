<?php
// https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs#user-content-defining-breadcrumbs

use App\Category;


// Home
    Breadcrumbs::for('home', function ($trail) {
        // $trail->push('Home', route('home'));
        $trail->push('Главная', route('home'));
    });


// Home > Login
    Breadcrumbs::for('login', function ($trail) {
        $trail->parent('home');
        // $trail->push('Login');
        $trail->push('Вход');
    });


// catalog
    // Home > Catalog       (categories.index)
    Breadcrumbs::for('catalog', function ($trail) {
        $trail->parent('home');
        // $trail->push('Catalog', route('categories.index'));
        $trail->push('Каталог', route('categories.index'));
    });

// categories
    // Home > Catalog > Create Categories
    Breadcrumbs::for('categories.create', function ($trail) {
        $trail->parent('catalog');
        // $trail->push('Create Categories', route('categories.create'));
        $trail->push('Создание категории', route('categories.create'));
    });
    // Home > Catalog > [Categories]
    Breadcrumbs::for('categories.show', function ($trail, $category) {
        // $trail->parent('catalog');
        // // get all parents:
        // $arr_parents = [];
        // $parent = $product;
        // while ( $parent->id > 1 ) {
        //     $arr_parents[] = $parent;
        //     $parent = Product::find($parent->parent_id);
        // }
        // $arr_parents = array_reverse($arr_parents);
        // // get breadcrumbs for all parents:
        // foreach ( $arr_parents as $parent ) {
        //     $trail->push($parent->title, route('categories.show', $parent->id));
        // }
        $trail->parent('catalog');
        // get all parents:
        $arr_parents = [];
        $parent = $category;
        while ( $parent->id > 1 ) {
            // dd($parent->id);
            $arr_parents[] = $parent;
            $parent = Category::find($parent->parent_id);
        }
        $arr_parents = array_reverse($arr_parents);
        // get breadcrumbs for all parents:
        foreach ( $arr_parents as $parent ) {
            $trail->push($parent->title, route('categories.show', $parent->id));
        }
    });
    // Home > Catalog > [Categories] > Edit
    Breadcrumbs::for('categories.edit', function ($trail, $category) {
        $trail->parent('categories.show', $category);
        // $trail->push('Edit', route('categories.edit', $category));
        $trail->push('Редактирование', route('categories.edit', $category));
    });


// products
    // Home > Catalog > Create Product
    Breadcrumbs::for('products.create', function ($trail) {
        $trail->parent('catalog');
        // $trail->push('Create Product', route('products.create'));
        $trail->push('Создание товара', route('products.create'));
    });
    // Home > Catalog > [Categories] > [Product]
    Breadcrumbs::for('products.show', function ($trail, $product) {
        $trail->parent('categories.show', $product->category);
        $trail->push($product->name, route('products.show', ['product' => $product->id]));
    });
    // Home > Catalog > [Categories] > [Product] > Edit Product
    Breadcrumbs::for('products.edit', function ($trail, $product) {
        $trail->parent('products.show', $product);
        // $trail->push('Edit Product', route('products.edit', ['product' => $product]));
        $trail->push(__('Edit'), route('products.edit', ['product' => $product]));
    });
    // Home > Catalog > [Categories] > [Product] > Copy Product
    Breadcrumbs::for('products.copy', function ($trail, $product) {
        $trail->parent('products.show', $product);
        // $trail->push('Copy Product', route('products.copy', ['product' => $product]));
        $trail->push('Копирование товара', route('products.copy', ['product' => $product]));
    });

    
// Home > Catalog > [Search]
Breadcrumbs::for('search', function ($trail) {
    $trail->parent('catalog');
    $trail->push('Результаты поиска', route('search'));
});



// Home > Cart
Breadcrumbs::for('cart', function ($trail) {
    $trail->parent('home');
    // $trail->push('Cart');
    $trail->push('Корзина');
});



// Home > Orders
Breadcrumbs::for('orders.index', function ($trail) {
    $trail->parent('home');
    // $trail->push('Orders', route('orders.index'));
    $trail->push('Заказы', route('orders.index'));
});


// Home > Orders > Order
Breadcrumbs::for('orders.show', function ($trail, $order) {
    $trail->parent('orders.index');
    // $trail->push('Order #' . $order->id);
    $trail->push('Заказ #' . $order->id, route('orders.show', $order));
});



// Roles
    // Home > Roles
    Breadcrumbs::for('roles', function ($trail) {
        $trail->parent('home');
        // $trail->push('Roles', route('roles.index'));
        $trail->push('Роли', route('roles.index'));
    });
    // Home > Roles > Role
    Breadcrumbs::for('role', function ($trail, $role) {
        $trail->parent('roles');
        $trail->push($role->display_name, route('roles.show', $role));
    });
    // Home > Roles > Create Role
    Breadcrumbs::for('roles.create', function ($trail) {
        $trail->parent('roles');
        // $trail->push('Create Role');
        $trail->push('Создание роли');
    });
    // Home > Roles > Edit Role
    Breadcrumbs::for('roles.edit', function ($trail, $role) {
        $trail->parent('role', $role);
        // $trail->push('Edit', route('roles.edit', $role));
        $trail->push('Редактирование роли', route('roles.edit', $role));
    });



// Users
    // Home > Users
    Breadcrumbs::for('users.index', function ($trail) {
        $trail->parent('home');
        // $trail->push('Users', route('users.index'));
        $trail->push('Пользователи', route('users.index'));
    });
    // Home > Users > Registrations
    Breadcrumbs::for('users.registrations', function ($trail) {
        $trail->parent('users.index');
        // $trail->push('Registrations Users', route('registrations.index'));
        $trail->push('Registrations Users', route('registrations.index'));
    });
    // Home > Users > [User]
    Breadcrumbs::for('users.show', function ($trail, $user) {
        // if ( auth()->user()->can('view_users') ) {
        //     $trail->parent('users.index');
        //     $trail->push('Profile "' . $user->name . '"', route('users.show', $user));
        // } else {
        //     $trail->parent('home');
        //     $trail->push('My Profile', route('users.show', $user));
        // }
        if ( auth()->user()->id == $user->id ) {
            $trail->parent('home');
            // $trail->push('My Profile', route('users.show', $user));
            $trail->push('Мой профиль', route('users.show', $user));
        } elseif ( auth()->user()->can('view_users') ) {
            $trail->parent('users.index');
            // $trail->push('Profile "' . $user->name . '"', route('users.show', $user));
            $trail->push('Профиль "' . $user->name . '"', route('users.show', $user));
        }
    });
    // Home > Users > [User] > edit
    Breadcrumbs::for('users.edit', function ($trail, $user) {
        $trail->parent('users.show', $user);
        // $trail->push('Edit', route('users.edit', $user));
        $trail->push('Редактирование', route('users.edit', $user));
    });



// Actions
    // users
    // Home > Users > Actions
    Breadcrumbs::for('actions.users', function ($trail) {
        $trail->parent('users.index');
        // $trail->push('Actions users', route('actions.users'));
        $trail->push('Активность пользователя', route('actions.users'));
    });
    // Home > Users > [User] > Actions
    Breadcrumbs::for('actions.user', function ($trail, $user) {
        $trail->parent('users.show', $user);
        $trail->push($user->name, route('actions.user', $user));
    });

    // comments
    // Home > Catalog > Comments > Actions
    Breadcrumbs::for('actions.comments', function ($trail) {
        $trail->parent('catalog');
        // $trail->push('Actions comments', route('actions.comments'));
        $trail->push('История комментирования', route('actions.comments'));
    });
    // Home > Catalog > Comments > [Comment] > Actions
    Breadcrumbs::for('actions.comment', function ($trail, $comment) {
        $trail->parent('actions.comments');
        $trail->push($comment->name, route('actions.comment', $comment));
    });

    // categories
    // Home > Catalog > Categories > Actions
    Breadcrumbs::for('actions.categories', function ($trail) {
        $trail->parent('catalog');
        // $trail->push('Actions categories', route('actions.categories'));
        $trail->push('История категорий', route('actions.categories'));
    });
    // Home > Catalog > Categories > [Category] > Actions
    Breadcrumbs::for('actions.category', function ($trail, $category) {
        $trail->parent('actions.categories');
        $trail->push($category->name, route('actions.category', $category));
    });

    // products
    // Home > Catalog > Actions
    Breadcrumbs::for('actions.products', function ($trail) {
        $trail->parent('catalog');
        // $trail->push('Actions products', route('actions.products'));
        $trail->push('История товаров', route('actions.products'));
    });
    // Home > Catalog > Products > [Product] > Actions
    Breadcrumbs::for('actions.product', function ($trail, $product) {
        $trail->parent('products.show', $product);
        $trail->push($product->name, route('actions.product', $product));
    });

    // orders
    // Home > Catalog > Actions
    Breadcrumbs::for('actions.orders', function ($trail) {
        // if ( auth()->user()->can('view_orders') ) {
        //     $trail->parent('orders.index');
        //     $trail->push('Actions orders', route('actions.orders'));
        // } else {

        // }
        $trail->parent('orders.index');
        // $trail->push('Actions orders', route('actions.orders'));
        $trail->push('История заказов', route('actions.orders'));
    });
    // Home > Catalog > Orders > [Order] > Actions
    Breadcrumbs::for('actions.order', function ($trail, $order) {
        $trail->parent('orders.show', $order);
        $trail->push('History', route('actions.order', $order));
    });



// Settings
    // Home > Settings
    Breadcrumbs::for('settings', function ($trail) {
        $trail->parent('home');
        // $trail->push('Settings');
        $trail->push('Настройки');
    });

    

// Tasks and Directive
    // Home > Users > [User] > Tasks
    Breadcrumbs::for('tasks.index', function ($trail, $user) {
        $trail->parent('users.show', $user);
        // $trail->push('Tasks', route('tasks.index', $user));
        $trail->push('Задачи', route('tasks.index', $user));
    });
    // Home > Users > [User] > Tasks > [Task]
    Breadcrumbs::for('tasks.show', function ($trail, $task) {
        $trail->parent('tasks.index', $task->getMaster);
        // $trail->push('Task #' . $task->id, route('tasks.show', $task));
        $trail->push('Задача #' . $task->id, route('tasks.show', $task));
    });
    // Home > Users > [User] > Tasks > Create
    Breadcrumbs::for('tasks.create', function ($trail, $user) {
        $trail->parent('tasks.index', $user);
        // $trail->push('Create', route('tasks.create'));
        $trail->push('Создание', route('tasks.create'));
    });
    // Home > Users > [User] > Tasks > [Task] > Edit
    Breadcrumbs::for('tasks.edit', function ($trail, $task) {
        $trail->parent('tasks.show', $task);
        // $trail->push('Edit', route('tasks.edit', $task));
        $trail->push('Редактирование', route('tasks.edit', $task));
    });

    // Home > Users > [User] > Directives
    Breadcrumbs::for('directives.index', function ($trail, $user) {
        $trail->parent('users.show', $user);
        // $trail->push('Directives', route('directives.index', $user));
        $trail->push('Поручения', route('directives.index', $user));
    });
    // Home > Users > [User] > Directives > [Directive]
    Breadcrumbs::for('directives.show', function ($trail, $task) {
        $trail->parent('directives.index', $task->getSlave);
        // $trail->push('Directive #' . $task->id, route('directives.show', $task));
        $trail->push('Поручение #' . $task->id, route('directives.show', $task));
    });



// admin side
    // Home > Categories
    Breadcrumbs::for('categories.adminindex', function ($trail) {
        $trail->parent('home');
        // $trail->push('Categories', route('categories.adminindex'));
        $trail->push('Категории', route('categories.adminindex'));
    });
    // Home > Categories -> [Category]
    Breadcrumbs::for('categories.adminshow', function ($trail, $category) {
        $trail->parent('categories.adminindex');
        // get all parents:
        $arr_parents = [];
        $parent = $category;
        while ( $parent->id > 1 ) {
            $arr_parents[] = $parent;
            $parent = Category::find($parent->parent_id);
        }
        $arr_parents = array_reverse($arr_parents);
        // get breadcrumbs for all parents:
        foreach ( $arr_parents as $parent ) {
            $trail->push($parent->title, route('categories.adminshow', $parent->id));
        }
    });


    // Home > All Products
    Breadcrumbs::for('products.adminindex', function ($trail) {
        $trail->parent('home');
        // $trail->push('All Products', route('products.adminindex'));
        $trail->push('Товары', route('products.adminindex'));
    });
    // Home > All Products > [Product]
    Breadcrumbs::for('products.adminshow', function ($trail, $product) {
        $trail->parent('products.adminindex');
        // $trail->push('Product', route('products.adminshow', $product));
        $trail->push('Товар', route('products.adminshow', $product));
    });


