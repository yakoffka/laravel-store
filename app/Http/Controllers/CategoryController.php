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

    
    public function list() {
        $categories = Category::all();

        return view('admin.categories.list', compact('categories'));
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

        if ( request()->file('image') ) {

            $image = request()->file('image');
            $directory = 'public/images/categories/' . $category->id;
            $filename = $image->getClientOriginalName();
    
            // if ( Storage::makeDirectory($directory) ) {
            //     if ( Storage::putFileAs($directory, $image, $filename )) {
            //         if ( $category->update('image' => $filename )) {
            //             return redirect()->route('category.show', ['category' => $category->id]);
            //         }
            //     }
            // }
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
            'action' => 'edit',
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

        return redirect()->route('categories.show', ['category' => $category->id]);
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
        
        if ( $category->products->count() ) {
            foreach ( $category->products as $product ) {
                $product->update([
                    'category_id' => 1,
                ]);
            }
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

        return redirect()->route('categories.index');
    }

}
