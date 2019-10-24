<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Customevent;

class Comment extends Model
{
    protected $guarded = [];
    // protected $fillable = ['body'];

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
     * @return void?
     */
    public function createCustomevent()
    {
        info(__METHOD__ . 'Some helpful information!');

        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();

        $details = [];
        foreach ( $attr as $property => $value ) {
            if ( !empty($original[$property]) or !empty($dirty[$property]) ) {
                $details[] = [ $property, $original[$property] ?? FALSE, $dirty[$property] ?? FALSE, ];
            }
        }

        Customevent::create([
            'user_id' => auth()->user()->id ?? $this->user_id ?? 7, // $this->user_id - for seeding; 7 - id for Undefined user.
            'model' => $this->getTable(),
            'model_id' => $this->id,
            'type' => debug_backtrace()[1]['function'],
            'description' => $this->description ?? FALSE,
            'details' => serialize($details) ?? FALSE,
            'description' => $this->description ?? FALSE,
        ]);
    }


    /**
     * Create records in table events.
     *
     * @return void?
     */
    public function sendEmailNotification()
    {
        // info(__METHOD__);
        $namesetting = 'settings.email_' . $this->getTable() . '_' . debug_backtrace()[1]['function'];
        $setting = config($namesetting);

        if ( $setting === '1' ) {
            info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

            // $user = auth()->user();
            // $bcc = config('mail.mail_bcc');
            // if ( config('settings.additional_email_bcc') ) {
            //     $bcc = array_merge( $bcc, explode(', ', config('settings.additional_email_bcc')));
            // }
            // $when = Carbon::now()->addMinutes(config('settings.email_send_delay'));
            // \Mail::to($user)->bcc($bcc)->later($when, new Created($product, $user));
        }

    }

}
