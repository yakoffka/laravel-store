<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use App\Filters\Product\ProductFilters;
use Nicolaslopezj\Searchable\SearchableTrait;
use Illuminate\Support\Carbon;
use App\Customevent;
use App\Mail\ProductNotification;
use Illuminate\Support\Str;
use App\Traits\Yakoffka\ImageYoTrait;
use App\Jobs\RewatermarkJob;
use Artisan;
use App\{Category, Image, Manufacturer};
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
 * @property int $category_id
 * @property string|null $seeable
 * @property string|null $materials
 * @property string|null $description
 * @property string|null $modification
 * @property string|null $workingconditions
 * @property string|null $date_manufactured
 * @property float|null $price
 * @property int $added_by_user_id
 * @property int|null $edited_by_user_id
 * @property int $views
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\User $creator
 * @property-read \App\User|null $editor
 * @property-read mixed $category_seeable
 * @property-read mixed $parent_category_seeable
 * @property-read mixed $short_description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Image[] $images
 * @property-read int|null $images_count
 * @property-read \App\Manufacturer|null $manufacturer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product filter(\Illuminate\Http\Request $request, $filters = [])
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereAddedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDateManufactured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereEditedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereManufacturerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereMaterials($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereModification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereSeeable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereSortOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereViews($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Product whereWorkingconditions($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use SearchableTrait;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
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
    private $event_type = '';


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
     * Accessor возвращает видимость родительской категории товара
     * in controller using snake-case: $category->parent_seeable!!!
     *
     * @deprecated
     */
    public function getCategorySeeableAttribute()
    {
        return $this->belongsTo(Category::class, 'category_id')->get()->max('seeable');
    }

    /**
     * Accessor возвращает видимость прародительской категории товара
     * in controller using snake-case: $category->parent_seeable!!!
     *
     * @deprecated
     */
    public function getParentCategorySeeableAttribute()
    {
        return $this->belongsTo(Category::class, 'category_id')->get()->max('parent_seeable');
    }


    /**
     * Increment number of views.
     *
     * @return void
     */
    public function incrementViews(): void
    {
        if ( !auth()->user() or auth()->user()->hasRole('user') ) {
            $this->increment('count_views');
        }
    }


    /**
     * set setCreator from auth user
     *
     * @return  Product $product
     */
    public function setCreator (): Product
    {
        info(__METHOD__);
        $this->added_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * set setCreator from auth user
     *
     * @return  Product $product
     */
    public function setEditor (): Product
    {
        info(__METHOD__);
        $this->edited_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * set title from dirty title or name fields
     *
     * @return  Product $product
     */
    public function setTitle (): Product
    {
        info(__METHOD__);
        if ( !$this->title ) { $this->title = $this->name; }
        return $this;
    }

    /**
     * set slug from dirty field slug or title
     * при одновременном изменении slug и title трансформирует поле slug.
     *
     * @return  Product $product
     */
    public function setSlug (): Product
    {
        info(__METHOD__);
        if ( $this->isDirty('slug') and $this->slug ) {
            $this->slug = Str::slug($this->slug, '-');
        } elseif ( $this->isDirty('title') ) {
            $this->slug = Str::slug($this->title, '-');
        }
        return $this;
    }

    /**
     * Create records in table events.
     *
     * @return  Product $product
     */
    public function createCustomevent(): Product
    {
        info(__METHOD__);
        $this->event_type = debug_backtrace()[1]['function'];
        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();
        // dd($attr, $dirty, $original);

        $details = [];
        foreach ( $attr as $property => $value ) {
            if ( array_key_exists( $property, $dirty ) or !$dirty ) {
                $details[] = [
                    $property,
                    $original[$property] ?? FALSE,
                    $dirty[$property] ?? FALSE,
                ];
            }
        }

        Customevent::create([
            'user_id' => auth()->user()->id,
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
     * @return  Product $product
     */
    public function sendEmailNotification(): Product
    {
        info(__METHOD__);
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $this->event_type;
        $setting = config($namesetting);
        info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

        if ( $setting === '1' ) {
            $to = auth()->user();

            $bcc = array_merge( config('mail.mail_bcc'), explode(', ', config('settigs.additional_email_bcc')));
            $bcc = array_diff($bcc, ['', auth()->user() ? auth()->user()->email : '', config('mail.email_send_delay')]);
            $bcc = array_unique($bcc);

            \Mail::to($to)->bcc($bcc)->later(
                Carbon::now()->addMinutes(config('mail.email_send_delay')),
                new ProductNotification($this->getTable(), $this->id, $this->name, auth()->user()->name, $this->event_type)
            );

            // restarting the queue to make sure they are started
            if( !empty(config('custom.exec_queue_work')) ) {
                info(__METHOD__ . ': ' . exec(config('custom.exec_queue_work')));
            }
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
     * @return  Product $product
     */
    public function attachImages (): Product
    {
        info(__METHOD__);
        if ( !request('imagespath') ) {
            return $this;
        }

        $imagepaths = explode(',', request('imagespath'));

        foreach( $imagepaths as $imagepath) {

            $image = storage_path('app/public') . str_replace( config('filesystems.disks.lfm.url'), '', $imagepath );

            // info('$image = ' . $image);
            // image re-creation
            $image_name = ImageYoTrait::saveImgSet($image, $this->id, 'lfm-mode');
            $originalName = basename($image_name);
            $path  = '/images/products/' . $this->id;

            // create record
            $images[] = Image::create([
                'product_id' => $this->id,
                'slug' => Str::slug($image_name, '-'),
                'path' => $path,
                'name' => $image_name,
                'ext' => config('imageyo.res_ext'),
                'alt' => str_replace( strrchr($originalName, '.'), '', $originalName),
                'sort_order' => 9,
                'orig_name' => $originalName,
            ]);
        }

        if ( !$this->isDirty() and !empty($images) ) {
            $this->touch();
        }

        return $this;
    }

    /**
     * метод очистки исходного украденного исходного кода таблиц
     *
     * @return  Product $product
     */
    public function cleanSrcCodeTables (): Product
    {
        info(__METHOD__);
        if ( !$this->isDirty('modification') or empty($this->modification) ) {
            return $this;
        }

        // удаление ненужных тегов
        $res = strip_tags($this->modification, '<table><caption><thead><tbody><th><tr><td>');

        $arr_replace = [
            /*['~</table>.*?<table[^>]*?>~u', 'REPLACE_THIS'],       // если таблиц несколько*/
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

        foreach($arr_replace as $replace) {
            $res = preg_replace( $replace[0], $replace[1], $res );
        }

        // удаление прочего мусора
        $arr_delete = [
            '&nbsp;',
        ];
        foreach($arr_delete as $delete) {
            $res = str_replace( $delete, '', $res );
        }

        // опционально: если последним столбцом таблицы идет цена, то вырезаем последний столбец
        if ( strpos($res,'<td>Цена</td></tr>') or strpos($res,'<th>Цена</th></tr>') ) {
            $arr_replace = [
                ['~<td>[^<]+?</td></tr>~u', '</tr>'],
                ['~<th>[^<]+?</th></tr>~u', '</tr>'],
            ];
            foreach($arr_replace as $replace) {
                $res = preg_replace( $replace[0], $replace[1], $res );
            }
        }

        // опционально: удаление столбца <tr><td>Код товара</td>
        if ( strpos($res,'<tr><td>Код товара</td>') or strpos($res,'<tr><th>Код товара</th>') ) {
            $arr_replace = [
                ['~<tr><td>[^<]+?</td>~u', '<tr>'],
                ['~<tr><th>[^<]+?</th>~u', '<tr>'],
            ];
            foreach($arr_replace as $replace) {
                $res = preg_replace( $replace[0], $replace[1], $res );
            }
        }

        $this->modification = $res;
        return $this;
    }


    /**
     * Copying all donor images and creating an entry in the image table.
     *
     * @return  self $this
     */
    public function additionallyIfCopy (): self
    {
        info(__METHOD__);
        if ( !request('copy_img') ) {
            return $this;
        }

        $donor_id = request('copy_img');
        // $donor = Product::find($donor_id);
        $d_images = self::find($donor_id)->images;

        // copy all entries from the image table related to this product
        foreach ( $d_images as $d_image ) {
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
        foreach ( $files as $src ) {
            $dst = str_replace($pathToDir.$donor_id, $pathToDir.$this->id, $src);
            Storage::copy($src, $dst);
        }

        // copy all files from uploads directory images of products
        $pathToDir = 'uploads/images/products/'; // TODO!!!
        $files = Storage::files($pathToDir . $donor_id);
        foreach ( $files as $src ) {
            $dst = str_replace($pathToDir.$donor_id, $pathToDir.$this->id, $src);
            Storage::copy($src, $dst);
        }

        return $this;
    }

    /**
     * Delete relative images
     *
     * @return  self $this
     */
    public function deleteImages(): self
    {
        if ($this->images) {
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
     * @return  self $this
     */
    public function deleteComments(): self
    {
        $this->comments()->delete();
        return $this;
    }

    /**
     * @return  self $this
     */
    public function setFlashMess(): self
    {
        info(__METHOD__);
        $message = __('Product__success', ['name' => $this->name, 'type_act' => __('masculine_'.$this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }
}
