<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Mail\ProductCreated;

use App\Product;
use App\Category;
use App\Cart;
// use App\Filters\Product\ManufacturerFilter;

class ProductsController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        // $products = DB::table('products')->orderBy('id', 'desc')->simplepaginate(config('custom.products_paginate'));
        // return view('products.index', compact('products'));

        // $products = Product::all()->filter( function ($product) {
        //     $byBass = substr_count($product->name, 'Bass');
        //     return $byBass;
        // });
        // return view('products.index', compact('products'));

        // $products = Product::paginate(config('custom.products_paginate'));
        // return view('products.index', compact('products'));

        // if( Auth::user() and  Auth::user()->can(['view_products'])) {
        //     $products = Product::latest()->paginate(config('custom.products_paginate'));
        // } else {
        //     $products = Product::latest()->where('visible', '=', 1)->paginate(config('custom.products_paginate'));
        // }
        // return view('products.index', compact('products'));

        // add filters
        // $products = Product::latest()->filter($this->filters())->paginate(config('custom.products_paginate'));
        // return view('products.index', compact('products'));


        $appends = [];
        foreach($request->query as $key => $val){
            $appends[$key] = $val;
        }

        $products = Product::filter($request, $this->getFilters())->paginate(config('custom.products_paginate'));
        // $products = Product::with('category')->filter($request, $this->getFilters())->paginate(config('custom.products_paginate'));
        // dd($products);
        return view('products.index', compact('products', 'appends'));
    }

    protected function getFilters() 
    {
        return [
            // 'manufacturer' => ManufacturerFilter::class,
        ];
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if ( !Auth::user()->can('create_products'), 403 );
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        abort_if (!Auth::user()->can('edit_products'), 403);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  request()
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product)
    {
        // dd(request()->all());
        abort_if ( Auth::user()->cannot('create_products'), 403 );

        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:255',
            'manufacturer' => 'nullable|string',
            'category_id' => 'required|integer',
            'visible' => 'required|boolean',
            'materials' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'image',
            'year_manufacture' => 'nullable|integer',
            'price' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        if (!$product = Product::create([
            'name' => request('name'),
            'manufacturer' => request('manufacturer') ?? '',
            'category_id' => request('category_id'),
            'visible' => request('visible'),
            'materials' => request('materials') ?? '',
            'description' => request('description') ?? '',
            'year_manufacture' => request('year_manufacture') ?? 0,
            'price' => request('price') ?? 0,
            'added_by_user_id' => Auth::user()->id,            
        ])) {
            return back()->withErrors(['something wrong!'])->withInput();
        }

        if (request()->file('image')) {

            $product->image = $this->storeImage(request()->file('image'), $product->id);

            if (!$product->image or !$product->update()) {
                return back()->withErrors(['something wrong. err' . __line__])->withInput();
            }
        }

        // sending notification
        \Mail::to(env('MAIL_ADMIN'))->send(
            new ProductCreated($product)
        );

        return redirect()->route('products.show', ['product' => $product->id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  request()
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Product $product)
    {
        abort_if ( Auth::user()->cannot('edit_products'), 403 );

        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:255',
            'manufacturer' => 'nullable|string',
            'category_id' => 'required|integer',
            'visible' => 'required|boolean',
            'materials' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'image',
            'year_manufacture' => 'nullable|integer',
            'price' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $product->update([
            'name' => request('name'),
            'manufacturer' => request('manufacturer'),
            'category_id' => request('category_id'),
            'visible' => request('visible'),
            'materials' => request('materials'),
            'description' => request('description'),
            'year_manufacture' => request('year_manufacture'),
            'price' => request('price'),
            'edited_by_user_id' => Auth::user()->id,
        ]);


        if (request()->file('image')) {

            $product->image = $this->storeImage(request()->file('image'), $product->id);

            if (!$product->image or !$product->update()) {
                return back()->withErrors(['something wrong. err' . __line__])->withInput();
            }
        }

        return redirect()->route('products.show', ['product' => $product->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        abort_if ( Auth::user()->cannot('delete_products'), 403 );

        // destroy product images
        if ($product->image) {
            $directory = 'public/images/products/' . $product->id;
            Storage::deleteDirectory($directory);
        }

        // destroy product comments
        $product->comments()->delete();

        // destroy product
        $product->delete();

        return redirect()->route('products.index');
    }

    /**
     * Store image product.
     *
     * @param  \Illuminate\Http\Request  request()
     * @return string $filename or false
     */
    private function storeImage($image, $product_id) {

        $directory = 'public/images/products/' . $product_id;
        $filename = $image->getClientOriginalName();

        if (Storage::makeDirectory($directory)) {
            if (Storage::putFileAs($directory, $image, $filename)) {
                return $filename;
            }
        }

        return false;
    }

}
