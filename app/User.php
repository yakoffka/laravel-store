<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Customevent;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Storage;
use Str;

class User extends Authenticatable
{
    // use Notifiable, EntrustUserTrait;
    use Notifiable, EntrustUserTrait;
    use SoftDeletes { SoftDeletes::restore insteadof EntrustUserTrait; }

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    // const STATUS_DELETED = 9;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'verify_token', 'status', 'uuid',];
    protected $perPage = 10;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    /**
     * Create records in table events.
     *
     * @return void?
     */
    public function createCustomevent()
    {
        info(__METHOD__);
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
            'type' => debug_backtrace()[1]['function'],
            'description' => $this->description ?? FALSE,
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
                    new CategoryNotification($this, $type, $username)
                );
        }
    }
}
