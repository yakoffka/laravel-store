<?php
// https://packagist.org/packages/davejamesmiller/laravel-breadcrumbs#user-content-defining-breadcrumbs

use App\Category;


// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});


// Home > Catalog
Breadcrumbs::for('catalog', function ($trail) {
    $trail->parent('home');
    $trail->push('Catalog', route('products.index'));
});
// Home > Catalog > Create Categories
Breadcrumbs::for('categories.create', function ($trail) {
    $trail->parent('catalog');
    $trail->push('Create Categories', route('categories.create'));
});
// Home > Catalog > [Categories]
Breadcrumbs::for('categories', function ($trail, $category) {
    $trail->parent('catalog');

    // $trail->push($category->title, route('categories.show', $category->id));
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
// Home > Catalog > [Categories] > Edit Category
Breadcrumbs::for('categories.edit', function ($trail, $category) {
    $trail->parent('categories', $category);
    $trail->push('Edit Category', route('categories.edit', $category));
});
// Home > Catalog > Create Product
Breadcrumbs::for('products.create', function ($trail) {
    $trail->parent('catalog');
    $trail->push('Create Product', route('products.create'));
});
// Home > Catalog > [Categories] > [Product]
Breadcrumbs::for('products.show', function ($trail, $product) {
    $trail->parent('categories', $product->category);
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
Breadcrumbs::for('orders', function ($trail) {
    $trail->parent('home');
    $trail->push('Orders', route('orders.index'));
});


// Home > Orders > Order
Breadcrumbs::for('orders_show', function ($trail, $order) {
    $trail->parent('orders');
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
Breadcrumbs::for('roles_create', function ($trail) {
    $trail->parent('roles');
    $trail->push('Create Role');
});
// Home > Roles > Edit Role
Breadcrumbs::for('roles_edit', function ($trail, $role) {
    $trail->parent('role', $role);
    $trail->push('Edit', route('roles.edit', $role));
});



// Home > Users
Breadcrumbs::for('users.index', function ($trail) {
    $trail->parent('home');
    $trail->push('List Of Users', route('users.index'));
});
// Home > Users > [User]
Breadcrumbs::for('users.show', function ($trail, $user) {
    if (auth()->user()->id === $user->id) {
        $trail->parent('home');
        $trail->push('My Profile', route('users.show', $user));
    } else {
        $trail->parent('users.index');
        $trail->push('Profile "' . $user->name . '"', route('users.show', $user));
    }
});
// Home > Users > [User] > edit
Breadcrumbs::for('users.edit', function ($trail, $user) {
    $trail->parent('users.show', $user);
    $trail->push('Edit', route('users.edit', $user));
});
// // Home > Users > [User] > actions
// Breadcrumbs::for('users.actions', function ($trail, $user) {
//     $trail->parent('users.show', $user);
//     $trail->push('Actions', route('users.actions', $user));
// });



// Home > Settings
Breadcrumbs::for('settings', function ($trail) {
    $trail->parent('home');
    $trail->push('Settings');
});



// order