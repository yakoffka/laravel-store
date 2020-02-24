<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Status
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $style
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status whereStyle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Status whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Status extends Model
{
    protected $fillable = [
        'name',
        'title',
        'description',
    ];

    private function orders() {
        return $this->hasMany(Order::class);
    }
}
