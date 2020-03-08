<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\{Category, Http\Requests\ManufacturerRequest, Manufacturer};
use Illuminate\View\View;

class ManufacturerController extends Controller
{
    /**
     * ManufacturerController constructor.
     */
    public function __construct() {
        $this->middleware(['auth', 'permission:view_manufacturers']);
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View
     */
    public function index(): View
    {
        $manufacturers = Manufacturer::all();
        return view('dashboard.adminpanel.manufacturers.index', compact('manufacturers'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Factory|View
     */
    public function create(): View
    {
        abort_if ( auth()->user()->cannot('create_manufacturers'), 403 );
        return view('dashboard.adminpanel.manufacturers.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param ManufacturerRequest $request
     * @return RedirectResponse
     */
    public function store(ManufacturerRequest $request): RedirectResponse
    {
        Manufacturer::create($request->validated());
        return redirect()->route('manufacturers.index');
    }

    /**
     * @param Manufacturer $manufacturer
     * @return Factory|View
     */
    public function show(Manufacturer $manufacturer)
    {
        $categories = Category::all();
        return view('dashboard.adminpanel.manufacturers.show', compact('manufacturer', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param Manufacturer $manufacturer
     * @return Factory|View
     */
    public function edit(Manufacturer $manufacturer): View
    {
        abort_if ( auth()->user()->cannot('edit_manufacturers'), 403 );
        return view('dashboard.adminpanel.manufacturers.edit', compact('manufacturer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ManufacturerRequest $request
     * @param Manufacturer $manufacturer
     * @return RedirectResponse
     */
    public function update(ManufacturerRequest $request, Manufacturer $manufacturer)
    {
        $manufacturer->update($request->validated());
        return redirect()->route('manufacturers.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param Manufacturer $manufacturer
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Manufacturer $manufacturer): RedirectResponse
    {
        abort_if ( auth()->user()->cannot('delete_manufacturers'), 403 );

        if ($manufacturer->imagepath) {
            $directory = 'public/images/manufacturers/' . $manufacturer->id;
            Storage::deleteDirectory($directory);
        }

        // product to noname or only empty manufacturer!

        $manufacturer->delete();

        return redirect()->route('manufacturers.index');
    }
}
