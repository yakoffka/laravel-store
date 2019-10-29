<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Mail\Product\{Created, Updated};
use Illuminate\Support\Carbon;
use Str;
use App\{Category, Image, Manufacturer, Product};
use App\Jobs\RewatermarkJob;
use Artisan;
use App\Traits\Yakoffka\ImageYoTrait;

class ProductsController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show', 'search']);
    }


    /**
     * Only for filters!
     * Display a listing of the resource for filters. Only for filters!
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if ( !$request->query->count() ){
            return redirect()->route('categories.index');
        }
        $appends = $request->query->all();
        $array_seeable_categories = Category::all()
            ->where('seeable', '=', 'on')
            ->where('parent_seeable', '=', 'on') // getParentSeeableAttribute
            ->pluck('id')
            ->toArray();
        $products = Product::where('seeable', '=', 'on')
            ->whereIn('category_id', $array_seeable_categories)
            ->orderBy('price')
            ->filter($request)
            ->paginate();
        return view('products.index', compact('products', 'appends'));
    }


    /**
     * Display a listing of the resource (all products) for admin side. 
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex() {
        $appends = request()->query->all();
        $products = Product::filter(request())
            // ->orderBy('category_id')
            ->paginate();
        $categories = Category::all();
        return view('dashboard.adminpanel.products.adminindex', compact('appends', 'categories', 'products'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if ( !auth()->user()->can('create_products'), 403 );
        $categories = Category::all();
        $manufacturers = Manufacturer::all();
        return view('dashboard.adminpanel.products.create', compact('categories', 'manufacturers'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product)
    {
        abort_if ( auth()->user()->cannot('create_products'), 403 );

        request()->validate([
            'name'              => 'required|string|unique:products,name',
            'title'             => 'nullable|string',
            'slug'              => 'nullable|string',
            'manufacturer_id'   => 'required|integer',
            'category_id'       => 'required|integer',
            'seeable'           => 'nullable|string|in:on',
            'materials'         => 'nullable|string',
            'description'       => 'nullable|string',
            'modification'      => 'nullable|string',
            'workingconditions' => 'nullable|string',
            'imagespath'        => 'nullable|string',
            'date_manufactured' => 'nullable|string|min:10|max:10',
            'price'             => 'nullable|integer',
            'copy_img'          => 'nullable|integer',
        ]);

        $product = Product::create([
            'name' => request('name'),
            'title' => request('title'),
            'slug' => request('slug'),
            'manufacturer_id' => request('manufacturer_id'),
            'category_id' => request('category_id'),
            'seeable' => request('seeable') ,
            'materials' => request('materials'),
            'description' => request('description'),
            'modification' => request('modification'),
            'workingconditions' => request('workingconditions'),
            'date_manufactured' => request('date_manufactured'),
            'price' => request('price'),
            'views' => 0,
        ]);

        $this->attachImages($product->id, request('imagespath'));

        // // send email-notification
        // if ( config('settings.email_new_product') ) {
        //     $user = auth()->user();
        //     $bcc = config('mail.mail_bcc');
        //     if ( config('settings.additional_email_bcc') ) {
        //         $bcc = array_merge( $bcc, explode(', ', config('settings.additional_email_bcc')));
        //     }
        //     $when = Carbon::now()->addMinutes(config('settings.email_send_delay'));
        //     \Mail::to($user)->bcc($bcc)->later($when, new Created($product, $user));
        // }

        // if ( $message ) {session()->flash('message', $message);}
        return redirect()->route('categories.show', $product->category_id);
    }


    /**
     * Display the specified resource.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product) {
        abort_if( !$product->seeable or !$product->category_seeable or !$product->parent_category_seeable, 404);
        $product->incrementViews();
        return view('products.show', compact('product'));
    }


    /**
     * Display the specified resource for admin side.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function adminShow(Product $product) {
        return view('dashboard.adminpanel.products.adminshow', compact('product'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        abort_if (!auth()->user()->can('edit_products'), 403);
        $categories = Category::all();
        $manufacturers = Manufacturer::all();
        return view('dashboard.adminpanel.products.edit', compact('product', 'categories', 'manufacturers'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function copy(Product $product)
    {
        abort_if (!auth()->user()->can('edit_products'), 403);
        $categories = Category::all();
        $manufacturers = Manufacturer::all();
        session()->flash('message', 'When copying an item, you must change its name!');

        return view('dashboard.adminpanel.products.copy', compact('product', 'categories', 'manufacturers'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  request()
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Product $product)
    {
        abort_if ( auth()->user()->cannot('edit_products'), 403 );

        request()->validate([
            'name'              => 'required|max:255',
            'title'             => 'nullable|string',
            'slug'              => 'nullable|string',
            'manufacturer_id'   => 'required|integer',
            'category_id'       => 'required|integer',
            'seeable'           => 'nullable|string|in:on',
            'materials'         => 'nullable|string',
            'description'       => 'nullable|string',
            'modification'      => 'nullable|string',
            'workingconditions' => 'nullable|string',
            'imagespath'        => 'nullable|string',
            'date_manufactured' => 'nullable|string|min:10|max:10',
            'price'             => 'nullable|integer',
        ]);

        $product->update([
            'name' => request('name'),
            'title' => request('title'),
            'slug' => request('slug'),
            'manufacturer_id' => request('manufacturer_id'),
            'category_id' => request('category_id'),
            'seeable' => request('seeable') ,
            'materials' => request('materials'),
            'description' => request('description'),
            'modification' => request('modification'),
            'workingconditions' => request('workingconditions'),
            'date_manufactured' => request('date_manufactured'),
            'price' => request('price'),
            'views' => 0,
        ]);

        $this->attachImages($product->id, request('imagespath'));

        // // send email-notification
        // if ( config('settings.email_update_product') ) {
        //     $user = auth()->user();
        //     $bcc = config('mail.mail_bcc');
        //     if ( config('settings.additional_email_bcc') ) {
        //         $bcc = array_merge( $bcc, explode(', ', config('settings.additional_email_bcc')));
        //     }
        //     $when = Carbon::now()->addMinutes(config('settings.email_send_delay'));
        //     \Mail::to($user)->bcc($bcc)->later($when, new Updated($product, $user));
        // }

        return redirect()->route('products.adminshow', $product->id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        abort_if ( auth()->user()->cannot('delete_products'), 403 );

        $product->delete();

        // возврат на предыдущую страницу, если удаление было инициировано не со страницы товара
        if ( preg_match( '~products/[^/]+$~' , back()->headers->get('location') ) ) {
            return redirect()->route('products.adminindex');
        } else {
            return back();
        }
    }


    public function rewatermark()
    {
        $products = Product::has('images')->get();

        if ( $products->count() ) {
            Artisan::call('queue:restart');
            foreach ($products as $product) {
                $job = new RewatermarkJob($product->id);
                dispatch($job);
            }
            session()->flash('message', 'Jobs for ' . $products->count() . ' send in queue to rewatermark.');
        } else {
            session()->flash('message', 'No products with images.');
        }
        return redirect()->route('products.index');
    }


    public function search(Request $request) 
    {
        $validator = request()->validate([
            'query' => 'required|string|min:3|max:100',
        ]);

        $query = request('query');
        $array_seeable_categories = Category::all()
            ->where('seeable', '=', 'on')
            ->where('parent_seeable', '=', 'on') // getParentSeeableAttribute
            ->pluck('id')
            ->toArray();
        $products = Product::where('seeable', 'on')
            ->whereIn('category_id', $array_seeable_categories)
            ->search($query)
            ->paginate(15)
            ;
        $appends = [];
        foreach($request->query as $key => $val){
            $appends[$key] = $val;
        }

        return view('products.index', compact('query', 'products', 'appends'));
    }


    /**
     * Приватный метод добавления изображений товара
     * 
     * Принимает строку с path файлов изображений, разделёнными запятой
     * Создает, при необходимости директорию для хранения изображений товара,
     *  и копирует в неё комплект превью с наложением водяных знаков.
     * Добавляет запись о каждом изображении в таблицу images
     *
     * Возвращает void
     */
    private function attachImages (int $product_id, string $imagespath = NULL)
    {
        if ( empty($imagespath) ) {
            return true;
        }
        $imagepaths = explode(',', $imagespath);
        foreach( $imagepaths as $imagepath) {
            $image = storage_path('app/public') . str_replace( config('filesystems.disks.lfm.url'), '', $imagepath );

            // image re-creation
            $image_name = ImageYoTrait::saveImgSet($image, $product_id, 'lfm-mode');
            $originalName = basename($image_name);
            $path  = '/images/products/' . $product_id;

            // create record
            $image = Image::create([
                'product_id' => $product_id,
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


    public function massupdate() {
        abort_if ( auth()->user()->cannot('edit_products'), 403 );

        request()->validate([
            'action' => 'required|string|in:delete,replace,inseeable,seeable',
            'products' => 'required|array',
            'category_id' => 'nullable|string',
        ]);

        if ( !count(request('products')) ) {
            return back()->withErrors(['Не выбран ни один товар!'])->withInput();
        }

        $products = Product::find(request('products'));
        if ( !$products->count() ) {
            return back()->withErrors(['Выбранные товары не существуют!'])->withInput();
        }

        // delete
        if (request('action') === 'delete') {
            abort_if ( auth()->user()->cannot('delete_products'), 403 );
            $products->each(function ($product) {
                if (!$this->destroy($product)) { $err = true; }
            });

        // replace
        } elseif (request('action') === 'replace') {
            $products->each(function ($product) {
                if (
                    $product->update([
                        'category_id' => request('category_id'),
                        'edited_by_user_id' => auth()->user()->id,
                    ])
                ) { $err = true; }
            });

        // inseeable
        } elseif (request('action') === 'inseeable') {
            $products->each(function ($product) {
                if (
                    $product->update([
                        'seeable' => false,
                        'edited_by_user_id' => auth()->user()->id,
                    ])
                ) { $err = true; }
            });

        // seeable
        } elseif (request('action') === 'seeable') {
            $products->each(function ($product) {
                if (
                    $product->update([
                        'seeable' => true,
                        'edited_by_user_id' => auth()->user()->id,
                    ])
                ) { $err = true; }
            });

        // unknown action
        } else {
            return back()->withErrors(['Выбранной операции не существует!'])->withInput();
        }


        if ( !empty($err) ) {
            $mess = 'Операция не удалась или удалась неполностью.';
        } else {
            $mess = 'Операция прошла успешно.';                
        }
    
        return back();
    }
}
