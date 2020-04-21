<?php

namespace App\Http\Controllers;

use App\{Category, Http\Requests\CategoryRequest, Product};
use Exception as ExceptionAlias;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * CategoryController constructor.
     */
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
     * @param CategoryRequest $request
     * @return RedirectResponse
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        $fields = $this->prepareFields($request->validated());
        Category::create($fields);
        return redirect()->route('categories.adminindex');
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return RedirectResponse|View
     */
    public function show(Category $category): View
    {
        abort_if ( !$category->isPublish(), 404 );

        if ( $category->id === 1 ) {
            return redirect()->route('categories.index');
        }

        if ( $category->subcategories->count() ) {
            $categories = Category::with(['parent', 'products', 'subcategories'])
                ->get()
                ->where('parent_id', $category->id)
                ->filter(static function (Category $value) {
                    return $value->hasDescendant() && $value->isPublish();
                })
                ->sortBy('sort_order');
            return view('categories.show', compact('category', 'categories'));
        }

        if ( $category->products->count() ) {
            $products = Product::where('category_id', $category->id)
                ->where('publish', '=', true)
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
     * @param CategoryRequest $request
     * @param Category $category
     * @return RedirectResponse
     */
    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $fields = $this->prepareFields($request->validated());
        $category->update($fields);
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
     * @param array $fields
     * @return array
     */
    private function prepareFields(array $fields): array
    {
        $fields['publish'] = $fields['publish'] ?? false;
        return $fields;
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
