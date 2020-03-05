<?php

namespace App\Http\Controllers;

use App\{Category, Product};
use Exception as ExceptionAlias;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource (parent categories).
     *
     */
    public function index()
    {
        return view('categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
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
     * @return RedirectResponse
     */
    public function store(): RedirectResponse
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
            'seeable'           => request('seeable') === 'on',
        ]);

        return redirect()->route('categories.adminindex');
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return RedirectResponse|View
     */
    public function show(Category $category)
    {
        abort_if ( !$category->fullSeeable(), 404 );

        if ( $category->id === 1 ) {
            return redirect()->route('categories.index');
        }

        if ( $category->children->count() ) {
            $categories = Category::with(['parent', 'products', 'children'])
                ->get()
                ->where('parent_id', $category->id)
                ->filter(static function ($value, $key) {
                    return $value->hasDescendant() && $value->fullSeeable();
                })
                ->sortBy('sort_order');
            return view('categories.show', compact('category', 'categories'));

        }

        if ( $category->products->count() ) {
            // $categories = Category::with(['parent', 'products'])
            $products = Product::where('category_id', $category->id)
                ->where('seeable', '=', true)
                ->orderBy('price')
                ->paginate();
            return view('products.index', compact('category', 'products'));
        }

        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     * @param Category $category
     * @return View
     */
    public function edit(Category $category): View
    {
        abort_if (auth()->user()->cannot('edit_categories'), 403);
        $categories = Category::all();
        return view('dashboard.adminpanel.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Category $category
     * @return RedirectResponse
     */
    public function update(Category $category): RedirectResponse
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
            'seeable'           => request('seeable') === 'on',
        ]);

        return redirect()->route('categories.adminindex');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return RedirectResponse
     * @throws ExceptionAlias
     */
    public function destroy(Category $category): RedirectResponse
    {
        abort_if ( auth()->user()->cannot('delete_categories'), 403 );

        if ( $category->id === 1 ) {
            return back()->withErrors(['"' . $category->name . '" is basic category and can not be removed.']);
        }

        // запрет удаления непустой категории
        if ( $category->hasDescendant() ) {
            return back()->withErrors(['Категория "' . $category->name . '" не может быть удалена, пока в ней находятся товары или подкатегории.']);
        }

        $category->delete();
        return redirect()->route('categories.adminindex');
    }

    /**
     * Display a listing of the resource (all categories) for admin side.
     *
     * @return View
     */
    public function adminIndex(): View
    {
        $categories = Category::all();
        return view('dashboard.adminpanel.categories.adminindex', compact('categories'));
    }

    /**
     * Display the specified resource for admin side.
     *
     * @param  Category $category
     * @return View
     */
    public function adminShow(Category $category): View
    {
        $categories = Category::all();
        return view('dashboard.adminpanel.categories.adminshow', compact('categories', 'category'));
    }
}
