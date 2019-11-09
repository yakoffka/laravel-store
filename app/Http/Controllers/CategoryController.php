<?php

namespace App\Http\Controllers;

use App\{Category, Product};

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }


    /**
     * Display a listing of the resource (parent categories). 
     * only catalog 
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        // // old (Queries 54)
        // $categories = Category::all()
        //     ->where('parent_id', '=', 1)
        //     ->where('seeable', '=', 'on')
        //     ->where('parent_seeable', '=', 'on') // getParentSeeableAttribute
        //     ->where('id', '>', 1)
        //     ->sortBy('sort_order');

        // // excluding parent_seeable  (Queries 44)
        // $categories = Category::all()
        //     ->where('parent_id', '=', 1)
        //     ->where('seeable', '=', 'on')
        //     // ->where('parent_seeable', '=', 'on') // getParentSeeableAttribute
        //     ->where('id', '>', 1)
        //     ->sortBy('sort_order');

        // eager loading (Queries 45)
        $categories = Category::with('children', 'products')
            ->get()
            ->where('parent_id', '=', 1)
            ->where('seeable', '=', 'on')
            ->where('id', '>', 1)
            ->sortBy('sort_order');

        // dd($categories);
        return view('categories.index', compact('categories'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if( auth()->user()->cannot('create_categories'), 403);
        $categories = Category::all();
        return view('dashboard.adminpanel.categories.create', compact('categories'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        abort_if( auth()->user()->cannot('create_categories'), 403);

        request()->validate([
            'name'          => 'required|string|max:255|unique:categories,name',
            'title'         => 'nullable|string|max:255',
            'slug'          => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:65535',
            'imagepath'     => 'nullable|string|max:255',
            'parent_id'     => 'required|integer|max:255',
            'sort_order'    => 'required|string|max:1',
            'seeable'       => 'nullable|string|in:on',
        ]);

        $category = Category::create([
            'name'              => request('name'),
            'title'             => request('title'),
            'slug'              => request('slug'),
            'description'       => request('description'),
            'imagepath'         => request('imagepath'),
            'parent_id'         => request('parent_id'),
            'sort_order'        => request('sort_order'),
            'seeable'           => request('seeable'),
        ]);

        return redirect()->route('categories.adminindex');
    }


    /**
     * Display the specified resource.
     *
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category) {
        abort_if( !$category->seeable or !$category->parent_seeable, 404);
        if ( $category->id === 1 ) { return redirect()->route('categories.index'); }

        if ( $category->children->count() ) {
            $categories = Category::all()
                ->where('parent_id', $category->id)
                ->where('seeable', '=', 'on')
                ->where('parent_seeable', '=', 'on') // getParentSeeableAttribute
                ->sortBy('sort_order');
            return view('categories.show', compact('category', 'categories'));

        } elseif ( $category->products->count() ) {
            $products = Product::where('category_id', $category->id)
                ->where('seeable', '=', 'on')
                ->orderBy('price')
                ->paginate();
            return view('products.index', compact('category', 'products'));
        }

        abort(404);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        abort_if (auth()->user()->cannot('edit_categories'), 403);
        $categories = Category::all();
        return view('dashboard.adminpanel.categories.edit', compact('category', 'categories'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Category $category)
    {
        abort_if( auth()->user()->cannot('edit_categories'), 403);

        request()->validate([
            'name'          => 'required|string|max:255|unique:categories,name,'.$category->id.',id',
            'title'         => 'nullable|string|max:255',
            'slug'          => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:65535',
            'imagepath'     => 'nullable|string|max:255',
            'parent_id'     => 'required|integer|max:255',
            'sort_order'    => 'required|string|max:1',
            'seeable'       => 'nullable|string|in:on',
        ]);

        $category->update([
            'name'              => request('name'),
            'slug'              => request('slug'),
            'title'             => request('title'),
            'description'       => request('description'),
            'imagepath'         => request('imagepath'),
            'parent_id'         => request('parent_id'),
            'sort_order'        => request('sort_order'),
            'seeable'           => request('seeable'),
        ]);

        return redirect()->route('categories.adminindex');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        abort_if ( auth()->user()->cannot('delete_categories'), 403 );

        if ( $category->id === 1 ) {
            return back()->withErrors(['"' . $category->name . '" is basic category and can not be removed.']);
        }

        // запрет удаления непустой категории
        if ( $category->products->count() or $category->children->count() ) {
            return back()->withErrors(['Категория "' . $category->name . '" не может быть удалена, пока в ней находятся товары или подкатегории.']);
        }

        $category->delete();
        return redirect()->route('categories.adminindex');
    }


    /**
     * Display a listing of the resource (all categories) for admin side. 
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex() {
        $categories = Category::all();
        return view('dashboard.adminpanel.categories.adminindex', compact('categories'));
    }


    /**
     * Display the specified resource for admin side.
     *
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function adminShow(Category $category) {
        $categories = Category::all();
        return view('dashboard.adminpanel.categories.adminshow', compact('categories', 'category'));
    }

}
