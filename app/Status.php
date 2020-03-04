<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Status
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $style
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Status newModelQuery()
 * @method static Builder|Status newQuery()
 * @method static Builder|Status query()
 * @method static Builder|Status whereCreatedAt($value)
 * @method static Builder|Status whereDescription($value)
 * @method static Builder|Status whereId($value)
 * @method static Builder|Status whereName($value)
 * @method static Builder|Status whereStyle($value)
 * @method static Builder|Status whereTitle($value)
 * @method static Builder|Status whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Status extends Model
{
    protected $fillable = [
        'name',
        'title',
        'description',
    ];

    /**
     * @return HasMany
     */
    private function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
