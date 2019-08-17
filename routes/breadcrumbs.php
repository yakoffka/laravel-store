<?php

// // Home
// Breadcrumbs::for('home', function ($trail) {
//     $trail->push('Home', route('home'));
// });

// // Home > About
// Breadcrumbs::for('about', function ($trail) {
//     $trail->parent('home');
//     $trail->push('About', route('about'));
// });

// // Home > Blog
// Breadcrumbs::for('blog', function ($trail) {
//     $trail->parent('home');
//     $trail->push('Blog', route('blog'));
// });

// // Home > Blog > [Category]
// Breadcrumbs::for('categories', function ($trail, $categories) {
//     $trail->parent('blog');
//     $trail->push($categories->title, route('categories', $categories->id));
// });

// // Home > Blog > [Category] > [Post]
// Breadcrumbs::for('post', function ($trail, $post) {
//     $trail->parent('categories', $post->categories);
//     $trail->push($post->title, route('post', $post->id));
// });




// 3
// // Catalog
Breadcrumbs::for('catalog', function ($trail) {
    $trail->push('Catalog', route('products.index'));
});

// Catalog > [Categories]
Breadcrumbs::for('categories', function ($trail, $category) {
    $trail->parent('catalog');
    $trail->push($category->title, route('categories.show', $category->id));
});

// Catalog > [Categories] > [Product]
Breadcrumbs::for('product', function ($trail, $product) {
    $trail->parent('categories', $product->category);
    $trail->push(
        $product->name, 
        route('products.show', ['product' => $product->id])
    );
});