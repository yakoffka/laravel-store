<?php

namespace App\Http\Controllers;

use App\{Comment, Product};
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class ProductCommentsController extends Controller
{
    /**
     * ProductCommentsController constructor.
     */
    public function __construct() {
        $this->middleware('auth')->except(['store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Product $product
     * @return Redirector|RedirectResponse
     */
    public function store(Product $product): RedirectResponse
    {
        request()->validate([
            'user_name' => 'string',
            'body' => 'required|string',
        ]);

        $comment = Comment::create([
            'product_id' => $product->id,
            'body' => request('body'),
        ]);

        return redirect('/products/' . $product->id . '#comment_' . $comment->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Comment $comment
     * @return Redirector|RedirectResponse
     */
    public function update(Comment $comment): RedirectResponse
    {
        abort_if ( auth()->user()->cannot('edit_comments') and auth()->user()->id !== $comment->user_id, 403 );

        request()->validate([ 'body' => 'required|string', ]);

        if ( !$comment->update(['body' => request('body')]) ) {
            return back()->withErrors(['something wrong! Err#' . __LINE__])->withInput();
        }

        return redirect('/products/' . $comment->product_id . '#comment_' . $comment->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Comment $comment
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        abort_if ( auth()->user()->cannot('delete_comments'), 403 );
        $comment->delete();
        return redirect()->route('products.show', ['product' => $comment->product_id]);
    }
}
