<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;

/**
 * App\Permission
 *
 * @property int $id
 * @property string $group
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Permission extends EntrustPermission
{
    /*
    *  The Permission model has the same three attributes as the Role:
    *  
    *  name — Unique name for the permission, used for looking up permission information in the application layer. For example: "create-post", "edit-user", "post-payment", "mailing-list-subscribe".
    *  display_name — Human readable name for the permission. Not necessarily unique and optional. For example "Create Posts", "Edit Users", "Post Payments", "Subscribe to mailing list".
    *  description — A more detailed explanation of the Permission.
    *  
    *  In general, it may be helpful to think of the last two attributes in the form of a sentence: "The permission display_name allows a user to description."
    */

    // ???
    // public function roles()
    // {
    //     return $this->belongsToMany('App\Role');
    // }
}
