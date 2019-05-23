<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Product;
use Illuminate\Support\Facades\Validator;
use Auth;

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
        if ( Auth::user() ) {
            $validator = Validator::make(request()->all(), [
                'comment_string' => 'required|string',
            ]);
        } else {
            $validator = Validator::make(request()->all(), [
                'user_name' => 'nullable|string',
                'comment_string' => 'required|string',
            ]);    
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $comment = Comment::create([
            'product_id' => $product->id,
            'user_id' => Auth::user() ? Auth::user()->id : 0,
            'user_name' => Auth::user() ? Auth::user()->name : request('user_name'),
            'comment_string' => request('comment_string'),
        ]);

        return redirect('/products/' . $product->id . '#comment_' . $comment->id);
    }

    public function update(Comment $comment) {
        abort_if ( Auth::user()->cannot('edit_comments'), 403 );

        $validator = Validator::make(request()->all(), [
            'comment_string' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $comment->update([
            'comment_string' => request('comment_string'),
        ]);

        // return redirect()->route('productsShow', ['product' => $comment->product_id]);
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
        return redirect()->route('productsShow', ['product' => $comment->product_id]);
    }

}
