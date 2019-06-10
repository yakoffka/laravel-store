<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Product;
use App\Cart;
use App\Order;

class CartController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  request()
     * @return \Illuminate\Http\Response
     */
    public function store(Cart $cart)
    {
        // abort_if ( Auth::user()->cannot('create_products'), 403 );
        return redirect()->route('products.show', ['product' => $product->id]);
    }

    /**
     * 
     *
     * 
     */
    public function confirmation()
    {
        $cart = Session::has('cart') ? Session::get('cart') : '';
        abort_if ( !$cart, 404 );
        return view('cart.confirmation', compact('cart'));
    }

        /**
         * Add to cart the specified resource.
         *
         * @param  Product $product
         * @return 
         */
    public function addToCart(Product $product)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product);
        session(['cart' => $cart]);

        // return redirect()->route('products.index');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $cart = Session::has('cart') ? Session::get('cart') : '';
        return view('cart.show', compact('cart'));
    }

    /**
     * Remove the specified resource from cart.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function deleteItem(Product $product)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->remove($product);
        session(['cart' => $cart]);
        $success = 'item is deleted from youre cart';
        // return view('cart.index', compact('cart', 'success'));
        return redirect()->route('cart.show'); // $success!!!
    }

    /**
     * Display the specified resource.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function changeItem(Product $product)
    {
        $cart = Session::has('cart') ? Session::get('cart') : '';
        abort_if ( !$cart, 404 );

        $validator = request()->validate([
            'quantity' => 'required|integer|min:1|max:255', // max - remaind in storage
        ]);

        $cart->change($product, request('quantity'));

        return redirect()->route('cart.show');
    }

}
