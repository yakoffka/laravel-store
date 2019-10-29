<?php

namespace App\Http\Controllers;

use App\{Comment, Product};

class ProductCommentsController extends Controller
{
    public function __construct() {
        $this->middleware('auth')->except(['store']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product) {

        // if ( auth()->user() ) {
        //     request()->validate([
        //         'body' => 'required|string',
        //     ]);
        // } else {
        //     request()->validate([
        //         'user_name' => 'required|string',
        //         'body' => 'required|string',
        //     ]);
        // }
        request()->validate([
            'user_name' => 'string',
            'body' => 'required|string',
        ]);

        $comment = Comment::create([
            'product_id' => $product->id,
            'user_id' => auth()->user() ? auth()->user()->id : 0,
            'user_name' => auth()->user() ? auth()->user()->name : request('user_name'),
            'body' => request('body'),
        ]);

        return redirect('/products/' . $product->id . '#comment_' . $comment->id);
    }


    public function update(Comment $comment) {
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
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        abort_if ( auth()->user()->cannot('delete_comments'), 403 );
        $comment->delete();
        return redirect()->route('products.show', ['product' => $comment->product_id]);
    }

}
