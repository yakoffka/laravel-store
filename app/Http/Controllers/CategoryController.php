<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Storage;
use Auth;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['index', 'show']);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $categories = Category::paginate(6);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if( Auth::user()->cannot('create_categories'), 403);
        $categories = Category::all();
        return view('categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if( Auth::user()->cannot('create_categories'), 403);
        $arrToValidate = [
            'name'          => 'required|string|max:255|unique',
            'title'         => 'required|string|max:255|unique',
            'description'   => 'string|max:255',
            'image'         => 'image',
            'show'          => 'required|boolean',
            'parent_id'     => 'required|integer|max:255',
        ];

        $category = Category::create([
            'name'            => request('name'),
            'title'           => request('title'),
            'description'     => request('description'),
            'show'            => request('show'),
            'parent_id'       => request('parent_id'),
            'added_by_user_id' => Auth::user()->id,
        ]);

        if ( request()->file('image') ) {

            $image = request()->file('image');
            $directory = 'public/images/categories/' . $category->id;
            $filename = $image->getClientOriginalName();
    
            if ( !Storage::makeDirectory($directory)
                or !Storage::putFileAs($directory, $image, $filename )
                or !$category->update(['image' => $filename])
            ) {
                return back()->withErrors(['something wrong. err' . __line__])->withInput();
            }
        }

        return redirect()->route('categories.show', ['category' => $category->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category) {
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        abort_if (Auth::user()->cannot('edit_categories'), 403);
        $categories = Category::all();
        return view('categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Category $category)
    {
        abort_if( Auth::user()->cannot('edit_categories'), 403);

        $validator = request()->validate([
            'name'          => 'required|string|max:255',
            'title'         => 'required|string|max:255',
            'description'   => 'string|max:255',
            'image'         => 'image',
            'show'          => 'required|boolean',
            'parent_id'     => 'required|integer|max:255',
        ]);

        $category->update([
            'name'              => request('name'),
            'title'             => request('title'),
            'description'       => request('description'),
            'show'              => request('show'),
            'parent_id'         => request('parent_id'),
            'edited_by_user_id' => Auth::user()->id,
        ]);

        if ( request()->file('image') ) {

            $image = request()->file('image');
            $directory = 'public/images/categories/' . $category->id;
            $filename = $image->getClientOriginalName();
    
            // if ( Storage::makeDirectory($directory) ) {
            //     if ( Storage::putFileAs($directory, $image, $filename )) {
            //         if ( $category->update('image' => $filename )) {
            //             return redirect()->route('category.show', ['category' => $category->id]);
            //         }
            //     }
            // }
            if ( !Storage::makeDirectory($directory)
                or !Storage::putFileAs($directory, $image, $filename )
                or !$category->update(['image' => $filename])
            ) {
                return back()->withErrors(['something wrong. err' . __line__])->withInput();
            }
        }

        return redirect()->route('categories.show', ['category' => $category->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
