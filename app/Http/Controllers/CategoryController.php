<?php

namespace App\Http\Controllers;

use App\{Category, Product};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends CustomController
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
        // $categories = Category::with('products') // whithout empty categories
        //     ->get()
        //     ->where('parent_id', '=', 1)
        //     ->where('seeable', '=', 'on')
        //     ->where('parent_seeable', '=', 'on') // getParentSeeableAttribute
        //     ->where('id', '>', 1)
        //     ->sortBy('sort_order');
        $categories = Category::all()
            ->where('parent_id', '=', 1)
            ->where('seeable', '=', 'on')
            ->where('parent_seeable', '=', 'on') // getParentSeeableAttribute
            ->where('id', '>', 1)
            ->sortBy('sort_order');
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
            'name'          => 'required|string|max:255',
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
            'title'             => request('title'), // 
            'slug'              => request('slug'), // 
            'description'       => request('description'),
            'imagepath'         => request('imagepath'),
            'parent_id'         => request('parent_id'),
            'sort_order'        => request('sort_order'),
            'seeable'           => request('seeable') ? true : false, // 
            'added_by_user_id'  => auth()->user()->id,
        ]);

        // $this->attachSingleImage($category, request('imagepath'));

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

        if ( $category->countChildren() ) {
            $categories = Category::all()
                ->where('parent_id', $category->id)
                ->where('seeable', '=', 'on')
                ->where('parent_seeable', '=', 'on') // getParentSeeableAttribute
                ->sortBy('sort_order');
            return view('categories.show', compact('category', 'categories'));

        } elseif ( $category->countProducts() ) {
            $products = Product::where('category_id', $category->id)
                ->where('seeable', '=', 'on')
                ->where('grandparent_seeable', '=', 'on')
                ->orderBy('price')
                ->paginate();
            // dd($products);
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
     * @param  \Illuminate\Http\Request  $request
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Category $category)
    {
        info(__METHOD__);
        abort_if( auth()->user()->cannot('edit_categories'), 403);

        request()->validate([
            'name'          => 'required|string|max:255',
            'title'         => 'nullable|string|max:255',
            'slug'          => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:65535',
            'imagepath'     => 'nullable|string|max:255',
            'sort_order'    => 'required|string|max:1',
            'seeable'       => 'nullable|string|in:on',
            'parent_id'     => 'required|integer|max:255',
        ]);

        $category->update([
            'name'              => request('name'),
            'slug'              => request('slug'), // depricated!
            'title'             => request('title'),
            'description'       => request('description'),
            'imagepath'         => request('imagepath'),
            'sort_order'        => request('sort_order'),
            'seeable'           => request('seeable'),
            'parent_id'         => request('parent_id'),
            'edited_by_user_id' => auth()->user()->id,
        ]);

        // $this->attachSingleImage($category, request('imagepath'));

        // $this->setSeeableChildren($category);

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

        // запрет удаления категории
        if ( $category->countProducts() or $category->countChildren() ) {
            return back()->withErrors(['Категория "' . $category->name . '" не может быть удалена, пока в ней находятся товары или подкатегории.']);
        }

        // $message = $this->createCustomevent($category, false, false, 'model_delete');
        $category->delete();
        
        // add email!

        // if ( $message ) {session()->flash('message', $message);}
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

    public function adminShow(Category $category) {
        $categories = Category::all();
        return view('dashboard.adminpanel.categories.adminshow', compact('categories', 'category'));
    }


    /**
     * Копирует файл изображения, загруженный с помощью laravel-filemanager в директорию категории
     * и обновляет запись в базе данных. 
     *
     * @return  string $imagepath
     */
    private function attachSingleImage (Category $category, $imagepath) {

        if ( !$imagepath ) {
            return TRUE;
        }

        // WORKAROUND #0... не совсем разобрался с тонкостями Filesystem
        $src = str_replace( config('filesystems.disks.lfm.url'), '', $imagepath );
        $dst_dir = 'images/categories/' . $category->id;
        $basename = basename($src);
        $dst = $dst_dir . '/' . $basename;

        // проверка на существование исходного файла. так как config('filesystems.disks.lfm.root') === config('filesystems.disks.public.root'), использую не 'Storage::disk(config('lfm.disk'))->exists($src)', а 'Storage::disk('public')->exists($src)'
        if ( !Storage::disk('public')->exists($src) ) {
            return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
        }

        // удаление всех файлов из директории назначения
        $arr_files = Storage::disk('public')->files($dst_dir);
        if ( $arr_files ) {
            if ( $arr_files = Storage::disk('public')->delete($arr_files) ) {
                // dd("all files in dir $dst_dir has been deleted");
            } else {
                return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
            }
        }

        // копирование файла
        if ( !Storage::disk('public')->copy($src, $dst) ) {
            return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
        }

        // update $category
        // if ( !($category->imagepath = $basename and $category->save()) ) {
        //     return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
        // }
        if ( !($category->update([ 'imagepath' => $basename ]) ) ) {
            return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
        }

        return TRUE;
    }



    /**
     * WORKAROUND #1 parent_seeable
     * устанавливает атрибуты потомков в соответствии с переданным значением
     * 
     * ПЕРЕДЕЛАТЬ! Добиться использования аксессоров в builder! 
     *
     * @return  void
     */
    private function setSeeableChildren (Category $category) {
        dd($category->isDirty);
        if ( $category->isDirty('seeable') ) {

            // for category top-level
            if ( $category->children->count() ) {
                foreach ( $category->children as $children_category ) {
                    $children_category->parent_seeable = $category->seeable;
                    $children_category->save();

                    if ( $children_category->products->count() ) {
                        foreach ( $children_category->products as $product ) {
                            $product->grandparent_seeable = $category->seeable;
                            $product->save();
                        };
                    }

                };

            // for subcategory
            } elseif ( $category->products->count() ) {
                foreach ( $category->products as $product ) {
                    $product->parent_seeable = $category->seeable;
                    $product->save();
                };
            }
        }
    }

}
