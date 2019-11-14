<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Customevent;
use App\Mail\ManufacturerNotification;
use Illuminate\Support\Facades\Storage;
use Str;

class Manufacturer extends Model
{
    protected $guarded = [];
    private $event_type = '';

    public function products() {
        return $this->hasMany(Product::class);
    }

    public function countProducts() {
        return $this->hasMany(Product::class)->count();
    }


    public function creator() {
        return $this->belongsTo(User::class, 'added_by_user_id')->withDefault([
            'name' => 'no author'
        ]);
    }

    public function editor() {
        // return $this->belongsTo(User::class, 'edited_by_user_id');
        return $this->belongsTo(User::class, 'edited_by_user_id')->withDefault([
            'name' => 'no editor'
        ]);
    }


    /**
     * set setCreator from auth user
     * 
     * @return  Manufacturer
     */
    public function setCreator () {
        info(__METHOD__);
        $this->added_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * set setCreator from auth user
     * 
     * @return  Manufacturer
     */
    public function setEditor () {
        info(__METHOD__);
        $this->edited_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * set title from dirty title or name fields
     * 
     * @return  Manufacturer
     */
    public function setTitle () {
        info(__METHOD__);
        if ( !$this->title ) { $this->title = $this->name; }
        return $this;
    }

    /**
     * set slug from dirty fiedl slug or title
     * при одновременном изменении slug и title трансформирует поле slug.
     * 
     * @return  Manufacturer
     */
    public function setSlug () {
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
     * @return  Manufacturer
     */
    public function createCustomevent()
    {
        info(__METHOD__);
        $this->event_type = debug_backtrace()[1]['function'];
        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();

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
            'description' => $this->event_description ?? FALSE,
            'details' => serialize($details) ?? FALSE,
        ]);
        return $this;
    }


    /**
     * Create event notification.
     * 
     * @return  Manufacturer
     */
    public function sendEmailNotification()
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
                new ManufacturerNotification($this->getTable(), $this->id, $this->name, auth()->user()->name, $this->event_type)
            );

            // restarting the queue to make sure they are started
            if( !empty(config('custom.exec_queue_work')) ) {
                info(__METHOD__ . ': ' . exec(config('custom.exec_queue_work')));
            }
        }
        return $this;
    }

    /**
     * Копирует файл изображения, загруженный с помощью laravel-filemanager в директорию категории
     * и обновляет запись в базе данных. 
     *
     * @return  string $imagepath
     */
    public function attachSingleImage () {
        info(__METHOD__);

        if ( !$this->isDirty('imagepath') or !$this->imagepath ) {
            return $this;
        }

        // смена файловой системы. 
        // переделать. WORKAROUND #0... не совсем разобрался с тонкостями Filesystem
        $src = str_replace( config('filesystems.disks.lfm.url'), '', $this->imagepath );
        $dst_dir = 'images/manufacturers/' . $this->uuid;
        $basename = basename($src);
        $dst = $dst_dir . '/' . $basename;

        // избыточная? проверка на существование исходного файла. так как config('filesystems.disks.lfm.root') === config('filesystems.disks.public.root'), использую не 'Storage::disk(config('lfm.disk'))->exists($src)', а 'Storage::disk('public')->exists($src)'
        if ( !Storage::disk('public')->exists($src) ) {
            return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
        }

        // удаление всех файлов из директории назначения
        Storage::disk('public')->deleteDirectory($dst_dir);

        // копирование файла
        if ( !Storage::disk('public')->copy($src, $dst) ) {
            return back()->withErrors(['something wrong. err' . __LINE__])->withInput();
        }

        $this->imagepath = $basename;
        return $this;
    }

    public function setFlashMess()
    {
        info(__METHOD__);
        $message = __('Manufacturer__success', ['name' => $this->name, 'type_act' => __('masculine_'.$this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }

    /**
     * set uuid for naming source category
     * 
     * @return  Category $category
     */
    public function setUuid () {
        info(__METHOD__);
        $this->uuid = Str::uuid();
        return $this;
    }
}
