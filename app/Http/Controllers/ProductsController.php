<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Mail\Product\{Created, Updated};
use Illuminate\Support\Carbon;
use Str;
use App\{Action, Category, Image, Manufacturer, Product};
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

        // save query string for pagination
        $appends = [];
        foreach($request->query as $key => $val){
            $appends[$key] = $val;
        }

        // // $view_products_whitout_price = Setting::all()->firstWhere('name', 'view_products_whitout_price');
        // if ( auth()->user() and  auth()->user()->can(['view_products'])) {
        //     $products = Product::filter($request, $this->getFilters())
        //         ->latest()
        //         ->paginate();

        // // } elseif ( $view_products_whitout_price->value ) {
        // } elseif ( config('settings.view_products_whitout_price') ) {
        //     $products = Product::filter($request, $this->getFilters())
        //         ->latest()->where('visible', '=', 1)
        //         ->paginate();

        // } else {
        //     $products = Product::filter($request, $this->getFilters())
        //         ->latest()->where('visible', '=', 1)
        //         ->where('price', '!=', 0)
        //         ->paginate();
        // }
        $products = Product::where('visible', '=', 1)
            ->orderBy('price')
            ->filter($request, $this->getFilters())
            // ->latest()
            ->paginate();
        return view('products.index', compact('products', 'appends'));
    }

    protected function getFilters() 
    {
        return [];
    }

    
    /**
     * Display a listing of the resource (all products) for admin side. 
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex() {

        $appends = [];
        foreach(request()->query as $key => $val){
            $appends[$key] = $val;
        }
        // $products = Product::all()->orderBy('category_id')->paginate(); // order!!
        $products = Product::filter(request(), $this->getFilters())
            ->orderBy('category_id')
            ->paginate();
        $categories = Category::all();

        return view('products.adminindex', compact('appends', 'categories', 'products'));
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $product = Product::findOrFail($id);
        if ( !auth()->user() or auth()->user()->hasRole('user') ) {
            $product->increment('views');
        }
        return view('products.show', compact('product'));
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
        return view('products.create', compact('categories', 'manufacturers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        abort_if (!auth()->user()->can('edit_products'), 403);
        $categories = Category::all();
        $manufacturers = Manufacturer::all();
        return view('products.edit', compact('product', 'categories', 'manufacturers'));
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

        return view('products.copy', compact('product', 'categories', 'manufacturers'));
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
        abort_if ( auth()->user()->cannot('create_products'), 403 );

        $validator = Validator::make(request()->all(), [
            'name'              => 'required|max:255|unique:products,name',
            'manufacturer_id'   => 'required|integer',
            'category_id'       => 'required|integer',
            'visible'           => 'nullable|string|in:on',
            'materials'         => 'nullable|string',
            'description'       => 'nullable|string',
            'modification'      => 'nullable|string',
            'workingconditions' => 'nullable|string',
            'imagespath'        => 'nullable|string',
            'date_manufactured' => 'nullable|string|min:10|max:10',
            'price'             => 'nullable|integer',
            'copy_img'          => 'nullable|integer',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        // get string table of modification srctablecode
        if ( request('modification') and config('settings.modification_wysiwyg') == 'srctablecode' ) {
            $dirty_modification = request('modification');
            $clean_modification = $this->cleanTables($dirty_modification);
            // dd($dirty_modification, $clean_modification);
            $modification = $clean_modification;
        } else {
            $modification = request('modification') ?? '';
        }


        if (!$product = Product::create([
            'name' => request('name'),
            'slug' => Str::slug(request('name'), '-'),
            'manufacturer_id' => request('manufacturer_id'),
            'category_id' => request('category_id'),
            'visible' => request('visible') ? 1 : 0,
            'materials' => request('materials') ?? '',
            'description' => request('description') ?? '',
            'modification' => $modification,
            'workingconditions' => request('workingconditions') ?? '',
            'date_manufactured' => request('date_manufactured') ?? '',
            'price' => request('price') ?? 0,
            'added_by_user_id' => auth()->user()->id,
            'views' => 0,
        ])) {
            return back()->withErrors(['something wrong!'])->withInput();
        }

        // if ( request()->file('images') and count(request()->file('images')) ) {
        //     foreach(request()->file('images') as $key => $image) {
                
        //         // // validation images
        //         // // $validator = Validator::make(['image' => $image], [$key => 'required|image|mimes:jpeg,bmp,png']);
        //         // $validator = Validator::make(
        //         //     ['image' => $image],
        //         //     [$key => 'required|image|mimetypes:image/png']
        //         // );
                
        //         // image re-creation
        //         $image_name = ImageYoTrait::saveImgSet($image, $product->id);
        //         $originalName = $image->getClientOriginalName();
        //         $path  = '/images/products/' . $product->id;

        //         // create record
        //         $image = Image::create([
        //             'product_id' => $product->id,
        //             // 'slug' => $image_name,
        //             'slug' => Str::slug($image_name, '-'),
        //             'path' => $path,
        //             'name' => $image_name,
        //             'ext' => config('imageyo.res_ext'),
        //             'alt' => str_replace( strrchr($originalName, '.'), '', $originalName),
        //             'sort_order' => 9,
        //             'orig_name' => $originalName,
        //         ]);
        //     }
        // }


        $this->attachImages($product->id, request('imagespath'));


        // copy all image and create records to images table
        if ( request('copy_img') ) {
            $images = Product::find(request('copy_img'))->images;
            // dd($images);
            if ( $images->count() ) {

                // create dir to preview the image
                $dst_dir = storage_path() . config('imageyo.dirdst') . '/' . $product->id;
                if ( !is_dir($dst_dir) ) {
                    if ( !mkdir($dst_dir, 0777, true) ) {
                        return back()->withErrors(['error #' . __line__])->withInput();
                    }
                }
                // create dir to copy the original image
                $dst_dir_origin = storage_path() . config('imageyo.dirdst_origin') . '/' . $product->id;
                if ( !is_dir($dst_dir_origin) ) {
                    if ( !mkdir($dst_dir_origin, 0777, true) ) {
                        return back()->withErrors(['error #' . __line__])->withInput();
                    }
                }

                while ( $images->count() ) {

                    $image = $images->shift();

                    // array of preview
                    foreach ( config('imageyo.previews') as $type_preview ) {
                        if ( config('imageyo.is_' . $type_preview) ) {

                            $rel_path = '/' . $image->name . '-' . $type_preview . $image->ext;

                            if ( $type_preview == 'origin' ) {
                                $source = storage_path() . config('imageyo.dirdst_origin') . '/' . $image->product_id . $rel_path;
                                $dest = $dst_dir_origin . $rel_path;
                            } else {
                                $source = storage_path() . config('imageyo.dirdst') . '/' . $image->product_id . $rel_path;
                                $dest = $dst_dir . $rel_path;    
                            }
                            // dd($source, $dest);


                            if ( !is_file($source) ) {
                                return back()->withErrors(['error #' . __line__ ])->withInput();
                            }
                            if ( !copy ($source , $dest) ) {
                                return back()->withErrors(['error #' . __line__])->withInput();
                            }
                        }
                    }

                    // create records in the images table
                    if ( !(Image::create([
                        'product_id' => $product->id,
                        'slug' => $image->slug,
                        'path' => $image->path,
                        'name' => $image->name,
                        'ext'  => $image->ext,
                        'alt'  => $image->alt,
                        'sort_order' => 9,
                        'orig_name' => $image->orig_name,
                    ])) ) {
                        return back()->withErrors(['error #' . __line__ ])->withInput();
                    }
                }
            }

            $donor = Product::find($image->product_id)->name;
            $description_action = 'Копирование товара "' . $product->name . '" из донора "' . $donor . '". Исполнитель: ' . auth()->user()->name . '.';
        } else {
            $description_action = 'Создание товара "' . $product->name . '". Исполнитель: ' . auth()->user()->name . '.';
        }

        // send email-notification
        // $email_new_product = Setting::all()->firstWhere('name', 'email_new_product');
        // if ( $email_new_product->value ) {
        if ( config('settings.email_new_product') ) {

            $user = auth()->user();
            $bcc = config('mail.mail_bcc');

            // $additional_email_bcc = Setting::all()->firstWhere('name', 'additional_email_bcc');
            // if ( $additional_email_bcc->value ) {
            if ( config('settings.additional_email_bcc') ) {
                // $bcc = array_merge( $bcc, explode(', ', $additional_email_bcc->value));
                $bcc = array_merge( $bcc, explode(', ', config('settings.additional_email_bcc')));
            }
            // $email_send_delay = Setting::all()->firstWhere('name', 'email_send_delay');
            // $when = Carbon::now()->addMinutes($email_send_delay);
            $when = Carbon::now()->addMinutes(config('settings.email_send_delay'));

            \Mail::to($user)
                ->bcc($bcc)
                ->later($when, new Created($product, $user));
        }

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'product',
            'type_id' => $product->id,
            'action' => 'create',
            'description' => $description_action,
            // 'old_value' => $product->id,
            // 'new_value' => $product->id,
        ]);

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
        abort_if ( auth()->user()->cannot('edit_products'), 403 );

        $validator = Validator::make(request()->all(), [
            'name'              => 'required|max:255',
            'manufacturer_id'   => 'required|integer',
            'category_id'       => 'required|integer',
            'visible'           => 'nullable|string|in:on',
            'materials'         => 'nullable|string',
            'description'       => 'nullable|string',
            'modification'      => 'nullable|string',
            'workingconditions' => 'nullable|string',
            'imagespath'        => 'nullable|string',
            'date_manufactured' => 'nullable|string|min:10|max:10',
            'price'             => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        // get string table of modification srctablecode
        if ( request('modification') and config('settings.modification_wysiwyg') == 'srctablecode' ) {
            $dirty_modification = request('modification');
            $clean_modification = $this->cleanTables($dirty_modification);
            // dd($dirty_modification, $clean_modification);
            $modification = $clean_modification;
        } else {
            $modification = request('modification') ?? '';
        }


        $product->update([
            'name' => request('name'),
            'slug' => Str::slug(request('name'), '-'),
            'manufacturer_id' => request('manufacturer_id'),
            'category_id' => request('category_id'),
            'visible' => request('visible') ? 1 : 0,
            'materials' => request('materials'),
            'description' => request('description'),
            'modification' => $modification,
            'workingconditions' => request('workingconditions') ?? '',
            'date_manufactured' => request('date_manufactured') ?? '',
            'price' => request('price'),
            'edited_by_user_id' => auth()->user()->id,
        ]);


        // if ( request()->file('images') and count(request()->file('images')) ) { // проверить на изображение!!!
        //     // dd(__METHOD__ . '@' . __LINE__);
        //     foreach(request()->file('images') as $image) {

        //         // image re-creation
        //         $image_name = ImageYoTrait::saveImgSet($image, $product->id);
        //         $originalName = $image->getClientOriginalName();
        //         $path  = '/images/products/' . $product->id;

        //         // create image record
        //         $image = Image::create([
        //             'product_id' => $product->id,
        //             // 'slug' => $image_name,
        //             'slug' => Str::slug($image_name, '-'),
        //             'path' => $path,
        //             'name' => $image_name,
        //             'ext' => config('imageyo.res_ext'),
        //             'alt' => str_replace( strrchr($originalName, '.'), '', $originalName),
        //             'sort_order' => 9,
        //             'orig_name' => $originalName,
        //         ]);
        //     }
        // }
        $this->attachImages($product->id, request('imagespath'));

        // send email-notification
        if ( config('settings.email_update_product') ) {

            $user = auth()->user();
            $bcc = config('mail.mail_bcc');
            
            if ( config('settings.additional_email_bcc') ) {
                $bcc = array_merge( $bcc, explode(', ', config('settings.additional_email_bcc')));
            }

            $when = Carbon::now()->addMinutes( config('settings.email_send_delay') ); // TODO convert to int?

            \Mail::to($user)
            ->bcc($bcc)
            ->later($when, new Updated($product));
        }

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'product',
            'type_id' => $product->id,
            'action' => 'update',
            'description' => 
                'Редактирование товара ' 
                // . '<a href="' . route('products.show', ['product' => $product->id]) . '">' . $product->name . '</a>'
                . $product->name
                . '. Исполнитель: ' 
                . auth()->user()->name 
                . '.',
            // 'old_value' => $product->id,
            // 'new_value' => $product->id,
        ]);

        session()->flash('message', 'Product "' . $product->name . '" has been updated');

        // return redirect()->route('products.index');
        return redirect()->route('products.show', $product->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        abort_if ( auth()->user()->cannot('delete_products'), 403 );

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

        // ADD DELETE PRODUCT EMAIL!
        // // send email-notification
        // $email_update_product = Setting::all()->firstWhere('name', 'email_update_product');
        // if ( $email_update_product->value ) {

        //     $user = auth()->user();
        //     $bcc = config('mail.mail_bcc');
            
        //     $additional_email_bcc = Setting::all()->firstWhere('name', 'additional_email_bcc');
        //     if ( $additional_email_bcc->value ) {
        //         $bcc = array_merge( $bcc, explode(', ', $additional_email_bcc->value));
        //     }

        //     $email_send_delay = Setting::all()->firstWhere('name', 'email_send_delay');
        //     $when = Carbon::now()->addMinutes($email_send_delay);

        //     \Mail::to($user)
        //     ->bcc($bcc)
        //     ->later($when, new Updated($product));
        // }

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'product',
            'type_id' => $product->id,
            'action' => 'delete',
            'description' => 
                'Удаление товара ' 
                // . '<a href="' . route('products.show', ['product' => $product->id]) . '">' . $product->name . '</a>'
                . $product->name
                . '. Исполнитель: ' 
                . auth()->user()->name 
                . '.',
            // 'old_value' => $product->id,
            // 'new_value' => $product->id,
        ]);

        session()->flash('message', 'Product "' . $products_name . '" with id=' . $products_id . ' was successfully removed.');

        // return redirect()->route('products.index');
        return back();
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
        $products = Product::search($query)->paginate(15);
        $appends = [];
        foreach($request->query as $key => $val){
            $appends[$key] = $val;
        }

        // return view('search.result', compact('query', 'products', 'appends'));
        return view('products.index', compact('query', 'products', 'appends'));
    }


    /*
    * Приватный метод очистки исходного украденного исходного кода таблиц
    *
    * Возвращает исходный код, очищенный от ненужных тегов, классов, стилей и др.
    */
    private function cleanTables (String $dirty_modification ): string {

        // удаление ненужных тегов
        $res = strip_tags($dirty_modification, '<table><caption><thead><tbody><th><tr><td>');

        $arr_replace = [
            ["~</table>.*?<table[^>]*?>~u",         "REPLACE_THIS"],                    // если таблиц несколько
            ["~.*?<table[^>]*?>~u",     "<table class=\"blue_table\">"],                // обрезка до таблицы
            ["~</table>.*?~u",          "</table>"],                                    // обрезка после таблицы
            ["~<caption[^>]*?>~u",      "<caption>"],                                   // чистка нужных тегов от классов, стилей и атрибутов
            ["~<thead[^>]*?>~u",        "<thead>"],                                     // чистка нужных тегов от классов, стилей и атрибутов
            ["~<tbody[^>]*?>~u",        "<tbody>"],                                     // чистка нужных тегов от классов, стилей и атрибутов
            ["~<th[\s]{1}[^>]*?>~u",    "<th>"],                                        // не зацепить <thead>!!
            ["~<tr[^>]*?>~u",           "<tr>"],
            ["~<td[^>]*?>~u",           "<td>"],
            ["~>[\s]*~",                ">"],
            ["~[\s]*>~",                ">"],
            ["~<[\s]*~",                "<"],
            ["~[\s]*<~",                "<"],
            ["~REPLACE_THIS~u",         "</table>\n<table class=\"blue_table\">"],

        ];
        foreach($arr_replace as $replace) {
            $res = preg_replace( $replace[0], $replace[1], $res );
        }

        // удаление прочего мусора
        $arr_delete = [
            '&nbsp;',
        ];
        foreach($arr_delete as $delete) {
            $res = str_replace( $delete, '', $res );
        }

        // опционально: если последним столбцом таблицы идет цена, то вырезаем весь столбец
        if ( strpos($res,'<td>Цена</td></tr>') or strpos($res,'<th>Цена</th></tr>') ) {
            $arr_replace = [
                ["~<td>[^<]+?</td></tr>~u","</tr>"],
                ["~<th>[^<]+?</th></tr>~u","</tr>"],
            ];
            foreach($arr_replace as $replace) {
                $res = preg_replace( $replace[0], $replace[1], $res );
            }
        }

        // опционально: удаление столбца <tr><td>Код товара</td>
        if ( strpos($res,'<tr><td>Код товара</td>') or strpos($res,'<tr><th>Код товара</th>') ) {
            $arr_replace = [
                ["~<tr><td>[^<]+?</td>~u","<tr>"],
                ["~<tr><th>[^<]+?</th>~u","<tr>"],
            ];
            foreach($arr_replace as $replace) {
                $res = preg_replace( $replace[0], $replace[1], $res );
            }
        }

        return $res;
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


    public function massupdate() {
        abort_if ( auth()->user()->cannot('edit_products'), 403 );
        // dd(request()->all());

        request()->validate([
            'action' => 'required|string|in:delete,replace,invisible,visible',
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
        if (request('action') == 'delete') {
            abort_if ( auth()->user()->cannot('delete_products'), 403 );
            $products->each(function ($product) {
                if (!$this->destroy($product)) { $err = true; }
            });

        // replace
        } elseif (request('action') == 'replace') {
            $products->each(function ($product) {
                if (
                    $product->update([
                        'category_id' => request('category_id'),
                        'edited_by_user_id' => auth()->user()->id,
                    ])
                ) { $err = true; }
            });

        // invisible
        } elseif (request('action') == 'invisible') {
            $products->each(function ($product) {
                if (
                    $product->update([
                        'visible' => false,
                        'edited_by_user_id' => auth()->user()->id,
                    ])
                ) { $err = true; }
            });

        // visible
        } elseif (request('action') == 'visible') {
            $products->each(function ($product) {
                if (
                    $product->update([
                        'visible' => true,
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
