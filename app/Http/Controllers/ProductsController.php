<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\{Category, Http\Requests\ProductRequest, Manufacturer, Product};
use App\Jobs\RewatermarkJob;
use Artisan;
use Illuminate\View\View;


/**
 * Class ProductsController
 * @package App\Http\Controllers
 */
class ProductsController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show', 'search']);
    }

    /**
     * Display a listing of the resource with filters. Only for FILTERS!
     *
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function index(Request $request)
    {
        if ( $request->query->count() === 0 ){
            return redirect()->route('categories.index');
        }

        $appends = $request->query->all();
        $array_seeable_categories = Category::with('parent')
            ->get()
            ->filter(static function (Category $value) {
                return $value->hasDescendant() && $value->fullSeeable();
            })
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
        $products = Product::with(['category', 'images'])
            ->paginate(config('custom.pagination_product_admin'));
        $categories = Category::with(['children', 'products'])->get();

        return view('dashboard.adminpanel.products.adminindex', compact('appends', 'categories', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        abort_if (!auth()->user()->can('create_products'), 403);
        $actions = [
            'type' => 'create',
            'action' => route('products.store'),
            'method' => 'POST'
        ];
        $manufacturers = Manufacturer::all();
        $catalog = Category::where('parent_id', null)
            ->with('childrenCategories')
            ->get();

        return view('dashboard.adminpanel.products.create_copy_edit',
            compact('actions', 'manufacturers', 'catalog'));    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return RedirectResponse
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $fields = $this->prepareFields($request->validated());
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
        abort_if( !$product->isAllVisible(), 404);
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
        $actions = [
            'type' => 'edit',
            'action' => route('products.update', ['product' => $product->id]),
            'method' => 'PATCH'
        ];
        $manufacturers = Manufacturer::all();
        $catalog = Category::where('parent_id', null)
            ->with('childrenCategories')
            ->get();

        return view('dashboard.adminpanel.products.create_copy_edit',
            compact('actions', 'product', 'manufacturers', 'catalog'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Product $product
     * @return View
     */
    public function copy(Product $product): View
    {
        abort_if (!auth()->user()->can('create_products'), 403);
        $actions = [
            'type' => 'copy',
            'action' => route('products.store'),
            'method' => 'POST'
        ];
        $manufacturers = Manufacturer::all();
        $catalog = Category::where('parent_id', null)
            ->with('childrenCategories')
            ->get();
        session()->flash('message', 'When copying an item, you must change its name!');

        return view('dashboard.adminpanel.products.create_copy_edit',
            compact('actions', 'product', 'manufacturers', 'catalog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param Product $product
     * @return RedirectResponse
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $fields = $this->prepareFields($request->validated());
        $product->update($fields);
        $product->attachImages();

        return redirect()->route('categories.adminshow', $product->category_id);
    }

    /**
     * @param array $fields
     * @return array
     */
    private function prepareFields(array $fields): array
    {
        unset(
            $fields['imagespath'],
            $fields['copy_img'],
        );
        $fields['seeable'] = $fields['seeable'] ?? '';

        return $fields;
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

        return view('products.index', compact(['query', 'products', 'appends']));
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
