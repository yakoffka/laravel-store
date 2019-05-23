<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Auth;

use App\Product;

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
    public function index() {
        $products = DB::table('products')->orderBy('id', 'desc')->simplePaginate(6);
        return view('products.index', compact('products'));
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
        return view('products.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        abort_if (!Auth::user()->can('edit_products'), 403);
        return view('products.edit', compact('product'));
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

        return redirect()->route('productsShow', ['product' => $product->id]);
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
            'materials' => request('materials'),
            'description' => request('description'),
            'year_manufacture' => request('year_manufacture'),
            'edited_by_user_id' => Auth::user()->id,
        ]);


        if (request()->file('image')) {

            $product->image = $this->storeImage(request()->file('image'), $product->id);

            if (!$product->image or !$product->update()) {
                return back()->withErrors(['something wrong. err' . __line__])->withInput();
            }
        }

        return redirect()->route('productsShow', ['product' => $product->id]);
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

        return redirect()->route('products');
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
