<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Session;
use App\Product;
use App\Cart;
use App\Order;

class CartController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @param Cart $cart @todo! зачем здесь передаётся параметр?
     * @return RedirectResponse
     */
    public function store(Cart $cart)
    {
        // abort_if ( auth()->user()->cannot('create_products'), 403 );
        return redirect()->route('products.index');
    }

    /**
     * @return Factory|View
     */
    public function confirmation()
    {
        // dd(__METHOD__);
        $cart = Session::has('cart') ? Session::get('cart') : '';
        abort_if ( !$cart, 404 );
        return view('cart.confirmation', compact('cart'));
    }

    /**
     * Add to cart the specified resource.
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function addToCart(Product $product): RedirectResponse
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product);
        session(['cart' => $cart]);

        return back();
    }

    /**
     * Display the specified resource.
     *
     *
     */
    public function show()
    {
        $cart = Session::has('cart') ? Session::get('cart') : '';
        return view('cart.show', compact('cart'));
    }

    /**
     * Remove the specified resource from cart.
     * @param Product $product
     * @return RedirectResponse
     */
    public function deleteItem(Product $product)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->remove($product);
        session(['cart' => $cart]);
        $success = 'item is deleted from your cart';

        // return view('cart.index', compact('cart', 'success'));
        return redirect()->route('cart.show'); // $success!!!
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return RedirectResponse
     */
    public function changeItem(Product $product): RedirectResponse
    {
        $cart = Session::has('cart') ? Session::get('cart') : '';
        abort_if ( !$cart, 404 );

        $validator = request()->validate([
            'quantity' => 'required|integer|min:1|max:255', // max - remain in storage
        ]);

        $cart->change($product, request('quantity'));

        return redirect()->route('cart.show');
    }
}
