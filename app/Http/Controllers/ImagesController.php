<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Auth;

class ImagesController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }

    public function destroy(Image $image)
    {
        abort_if (Auth::user()->cannot('edit_products'), 403);
        $image->delete();
        return redirect()->back();
    }

    public function update(Image $image)
    {
        abort_if (Auth::user()->cannot('edit_products'), 403);

        $validator = request()->validate([
            'sort_order' => 'required|integer|min:1|max:9',
        ]);

        $image->update([
            'sort_order' => request('sort_order'),
        ]);

        return redirect()->back();

    }
}
