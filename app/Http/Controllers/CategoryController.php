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
        $categories = Category::all()
            ->where('parent_id', '=', 1)
            ->where('visible', '=', true)
            ->where('parent_visible', '=', true) // getParentVisibleAttribute
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
            'description'   => 'nullable|string|max:255',
            'imagepath'     => 'nullable|string',
            'parent_id'     => 'required|integer|max:255',
            'sort_order'    => 'required|string|max:1',
            'visible'       => 'nullable|string|in:on',
        ]);

        $category = new Category;

        $category->name              = request('name');
        $category->title             = request('title') ?? request('name');
        $category->slug              = request('slug') ?? Str::slug(request('title'), '-');
        $category->description       = request('description');
        $category->parent_id         = request('parent_id');
        $category->sort_order        = request('sort_order');
        $category->visible           = request('visible') ? true : false;
        $category->added_by_user_id  = auth()->user()->id;

        $dirty_properties = $category->getDirty();

        if ( !$category->save() ){ return back()->withErrors(['something wrong. err' . __line__])->withInput();};

        $dirty_properties['image'] = $this->attachImage($category, request('imagepath'), $dirty_properties);

        // add email!

        $description = $this->createAction($category, $dirty_properties, false, 'model_create');

        if ( $description ) {session()->flash('message', __('SuccessOperationMessage'));}

        return redirect()->route('categories.adminindex');
    }


    /**
     * Display the specified resource.
     *
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category) {
        abort_if( !$category->visible or !$category->parent_visible, 404);
        if ( $category->id == 1 ) { return redirect()->route('categories.index'); }

        if ( $category->countChildren() ) {
            $categories = Category::all()
                ->where('parent_id', $category->id)
                ->where('visible', '=', true)
                ->where('parent_visible', '=', true) // getParentVisibleAttribute
                ->sortBy('sort_order');
            return view('categories.show', compact('category', 'categories'));

        } elseif ( $category->countProducts() ) {
            $products = Product::where('category_id', $category->id)
                ->where('visible', '=', 1)
                ->where('depricated_grandparent_visible', '=', 1)
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
     * @param  \Illuminate\Http\Request  $request
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Category $category)
    {
        abort_if( auth()->user()->cannot('edit_categories'), 403);

        request()->validate([
            'name'          => 'required|string|max:255',
            'title'         => 'nullable|string|max:255',
            'slug'          => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:255',
            'imagepath'     => 'nullable|string',
            'sort_order'    => 'required|string|max:1',
            'visible'       => 'nullable|string|in:on',
            'parent_id'     => 'required|integer|max:255',
        ]);

        $category->name              = request('name');
        $category->slug              = request('slug');
        $category->title             = request('title');
        $category->description       = request('description');
        $category->sort_order        = request('sort_order');
        $category->visible           = request('visible') ? true : false;
        $category->parent_id         = request('parent_id');
        $category->edited_by_user_id = auth()->user()->id;

        $dirty_properties = $category->getDirty();
        $original = $category->getOriginal();

        if ( !$category->save() ){ return back()->withErrors(['something wrong. err' . __line__])->withInput();};

        $dirty_properties['image'] = $this->attachImage($category, request('imagepath'), $dirty_properties);

        $this->setVisibleChildren($category, $dirty_properties);

        // add email!

        $description = $this->createAction($category, $dirty_properties, $original, 'model_update');

        if ( $description ) {session()->flash('message', __('SuccessOperationMessage'));}

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

        if ( $category->id == 1 ) {
            return back()->withErrors(['"' . $category->name . '" is basic category and can not be removed.']);
        }

        // запрет удаления категории
        if ( $category->countProducts() or $category->countChildren() ) {
            return back()->withErrors(['Категория "' . $category->name . '" не может быть удалена, пока в ней находятся товары или подкатегории.']);
        }

        $description = $this->createAction($category, false, false, 'model_delete');

        $category->delete();
        
        // add email!

        if ( $description ) {session()->flash('message', __('SuccessOperationMessage'));}

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
    private function attachImage (Category $category, $imagepath, $dirty_properties) {

        if ( !$imagepath ) {
            return $dirty_properties;
        }

        // WORKAROUND #0... не совсем разобрался с тонкостями Filesystem
        $src = str_replace( config('filesystems.disks.lfm.url'), '', $imagepath );
        $dst_dir = 'images/categories/' . $category->id;
        $basename = basename($src);
        $dst = $dst_dir . '/' . $basename;

        // проверка на существование исходного файла. так как config('filesystems.disks.lfm.root') === config('filesystems.disks.public.root'), использую не 'Storage::disk(config('lfm.disk'))->exists($src)', а 'Storage::disk('public')->exists($src)'
        if ( !Storage::disk('public')->exists($src) ) {
            return back()->withErrors(['something wrong. err' . __line__])->withInput();
        }

        // удаление всех файлов из директории назначения
        $arr_files = Storage::disk('public')->files($dst_dir);
        if ( $arr_files ) {
            if ( $arr_files = Storage::disk('public')->delete($arr_files) ) {
                // dd("all files in dir $dst_dir has been deleted");
            } else {
                return back()->withErrors(['something wrong. err' . __line__])->withInput();
            }
        }

        // копирование файла
        if ( !Storage::disk('public')->copy($src, $dst) ) {
            return back()->withErrors(['something wrong. err' . __line__])->withInput();
        }

        // update $category
        if ( !($category->image = $basename and $category->save()) ) {
            return back()->withErrors(['something wrong. err' . __line__])->withInput();
        }

        return $dst;
    }



    /**
     * WORKAROUND #1 depricated_parent_visible
     * устанавливает атрибуты потомков в соответствии с переданным значением
     * 
     * ПЕРЕДЕЛАТЬ! Добиться использования аксессоров в builder! 
     *
     * @return  void
     */
    private function setVisibleChildren (Category $category, $dirty_properties) {
        if ( array_key_exists('visible', $dirty_properties) ) {

            // for category top-level
            if ( $category->children->count() ) {
                foreach ( $category->children as $children_category ) {
                    $children_category->depricated_parent_visible = $category->visible;
                    $children_category->save();

                    if ( $children_category->products->count() ) {
                        foreach ( $children_category->products as $product ) {
                            $product->depricated_grandparent_visible = $category->visible;
                            $product->save();
                        };
                    }

                };

            // for subcategory
            } elseif ( $category->products->count() ) {
                foreach ( $category->products as $product ) {
                    $product->depricated_parent_visible = $category->visible;
                    $product->save();
                };
            }
        }
    }

}
