<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use App\Mail\CategoryNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mail;

/**
 * App\Category
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $slug
 * @property int $sort_order
 * @property string $title
 * @property string|null $description
 * @property string|null $imagepath
 * @property boolean $publish
 * @property int|null $parent_id
 * @property int $added_by_user_id
 * @property int|null $edited_by_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Category[] $children
 * @property-read int|null $children_count
 * @property-read string $full_image_path
 * @property-read int $value_for_trans_choice_children
 * @property-read int $value_for_trans_choice_products
 * @property-read Category|null $parent
 * @property-read Collection|Product[] $products
 * @property-read int|null $products_count
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereAddedByUserId($value)
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereDescription($value)
 * @method static Builder|Category whereEditedByUserId($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereImagepath($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category whereParentSeeable($value)
 * @method static Builder|Category whereSeeable($value)
 * @method static Builder|Category whereSlug($value)
 * @method static Builder|Category whereSortOrder($value)
 * @method static Builder|Category whereTitle($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @method static Builder|Category whereUuid($value)
 * @mixin Eloquent
 */
class Category extends Model
{
    protected $guarded = [];
    private string $event_type = '';

    /**
     * @return BelongsTo
     */
    public function parent(): belongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return HasMany
     */
    public function publishedProducts(): HasMany
    {
        return $this->hasMany(Product::class)->where('publish', '=', true);
    }

    /**
     * @return HasMany
     */
    public function categories(): hasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function childrenCategories(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->with('categories');
    }

    /**
     * @return HasMany
     */
    public function children(): hasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * @return BelongsTo
     */
    public function getAllParents(): BelongsTo
    {
        return $this->parent()->with($this->getAllParents());
    }

    /**
     * @return HasMany
     */
    public function allChildren(): HasMany
    {
        return $this->hasMany(self::class)->with($this->children());
    }

    /**
     * @return int
     */
    public function countChildren(): int
    {
        return $this->children()->count();
    }

    /**
     * @return int
     */
    public function countProducts(): int
    {
        return $this->products()->count();
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return $this->countChildren() > 0;
    }

    /**
     * @return bool
     */
    public function hasProducts(): bool
    {
        return $this->countProducts() > 0;
    }

    /**
     * @return bool
     */
    public function hasDescendant(): bool
    {
        return $this->hasProducts() or $this->hasChildren();
    }

    /**
     * @return bool
     */
    public function isPublish(): bool
    {
        if ($this->parent_id === null) {
            return $this->publish;
        }
        return $this->publish && $this->parent->publish;
    }

    /**
     * @return bool
     */
    public function parentSeeable(): bool
    {
        return $this->parent->publish;
    }

    /**
     * Accessor
     *
     * @return integer [^1]{1}\d or [1]{1}\d
     *
     * in controller or blade using snake-case: $category->value_for_trans_choice_children
     */
    public function getValueForTransChoiceChildrenAttribute(): int
    {
        if (substr($this->children->count(), -2, 1) === '1') {
            return substr($this->children->count(), -2);
        }

        return substr($this->children->count(), -1);
    }

    /**
     * Accessor
     *
     * @return integer [^1]{1}\d or [1]{1}\d
     *
     * in controller or blade using snake-case: $category->value_for_trans_choice_products
     */
    public function getValueForTransChoiceProductsAttribute(): int
    {
        if (substr($this->products->count(), -2, 1) === '1') {
            return substr($this->products->count(), -2);
        }

        return substr($this->products->count(), -1);
    }

    /**
     * Accessor
     * @return string
     */
    public function getFullImagePathAttribute(): string
    {
        if ($this->imagepath) {
            return '/images/categories/' . $this->uuid . '/' . $this->imagepath;
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
        $this->attributes['publish'] = ($value === 'on' || $value === true );
    }

    /**
     * Копирует файл изображения, загруженный с помощью laravel-filemanager в директорию категории
     * и обновляет запись в базе данных.
     *
     */
    public function attachSingleImage()
    {
        if (!$this->imagepath || !$this->isDirty('imagepath')) {
            return $this;
        }

        // смена файловой системы.
        // переделать. WORKAROUND #0... не совсем разобрался с тонкостями Filesystem
        $src = str_replace(config('filesystems.disks.lfm.url'), '', $this->imagepath);
        $dst_dir = 'images/categories/' . $this->uuid;
        $basename = basename($src);
        $dst = $dst_dir . '/' . $basename;

        // избыточная? проверка на существование исходного файла. так как config('filesystems.disks.lfm.root') === config('filesystems.disks.public.root'), использую не 'Storage::disk(config('lfm.disk'))->exists($src)', а 'Storage::disk('public')->exists($src)'
        if (!Storage::disk('public')->exists($src)) {
            return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
        }

        // удаление всех файлов из директории назначения
        Storage::disk('public')->deleteDirectory($dst_dir);

        // копирование файла
        if (!Storage::disk('public')->copy($src, $dst)) {
            return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
        }

        $this->imagepath = $basename;
        return $this;
    }

    /**
     * Create records in table events.
     *
     * @return  $this
     */
    public function createCustomevent(): self
    {
        $this->event_type = debug_backtrace()[1]['function'];
        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();

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
            'user_id' => auth()->user()->id,
            'model' => $this->getTable(),
            'model_id' => $this->id,
            'model_name' => $this->name,
            'type' => $this->event_type,
            'description' => $this->event_description ?? FALSE,
            'details' => serialize($details) ?? '',
        ]);
        return $this;
    }

    /**
     * Create event notification.
     *
     * @return $this
     */
    public function sendEmailNotification(): self
    {
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $this->event_type;
        $setting = config($namesetting);
        info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

        if ($setting === '1') {
            $to = auth()->user();

            $bcc = array_merge(config('mail.mail_bcc'), explode(', ', config('settigs.additional_email_bcc')));
            $bcc = array_diff($bcc, ['', auth()->user() ? auth()->user()->email : '', config('mail.email_send_delay')]);
            $bcc = array_unique($bcc);

            Mail::to($to)->bcc($bcc)->later(
                Carbon::now()->addMinutes(config('mail.email_send_delay')),
                new CategoryNotification($this->getTable(), $this->id, $this->name, auth()->user()->name, $this->event_type)
            );

            // restarting the queue to make sure they are started
            if (!empty(config('custom.exec_queue_work'))) {
                info(__METHOD__ . ': ' . exec(config('custom.exec_queue_work')));
            }
        }
        return $this;
    }

    /**
     * sets message the variable for the next request only
     *
     * @return  $this
     */
    public function setFlashMess(): self
    {
        $message = __('Category__success', ['name' => $this->name, 'type_act' => __('feminine_' . $this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }

    /**
     * set setCreator from auth user
     *
     * @return  $this
     */
    public function setCreator(): self
    {
        $this->added_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * set setCreator from auth user
     *
     * @return  $this
     */
    public function setEditor(): self
    {
        $this->edited_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * set slug from dirty field slug or title
     * while changing slug and title transforms the slug field.
     *
     * @return  $this
     */
    public function setSlug(): self
    {
        if ($this->slug && $this->isDirty('slug')) {
            $this->slug = Str::slug($this->slug, '-');
        } elseif ($this->isDirty('title')) {
            $this->slug = Str::slug($this->title, '-');
        }
        return $this;
    }

    /**
     * set title from dirty title or name fields
     *
     * @return  $this
     */
    public function setTitle(): self
    {
        if (!$this->title) {
            $this->title = $this->name;
        }
        return $this;
    }

    /**
     * set uuid for naming source category
     *
     * @return  $this
     */
    public function setUuid(): self
    {
        $this->uuid = Str::uuid();
        return $this;
    }
}
