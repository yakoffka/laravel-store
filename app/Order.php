<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Customevent;
use App\Mail\OrderNotification;
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
     * Accessor
     * in controller using snake-case
     */
    public function getNameAttribute()
    {
        return str_pad($this->id, 5, "0", STR_PAD_LEFT);
    }

    /**
     * Create records in table events.
     *
     * @param  Order $order
     */
    public function createCustomevent()
    {
        // !!! skip property 'cart' in $details!!!
        info(__METHOD__);
        $this->event_type = debug_backtrace()[1]['function'];
        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();

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
     * @param  Order $order
     */
    public function sendEmailNotification()
    {
        info(__METHOD__);
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $this->event_type;
        $setting = config($namesetting);
        info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

        if ( $setting === '1' ) {
            $to = auth()->user();

            $bcc = array_merge( config('mail.mail_bcc'), explode(', ', config('settigs.additional_email_bcc')));
            $bcc = array_diff($bcc, ['', auth()->user() ? auth()->user()->email : '', config('mail.email_send_delay')]);
            $bcc = array_unique($bcc);

            \Mail::to($to)->bcc($bcc)->later( 
                Carbon::now()->addMinutes(config('mail.email_send_delay')), 
                new OrderNotification($this->getTable(), $this->id, $this->name, auth()->user()->name, $this->event_type)
            );

            // restarting the queue to make sure they are started
            if( !empty(config('custom.exec_queue_work')) ) {
                info(__METHOD__ . ': ' . exec(config('custom.exec_queue_work')));
            }
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

    public function setFlashMess()
    {
        info(__METHOD__);
        $message = __('Order__success', ['name' => $this->name, 'type_act' => __('masculine_'.$this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }
}
