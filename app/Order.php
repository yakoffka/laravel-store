<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Customevent;
use App\Mail\OrderNotification;
use Str;
use Session;

class Order extends Model
{
    protected $guarded = [];
    private $event_type = '';

    public function status() {
        return $this->belongsTo(Status::class);
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function manager() {
        return $this->belongsTo(User::class, 'manager_id');
    }


    /**
     * Create records in table events.
     *
     * @return void?
     */
    public function createCustomevent()
    {
        // skip property 'cart'!!!
        info(__METHOD__);
        $this->event_type = debug_backtrace()[1]['function'];
        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();
        // dd($attr, $dirty, $original);

        $details = [];
        foreach ( $attr as $property => $value ) {
            if ( (array_key_exists( $property, $dirty ) or !$dirty) and $property !== 'cart' ) {
                $details[] = [ 
                    $property, 
                    $original[$property] ?? FALSE, 
                    $dirty[$property] ?? FALSE,
                ];
            }
        }

        Customevent::create([
            'user_id' => auth()->user()->id,
            'model' => $this->getTable(),
            'model_id' => $this->id,
            'model_name' => $this->name,
            'type' => $this->event_type,
            'description' => $this->event_description ?? FALSE,
            'details' => serialize($details) ?? FALSE,
        ]);
        return $this;
    }


    /**
     * Create event notification.
     * 
     * @return void?
     */
    public function sendEmailNotification()
    {
        info(__METHOD__);
        $event_type = $this->event_type;
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $event_type;
        $setting = config($namesetting);

        info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

        if ( $setting === '1' ) {

            $bcc = config('mail.mail_bcc');
            $additional_email_bcc = Setting::all()->firstWhere('name', 'additional_email_bcc');
            if ( $additional_email_bcc->value ) {
                $bcc = array_merge( $bcc, explode(', ', $additional_email_bcc->value));
            }
            $email_send_delay = Setting::all()->firstWhere('name', 'email_send_delay');
            $when = Carbon::now()->addMinutes($email_send_delay);
            $username = auth()->user() ? auth()->user()->name : 'Unregistered';

            \Mail::to( auth()->user() ?? config('mail.from.address') )
                ->bcc($bcc)
                ->later( 
                    $when, 
                    new OrderNotification($this, $event_type, $username)
                );
        }
        return $this;
    }

    public function createFromCart() 
    {
        $cart = Session::has('cart') ? Session::get('cart') : null;
        abort_if ( !$cart, 404 );

        $this->cart = serialize($cart);
        $this->total_qty = $cart->total_qty;
        $this->total_payment = $cart->total_payment;

        Session::forget('cart');

        $this->status_id = 1;

        return $this;
    }

    public function setCustomer () 
    {
        info(__METHOD__);
        $this->customer_id = auth()->user()->id;
        return $this;
    }

    public function setManager () 
    {
        info(__METHOD__);
        $this->manager_id = auth()->user()->id;
        return $this;
    }

    public function setName ()
    {
        info(__METHOD__);
        $this->name = str_pad($this->id, 5, "0", STR_PAD_LEFT);
        return $this;
    }

    public function setFlashMess()
    {
        info(__METHOD__);
        $message = __('Order__success', ['name' => $this->name, 'type_act' => __('masculine_'.$this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }
}
