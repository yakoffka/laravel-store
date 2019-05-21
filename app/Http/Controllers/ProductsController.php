<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use App\Product;

class ProductsController extends Controller
{
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
        $product = Product::find($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'manufacturer' => 'nullable|string',
            'materials' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'image',
            'year_manufacture' => 'nullable|integer',
            'price' => 'nullable|integer',
            'added_by_user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $product = new Product;

        $product->name = $request->get('name');
        $product->manufacturer = $request->get('manufacturer') ?? '';
        $product->materials = $request->get('materials') ?? '';
        $product->description = $request->get('description') ?? '';
        $product->year_manufacture = $request->get('year_manufacture') ?? 0;
        $product->price = $request->get('price') ?? 0;
        $product->added_by_user_id = $request->get('added_by_user_id');        

        if (!$product->save()) {
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Product $product)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:255',
            'manufacturer' => 'nullable|string',
            'materials' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'image',
            'year_manufacture' => 'nullable|integer',
            'price' => 'nullable|integer',
            'edited_by_user_id' => 'required|integer',
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
            'edited_by_user_id' => request('edited_by_user_id'),
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
    public function destroy($id)
    {
        $product = Product::find($id);

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
     * @param  \Illuminate\Http\Request  $request
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

        return FALSE;
    }

}
