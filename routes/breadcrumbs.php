<?php
// https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs#user-content-defining-breadcrumbs

use App\Category;


// Home
    Breadcrumbs::for('home', function ($trail) {
        $trail->push('Главная', route('home'));
    });



// Home > Login
    Breadcrumbs::for('login', function ($trail) {
        $trail->parent('home');
        // $trail->push('Login');
        $trail->push('Вход');
    });



// catalog
    // Home > Catalog
    Breadcrumbs::for('catalog', function ($trail) {
        $trail->parent('home');
        $trail->push('Каталог', route('categories.index'));
    });



// categories
    // Home > Catalog > [Category]
    Breadcrumbs::for('categories.show', function ($trail, $category) {
        $trail->parent('catalog');
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
            $trail->push($parent->title, route('categories.show', $parent->id));
        }
    });



// products
    // Home > Catalog > [Category] > [Product]
    Breadcrumbs::for('products.show', function ($trail, $product) {
        $trail->parent('categories.show', $product->category);
        $trail->push($product->name, route('products.show', ['product' => $product->id]));
    });



// search
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







////////////////////////////////////////////////////////////////////////////////
//          Dashboard
////////////////////////////////////////////////////////////////////////////////


// Home > Dashboard
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->parent('home');
    $trail->push(__('Dashboard'), route('dashboard'));
});



// categories
    // Home > Dashboard > Categories
    Breadcrumbs::for('categories.adminindex', function ($trail) {
        $trail->parent('dashboard');
        $trail->push('Категории', route('categories.adminindex'));
    });
    
    // Home > Dashboard > Categories > [Category]
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
    // Home > Dashboard > Categories > Create Categories
    Breadcrumbs::for('categories.create', function ($trail) {
        $trail->parent('categories.adminindex');
        $trail->push('Создание категории', route('categories.create'));
    });
    // Home > Dashboard > Categories > [Category] > Edit
    Breadcrumbs::for('categories.edit', function ($trail, $category) {
        $trail->parent('categories.adminshow', $category);
        $trail->push('Редактирование', route('categories.edit', $category));
    });



// products
    // Home > Dashboard > Products
    Breadcrumbs::for('products.adminindex', function ($trail) {
        $trail->parent('dashboard');
        $trail->push('Товары', route('products.adminindex'));
    });

    // Home > Dashboard > Categories > [Category] > [Product]
    Breadcrumbs::for('products.adminshow', function ($trail, $product) {
        $trail->parent('categories.adminshow', $product->category);
        $trail->push('Товар', route('products.adminshow', $product));
    });
    // Home > Dashboard > Products > Create Product
    Breadcrumbs::for('products.create', function ($trail) {
        $trail->parent('catalog');
        $trail->push('Создание товара', route('products.create'));
    });
    // Home > Dashboard > Products > [Product] > Edit Product
    Breadcrumbs::for('products.edit', function ($trail, $product) {
        $trail->parent('products.adminshow', $product);
        $trail->push(__('Edit'), route('products.edit', ['product' => $product]));
    });
    // Home > Dashboard > Products > [Product] > Copy Product
    Breadcrumbs::for('products.copy', function ($trail, $product) {
        $trail->parent('products.adminshow', $product);
        $trail->push('Копирование товара', route('products.copy', ['product' => $product]));
    });



// Tasks and Directive
    // Home > Users > [User] > Tasks  /  Home > Dashboard > Tasks
    Breadcrumbs::for('tasks.index', function ($trail, $user) {
        if ( auth()->user()->id === $user->id ) {
            $trail->parent('dashboard');
        } else {
            $trail->parent('users.show', $user);
        }
        $trail->push('Мои задачи', route('tasks.index', $user));
    });
    // Home > Users > [User] > Tasks > [Task]  /  Home > Dashboard > Tasks > [Task]
    Breadcrumbs::for('tasks.show', function ($trail, $task) {
        $trail->parent('tasks.index', $task->getMaster);
        $trail->push('Задача #' . $task->id, route('tasks.show', $task));
    });
    // Home > Users > [User] > Tasks > Create
    Breadcrumbs::for('tasks.create', function ($trail, $user) {
        $trail->parent('tasks.index', $user);
        $trail->push('Создание поручения', route('tasks.create'));
    });
    // Home > Users > [User] > Tasks > [Task] > Edit
    Breadcrumbs::for('tasks.edit', function ($trail, $task) {
        $trail->parent('tasks.show', $task);
        $trail->push('Редактирование', route('tasks.edit', $task));
    });

    // Home > Users > [User] > Directives
    Breadcrumbs::for('directives.index', function ($trail, $user) {
        if ( auth()->user()->id === $user->id ) {
            $trail->parent('dashboard');
        } else {
            $trail->parent('users.show', $user);
        }
        $trail->push('Мои поручения', route('directives.index', $user));
    });
    // Home > Users > [User] > Directives > [Directive]
    Breadcrumbs::for('directives.show', function ($trail, $task) {
        $trail->parent('directives.index', $task->getSlave);
        $trail->push('Поручение #' . $task->id, route('directives.show', $task));
    });



// Orders
    // Home > Dashboard > Orders
    Breadcrumbs::for('orders.index', function ($trail) {
        $trail->parent('dashboard');
        if ( auth()->user()->can('view_orders') ) {
            $trail->push('Заказы', route('orders.index'));
        } else {
            $trail->push('Мои заказы', route('orders.index'));
        }
    });
    // Home > Dashboard > Orders > [Order]
    Breadcrumbs::for('orders.show', function ($trail, $order) {
        $trail->parent('orders.index');
        // $trail->push('Order #' . $order->id);
        $trail->push('Заказ #' . $order->id, route('orders.show', $order));
    });



// Users
    // Home > Dashboard > Users
    Breadcrumbs::for('users.index', function ($trail) {
        $trail->parent('dashboard');
        $trail->push('Пользователи', route('users.index'));
    });
    // WHAT???
    // // Home > Dashboard > Users > Registrations
    // Breadcrumbs::for('users.registrations', function ($trail) {
    //     $trail->parent('users.index');
    //     // $trail->push('Registrations Users', route('registrations.index'));
    //     $trail->push('Registrations Users', route('registrations.index'));
    // });
    // Home > Dashboard > Users > [User]
    Breadcrumbs::for('users.show', function ($trail, $user) {
        if ( auth()->user()->id == $user->id ) {
            $trail->parent('dashboard');
            $trail->push('Мой профиль', route('users.show', $user));
        } elseif ( auth()->user()->can('view_users') ) {
            $trail->parent('users.index');
            $trail->push('Профиль "' . $user->name . '"', route('users.show', $user));
        }
    });
    // Home > Dashboard > Users > [User] > edit
    Breadcrumbs::for('users.edit', function ($trail, $user) {
        $trail->parent('users.show', $user);
        $trail->push('Редактирование', route('users.edit', $user));
    });


// Settings
    // Home > Dashboard > Settings
    Breadcrumbs::for('settings.index', function ($trail) {
        $trail->parent('dashboard');
        $trail->push('Настройки', route('settings.index'));
    });



// Roles
    // Home > Dashboard > Roles
    Breadcrumbs::for('roles', function ($trail) {
        $trail->parent('dashboard');
        // $trail->push('Roles', route('roles.index'));
        $trail->push('Роли', route('roles.index'));
    });
    // Home > Dashboard > Roles > [Role]
    Breadcrumbs::for('role', function ($trail, $role) {
        $trail->parent('roles');
        $trail->push($role->display_name, route('roles.show', $role));
    });
    // Home > Dashboard > Roles > [Role] > Create Role
    Breadcrumbs::for('roles.create', function ($trail) {
        $trail->parent('roles');
        // $trail->push('Create Role');
        $trail->push('Создание роли');
    });
    // Home > Dashboard > Roles > [Role] > Edit Role
    Breadcrumbs::for('roles.edit', function ($trail, $role) {
        $trail->parent('role', $role);
        // $trail->push('Edit', route('roles.edit', $role));
        $trail->push('Редактирование роли', route('roles.edit', $role));
    });




// actions ПОПРАВИТЬ!
    // // users
    // // Home > Users > Actions
    // Breadcrumbs::for('actions.users', function ($trail) {
    //     $trail->parent('users.adminindex');
    //     // $trail->push('Actions users', route('actions.users'));
    //     $trail->push('Активность пользователя', route('actions.users'));
    // });
    // Home > Dashboard > Users > [User] > Actions
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
