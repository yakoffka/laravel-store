<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use App\Filters\Customevent\CustomeventFilters;
use Illuminate\Support\Carbon;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Customevent filter(Request $request, $filters = [])
 * @method static Builder|Customevent newModelQuery()
 * @method static Builder|Customevent newQuery()
 * @method static Builder|Customevent query()
 * @method static Builder|Customevent whereCreatedAt($value)
 * @method static Builder|Customevent whereDescription($value)
 * @method static Builder|Customevent whereDetails($value)
 * @method static Builder|Customevent whereId($value)
 * @method static Builder|Customevent whereModel($value)
 * @method static Builder|Customevent whereModelId($value)
 * @method static Builder|Customevent whereModelName($value)
 * @method static Builder|Customevent whereType($value)
 * @method static Builder|Customevent whereUpdatedAt($value)
 * @method static Builder|Customevent whereUserId($value)
 * @mixin Eloquent
 */
class Customevent extends Model
{

    protected $guarded = [];
    protected $perPage = 50;

    /**
     * @return BelongsTo
     */
    public function getInitiator (): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param Builder $builder
     * @param Request $request
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter(Builder $builder, Request $request, array $filters = []): Builder
    {
        // https://coursehunters.net/course/filtry-v-laravel
        return (new CustomeventFilters($request))->add($filters)->filter($builder);
    }

}
