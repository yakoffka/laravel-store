<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Cart;
use App\Status;
use Session;
use App\Mail\Order\{Created, StatusChanged};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

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
            $orders = Order::paginate(config('custom.orders_paginate'));
        } else {
            $orders = Order::where('user_id', '=', auth()->user()->id)
                ->paginate(config('custom.orders_paginate'));
        }
        // dd($orders);

        // $orders = auth()->user()->orders;
        // $orders->transform( function($order, $key) { // Call to undefined method Illuminate\Database\Eloquent\Builder::transform()
        //     $order->cart = unserialize($order->cart);
        //     return $order;
        // });
        // dd($orders);

        $statuses = Status::all();
        
        return view('orders.index', compact('orders', 'statuses'));
    }

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
        Session::forget('cart');

        $validator = Validator::make(request()->all(), [
            'comment' => 'required|string|max:600', // ???
        ]);

        $order = Order::create([
            'user_id' => auth()->user()->id,
            'cart' => serialize($cart),
            'status_id' => 1,
            'total_qty' => $cart->total_qty,
            'total_payment' => $cart->total_payment,
            'comment' => request('comment'),
            // address, shipping
        ]);

        if ($order) {
            // \Mail::to(config('mail.mail_to_test'))->bcc(config('mail.mail_bcc'))->send(
            //     new OrderCreated($order)
            // );
            
            // sending notification later with queue
            $when = Carbon::now()->addMinutes(1);
            \Mail::to(config('mail.mail_to_test'))
                ->bcc(config('mail.mail_bcc'))
                ->later($when, new Created($order));

            // return view('orders.show', compact('order'));
            return redirect()->route('orders.show', ['order' => $order->id]);
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
        $order->cart = unserialize($order->cart);
        $statuses = Status::all();
        return view('orders.show', compact('order', 'statuses'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Order $order
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Order $order)
    {
        abort_if ( auth()->user()->cannot('edit_orders'), 403 );

        $validator = Validator::make(request()->all(), [
            'status_id' => 'required|integer|max:9', // Status::all()->count()
        ]);

        if (!$order->update([
            'status_id' => request('status_id'),
        ])) {
            return back()->withError(['something wrong. err' . __line__]);
        }


        // \Mail::to(config('mail.mail_to_test'))
        //     ->bcc(config('mail.mail_bcc'))
        //     ->send(
        //     new OrderStatusChanged($order, $order->customer)
        // );

        // sending notification later with queue
        $when = Carbon::now()->addMinutes(1);
        \Mail::to(config('mail.mail_to_test'))
            ->bcc(config('mail.mail_bcc'))
            ->later($when, new StatusChanged($order, $order->status->name, $order->customer));

        // return redirect()->route('orders.show', ['order' => $order->id]);
        return back();
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
