<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Mail\Product\Created;
use Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Str;

use App\Product;
use App\Category;
use App\Cart;
use App\Manufacturer;
use App\Image;
// use App\Filters\Product\ManufacturerFilter;
// use Intervention\Image\Facades\Image;
use App\Traits\Yakoffka\ImageYoTrait; // Traits???
use App\Jobs\RewatermarkJob;
// use Artisan;

class ProductsController extends Controller
{

    public function __construct() {
        $this->middleware('auth')->except(['index', 'show', 'filter']);
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
        // dd(Image::min('sort_order'));
        // $topimages = Image::all()->firstWhere('sort_order', Image::min('sort_order'));
        // dd($topimages);
        
        // $thiscategories = Product::filter($request, $this->getFilters())->get('category_id');
        // dd($products->total());
        // dd($thiscategories);
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


        // if ( request()->file('image') ) { // убрать лишнюю перезапись!

        //     // $product->image = $this->storeImage(request()->file('image'), $product->id);
        //     // $product->image = ImageYoTrait::saveImgSet(request()->file('image'), $product->id);

        //     $image_name = ImageYoTrait::saveImgSet(request()->file('image'), $product->id);
        //     $originalName = request()->file('image')->getClientOriginalName();

        //     $image = Image::create([
        //         'product_id' => $product->id,
        //         'orig_name' => $originalName,
        //         'slug' => Str::slug($originalName),
        //         'alt' => str_replace( strrchr($originalName, '.'), '', $originalName), // cut extention
        //         'sort_order' => request('sort_order') ?? 9,
        //     ]);

        //     if (!$product->image or !$product->update()) {
        //         return back()->withErrors(['something wrong. err' . __line__])->withInput();
        //     }
        // }
        // dd(request()->file('images'));
        if ( count(request()->file('images')) ) {
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
                    'slug' => $image_name,
                    'path' => $path,
                    'name' => $image_name,
                    'ext' => config('imageyo.res_ext'),
                    'alt' => str_replace( strrchr($originalName, '.'), '', $originalName),
                    'sort_order' => rand(1, 9),
                    'orig_name' => $originalName,
                ]);
            }
        }


        // sending notification
        // replace config('mail.mail_to_test') => auth()->user()->email
        // \Mail::to(config('mail.mail_to_test'))
        //     ->bcc(config('mail.mail_bcc'))
        //     ->send(
        //         new ProductCreated($product)
        //     );

        // sending notification with queue
        // \Mail::to(config('mail.mail_to_test'))
        // ->bcc(config('mail.mail_bcc'))
        // ->queue(new ProductCreated($product));

        // sending notification later with queue
        $when = Carbon::now()->addMinutes(1);
        \Mail::to(config('mail.mail_to_test'))
            ->bcc(config('mail.mail_bcc'))
            ->later($when, new Created($product));

        session()->flash('message', 'products ' . $product->name . ' has been created');
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
            'manufacturer_id' => request('manufacturer_id'),
            'category_id' => request('category_id'),
            'visible' => request('visible'),
            'materials' => request('materials'),
            'description' => request('description'),
            'year_manufacture' => request('year_manufacture'),
            'price' => request('price'),
            'edited_by_user_id' => Auth::user()->id,
        ]);

        if ( count(request()->file('images')) ) { // проверить на изображение!!!
            foreach(request()->file('images') as $image) {

                // image re-creation
                $image_name = ImageYoTrait::saveImgSet($image, $product->id);
                $originalName = $image->getClientOriginalName();
                $path  = '/images/products/' . $product->id;

                // create record
                $image = Image::create([
                    'product_id' => $product->id,
                    'slug' => $image_name,
                    'path' => $path,
                    'name' => $image_name,
                    'ext' => config('imageyo.res_ext'),
                    'alt' => str_replace( strrchr($originalName, '.'), '', $originalName),
                    'sort_order' => rand(1, 9),
                    'orig_name' => $originalName,
                ]);
            }
        }

        // return redirect()->route('products.show', ['product' => $product->id]);
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

    // public function _rewatermark(Request $request)
    // {
    //     $start = microtime(true);
    //     // info("\n\n\n".__line__ . ' ' . __METHOD__ . 'start');
    //     // info(__method__ . '@' . __line__ . ' has: ' . $request->session()->has('rewatermarks'));

    //     // получение данных
    //     if ( !$request->session()->has('rewatermarks') ) {
    //         // info(__method__ . '@' . __line__);

    //         // первая итерация
    //         \Artisan::call('config:cache');
    //         $rewatermarks = Product::all()->where('image', '!=', null)->pluck('id');

    //         $request->session()->put('rewatermarks_start', $start);
    //         // $request->session()->save();
    //     } else {
    //         $rewatermarks = $request->session()->get('rewatermarks', null);
    //         // info(__method__ . '@' . __line__  . ' $rewatermarks->count() = '. $rewatermarks ? $rewatermarks->count() : 'null');
    //     }

    //     // если полученная коллекция не пуста
    //     if ( $rewatermarks->count() ) {
    //         // info(__method__ . '@' . __line__ . ' $rewatermarks->count() = ' . $rewatermarks->count());

    //         // вырезаем очередной id
    //         $product_id = $rewatermarks->pop();
    //         // info(__method__ . '@' . __line__ . ' $product_id = ' . $product_id);

    //         $request->session()->put('rewatermarks', $rewatermarks);
    //         // $request->session()->save();

    //         $product = Product::findOrFail($product_id); // find???
    //         // dd($product);

    //         // получаем имя оригинального файла (проверить на существование?)
    //         $image = storage_path() . config('imageyo.dirdst_origin') . '/' . $product->id . '/' . $product->image . '_origin' . config('imageyo.res_ext');
            
    //         // и преобразуем получаемый файл
    //         if ( !ImageYoTrait::saveImgSet($image, $product->id, 'rewatermark') ) {
    //             return redirect()->route('products.index')->withErrors(['Something wrong: ' . $product->name]);
    //         }
    //         // info(__method__ . '@' . __line__ . ' $product->image = ' . $product->image);

    //         // если коллекция всё ещё не пуста, инициируем ещё одну итерацию
    //         if ( $rewatermarks->count() ) {
    //             // info(__method__ . '@' . __line__ . " redirect()->route('products.rewatermark');");

    //             return redirect()->route('products.rewatermark');
    //         }
    //     }
        
    //     $time = microtime(true) - $request->session()->get('rewatermarks_start', 0);

    //     $request->session()->forget('rewatermarks');
    //     $request->session()->forget('rewatermarks_start');
    //     $request->session()->forget('rewatermarks_timing');
    //     $request->session()->save();

    //     session()->flash('message', 'Rewatermarks is complited. execute time: ' . $time);

    //     return redirect()->route('products.index');
        
    // }


    public function rewatermark()
    {
        // \Artisan::call('config:cache');
        // // dd(\Artisan::output());
        // \Artisan::call('queue:restart');
        // // dd(\Artisan::output());

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

    // /*
    // * заготовка для фильтра (куда бы её покласть?)
    // *
    // */
    // public function filter (Request $request) {
    //     // dd($request);

    //     $products = Product::filter($request, $this->getFilters())->paginate(config('custom.products_paginate'));

    //     $appends = [];
    //     foreach($request->query as $key => $val){
    //         $appends[$key] = $val;
    //     }
    //     return view('products.index', compact('products', 'appends'));
    // }

    


}
