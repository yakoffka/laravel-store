<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Cart;
use Session;
use App\Mail\OrderCreated;

class OrderController extends Controller
{
    public function __construct(Cart $cart) {
        $this->middleware('auth');


    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ( auth()->user()->can('view_orders') ) {
            $orders = Order::all();
        } else {
            $orders = auth()->user()->orders();
        }
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     return view('orders.show', compact('order'));
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) // protected??
    {
        $cart = Session::has('cart') ? Session::get('cart') : null;
        abort_if ( !$cart, 404 );
        Session::forget('cart'); // wtf??

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'cart' => serialize($cart),
            'status_id' => 1,
            // address, comment
        ]);

        if ($order) {

            \Mail::to([env('MAIL_ADMIN'), auth()->user()->email])->send(
                new OrderCreated($order)
            );

            return view('orders.show', compact('order'));
        }

        return back()->withErrors(['something wrong. err' . __line__])->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if ( !Auth::user()->can('edit_orders'), 403 );
        $categories = Category::all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
