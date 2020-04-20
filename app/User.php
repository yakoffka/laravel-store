<?php

namespace App;

use Eloquent;
use Illuminate\Config\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mail;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Mail\UserNotification;

/**
 * App\User
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property int $status
 * @property string|null $verify_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @method static bool|null forceDelete()
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static Builder|User query()
 * @method static bool|null restore()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereStatus($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUuid($value)
 * @method static Builder|User whereVerifyToken($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait;
    use SoftDeletes {
        SoftDeletes::restore insteadof EntrustUserTrait;
    }

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;
    public const URUID = 7;  // unregister user id
    public const SYSUID = 1; // system user id
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

    private string $event_type = '';

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Create records in table events.
     *
     * @return $this $user
     */
    public function createCustomevent(): self
    {
        $this->event_type = debug_backtrace()[1]['function'];
        if ($this->isDirty('status') and $this->status === User::STATUS_ACTIVE) {
            $this->event_type = 'verify';
        }
        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();
        // dd($attr, $dirty, $original);

        $details = [];
        foreach ($attr as $property => $value) {
            if (array_key_exists($property, $dirty) or !$dirty) {
                $details[] = [
                    $property,
                    $original[$property] ?? FALSE,
                    $dirty[$property] ?? FALSE,
                ];
            }
        }

        Customevent::create([
            'user_id' => auth()->user() ? auth()->user()->id : self::URUID, // unregistered user id
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
     * @return $this $user
     */
    public function sendEmailNotification(): self
    {
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $this->event_type;
        $setting = config($namesetting);

        if ($setting === '1') {
            $to = auth()->user() ?? config('mail.from.address');
            $user_name = auth()->user() ? auth()->user()->name : 'Unregistered';

            $bcc = array_merge(config('mail.mail_bcc'), explode(', ', config('settigs.additional_email_bcc')));
            $bcc = array_diff($bcc, ['', auth()->user() ? auth()->user()->email : '', config('mail.email_send_delay')]);
            $bcc = array_unique($bcc);

            Mail::to($to)->bcc($bcc)->later(
                Carbon::now()->addMinutes(config('mail.email_send_delay')),
                new UserNotification($this->getTable(), $this->id, $this->name, $user_name, $this->event_type)
            );
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function setFlashMess(): self
    {
        $message = __('User__success', ['name' => $this->name, 'type_act' => __('masculine_' . $this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }

    /**
     * @param null $notification
     * @return string
     */
    public function routeNotificationForSlack($notification = null): string
    {
        return config('custom.slack_webhook');
    }
}
