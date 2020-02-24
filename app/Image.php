<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Image
 *
 * @property int $id
 * @property int $product_id
 * @property string $slug
 * @property string $path
 * @property string $name
 * @property string $ext
 * @property string $alt
 * @property int $sort_order
 * @property string $orig_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereOrigName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Image extends Model
{
    protected $guarded = [];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
