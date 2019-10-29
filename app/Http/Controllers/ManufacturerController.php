<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Str;
use App\{Category, Manufacturer};

class ManufacturerController extends Controller
{
    public function __construct() {$this->middleware(['auth', 'permission:view_manufacturers']);}


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $manufacturers = Manufacturer::all();
        return view('dashboard.adminpanel.manufacturers.index', compact('manufacturers'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if ( auth()->user()->cannot('create_manufacturers'), 403 );
        return view('dashboard.adminpanel.manufacturers.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function store(Manufacturer $manufacturer)
    {
        abort_if ( auth()->user()->cannot('create_manufacturers'), 403 );

        request()->validate([
            'name'              => 'required|max:255|unique:manufacturers,name',
            'description'       => 'nullable|string',
            'imagepath'         => 'nullable|string',
            'sort_order'        => 'required|string|max:1',
            'title'             => 'nullable|string', // ?
        ]);

        $manufacturer = Manufacturer::create([
            'name'              => request('name'),
            'description'       => request('description'),
            'imagepath'         => request('imagepath'),
            'sort_order'        => request('sort_order'),
            'title'             => request('title'),
        ]);

        return redirect()->route('manufacturers.index');
    }


    /**
     * Display the specified resource.
     *
     * @param Manufacturer $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function show(Manufacturer $manufacturer)
    {
        $categories = Category::all();
        return view('dashboard.adminpanel.manufacturers.show', compact('manufacturer', 'categories'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Manufacturer $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function edit(Manufacturer $manufacturer)
    {
        abort_if ( auth()->user()->cannot('edit_manufacturers'), 403 );
        return view('dashboard.adminpanel.manufacturers.edit', compact('manufacturer'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Manufacturer $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function update(Manufacturer $manufacturer)
    {
        abort_if ( auth()->user()->cannot('edit_manufacturers'), 403 );

        request()->validate([
            'name'              => 'required|max:255', // unique?
            'description'       => 'nullable|string',
            'imagepath'         => 'nullable|string',
            'sort_order'        => 'required|string|max:1',
            'title'             => 'nullable|string',
        ]);

        $manufacturer->update([
            'name' => request('name'),
            'description' => request('description'),
            'sort_order' => request('sort_order'),
            'title' => request('title'),
        ]);

        return redirect()->route('manufacturers.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Manufacturer $manufacturer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Manufacturer $manufacturer)
    {
        abort_if ( auth()->user()->cannot('delete_manufacturers'), 403 );

        if ($manufacturer->imagepath) {
            $directory = 'public/images/manufacturers/' . $manufacturer->id;
            Storage::deleteDirectory($directory);
        }

        // ADD DELETE PRODUCT EMAIL!

        // $message = $this->createCustomevent($manufacturer, false, false, 'model_delete');
        $manufacturer->delete();

        // if ( $message ) {session()->flash('message', $message);}
        return redirect()->route('manufacturers.index');
    }
}
