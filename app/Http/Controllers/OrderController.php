<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{Event, Cart, Order, Setting, Status};
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
            $orders = Order::paginate();
        } else {
            $orders = Order::where('user_id', '=', auth()->user()->id)
                ->paginate();
        }
        // dd($orders);

        // $orders = auth()->user()->orders;
        // $orders->transform( function($order, $key) { // Call to undefined method Illuminate\Database\Eloquent\Builder::transform()
        //     $order->cart = unserialize($order->cart);
        //     return $order;
        // });
        // dd($orders);

        $statuses = Status::all();
        
        return view('dashboard.orders.index', compact('orders', 'statuses'));
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

        if ( $order ) {

            // send email-notification
            if ( config('settings.email_new_order') ) {

                $user = auth()->user();
                $bcc = config('mail.mail_bcc');
                if ( config('settings.additional_email_bcc') ) {
                    $bcc = array_merge( $bcc, explode(', ', config('settings.additional_email_bcc')) );
                }

                $when = Carbon::now()->addMinutes(config('settings.email_send_delay'));

                \Mail::to($user)
                    ->bcc($bcc)
                    ->later($when, new Created($order));
            }

            // create event record
            $event = Event::create([
                'user_id' => auth()->user()->id,
                'type' => 'order',
                'type_id' => $order->id,
                'type' => 'model_create',
                'description' => 
                    'Создание заказа №' 
                    . str_pad($order->id, 5, "0", STR_PAD_LEFT) 
                    . '. Заказчик: ' 
                    . auth()->user()->name 
                    . '.',
                // 'old_value' => $product->id,
                // 'new_value' => $product->id,
            ]);

            return redirect()->route('orders.show', ['order' => $order->id]);
        }

        return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
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
        $events = Event::where('type', 'order')->where('type_id', $order->id)->get();
        
        return view('dashboard.orders.show', compact('order', 'statuses', 'events'));
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

        $old_status_id = $order->status_id;

        if (!$order->update([
            'status_id' => request('status_id'),
        ])) {
            return back()->withError(['something wrong. err' . __line__]);
        }

        // send email-notification
        if ( config('settings.email_update_order') ) {

            $user = $order->customer;
            $bcc = config('mail.mail_bcc');

            if ( config('settings.additional_email_bcc') ) {
                $bcc = array_merge( $bcc, explode(', ', config('settings.additional_email_bcc')) );
            }

            $when = Carbon::now()->addMinutes(config('settings.email_send_delay'));

            \Mail::to($user)
                ->bcc($bcc)
                ->later($when, new StatusChanged($order, $order->status->name, $user));
        }

        // create event record
        $event = Event::create([
            'user_id' => auth()->user()->id,
            'type' => 'order',
            'type_id' => $order->id,
            'type' => 'model_update',
            'description' => 
                'Изменение статуса заказа №' 
                . str_pad($order->id, 5, "0", STR_PAD_LEFT) 
                . ' на "' . $order->status->description . '".',
            'old_value' => $old_status_id,
            'new_value' => $order->id,
        ]);

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
        // soft delete?
    }
}
