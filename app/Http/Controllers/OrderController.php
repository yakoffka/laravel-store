<?php

namespace App\Http\Controllers;

use App\{Customevent, Cart, Order, Status};
use Session;
use App\Mail\Order\{Created, StatusChanged};
use Illuminate\Support\Carbon;
use App\Mail\ManufacturerNotification;
use Illuminate\Support\Facades\Storage;
use Str;

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
            $orders = Order::where('customer_id', '=', auth()->user()->id)
                ->paginate();
        }
        $statuses = Status::all();
        return view('dashboard.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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

        // // send email-notification
        // if ( config('settings.email_new_order') ) {
        //     $user = auth()->user();
        //     $bcc = config('mail.mail_bcc');
        //     if ( config('settings.additional_email_bcc') ) {
        //         $bcc = array_merge( $bcc, explode(', ', config('settings.additional_email_bcc')) );
        //     }
        //     $when = Carbon::now()->addMinutes(config('settings.email_send_delay'));
        //     \Mail::to($user)
        //         ->bcc($bcc)
        //         ->later($when, new Created($order));
        // }

        // $message = 'Создание заказа №' . str_pad($order->id, 5, "0", STR_PAD_LEFT) . '. Заказчик: ' 
        //     . auth()->user()->name  . '.';

        // create event record
        // $customevent = new Customevent;
        // $customevent->user_id = auth()->user()->id;
        // $customevent->model = 'order';
        // $customevent->model_id = $order->id;
        // $customevent->type = 'model_create';
        // $customevent->description = $message;
        // dd(__LINE__);
        // $customevent->save();
        // dd(__LINE__);
        // // if ( !$customevent->save() ) {
        // //     return back()->withErrors(['something wrong! Err#' . __LINE__])->withInput();
        // // }
        // // if ( $customevent ) {session()->flash('message', $message);}
        // $message = $this->createCustomevent($order, $dirty_properties, false, 'model_create');
        // if ( $message ) {session()->flash('message', $message);}
        return redirect()->route('orders.show', ['order' => $order->id]);
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
        $customevents = Customevent::where('model', 'Order')->where('model_id', $order->id)->get();
        return view('dashboard.orders.show', compact('order', 'statuses', 'customevents'));
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

        request()->validate([
            'status_id' => 'required|integer|max:9', // Status::all()->count()
        ]);

        // $old_status_id = $order->status_id;

        if (!$order->update([
            'status_id' => request('status_id'),
        ])) {
            return back()->withError(['something wrong. err' . __line__]);
        }
        // $order->status_id = request('status_id');
        // $dirty_properties = $order->getDirty();
        // $original = $order->getOriginal();

        // if ( !$order->save() ) {
        //     return back()->withErrors(['something wrong! Err#' . __LINE__])->withInput();
        // }

        // // send email-notification
        // if ( config('settings.email_update_order') ) {
        //     $user = $order->customer;
        //     $bcc = config('mail.mail_bcc');
        //     if ( config('settings.additional_email_bcc') ) {
        //         $bcc = array_merge( $bcc, explode(', ', config('settings.additional_email_bcc')) );
        //     }
        //     $when = Carbon::now()->addMinutes(config('settings.email_send_delay'));
        //     \Mail::to($user)
        //         ->bcc($bcc)
        //         ->later($when, new StatusChanged($order, $order->status->name, $user));
        // }

        // create event record
        // $message = $this->createCustomevent($order, $dirty_properties, $original, 'model_update');
        // if ( $message ) {session()->flash('message', $message);}

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
        $message = __('mess_function_i_development');
        session()->flash('message', $message);
        // session()->flash('alert-class', $message);
        return back();
    }
}
