<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Filters\Customevent\CustomeventFilters;

/**
 * App\Customevent
 *
 * @property int $id
 * @property int $user_id
 * @property mixed $model
 * @property int $model_id
 * @property string $model_name
 * @property mixed $type
 * @property string|null $description
 * @property string|null $details
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent filter(\Illuminate\Http\Request $request, $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent whereModelName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Customevent whereUserId($value)
 * @mixin \Eloquent
 */
class Customevent extends Model
{

    protected $guarded = [];
    protected $perPage = 50;

    public function getInitiator () {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeFilter(Builder $builder, Request $request, array $filters = []) { // https://coursehunters.net/course/filtry-v-laravel
        return (new CustomeventFilters($request))->add($filters)->filter($builder);
    }

}
