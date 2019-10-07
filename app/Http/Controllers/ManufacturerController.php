<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;
use App\{Category, Manufacturer};

class ManufacturerController extends CustomController
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
            'title'             => 'nullable|string|max:65535', // ?
        ]);

        $manufacturer = new Manufacturer;

        $manufacturer->name = request('name');
        $manufacturer->description = request('description');
        $manufacturer->sort_order = request('sort_order');
        $manufacturer->title = request('title');
        $manufacturer->slug = Str::slug(request('title') ?? request('name'), '-');
        $manufacturer->added_by_user_id = auth()->user()->id;

        $dirty_properties = $manufacturer->getDirty();

        if ( !$manufacturer->save() ) {
            return back()->withErrors(['something wrong! Err#' . __LINE__])->withInput();
        }

        $dirty_properties = $this->attachSingleImage($manufacturer, request('imagepath'), $dirty_properties);

        $description = $this->createAction($manufacturer, $dirty_properties, false, 'model_create');

        if ( $description ) {session()->flash('message', $description);}

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
            'title'             => 'nullable|string|max:65535', // ?
        ]);

        $manufacturer->name = request('name');
        $manufacturer->description = request('description');
        $manufacturer->sort_order = request('sort_order');
        $manufacturer->title = request('title');
        $manufacturer->slug = Str::slug(request('title') ?? request('name'), '-');
        $manufacturer->edited_by_user_id = auth()->user()->id;

        $dirty_properties = $manufacturer->getDirty();
        $original = $manufacturer->getOriginal();

        if ( !$manufacturer->save() ) {
            return back()->withErrors(['something wrong! Err#' . __LINE__])->withInput();
        }

        $dirty_properties = $this->attachSingleImage($manufacturer, request('imagepath'), $dirty_properties);

        $description = $this->createAction($manufacturer, $dirty_properties, $original, 'model_update');

        if ( $description ) {session()->flash('message', $description);}

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

        $description = $this->createAction($manufacturer, false, false, 'model_delete');

        $manufacturer->delete();

        session()->flash('message', $description);

        return redirect()->route('manufacturers.index');


    }




    /**
     * Копирует файл изображения, загруженный с помощью laravel-filemanager в директорию производителя
     * и обновляет запись в базе данных. 
     *
     * @return  array $dirty_properties
     */
    private function attachSingleImage (Manufacturer $manufacturer, $imagepath, $dirty_properties) {

        // dd($imagepath);
        if ( !$imagepath ) {
            return $dirty_properties;
        }

        // WORKAROUND #0... не совсем разобрался с тонкостями Filesystem
        $src = str_replace( config('filesystems.disks.lfm.url'), '', $imagepath );
        $dst_dir = 'images/manufacturers/' . $manufacturer->id;
        $basename = basename($src);
        $dst = $dst_dir . '/' . $basename;

        // проверка на существование исходного файла. так как config('filesystems.disks.lfm.root') === config('filesystems.disks.public.root'), использую не 'Storage::disk(config('lfm.disk'))->exists($src)', а 'Storage::disk('public')->exists($src)'
        if ( !Storage::disk('public')->exists($src) ) {
            return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
        }

        // удаление всех файлов из директории назначения
        $arr_files = Storage::disk('public')->files($dst_dir);
        if ( $arr_files ) {
            if ( !$arr_files = Storage::disk('public')->delete($arr_files) ) {
                return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
            }
        }

        // копирование файла
        if ( !Storage::disk('public')->copy($src, $dst) ) {
            return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
        }

        // update $manufacturer
        if ( !($manufacturer->imagepath = $basename and $manufacturer->save()) ) {
            return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
        }

        $dirty_properties['imagepath'] = $dst;
        return $dirty_properties;
    }

}
