<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Taskspriority
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
 * @method static Builder|Taskspriority newModelQuery()
 * @method static Builder|Taskspriority newQuery()
 * @method static Builder|Taskspriority query()
 * @method static Builder|Taskspriority whereClass($value)
 * @method static Builder|Taskspriority whereCreatedAt($value)
 * @method static Builder|Taskspriority whereDescription($value)
 * @method static Builder|Taskspriority whereDisplayName($value)
 * @method static Builder|Taskspriority whereId($value)
 * @method static Builder|Taskspriority whereName($value)
 * @method static Builder|Taskspriority whereSlug($value)
 * @method static Builder|Taskspriority whereTitle($value)
 * @method static Builder|Taskspriority whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Taskspriority extends Model
{
    //
}
