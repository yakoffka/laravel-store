<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Tasksstatus
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $display_name
 * @property string|null $description
 * @property string $title
 * @property string $class
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Tasksstatus newModelQuery()
 * @method static Builder|Tasksstatus newQuery()
 * @method static Builder|Tasksstatus query()
 * @method static Builder|Tasksstatus whereClass($value)
 * @method static Builder|Tasksstatus whereCreatedAt($value)
 * @method static Builder|Tasksstatus whereDescription($value)
 * @method static Builder|Tasksstatus whereDisplayName($value)
 * @method static Builder|Tasksstatus whereId($value)
 * @method static Builder|Tasksstatus whereName($value)
 * @method static Builder|Tasksstatus whereSlug($value)
 * @method static Builder|Tasksstatus whereTitle($value)
 * @method static Builder|Tasksstatus whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Tasksstatus extends Model
{
    //
}
