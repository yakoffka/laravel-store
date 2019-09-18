<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Action, Category, Product};
use Illuminate\Support\Facades\Storage;
use Auth;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }
    
    /**
     * Display a listing of the resource (parent categories). 
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        // get appends?
        $appends = [];

        // show subcategories and products only directly from catalog
        if ( config('settings.display_subcategories') ) {

            $categories = Category::all()->where('parent_id', '=', 1)->where('id', '>', 1);
            // dd($categories);

            $products = Product::all()->where('category_id', 1);
            // dd($products);

            return view('categories.index', compact('categories', 'products', 'appends'));

        // show products in this category, including subcategory products
        } else {

            // get products
            if( Auth::user() and  Auth::user()->can(['view_products'])) {
                $products = Product::paginate();
            } else {
                $products = Product::where('visible', '=', 1)->paginate();
            }

            $category = Category::find(1);

            return view('products.index', compact('products', 'category', 'appends'));
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if( Auth::user()->cannot('create_categories'), 403);
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if( Auth::user()->cannot('create_categories'), 403);
        $arrToValidate = [
            'name'          => 'required|string|max:255|unique',
            'title'         => 'required|string|max:255|unique',
            'description'   => 'string|max:255',
            'image'         => 'image',
            'visible'       => 'required|boolean',
            'parent_id'     => 'required|integer|max:255',
        ];

        $category = Category::create([
            'name'            => request('name'),
            'slug'            => Str::slug(request('name'), '-'),
            'title'           => request('title'),
            'description'     => request('description'),
            'visible'         => request('visible'),
            'parent_id'       => request('parent_id'),
            'added_by_user_id'=> Auth::user()->id,
        ]);

        if ( request()->file('image') ) {

            $image = request()->file('image');
            $directory = 'public/images/categories/' . $category->id;
            $filename = $image->getClientOriginalName();
    
            if ( !Storage::makeDirectory($directory)
                or !Storage::putFileAs($directory, $image, $filename )
                or !$category->update(['image' => $filename])
            ) {
                return back()->withErrors(['something wrong. err' . __line__])->withInput();
            }
        }

        // add email!

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'category',
            'type_id' => $category->id,
            'action' => 'create',
            'description' => 
                'Создание категории ' 
                . $category->name
                . '. Исполнитель: ' 
                . auth()->user()->name 
                . '.',
            // 'old_value' => $category->id,
            // 'new_value' => $category->id,
        ]);

        session()->flash('message', 'Category "' . $category->name . '" with id=' . $category->id . ' was successfully created.');

        return redirect()->route('categories.show', ['category' => $category->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category) {
        
        // // get all product in all subcategory this category
        // $arr_children = $this->getAllChildren([$category->id], $category);
        // if( Auth::user() and  Auth::user()->can(['view_products'])) {
        //     $products = Product::whereIn('category_id', $arr_children)->paginate();
        // } else {
        //     $products = Product::whereIn('category_id', $arr_children)->where('visible', '=', 1)->paginate();
        // }
        
        // // get appends
        // $appends = [];
                
        // return view('products.index', compact('products', 'category', 'appends'));



        // get appends?
        $appends = [];

        // show subcategories and products only directly from this category
        if ( config('settings.display_subcategories') and $category->children->count() ) {

            // добавить перенаправление при $category->id == 1?
            $categories = Category::all()->where('parent_id', $category->id);
            $products = Product::all()->where('category_id', $category->id);
            return view('categories.index', compact('categories', 'category', 'products', 'appends'));


        // show products in this category, including subcategory products
        } else {

            // get products
            if( Auth::user() and Auth::user()->can(['view_products'])) {
                $products = Product::where('category_id', $category->id)->paginate();
            } else {
                $products = Product::where('visible', '=', 1)->where('category_id', $category->id)->paginate();
            }

            //$category = Category::find(1);

            return view('products.index', compact('products', 'category', 'appends'));
        }
    }

    private function getAllChildren($arr_children, $parent) {
        $arr_subchildren = $parent->children->toArray();
        foreach ( $arr_subchildren as $child ) {
            if ( $child['id'] != 1 ) {
                $arr_children[] = $child['id'];
                $parent = Category::find($child['id']);
                $arr_children = $this->getAllChildren($arr_children, $parent);
            }
        }

        return $arr_children;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        abort_if (Auth::user()->cannot('edit_categories'), 403);
        $categories = Category::all();
        return view('categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Category $category)
    {
        abort_if( Auth::user()->cannot('edit_categories'), 403);

        $validator = request()->validate([
            'name'          => 'required|string|max:255',
            'title'         => 'required|string|max:255',
            'description'   => 'string|max:255',
            'image'         => 'image',
            'visible'       => 'required|boolean',
            'parent_id'     => 'required|integer|max:255',
        ]);

        // добавить проверку успешности сохранения!
        $category->update([
            'name'              => request('name'),
            'slug'              => Str::slug(request('name'), '-'),
            'title'             => request('title'),
            'description'       => request('description'),
            'visible'           => request('visible'),
            'parent_id'         => request('parent_id'),
            'edited_by_user_id' => Auth::user()->id,
        ]);

        // if ( request()->file('image') ) {
        //     $image = request()->file('image');
        //     $directory = 'public/images/categories/' . $category->id;
        //     $filename = $image->getClientOriginalName();
        //     if ( !Storage::makeDirectory($directory)
        //         or !Storage::putFileAs($directory, $image, $filename )
        //         or !$category->update(['image' => $filename])
        //     ) {
        //         return back()->withErrors(['something wrong. err' . __line__])->withInput();
        //     }
        // }
        if ( request('imagepath') ) {

            $dst_dir = storage_path() . config('imageyo.rel_path_category_img') . '/' . $category->id;

            dd(config('lfm.relative_paths'), request('imagepath'));

            dd(storage_path(), request('imagepath'), config('imageyo.rel_path_category_img'), $dst_dir);

            // if ( !is_dir($dst_dir) ) {
            //     if ( !mkdir($dst_dir, 0777, true) ) {
            //         return back()->withErrors(['error #' . __line__])->withInput();
            //     }
            // }
            // // create dir to copy the original image
            // $dst_dir_origin = storage_path() . config('imageyo.dirdst_origin') . '/' . $product->id;
            // if ( !is_dir($dst_dir_origin) ) {
            //     if ( !mkdir($dst_dir_origin, 0777, true) ) {
            //         return back()->withErrors(['error #' . __line__])->withInput();
            //     }
            // }

            // while ( $images->count() ) {

            //     $image = $images->shift();

            //     // array of preview
            //     foreach ( config('imageyo.previews') as $type_preview ) {
            //         if ( config('imageyo.is_' . $type_preview) ) {

            //             $rel_path = '/' . $image->name . '-' . $type_preview . $image->ext;

            //             if ( $type_preview == 'origin' ) {
            //                 $source = storage_path() . config('imageyo.dirdst_origin') . '/' . $image->product_id . $rel_path;
            //                 $dest = $dst_dir_origin . $rel_path;
            //             } else {
            //                 $source = storage_path() . config('imageyo.dirdst') . '/' . $image->product_id . $rel_path;
            //                 $dest = $dst_dir . $rel_path;    
            //             }
            //             // dd($source, $dest);


            //             if ( !is_file($source) ) {
            //                 return back()->withErrors(['error #' . __line__ ])->withInput();
            //             }
            //             if ( !copy ($source , $dest) ) {
            //                 return back()->withErrors(['error #' . __line__])->withInput();
            //             }
            //         }
            //     }

            //     // create records in the images table
            //     if ( !(Image::create([
            //         'product_id' => $product->id,
            //         'slug' => $image->slug,
            //         'path' => $image->path,
            //         'name' => $image->name,
            //         'ext'  => $image->ext,
            //         'alt'  => $image->alt,
            //         'sort_order' => 9,
            //         'orig_name' => $image->orig_name,
            //     ])) ) {
            //         return back()->withErrors(['error #' . __line__ ])->withInput();
            //     }
            // }
        }

        // add email!

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'category',
            'type_id' => $category->id,
            'action' => 'update',
            'description' => 
                'Редактирование категории ' 
                . $category->name
                . '. Исполнитель: ' 
                . auth()->user()->name 
                . '.',
            // 'old_value' => $category->id,
            // 'new_value' => $category->id,
        ]);

        session()->flash('message', 'Category "' . $category->name . '" with id=' . $category->id . ' was successfully edit.');

        // return redirect()->route('categories.show', ['category' => $category->id]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        abort_if ( Auth::user()->cannot('delete_categories'), 403 );

        if ( $category->id == 1 ) {
            return back()->withErrors(['"' . $category->name . '" is basic category and can not be removed.']);
        }
        
        if ( $category->countProducts() ) {

            // // перемещение содержащихся в категории товаров в каталог
            // foreach ( $category->products as $product ) {
            //     $product->update([
            //         'category_id' => 1,
            //     ]);
            // }

            //  перемещение содержащихся в категории товаров во временную категорию

            // запрет удаления категории
            return back()->withErrors(['Категория "' . $category->name . '" не может быть удалена, пока в ней находятся товары или подкатегории.']);
        }

        if ( $category->countChildren() ) {

            // // перемещение содержащихся в категории подкатегорий в каталог
            // foreach ( $category->products as $product ) {
            //     $product->update([
            //         'category_id' => 1,
            //     ]);
            // }

            //  перемещение содержащихся в категории подкатегорий во временную категорию

            // запрет удаления категории
            return back()->withErrors(['Категория "' . $category->name . '" не может быть удалена, пока в ней находятся товары или подкатегории.']);
        }

        $category->delete();

        // add email!

        // create action record
        $action = Action::create([
            'user_id' => auth()->user()->id,
            'type' => 'category',
            'type_id' => $category->id,
            'action' => 'delete',
            'description' => 
                'Удаление категории ' 
                . $category->name
                . '. Исполнитель: ' 
                . auth()->user()->name 
                . '.',
            // 'old_value' => $category->id,
            // 'new_value' => $category->id,
        ]);

        session()->flash('message', 'Category "' . $category->name . '" with id=' . $category->id . ' was successfully delete.');

        // return redirect()->route('categories.index');
        return back();
    }


    
    /**
     * Display a listing of the resource (all categories) for admin side. 
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex() {
        $categories = Category::all();
        return view('categories.adminindex', compact('categories'));
    }

    public function adminShow(Category $category) {
        return view('categories.adminshow', compact('category'));
    }

    public function massupdate(Category $category) {
        dd(request()->all());
        dd('эта функция пока не доступна');
    }

}
