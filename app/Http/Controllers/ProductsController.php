<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Mail\Product\{Created, Updated};
use Illuminate\Support\Carbon;
use Str;
use App\Setting;
use App\Product;
use App\Category;
use App\Manufacturer;
use App\Image;
use App\Traits\Yakoffka\ImageYoTrait; // Traits???
use App\Jobs\RewatermarkJob;

class ProductsController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show', 'filter', 'search']);
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

        // $products = Product::filter($request, $this->getFilters())->paginate(config('custom.products_paginate'));

        // visible/invisible products where 'visible' == 0
        // if( Auth::user() and  Auth::user()->can(['view_products'])) {
        //     $products = Product::filter($request, $this->getFilters())->latest()->paginate(config('custom.products_paginate'));
        // } else {
        //     $products = Product::filter($request, $this->getFilters())->latest()->where('visible', '=', 1)->paginate(config('custom.products_paginate'));
        // }

        $view_products_whitout_price = Setting::all()->firstWhere('name', 'view_products_whitout_price');

        if ( Auth::user() and  Auth::user()->can(['view_products'])) {
            $products = Product::filter($request, $this->getFilters())
                ->latest()
                ->paginate(config('custom.products_paginate'));

        } elseif ( $view_products_whitout_price->value ) {
            $products = Product::filter($request, $this->getFilters())
                ->latest()->where('visible', '=', 1)
                ->paginate(config('custom.products_paginate'));

        } else {
            $products = Product::filter($request, $this->getFilters())
                ->latest()->where('visible', '=', 1)
                ->where('price', '!=', 0)
                ->paginate(config('custom.products_paginate'));
        }

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
        $manufacturers = Manufacturer::all();
        return view('products.create', compact('categories', 'manufacturers'));
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
        $manufacturers = Manufacturer::all();
        return view('products.edit', compact('product', 'categories', 'manufacturers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  request()
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product)
    {
        // dd(config('mail.name_info'));

        // dd(
        //     config('mail.driver') . '; ' .
        //     config('mail.host') . '; ' .
        //     config('mail.port') . '; ' .
        //     config('mail.from.name') . '; ' .
        //     config('mail.from.address') . '; ' .
        //     config('mail.encryption') . '; ' .
        //     config('mail.username') . '; ' .
        //     config('mail.password') . '; ' .
        //     config('mail.sendmail')
        // );
        // dd(request()->all());
        // dd(request()->file('image')->getClientOriginalName());
        // $image = request()->file('image');
        // dd($image);

        abort_if ( Auth::user()->cannot('create_products'), 403 );

        $validator = Validator::make(request()->all(), [
            'name' => 'required|max:255',
            'manufacturer_id' => 'required|integer',
            'category_id' => 'required|integer',
            'visible' => 'required|boolean',
            'materials' => 'nullable|string',
            'description' => 'nullable|string',
            'images.*' => 'bail|image|mimetypes:image/png,image/jpeg,image/bmp',
            'year_manufacture' => 'nullable|integer',
            'price' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        if (!$product = Product::create([
            'name' => request('name'),
            'slug' => Str::slug(request('name'), '-'),
            'manufacturer_id' => request('manufacturer_id'),
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

        if ( request()->file('images') and count(request()->file('images')) ) {
            foreach(request()->file('images') as $key => $image) {
                
                // // validation images
                // // $validator = Validator::make(['image' => $image], [$key => 'required|image|mimes:jpeg,bmp,png']);
                // $validator = Validator::make(
                //     ['image' => $image],
                //     [$key => 'required|image|mimetypes:image/png']
                // );
                
                // image re-creation
                $image_name = ImageYoTrait::saveImgSet($image, $product->id);
                $originalName = $image->getClientOriginalName();
                $path  = '/images/products/' . $product->id;

                // create record
                $image = Image::create([
                    'product_id' => $product->id,
                    // 'slug' => $image_name,
                    'slug' => Str::slug($image_name, '-'),
                    'path' => $path,
                    'name' => $image_name,
                    'ext' => config('imageyo.res_ext'),
                    'alt' => str_replace( strrchr($originalName, '.'), '', $originalName),
                    'sort_order' => rand(1, 9),
                    'orig_name' => $originalName,
                ]);
            }
        }

        // send email-notification
        $email_new_product = Setting::all()->firstWhere('name', 'email_new_product');
        if ( $email_new_product->value ) {

            $user = Auth::user();
            $bcc = config('mail.mail_bcc');

            $additional_email_bcc = Setting::all()->firstWhere('name', 'additional_email_bcc');
            if ( $additional_email_bcc->value ) {
                $bcc = array_merge( $bcc, explode(', ', $additional_email_bcc->value));
            }
            $email_send_delay = Setting::all()->firstWhere('name', 'email_send_delay');
            $when = Carbon::now()->addMinutes($email_send_delay);

            \Mail::to($user)
                ->bcc($bcc)
                ->later($when, new Created($product, $user));
        }

        session()->flash('message', 'New product "' . $product->name . '" has been created');

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
            'manufacturer_id' => 'required|integer',
            'category_id' => 'required|integer',
            'visible' => 'required|boolean',
            'materials' => 'nullable|string',
            'description' => 'nullable|string',
            'images.*' => 'bail|image|mimetypes:image/png,image/jpeg,image/bmp',
            'year_manufacture' => 'nullable|integer',
            'price' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $product->update([
            'name' => request('name'),
            'slug' => Str::slug(request('name'), '-'),
            'manufacturer_id' => request('manufacturer_id'),
            'category_id' => request('category_id'),
            'visible' => request('visible'),
            'materials' => request('materials'),
            'description' => request('description'),
            'year_manufacture' => request('year_manufacture'),
            'price' => request('price'),
            'edited_by_user_id' => Auth::user()->id,
        ]);
        // dd(__METHOD__ . '@' . __LINE__);

        if ( request()->file('images') and count(request()->file('images')) ) { // проверить на изображение!!!
            // dd(__METHOD__ . '@' . __LINE__);
            foreach(request()->file('images') as $image) {

                // image re-creation
                $image_name = ImageYoTrait::saveImgSet($image, $product->id);
                $originalName = $image->getClientOriginalName();
                $path  = '/images/products/' . $product->id;

                // create image record
                $image = Image::create([
                    'product_id' => $product->id,
                    // 'slug' => $image_name,
                    'slug' => Str::slug($image_name, '-'),
                    'path' => $path,
                    'name' => $image_name,
                    'ext' => config('imageyo.res_ext'),
                    'alt' => str_replace( strrchr($originalName, '.'), '', $originalName),
                    'sort_order' => 9,
                    'orig_name' => $originalName,
                ]);
            }
        }

        // // send email-notification
        // $email_update_product = Setting::all()->firstWhere('name', 'email_update_product');
        // if ( $email_update_product->value ) {
        //     $when = Carbon::now()->addMinutes(1);
        //     \Mail::to(config('mail.mail_to_test'))
        //         ->bcc(config('mail.mail_bcc'))
        //         ->later($when, new Created($product)); // TODO! Updated($product)
        // }
        // send email-notification
        $email_update_product = Setting::all()->firstWhere('name', 'email_update_product');
        if ( $email_update_product->value ) {

            $user = Auth::user();
            $bcc = config('mail.mail_bcc');
            
            $additional_email_bcc = Setting::all()->firstWhere('name', 'additional_email_bcc');
            if ( $additional_email_bcc->value ) {
                $bcc = array_merge( $bcc, explode(', ', $additional_email_bcc->value));
            }

            $email_send_delay = Setting::all()->firstWhere('name', 'email_send_delay');
            $when = Carbon::now()->addMinutes($email_send_delay);

            \Mail::to($user)
            ->bcc($bcc)
            ->later($when, new Updated($product));
        }

        session()->flash('message', 'Product "' . $product->name . '" has been updated');

        return redirect()->route('products.index');
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

        $products_name = $product->name;
        $products_id = $product->id;

        // destroy product images
        if ($product->images) {

            // delete public directory (converted images)
            $directory_pub = 'public/images/products/' . $product->id;
            Storage::deleteDirectory($directory_pub);

            // delete uploads directory (original images)
            $directory_upl = 'uploads/images/products/' . $product->id;
            Storage::deleteDirectory($directory_upl);

        }

        // destroy product comments
        $product->comments()->delete();

        // destroy product
        $product->delete();

        session()->flash('message', 'Product "' . $products_name . '" with id=' . $products_id . ' was successfully removed.');

        return redirect()->route('products.index');
    }


    public function rewatermark()
    {
        info(__method__ . '@' . __line__ . ': config(\'imageyo.watermark\') = ' . config('imageyo.watermark'));

        // $products = Product::all()->where('image', '!=', null);
        $products = Product::all();

        foreach ($products as $product) {
            // RewatermarkJob::dispatch($product->id);
            $job = new RewatermarkJob($product->id);
            dispatch($job);
            // dispatch($job)->onQueue('rewatermark');
        }

        session()->flash('message', 'Jobs for ' . $products->count() . ' send in queue to rewatermark.');
        return redirect()->route('products.index');
    }

    public function search(Request $request) 
    {
        $validator = request()->validate([
            'query' => 'required|string|min:3|max:100',
        ]);
        $query = request('query');
        $products = Product::search($query)
            ->paginate(15);
        $appends = [];
        foreach($request->query as $key => $val){
            $appends[$key] = $val;
        }

        return view('search.result', compact('query', 'products', 'appends'));
    }

}
