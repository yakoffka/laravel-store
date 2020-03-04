<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use App\Mail\OrderNotification;
use Mail;
use Session;

/**
 * App\Order
 *
 * @property int $id
 * @property int $customer_id
 * @property int $total_qty
 * @property int $total_payment
 * @property string $cart
 * @property int $status_id
 * @property string|null $comment
 * @property string|null $address
 * @property int|null $manager_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $customer
 * @property-read mixed $name
 * @property-read User|null $manager
 * @property-read Status $status
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order query()
 * @method static Builder|Order whereAddress($value)
 * @method static Builder|Order whereCart($value)
 * @method static Builder|Order whereComment($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereCustomerId($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereManagerId($value)
 * @method static Builder|Order whereStatusId($value)
 * @method static Builder|Order whereTotalPayment($value)
 * @method static Builder|Order whereTotalQty($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Order extends Model
{
    protected $guarded = [];
    private $event_type = '';

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * @return BelongsTo
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Accessor
     * in controller using snake-case
     */
    public function getNameAttribute(): string
    {
        return str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Create records in table events.
     *
     * @return $this
     */
    public function createCustomevent(): self
    {
        // !!! skip property 'cart' in $details!!!
        $this->event_type = debug_backtrace()[1]['function'];
        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();

        $details = [];
        foreach ($attr as $property => $value) {
            if ((array_key_exists($property, $dirty) or !$dirty) and $property !== 'cart') {
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
            'details' => serialize($details) ?? '',
        ]);
        return $this;
    }

    /**
     * Create event notification.
     *
     * @return $this
     */
    public function sendEmailNotification(): self
    {
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $this->event_type;
        $setting = config($namesetting);
        info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

        if ( $setting === '1' ) {
            $to = auth()->user();

            $bcc = array_merge(config('mail.mail_bcc'), explode(', ', config('settigs.additional_email_bcc')));
            $bcc = array_diff($bcc, ['', auth()->user() ? auth()->user()->email : '', config('mail.email_send_delay')]);
            $bcc = array_unique($bcc);

            Mail::to($to)->bcc($bcc)->later(
                Carbon::now()->addMinutes(config('mail.email_send_delay')),
                new OrderNotification($this->getTable(), $this->id, $this->name, auth()->user()->name, $this->event_type)
            );

            // restarting the queue to make sure they are started
            if (!empty(config('custom.exec_queue_work'))) {
                info(__METHOD__ . ': ' . exec(config('custom.exec_queue_work')));
            }
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function createFromCart(): self
    {
        $cart = Session::has('cart') ? Session::get('cart') : null;
        abort_if(!$cart, 404);

        $this->cart = serialize($cart);
        $this->total_qty = $cart->total_qty;
        $this->total_payment = $cart->total_payment;

        Session::forget('cart');

        $this->status_id = 1;

        return $this;
    }

    /**
     * @return $this
     */
    public function setCustomer(): self
    {
        $this->customer_id = auth()->user()->id;
        return $this;
    }

    /**
     * @return $this
     */
    public function setManager(): self
    {
        $this->manager_id = auth()->user()->id;
        return $this;
    }

    /**
     * @return $this
     */
    public function setFlashMess(): self
    {
        $message = __('Order__success', ['name' => $this->name, 'type_act' => __('masculine_' . $this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }
}
