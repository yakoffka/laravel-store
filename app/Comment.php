<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Customevent;
use App\Mail\CommentNotification;

class Comment extends Model
{
    protected $guarded = [];
    protected $perPage = 15;
    private $event_type = '';


    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function setAuthor()
    {
        info(__METHOD__);
        $this->user_id = auth()->user() ? auth()->user()->id : 7;
        $this->user_name = auth()->user() ? auth()->user()->name : (request('user_name') ? __('Guest ') . request('user_name') : __('Guest ') . 'Anonimous');
        return $this;
    }

    public function setName()
    {
        info(__METHOD__);
        $this->name = \Str::limit($this->body, 20);
        return $this;
    }

    public function transformBody()
    {
        info(__METHOD__);
        $this->body = str_replace(["\r\n", "\r", "\n"], '<br>', $this->body);
        return $this;
    }

    /**
     * Create records in table events.
     *
     * @return Comment $comment
     */
    public function createCustomevent()
    {
        info(__METHOD__);
        $this->event_type = debug_backtrace()[1]['function'];
        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();
        // dd($attr, $dirty, $original);

        $details = [];
        foreach ( $attr as $property => $value ) {
            if ( array_key_exists( $property, $dirty ) or !$dirty ) {
                $details[] = [ 
                    $property, 
                    $original[$property] ?? FALSE, 
                    $dirty[$property] ?? FALSE, 
                ];
            }
        }

        Customevent::create([
            'user_id' => auth()->user()->id ?? $this->user_id ?? 7, // $this->user_id - for seeding; 7 - id for Undefined user.
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
     * @return Comment $comment
     */
    public function sendEmailNotification()
    {
        info(__METHOD__);
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $this->event_type;
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
                    new CommentNotification($this->getTable(), $this->id, $this->name, $username, $this->event_type, $this->product_id, $this->body)
                );
        }
        return $this;
    }

    public function setFlashMess()
    {
        info(__METHOD__);
        $message = __('Comment__success', ['name' => $this->name, 'type_act' => __('masculine_'.$this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }
}
