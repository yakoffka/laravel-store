<?php
// https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs#user-content-defining-breadcrumbs

use App\Category;


// Home
    Breadcrumbs::for('home', function ($trail) {
        $trail->push('Home', route('home'));
    });


// catalog
    // Home > Catalog       (categories.index)
    Breadcrumbs::for('catalog', function ($trail) {
        $trail->parent('home');
        $trail->push('Catalog', route('categories.index'));
    });

// categories
    // Home > Catalog > Create Categories
    Breadcrumbs::for('categories.create', function ($trail) {
        $trail->parent('catalog');
        $trail->push('Create Categories', route('categories.create'));
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
        $trail->push('Edit', route('categories.edit', $category));
    });


// products
    // Home > Catalog > Create Product
    Breadcrumbs::for('products.create', function ($trail) {
        $trail->parent('catalog');
        $trail->push('Create Product', route('products.create'));
    });
    // Home > Catalog > [Categories] > [Product]
    Breadcrumbs::for('products.show', function ($trail, $product) {
        $trail->parent('categories.show', $product->category);
        $trail->push($product->name, route('products.show', ['product' => $product->id]));
    });
    // Home > Catalog > [Categories] > [Product] > Edit Product
    Breadcrumbs::for('products.edit', function ($trail, $product) {
        $trail->parent('products.show', $product);
        $trail->push('Edit Product', route('products.edit', ['product' => $product]));
    });



// Home > Catalog > [Search]
Breadcrumbs::for('search', function ($trail) {
    $trail->parent('catalog');
    $trail->push('Результаты поиска', route('search'));
});



// Home > Cart
Breadcrumbs::for('cart', function ($trail) {
    $trail->parent('home');
    $trail->push('Cart');
});



// Home > Orders
Breadcrumbs::for('orders.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Orders', route('orders.index'));
});


// Home > Orders > Order
Breadcrumbs::for('orders.show', function ($trail, $order) {
    $trail->parent('orders.index');
    $trail->push('Order #' . $order->id);
});



// Home > Roles
Breadcrumbs::for('roles', function ($trail) {
    $trail->parent('home');
    $trail->push('Roles', route('roles.index'));
});
// Home > Roles > Role
Breadcrumbs::for('role', function ($trail, $role) {
    $trail->parent('roles');
    $trail->push($role->display_name, route('roles.show', $role));
});
// Home > Roles > Create Role
Breadcrumbs::for('roles.create', function ($trail) {
    $trail->parent('roles');
    $trail->push('Create Role');
});
// Home > Roles > Edit Role
Breadcrumbs::for('roles.edit', function ($trail, $role) {
    $trail->parent('role', $role);
    $trail->push('Edit', route('roles.edit', $role));
});



// Home > Users
Breadcrumbs::for('users.index', function ($trail) {
    $trail->parent('home');
    $trail->push('Users', route('users.index'));
});
// Home > Users > Registrations
Breadcrumbs::for('users.registrations', function ($trail) {
    $trail->parent('users.index');
    $trail->push('Registrations Users', route('registrations.index'));
});
// Home > Users > [User]
Breadcrumbs::for('users.show', function ($trail, $user) {
    if ( auth()->user()->can('view_users') ) {
        $trail->parent('users.index');
        $trail->push('Profile "' . $user->name . '"', route('users.show', $user));
    } else {
        $trail->parent('home');
        $trail->push('My Profile', route('users.show', $user));
    }
});
// Home > Users > [User] > edit
Breadcrumbs::for('users.edit', function ($trail, $user) {
    $trail->parent('users.show', $user);
    $trail->push('Edit', route('users.edit', $user));
});



// Actions

    // users
    // Home > Users > Actions
    Breadcrumbs::for('actions.users', function ($trail) {
        $trail->parent('users.index');
        $trail->push('Actions users', route('actions.users'));
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
        $trail->push('Actions comments', route('actions.comments'));
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
        $trail->push('Actions categories', route('actions.categories'));
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
        $trail->push('Actions products', route('actions.products'));
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
        $trail->push('Actions orders', route('actions.orders'));
    });
    // Home > Catalog > orders > [order] > Actions
    Breadcrumbs::for('actions.order', function ($trail, $order) {
        $trail->parent('orders.show', $order);
        $trail->push($order->name, route('actions.order', $order));
    });



// Home > Settings
Breadcrumbs::for('settings', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings');
});
