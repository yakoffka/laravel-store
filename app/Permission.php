<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Zizaco\Entrust\EntrustPermission;

/**
 * App\Permission
 *
 * @property int $id
 * @property string $group
 * @property string $name
 * @property string|null $display_name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @method static Builder|Permission newModelQuery()
 * @method static Builder|Permission newQuery()
 * @method static Builder|Permission query()
 * @method static Builder|Permission whereCreatedAt($value)
 * @method static Builder|Permission whereDescription($value)
 * @method static Builder|Permission whereDisplayName($value)
 * @method static Builder|Permission whereGroup($value)
 * @method static Builder|Permission whereId($value)
 * @method static Builder|Permission whereName($value)
 * @method static Builder|Permission whereUpdatedAt($value)
 * @mixin Eloquent
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
}
