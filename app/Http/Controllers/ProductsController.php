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

        if ($request->file('image')) {
            
            $directory = 'public/images/products/' . $product->id;
            Storage::makeDirectory($directory);

            $file = $request->file('image');
            $filename = $file->getClientOriginalName();

            Storage::putFileAs($directory, $file, $filename);

            $product->image = $filename;

            if (!$product->update()) {
                return back()->withErrors(['something wrong. err' . __line__])->withInput();
            }

        }
        return redirect()->route('products');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|max:255',
    //         'manufacturer' => 'nullable|string',
    //         'materials' => 'nullable|string',
    //         'description' => 'nullable|string',
    //         'image' => 'image',
    //         'year_manufacture' => 'nullable|integer',
    //         'price' => 'nullable|integer',
    //         'added_by_user_id' => 'required|integer',
    //     ]);

    //     if ($validator->fails()) {
    //         return back()->withErrors($validator)->withInput();
    //     }
        
    //     $product = Product::find($id);

    //     $product->name = $request->get('name');
    //     $product->manufacturer = $request->get('manufacturer') ?? '';
    //     $product->materials = $request->get('materials') ?? '';
    //     $product->description = $request->get('description') ?? '';
    //     $product->year_manufacture = $request->get('year_manufacture') ?? 0;
    //     $product->price = $request->get('price') ?? 0;
    //     $product->added_by_user_id = $request->get('added_by_user_id');        

    //     if (!$product->update()) {
    //         return back()->withErrors(['something wrong!'])->withInput();
    //     }

    //     if ($request->file('image')) {

    //         $upload_folder = 'public/images/' . $product->id;
    //         $upload_folder_ = __DIR__ . '/../../../storage/app/' . $upload_folder;
            
    //         // if (!mkdir($upload_folder_)){
    //         //     // return back()->withErrors(['failed to create upload_folder'])->withInput();
    //         // }
    //         if (!is_dir($upload_folder_)) {
    //             mkdir($upload_folder_);
    //         }

    //         $file = $request->file('image');
    //         $filename = $file->getClientOriginalName();

    //         Storage::putFileAs($upload_folder, $file, $filename);

    //         $product->image = $filename;

    //         if ($product->update()) {
    //             return redirect()->route('products');
    //         }    
    //         return back()->withErrors(['something wrong!!'])->withInput();    
    //     }
    //     return redirect()->route('products');
    // }

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

        // destroy product
        $product->delete();

        return redirect()->route('products');
    }

}
