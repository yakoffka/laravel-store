<?php

namespace App\Http\Controllers;

use App\{Comment, Product};
use Auth;

class ProductCommentsController extends CustomController
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

        if ( Auth::user() ) {
            request()->validate([
                'body' => 'required|string',
            ]);
        } else {
            request()->validate([
                'user_name' => 'required|string',
                'body' => 'required|string',
            ]);
        }

        $comment = Comment::create([
            'product_id' => $product->id,
            'user_id' => Auth::user() ? Auth::user()->id : 0,
            'user_name' => Auth::user() ? Auth::user()->name : request('user_name'),
            'body' => request('body'),
        ]);

        return redirect('/products/' . $product->id . '#comment_' . $comment->id);
    }


    public function update(Comment $comment) {
        abort_if ( Auth::user()->cannot('edit_comments') and Auth::user()->id !== $comment->user_id, 403 );

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
        abort_if ( Auth::user()->cannot('delete_comments'), 403 );
        $comment->delete();
        return redirect()->route('products.show', ['product' => $comment->product_id]);
    }

}
