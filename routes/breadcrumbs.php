<?php

use App\Category;

// Catalog
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
    $trail->parent('catalog');

    $parent = $product->category;
    while ( $parent->id > 1 ) {
        $trail->push($parent->title, route('categories.show', $parent->id));
        $parent = Category::find($parent->parent_id);
    }

    $trail->push($product->name, route('products.show', ['product' => $product->id]));
});