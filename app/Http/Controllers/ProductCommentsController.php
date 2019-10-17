<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Product;
use Illuminate\Support\Facades\Validator;
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
            $validator = Validator::make(request()->all(), [
                'body' => 'required|string',
            ]);
        } else {
            $validator = Validator::make(request()->all(), [
                'user_name' => 'nullable|string',
                'body' => 'required|string',
            ]);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $body = str_replace(["\r\n", "\r", "\n"], '<br>', request('body'));

        // $comment = Comment::create([
        //     'product_id' => $product->id,
        //     'user_id' => Auth::user() ? Auth::user()->id : 0,
        //     'user_name' => Auth::user() ? Auth::user()->name : request('user_name'),
        //     'body' => $body,
        // ]);
        $comment = new Comment;
            $comment->product_id = $product->id;
            $comment->user_id = Auth::user() ? Auth::user()->id : 0;
            $comment->user_name = Auth::user() ? Auth::user()->name : request('user_name');
            $comment->body = $body;

        $dirty_properties = $comment->getDirty();

        if ( !$comment->save() ) {
            return back()->withErrors(['something wrong! Err#' . __LINE__])->withInput();
        }

        $message = $this->createEvents($comment, $dirty_properties, false, 'model_create');
        if ( $message ) {session()->flash('message', $message);}
        return redirect('/products/' . $product->id . '#comment_' . $comment->id);
    }

    public function update(Comment $comment) {
        abort_if ( Auth::user()->cannot('edit_comments') and Auth::user()->id !== $comment->user_id, 403 );

        $validator = Validator::make(request()->all(), [
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $body = str_replace(["\r\n", "\r", "\n"], '<br>', request('body'));
        $comment->body = $body;

        $dirty_properties = $comment->getDirty();
        $original = $comment->getOriginal();
        // dd($dirty_properties, $original);
        if ( !$comment->save() ) {
            return back()->withErrors(['something wrong! Err#' . __LINE__])->withInput();
        }
        $message = $this->createEvents($comment, $dirty_properties, $original, 'model_update');
        if ( $message ) {session()->flash('message', $message);}
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
        $message = $this->createEvents($comment, false, false, 'model_delete');
        $comment->delete();
        if ( $message ) {session()->flash('message', $message);}
        return redirect()->route('products.show', ['product' => $comment->product_id]);
    }

}
