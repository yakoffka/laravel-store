<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Image;
use Exception;
use Illuminate\Http\RedirectResponse;

class ImagesController extends Controller
{
    /**
     * ImagesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Image $image
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Image $image): RedirectResponse
    {
        abort_if (auth()->user()->cannot('edit_products'), 403);
        $image->delete();
        return redirect()->back();
    }

    /**
     * @param ImageRequest $request
     * @param Image $image
     * @return RedirectResponse
     */
    public function update(ImageRequest $request, Image $image): RedirectResponse
    {
        $image->update($request->validated());
        return redirect()->back();
    }
}
