<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use App\Mail\ManufacturerNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mail;

/**
 * App\Manufacturer
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property string $slug
 * @property int $sort_order
 * @property string|null $title
 * @property string|null $description
 * @property string|null $imagepath
 * @property int $added_by_user_id
 * @property int|null $edited_by_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $creator
 * @property-read User|null $editor
 * @property-read Collection|Product[] $products
 * @property-read int|null $products_count
 * @method static Builder|Manufacturer newModelQuery()
 * @method static Builder|Manufacturer newQuery()
 * @method static Builder|Manufacturer query()
 * @method static Builder|Manufacturer whereAddedByUserId($value)
 * @method static Builder|Manufacturer whereCreatedAt($value)
 * @method static Builder|Manufacturer whereDescription($value)
 * @method static Builder|Manufacturer whereEditedByUserId($value)
 * @method static Builder|Manufacturer whereId($value)
 * @method static Builder|Manufacturer whereImagepath($value)
 * @method static Builder|Manufacturer whereName($value)
 * @method static Builder|Manufacturer whereSlug($value)
 * @method static Builder|Manufacturer whereSortOrder($value)
 * @method static Builder|Manufacturer whereTitle($value)
 * @method static Builder|Manufacturer whereUpdatedAt($value)
 * @method static Builder|Manufacturer whereUuid($value)
 * @mixin Eloquent
 */
class Manufacturer extends Model
{
    protected $guarded = [];
    private string $event_type = '';

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function countProducts(): int
    {
        return $this->hasMany(Product::class)->count();
    }


    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by_user_id')->withDefault([
            'name' => 'no author'
        ]);
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by_user_id')->withDefault([
            'name' => 'no editor'
        ]);
    }

    /**
     * set setCreator from auth user
     *
     * @return self $this
     */
    public function setCreator(): self
    {
        $this->added_by_user_id = auth()->user() ? auth()->user()->id : User::SYSUID;
        return $this;
    }

    /**
     * set setCreator from auth user
     *
     * @return self $this
     */
    public function setEditor(): self
    {
        $this->edited_by_user_id = auth()->user() ? auth()->user()->id : User::SYSUID;
        return $this;
    }

    /**
     * set title from dirty title or name fields
     *
     * @return self $this
     */
    public function setTitle(): self
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
     * @return self $this
     */
    public function setSlug(): self
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
     * @return self $this
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
            'user_id' => auth()->user() ? auth()->user()->id : User::SYSUID,
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
     * @return self $this
     */
    public function sendEmailNotification(): self
    {
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $this->event_type;
        $setting = config($namesetting);
        info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

        if ( $setting === '1' ) {
            $to = auth()->user();

            $bcc = array_merge(config('mail.mail_bcc'), explode(', ', config('settigs.additional_email_bcc')));
            $bcc = array_diff($bcc, ['', auth()->user() ? auth()->user()->email : '', config('mail.email_send_delay')]);
            $bcc = array_unique($bcc);

            Mail::to($to)->bcc($bcc)->later(
                Carbon::now()->addMinutes(config('mail.email_send_delay')),
                new ManufacturerNotification($this->getTable(), $this->id, $this->name, auth()->user()->name, $this->event_type)
            );
        }
        return $this;
    }

    /**
     * Копирует файл изображения, загруженный с помощью laravel-filemanager в директорию категории
     * и обновляет запись в базе данных.
     */
    public function attachSingleImage()
    {
        if (!$this->isDirty('imagepath') or !$this->imagepath) {
            return $this;
        }

        // смена файловой системы.
        // @todo WORKAROUND #0... не совсем разобрался с тонкостями Filesystem
        $src = str_replace(config('filesystems.disks.lfm.url'), '', $this->imagepath);
        $dst_dir = 'images/manufacturers/' . $this->uuid;
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
     * @return self $this
     */
    public function setFlashMess(): self
    {
        $message = __('Manufacturer__success', ['name' => $this->name, 'type_act' => __('masculine_' . $this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }

    /**
     * set uuid for naming source category
     *
     * @return self $this
     */
    public function setUuid(): self
    {
        $this->uuid = Str::uuid();
        return $this;
    }
}
