<?php

namespace App\Http\Controllers;

use App\{Customevent, Cart, Order, Status};
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    /* php artisan route:list
PHP Fatal error:  Allowed memory size of 268435456 bytes exhausted
     * OrderController constructor.
     * @param Cart $cart @todo! зачем здесь передаётся параметр?
     */
    /*public function __construct(Cart $cart)
    {
        // dd(__METHOD__);
        $this->middleware('auth');
    }*/
    /**
     * OrderController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $orders = Order::where('customer_id', '=', auth()->user()->id)->paginate();
        $statuses = Status::all();
        return view('dashboard.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Display a listing of the resource for admin side.
     *
     */
    public function adminIndex()
    {
        abort_if(auth()->user()->cannot('view_orders'), 403);
        $orders = Order::paginate();
        $statuses = Status::all();
        return view('dashboard.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store()
    {
        request()->validate([
            'comment' => 'nullable|string|max:1000',
        ]);

        $order = Order::create([
            'comment' => request('comment'),
            // address, shipping
        ]);

        return redirect()->route('orders.show', ['order' => $order->id]);
    }

    /**
     * Display the specified resource.
     * @param Order $order
     * @return Factory|View
     */
    public function show(Order $order)
    {
        $order->cart = unserialize($order->cart);
        $statuses = Status::all();
        $customevents = Customevent::where('model', 'Order')->where('model_id', $order->id)->get();
        return view('dashboard.orders.show', compact('order', 'customevents', 'statuses'));
    }

    /**
     * Display the specified resource for admin side.
     * @param Order $order
     * @return Factory|View
     */
    public function adminShow(Order $order)
    {
        abort_if(auth()->user()->cannot('view_orders'), 403);
        $order->cart = unserialize($order->cart);
        $statuses = Status::all();
        $customevents = Customevent::where('model', 'Order')->where('model_id', $order->id)->get();
        return view('dashboard.orders.show', compact('order', 'statuses', 'customevents'));
    }

    /**
     * Update the specified resource in storage.
     * @param Order $order
     * @return RedirectResponse
     */
    public function update(Order $order): RedirectResponse
    {
        abort_if(auth()->user()->cannot('edit_orders'), 403);

        request()->validate([
            'status_id' => 'required|integer|exists:statuses,id',
        ]);

        if (!$order->update([
            'status_id' => request('status_id'),
        ])) {
            return back()->withError(['something wrong. err' . __line__]);
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     * @param Order $order
     * @return RedirectResponse
     */
    public function destroy(Order $order): RedirectResponse
    {
        // @todo! soft delete? or only status complete?
        $message = __('mess_function_i_development');
        session()->flash('message', $message);
        return back();
    }
}
