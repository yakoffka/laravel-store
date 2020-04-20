<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use App\Filters\Product\ProductFilters;
use Mail;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Support\Carbon;
use App\Mail\ProductNotification;
use Illuminate\Support\Str;
use App\Traits\Yakoffka\ImageYoTrait;
use Illuminate\Support\Facades\Storage;

/**
 * App\Product
 *
 * @property int $id
 * @property string $name
 * @property string|null $title
 * @property string|null $slug
 * @property int $sort_order
 * @property int|null $manufacturer_id
 * @property string|null $vendor_code
 * @property number $promotional_price
 * @property  $promotional_percentage
 * @property int|null $length
 * @property int|null $width
 * @property int|null $height
 * @property int|null $diameter
 * @property number $remaining
 * @property string|null $code_1c
 * @property int $category_id
 * @property boolean $publish
 * @property string|null $materials
 * @property string|null $description
 * @property string|null $modification
 * @property string|null $workingconditions
 * @property string|null $date_manufactured
 * @property float|null $price
 * @property int $added_by_user_id
 * @property int|null $edited_by_user_id
 * @property int|null $count_views
 * @property int $views
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Category $category
 * @property-read Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read User $creator
 * @property-read User|null $editor
 * @property-read mixed $short_description
 * @property-read Collection|Image[] $images
 * @property-read int|null $images_count
 * @property-read Manufacturer|null $manufacturer
 * @method static Builder|Product filter(Request $request, $filters = [])
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|Product searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static Builder|Product whereAddedByUserId($value)
 * @method static Builder|Product whereCategoryId($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDateManufactured($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereEditedByUserId($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereManufacturerId($value)
 * @method static Builder|Product whereMaterials($value)
 * @method static Builder|Product whereModification($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product whereSeeable($value)
 * @method static Builder|Product whereSlug($value)
 * @method static Builder|Product whereSortOrder($value)
 * @method static Builder|Product whereTitle($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @method static Builder|Product whereViews($value)
 * @method static Builder|Product whereWorkingconditions($value)
 * @mixin Eloquent
 */
class Product extends Model
{
    use SearchableTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected array $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            // 'products.id' => 10,
            'products.name' => 10,
            'products.description' => 10,
        ],
    ];

    protected $guarded = [];
    protected $perPage = 12;
    private string $event_type = '';


    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by_user_id')->withDefault([
            'name' => 'no author'
        ]);
    }

    /**
     * @return BelongsTo
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by_user_id')->withDefault([
            'name' => 'no editor'
        ]);
    }

    /**
     * @return BelongsTo
     */
    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class)->withDefault([
            'name' => 'noname'
        ]);
    }

    /**
     * @param Builder $builder
     * @param Request $request
     * @param array $filters
     * @return Builder
     */
    public function scopeFilter(Builder $builder, Request $request, array $filters = []): Builder
    { // https://coursehunters.net/course/filtry-v-laravel
        return (new ProductFilters($request))->add($filters)->filter($builder);
    }

    /**
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class)->orderBy('sort_order');
    }

    /**
     * Accessor
     * in blade using snake-case: $product->short_description!!!
     */
    public function getShortDescriptionAttribute(): string
    {
        return Str::limit(strip_tags($this->description), 80);
    }

    /**
     * Accessor
     * @return string
     */
    public function getFullImagePathLAttribute(): string
    {
        if ($this->images->count()) {
            $img = $this->images->first();
            return '/images/products/' . $this->id . '/' . $img->name . '-l' . $img->ext;
        }

        return config('imageyo.default_img');
    }

    /**
     * Accessor
     * @return string
     */
    public function getFullImagePathMAttribute(): string
    {
        if ($this->images->count()) {
            $img = $this->images->first();
            return '/images/products/' . $this->id . '/' . $img->name . '-m' . $img->ext;
        }

        return config('imageyo.default_img');
    }

    /**
     * Accessor
     * @return string
     */
    public function getFullImagePathSAttribute(): string
    {
        if ($this->images->count()) {
            $img = $this->images->first();
            return '/images/products/' . $this->id . '/' . $img->name . '-s' . $img->ext;
        }

        return config('imageyo.default_img');
    }

    /**
     * Mutator for format 'publish' field values
     *
     * @param $value
     * @return void
     */
    public function setPublishAttribute($value): void
    {
        $this->attributes['publish'] = ($value === 'on' || $value === true);
    }

    /**
     * Increment number of views.
     *
     * @return void
     */
    public function incrementViews(): void
    {
        if (!auth()->user() || auth()->user()->hasRole('user')) {
            $this->increment('count_views');
        }
    }


    /**
     * set setCreator from auth user
     *
     * @return Product $product
     */
    public function setCreator(): Product
    {
        $this->added_by_user_id = auth()->user() ? auth()->user()->id : User::SYSUID;
        return $this;
    }

    /**
     * set setCreator from auth user
     *
     * @return Product $product
     */
    public function setEditor(): Product
    {
        $this->edited_by_user_id = auth()->user() ? auth()->user()->id : User::SYSUID;
        return $this;
    }

    /**
     * set title from dirty title or name fields
     *
     * @return Product $product
     */
    public function setTitle(): Product
    {
        if (!$this->title) {
            $this->title = $this->name;
        }
        return $this;
    }

    /**
     * set slug from dirty field slug or title
     * при одновременном изменении slug и title трансформирует поле slug.
     *
     * @return Product $product
     */
    public function setSlug(): Product
    {
        if ($this->isDirty('slug') and $this->slug) {
            $this->slug = Str::slug($this->slug, '-');
        } elseif ($this->isDirty('title')) {
            $this->slug = Str::slug($this->title, '-');
        }
        return $this;
    }

    /**
     * Create records in table events.
     *
     * @return Product $product
     */
    public function createCustomevent(): Product
    {
        $this->event_type = debug_backtrace()[1]['function'];
        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();
        // dd($attr, $dirty, $original);

        $details = [];
        foreach ($attr as $property => $value) {
            if (array_key_exists($property, $dirty) or !$dirty) {
                $details[] = [
                    $property,
                    $original[$property] ?? FALSE,
                    $dirty[$property] ?? FALSE,
                ];
            }
        }

        Customevent::create([
            'user_id' => auth()->user() ? auth()->user()->id : User::SYSUID,
            'model' => $this->getTable(),
            'model_id' => $this->id,
            'model_name' => $this->name,
            'type' => $this->event_type,
            'description' => $this->event_description ?? '',
            'details' => serialize($details) ?? '',
        ]);
        return $this;
    }


    /**
     * Create event notification.
     *
     * @return Product $product
     */
    public function sendEmailNotification(): Product
    {
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $this->event_type;
        $setting = config($namesetting);
        // info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

        if ($setting === '1') {
            $to = auth()->user();

            $bcc = array_merge(config('mail.mail_bcc'), explode(', ', config('settigs.additional_email_bcc')));
            $bcc = array_diff($bcc, ['', auth()->user() ? auth()->user()->email : '', config('mail.email_send_delay')]);
            $bcc = array_unique($bcc);

            Mail::to($to)->bcc($bcc)->later(
                Carbon::now()->addMinutes(config('mail.email_send_delay')),
                new ProductNotification($this->getTable(), $this->id, $this->name, auth()->user()->name, $this->event_type)
            );
        }
        return $this;
    }

    /**
     * метод добавления изображений товара
     *
     * Принимает строку с path файлов изображений, разделёнными запятой
     * Создает, при необходимости директорию для хранения изображений товара,
     *  и копирует в неё комплект превью с наложением водяных знаков.
     * Добавляет запись о каждом изображении в таблицу images
     *
     * @return Product $product
     */
    public function attachImages(): Product
    {// @todo: вынести в сервисный слой. использовать метод из App\Imports\ProductImport@processingImages
        if (!request('images_path')) {
            return $this;
        }

        $image_paths = explode(',', request('images_path'));

        foreach ($image_paths as $imagepath) {

            $image = storage_path('app/public') . str_replace(config('filesystems.disks.lfm.url'), '', $imagepath);

            // info('$image = ' . $image);
            // image re-creation
            $image_name = ImageYoTrait::saveImgSet($image, $this->id, 'lfm-mode');
            $basename = basename($image_name);
            $path = '/images/products/' . $this->id;

            // create record
            $images[] = Image::create([
                'product_id' => $this->id,
                'slug' => Str::slug($image_name, '-'),
                'path' => $path,
                'name' => $image_name,
                'ext' => config('imageyo.res_ext'),
                'alt' => str_replace(strrchr($basename, '.'), '', $basename),
                'sort_order' => 9,
                'orig_name' => $imagepath,
            ]);
        }

        if (!empty($images) && !$this->isDirty()) {
            $this->touch();
        }

        return $this;
    }

    /**
     * метод очистки исходного украденного исходного кода таблиц
     *
     * @return Product $product
     */
    public function cleanSrcCodeTables(): Product
    {
        if (empty($this->modification) || !$this->isDirty('modification')) {
            return $this;
        }

        // удаление ненужных тегов
        $res = strip_tags($this->modification, '<table><caption><thead><tbody><th><tr><td>');

        $arr_replace = [
            ['~</table>.*?<table[^>]*?>~u', 'REPLACE_THIS'],       // если таблиц несколько
            ['~.*?<table[^>]*?>~u', '<table class="blue_table">'], // обрезка до таблицы
            ['~</table>.*?~u', '</table>'],                        // обрезка после таблицы
            ['~<caption[^>]*?>~u', '<caption>'],                   // чистка нужных тегов от классов, стилей и атрибутов
            ['~<thead[^>]*?>~u', '<thead>'],                       // чистка нужных тегов от классов, стилей и атрибутов
            ['~<tbody[^>]*?>~u', '<tbody>'],                       // чистка нужных тегов от классов, стилей и атрибутов
            ["~<th[\s]{1}[^>]*?>~u", '<th>'],                      // не зацепить <thead>!!
            ['~<tr[^>]*?>~u', '<tr>'],
            ['~<td[^>]*?>~u', '<td>'],
            ['~>[\s]*~', '>'],
            ['~[\s]*>~', '>'],
            ['~<[\s]*~', '<'],
            ['~[\s]*<~', '<'],
            ['~REPLACE_THIS~u', "</table>\n<table class=\"blue_table\">"],
        ];

        foreach ($arr_replace as $replace) {
            $res = preg_replace($replace[0], $replace[1], $res);
        }

        // удаление прочего мусора
        $arr_delete = [
            '&nbsp;',
        ];
        foreach ($arr_delete as $delete) {
            $res = str_replace($delete, '', $res);
        }

        // опционально: если последним столбцом таблицы идет цена, то вырезаем последний столбец
        if (strpos($res, '<td>Цена</td></tr>') || strpos($res, '<th>Цена</th></tr>')) {
            $arr_replace = [
                ['~<td>[^<]+?</td></tr>~u', '</tr>'],
                ['~<th>[^<]+?</th></tr>~u', '</tr>'],
            ];
            foreach ($arr_replace as $replace) {
                $res = preg_replace($replace[0], $replace[1], $res);
            }
        }

        // опционально: удаление столбца <tr><td>Код товара</td>
        if (strpos($res, '<tr><td>Код товара</td>') || strpos($res, '<tr><th>Код товара</th>')) {
            $arr_replace = [
                ['~<tr><td>[^<]+?</td>~u', '<tr>'],
                ['~<tr><th>[^<]+?</th>~u', '<tr>'],
            ];
            foreach ($arr_replace as $replace) {
                $res = preg_replace($replace[0], $replace[1], $res);
            }
        }

        $this->modification = $res;
        return $this;
    }


    /**
     * Copying all donor images and creating an entry in the image table.
     *
     * @return self $this
     */
    public function additionallyIfCopy(): self
    {
        if (!request('copy_img')) {
            return $this;
        }

        $donor_id = request('copy_img');
        // $donor = Product::find($donor_id);
        $d_images = self::find($donor_id)->images;

        // copy all entries from the image table related to this product
        foreach ($d_images as $d_image) {
            $image = new Image;
            $image->product_id = $this->id;
            $image->slug = $d_image->slug;
            $image->path = $d_image->path;
            $image->name = $d_image->name;
            $image->ext = $d_image->ext;
            $image->alt = $d_image->alt;
            $image->sort_order = $d_image->sort_order;
            $image->orig_name = $d_image->orig_name;
            $image->save();
        }

        // copy all files from public directory images of products
        $pathToDir = 'public/images/products/'; // TODO!!!
        $files = Storage::files($pathToDir . $donor_id);
        foreach ($files as $src) {
            $dst = str_replace($pathToDir . $donor_id, $pathToDir . $this->id, $src);
            Storage::copy($src, $dst);
        }

        // copy all files from uploads directory images of products
        $pathToDir = 'uploads/images/products/'; // TODO!!!
        $files = Storage::files($pathToDir . $donor_id);
        foreach ($files as $src) {
            $dst = str_replace($pathToDir . $donor_id, $pathToDir . $this->id, $src);
            Storage::copy($src, $dst);
        }

        return $this;
    }

    /**
     * Delete relative images
     *
     * @return self $this
     */
    public function deleteImages(): self
    {
        if ($this->images->count()) {
            // delete public directory (converted images)
            $directory_pub = 'public/images/products/' . $this->id;
            Storage::deleteDirectory($directory_pub);
            // delete uploads directory (original images)
            $directory_upl = 'uploads/images/products/' . $this->id;
            Storage::deleteDirectory($directory_upl);
        }
        return $this;
    }

    /**
     * Delete relative comments
     *
     * @return self $this
     */
    public function deleteComments(): self
    {
        $this->comments()->delete();
        return $this;
    }

    /**
     * @return self $this
     */
    public function setFlashMess(): self
    {
        $message = __('Product__success', ['name' => $this->name, 'type_act' => __('masculine_' . $this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }

    /**
     * @return bool
     */
    public function isPublish(): bool
    {
        return $this->publish && $this->category->isPublish();
    }

    /**
     * @return array
     */
    public function getProductProperties(): array
    {
        $properties['store_article'] = str_pad($this->id, 7, '0', STR_PAD_LEFT);
        if ($this->manufacturer->title) {
            $properties['manufacturer_title'] = $this->manufacturer->title;
        }
        if ($this->materials) {
            $properties['materials'] = $this->materials;
        }
        if ($this->date_manufactured) {
            $properties['date_manufactured'] = $this->date_manufactured;
        }
        if ($this->vendor_code) {
            $properties['vendor_code'] = $this->vendor_code;
        }
        if ($this->code_1c) {
            $properties['manufacturer_title'] = $this->code_1c;
        }
        if ($this->promotional_percentage && config('settings.display_prices')) {
            $properties['promotional_percentage'] =
                ((string)number_format($this->promotional_percentage, 0, ',', ' ')) . ' %';
        }
        if (config('settings.display_prices')) {
            $properties['price'] = $this->getFormatterPrice(true);
        }
        if ($this->promotional_price && config('settings.display_prices')) {
            $properties['promotional_price'] = $this->getFormatterPromoPrice();
        }
        if ($this->length) {
            $properties['length'] = (string)$this->length . ' мм.';
        }
        if ($this->width) {
            $properties['width'] = (string)$this->width . ' мм.';
        }
        if ($this->height) {
            $properties['height'] = (string)$this->height . ' мм.';
        }
        if ($this->diameter) {
            $properties['diameter'] = (string)$this->diameter . ' мм.';
        }
        if ($this->remaining) {
            $properties['remaining'] =
                ((string)number_format($this->remaining, 0, ',', ' ')) . ' шт.';
        }
        if ($this->count_views) {
            $properties['count_views'] = $this->count_views;
        }
        return $properties;
    }

    /**
     * @param bool $short
     * @return string
     */
    public function getFormatterPrice($short = false): string
    {
        $fPrice = $this->formattingPrice($this->price, $short);
        if ($this->promotional_price === null) {
            return $fPrice;
        }
        return "<span class='strike_price'>$fPrice</span>";
    }

    /**
     * @param bool $short
     * @return string
     */
    public function getFormatterPromoPrice($short = false): string
    {
        return $this->formattingPrice($this->promotional_price, $short);
    }

    /**
     * @param $val
     * @param bool $short
     * @return string
     */
    private function formattingPrice($val, $short = false): string
    {
        if ($val === null) {
            return $short ? __('not specified') : __('Price not specified');
        }
        return (string)number_format($val, 0, ', ', ' ') . ' <span class="currency">&#8381;</span>';
    }

    /**
     * @param bool $short
     * @return string
     */
    public function getActualPrice($short = false): string
    {
        $fPrice = $this->formattingPrice($this->price, $short);
        if ($this->promotional_price === null) {
            return $fPrice;
        }

        $fPromoPrice = $this->formattingPrice($this->promotional_price, $short);
        return '<span class="strike_price">' . $fPrice . "</span> <span class='promo_price'>" . $fPromoPrice . '</span>';
    }
}
