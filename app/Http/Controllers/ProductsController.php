<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\{Category, Http\Requests\ProductRequest, Manufacturer, Product};
use App\Jobs\RewatermarkJob;
use Artisan;
use Illuminate\View\View;

class ProductsController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show', 'search']);
    }

    /**
     * Display a listing of the resource for filters. Only for filters!
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function index(Request $request): RedirectResponse
    {
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
     * @return View
     */
    public function adminIndex(): View
    {
        $appends = request()->query->all();
        $products = Product::filter(request())->paginate();
        $categories = Category::all();

        return view('dashboard.adminpanel.products.adminindex', compact('appends', 'categories', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        abort_if ( !auth()->user()->can('create_products'), 403 );
        $categories = Category::all();
        $manufacturers = Manufacturer::all();

        return view('dashboard.adminpanel.products.create', compact('categories', 'manufacturers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return RedirectResponse
     */
    public function store(ProductRequest $request): RedirectResponse
    {
//        dd($request->validated());
        $fields = $request->validated();
        $imagePath = $fields['imagespath'];
        unset($fields['imagespath']);
        $product = Product::create($fields);
        $product->attachImages();

        return redirect()->route('categories.show', $product->category_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  Product $product
     * @return View
     */
    public function show(Product $product): View
    {
        abort_if( !$product->seeable or !$product->category_seeable or !$product->parent_category_seeable, 404);
        $product->incrementViews();

        return view('products.show', compact('product'));
    }

    /**
     * Display the specified resource for admin side.
     *
     * @param  Product $product
     * @return View
     */
    public function adminShow(Product $product): View
    {
        return view('dashboard.adminpanel.products.adminshow', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Product $product
     * @return View
     */
    public function edit(Product $product): View
    {
        abort_if (!auth()->user()->can('edit_products'), 403);
        $categories = Category::all();
        $manufacturers = Manufacturer::all();
        return view('dashboard.adminpanel.products.edit', compact('product', 'categories', 'manufacturers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Product $product
     * @return View
     */
    public function copy(Product $product): View
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
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(Product $product): RedirectResponse
    {
        abort_if ( auth()->user()->cannot('edit_products'), 403 );

        request()->validate([
            'name'              => 'required|max:255|unique:products,name,'.$product->id.',id',
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

        $product->attachImages();

        return redirect()->route('products.adminshow', $product->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Product $product): RedirectResponse
    {
        abort_if ( auth()->user()->cannot('delete_products'), 403 );

        $product->delete();

        // возврат на предыдущую страницу, если удаление было инициировано не со страницы товара
        if ( preg_match( '~products/[^/]+$~' , back()->headers->get('location') ) ) {
            return redirect()->route('products.adminindex');
        }

        return back();
    }

    /**
     * @return RedirectResponse
     */
    public function rewatermark(): RedirectResponse
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

    /**
     * @param Request $request
     * @return View
     */
    public function search(Request $request): View
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
     * @return RedirectResponse
     */
    public function massupdate(): RedirectResponse
    {
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
            $message = 'Операция не удалась или удалась неполностью.';
        } else {
            $message = 'Операция прошла успешно.';
        }

        session()->flash('message', $message);
        return back();
    }
}
