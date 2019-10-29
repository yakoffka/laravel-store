<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Customevent;
use App\Mail\CommentNotification;

class Comment extends Model
{
    protected $guarded = [];

    /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 15;


    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Create records in table events.
     *
     * @return Comment $comment
     */
    public function createCustomevent()
    {
        // info(__METHOD__ . 'Some helpful information!');

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
            'model_name' => $this->id,
            'type' => debug_backtrace()[1]['function'],
            'description' => $this->description ?? FALSE,
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
        $type = debug_backtrace()[1]['function'];
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $type;
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
                    new CommentNotification($this, $type, $username)
                );
        }
        return $this;
    }
}
