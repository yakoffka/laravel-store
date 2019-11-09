<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Product;
use App\Customevent;
use App\Mail\CategoryNotification;
use Illuminate\Support\Facades\Storage;
use Str;

class Category extends Model
{
    protected $guarded = [];
    // public $appends = [
    //     'parent_seeable', // shared accessor getParentSeeableAttribute
    // ];
    private $event_type = '';


    public function products() 
    {
        return $this->hasMany(Product::class);
    }

    public function parent() 
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // public function countChildren() // учесть видимость (свою и родительскую)!
    // {
    //     // return $this->hasMany(Category::class, 'parent_id')->count();
    //     return $this->children()->count();
    // }

    // public function countProducts() // учесть видимость (свою и родительскую)!
    // {
    //     // return $this->hasMany(Product::class)->count();
    //     return $this->products()->count();
    // }

    /**
     * Accessor
     * in controller or blade using snake-case: $category->parent_seeable!!!
     */
    public function getParentSeeableAttribute()
    {
        return $this->belongsTo(Category::class, 'parent_id')->get()->max('seeable');
        // return $this->parent->get()->max('seeable');
    }

    /**
     * Accessor
     * 
     * @return unsigned integer [^1]{1}\d or [1]{1}\d
     * 
     * in controller or blade using snake-case: $category->value_for_trans_choice_children
     */
    public function getValueForTransChoiceChildrenAttribute()
    {
        $countChildren = $this->children->count();
        if( substr($countChildren, -2, 1) === '1' ) {
            return substr($countChildren, -2);
        } else {
            return substr($countChildren, -1);
        }
    }

    /**
     * Accessor
     * 
     * @return unsigned integer [^1]{1}\d or [1]{1}\d
     * 
     * in controller or blade using snake-case: $category->value_for_trans_choice_products
     */
    public function getValueForTransChoiceProductsAttribute()
    {
        $countProducts = $this->children->count();
        if( substr($countProducts, -2, 1) === '1' ) {
            return substr($countProducts, -2);
        } else {
            return substr($countProducts, -1);
        }
    }



    /**
     * Копирует файл изображения, загруженный с помощью laravel-filemanager в директорию категории
     * и обновляет запись в базе данных. 
     *
     * @return  Category $category
     */
    public function attachSingleImage () {
        info(__METHOD__);
        if ( !$this->isDirty('imagepath') or !$this->imagepath ) {
            return $this;
        }

        // смена файловой системы. 
        // переделать. WORKAROUND #0... не совсем разобрался с тонкостями Filesystem
        $src = str_replace( config('filesystems.disks.lfm.url'), '', $this->imagepath );
        $dst_dir = 'images/categories/' . $this->uuid;
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

    /**
     * Create records in table events.
     *
     * @return  Category $category
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
     * @return Comment $comment
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
                new CategoryNotification($this->getTable(), $this->id, $this->name, auth()->user()->name, $this->event_type)
            );
        }
        return $this;
    }

    /**
     * sets message the variable for the next request only
     * 
     * @return  Category $category
     */
    public function setFlashMess()
    {
        info(__METHOD__);
        $message = __('Category__success', ['name' => $this->name, 'type_act' => __('feminine_'.$this->event_type)]);
        session()->flash('message', $message);
        return $this;
    }

    /**
     * set setCreator from auth user
     * 
     * @return  Category $category
     */
    public function setCreator () {
        info(__METHOD__);
        $this->added_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * set setCreator from auth user
     * 
     * @return  Category $category
     */
    public function setEditor () {
        info(__METHOD__);
        $this->edited_by_user_id = auth()->user()->id;
        return $this;
    }

    /**
     * set slug from dirty fiedl slug or title
     * while changing slug and title transforms the slug field.
     * 
     * @return  Category $category
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
     * set title from dirty title or name fields
     * 
     * @return  Category $category
     */
    public function setTitle () {
        info(__METHOD__);
        if ( !$this->title ) { $this->title = $this->name; }
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
