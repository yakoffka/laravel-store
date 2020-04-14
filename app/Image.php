<?php

namespace App;

use App\Http\Controllers\Import\ImportController;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Storage;

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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Product $product
 * @method static Builder|Image newModelQuery()
 * @method static Builder|Image newQuery()
 * @method static Builder|Image query()
 * @method static Builder|Image whereAlt($value)
 * @method static Builder|Image whereCreatedAt($value)
 * @method static Builder|Image whereExt($value)
 * @method static Builder|Image whereId($value)
 * @method static Builder|Image whereName($value)
 * @method static Builder|Image whereOrigName($value)
 * @method static Builder|Image wherePath($value)
 * @method static Builder|Image whereProductId($value)
 * @method static Builder|Image whereSlug($value)
 * @method static Builder|Image whereSortOrder($value)
 * @method static Builder|Image whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Image extends Model
{
    protected $guarded = [];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function deleteImageFile(): void
    {
        $aImageSet = config('adaptation_image_service.set.store_product');
        foreach ($aImageSet as $imageType) {
            $imagePath = config('adaptation_image_service.product_images_path')
                . '/' . $this->product_id . '/' . $this->name . '-' . $imageType . $this->ext;
            Storage::disk('public')->delete($imagePath);
        }
    }
}
