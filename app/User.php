<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    protected $fillable = [
        'name', 'email', 'password', 'verify_token', 'status',
    ];

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


    // protected $dates = ['deleted_at'];

    // public function roles()
    // {
    //     return $this->belongsToMany('App\Role');
    // }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // public function getRank() {
    //     return $this->hasMany(Order::class);
    // }


    // это не нужно? получать в контроллере через where?
    // public function getActions () {
    //     return $this->hasMany(Action::class);
    // }

    // public function getTask () {
    //     return $this->hasMany(Task::class, )
    // }

}
