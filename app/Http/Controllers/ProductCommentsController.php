<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Product;
use Illuminate\Support\Facades\Validator;

class ProductCommentsController extends Controller
{
    public function index() { // testing deletion of product comments upon product removal
        return $comments = Comment::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product) {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer',
            'user_id' => 'required|integer',
            'guest_name' => 'nullable|string',
            'comment_string' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $comment = Comment::create([
            'product_id' => $product->id,
            'user_id' => request('user_id'),
            'guest_name' => request('guest_name'),
            'comment_string' => request('comment_string'),
        ]);

        // return back();
        return redirect('/products/' . $product->id . '#comment_' . $comment->id);
    }

    public function update(Comment $comment) {
        // dd($comment);
        // dd(request()->all());
        
        $validator = Validator::make($request->all(), [
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
        // dd($comment);
        $product_id = $comment->product_id;
        $comment->delete();

        return redirect()->route('productsShow', ['product' => $product_id]);
    }

}
