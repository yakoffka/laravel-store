<?php

namespace App;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    /*
    *  The Role model has three main attributes:
    *  
    *  name — Unique name for the Role, used for looking up role information in the application layer. For example: "admin", "owner", "employee".
    *  display_name — Human readable name for the Role. Not necessarily unique and optional. For example: "User Administrator", "Project Owner", "Widget Co. Employee".
    *  description — A more detailed explanation of what the Role does. Also optional.
    */

    /**
    * The attributes that are mass assignable. yo
    *
    * @var array
    */
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'added_by_user_id',
        'edited_by_user_id',
    ];

    /**
    * Many-to-Many relations with the user model.
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
    * Many-to-One relations with the user model.
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function creator() {
        return $this->belongsTo(User::class, 'added_by_user_id');
    }

    /**
    * Many-to-One relations with the user model.
    *
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function editor() {
        return $this->belongsTo(User::class, 'edited_by_user_id');
    }

}
