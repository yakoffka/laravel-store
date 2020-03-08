<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Session;
use App\Product;
use App\Cart;

class CartController extends Controller
{
    /**
     * @return Factory|View
     */
    public function confirmation()
    {
        $cart = Session::has('cart') ? Session::get('cart') : null;
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
     * @return Factory|View
     */
    public function show()
    {
        $cart = Session::has('cart') ? Session::get('cart') : null;
        return view('cart.show', compact('cart'));
    }

    /**
     * Remove the specified resource from cart.
     * @param Product $product
     * @return RedirectResponse
     */
    public function deleteItem(Product $product): RedirectResponse
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->remove($product);
        session(['cart' => $cart]);

        return redirect()->route('cart.show');
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
