<?php

namespace App\Http\Controllers;

use App\{Customevent, Cart, Http\Requests\OrderRequest, Order, Status};
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
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
     * @param OrderRequest $request
     * @return RedirectResponse
     */
    public function store(OrderRequest $request): RedirectResponse
    {
        $order = Order::create($request->validated());
        return redirect()->route('orders.show', ['order' => $order->id]);
    }

    /**
     * Display the specified resource.
     * @param Order $order
     * @return Factory|View
     */
    public function show(Order $order)
    {
        $order->cart = unserialize($order->cart, [Cart::class]);
        $statuses = Status::all();
        $customevents = Customevent::where('model', 'Order')
            ->where('model_id', $order->id)
            ->get();
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
        $order->cart = unserialize($order->cart, [Cart::class]);
        $statuses = Status::all();
        $customevents = Customevent::where('model', 'Order')->where('model_id', $order->id)->get();
        return view('dashboard.orders.show', compact('order', 'statuses', 'customevents'));
    }

    /**
     * Update the specified resource in storage.
     * @param OrderRequest $request
     * @param Order $order
     * @return RedirectResponse
     */
    public function update(OrderRequest $request, Order $order): RedirectResponse
    {
        $order->update($request->validated());
        return back();
    }

    /**
     * Remove the specified resource from storage.
     * @param Order $order
     * @return RedirectResponse
     */
    public function destroy(Order $order): RedirectResponse
    {
        abort_if(auth()->user()->cannot('delete_orders'), 403);
        // @todo! soft delete? or only status complete?
        $message = __('mess_function_i_development');
        session()->flash('message', $message);
        return back();
    }
}
