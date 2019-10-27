<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Product;
use App\Customevent;
use App\Mail\CategoryNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $guarded = [];
    
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

    public function countChildren() // учесть видимость (свою и родительскую)!
    {
        return $this->hasMany(Category::class, 'parent_id')->count();
    }

    public function countProducts() // учесть видимость (свою и родительскую)!
    {
        return $this->hasMany(Product::class)->count();
    }

    /**
     * Accessor
     * in controller using snake-case: $category->parent_visible!!!
     */
    public function getParentVisibleAttribute()
    {
        return $this->belongsTo(Category::class, 'parent_id')->get()->max('visible');
    }


    /**
     * Create records in table events.
     *
     * @return void?
     */
    public function createCustomevent()
    {
        info(__METHOD__);

        $attr = $this->getAttributes();
        $dirty = $this->getDirty();
        $original = $this->getOriginal();
        // dd($attr, $dirty, $original);

        $details = [];
        foreach ( $attr as $property => $value ) {
            if ( array_key_exists( $property, $dirty ) ) {
                $details[] = [ 
                    $property, 
                    $original[$property] ?? FALSE, 
                    $dirty[$property] ?? FALSE,
                ];
            }
        }

        Customevent::create([
            'user_id' => auth()->user()->id ?? $this->user_id ?? 7, // $this->user_id - for seeding; 7 - id for Undefined user.
            'model' => $this->getTable(),
            'model_id' => $this->id,
            'type' => debug_backtrace()[1]['function'],
            'description' => $this->description ?? FALSE,
            'details' => serialize($details) ?? FALSE,
            'description' => $this->description ?? FALSE,
        ]);
        return $this;
    }


    /**
     * Create event notification.
     * 
     * @return void?
     */
    public function sendEmailNotification()
    {
        info(__METHOD__);

        $type = debug_backtrace()[1]['function'];
        $namesetting = 'settings.email_' . $this->getTable() . '_' . $type;
        $setting = config($namesetting);

        info(__METHOD__ . ' ' . $namesetting . ' = ' . $setting);

        if ( $setting === '1' ) {

            $bcc = config('mail.mail_bcc');
            $additional_email_bcc = Setting::all()->firstWhere('name', 'additional_email_bcc');
            if ( $additional_email_bcc->value ) {
                $bcc = array_merge( $bcc, explode(', ', $additional_email_bcc->value));
            }
            $email_send_delay = Setting::all()->firstWhere('name', 'email_send_delay');
            $when = Carbon::now()->addMinutes($email_send_delay);
            $username = auth()->user() ? auth()->user()->name : 'Unregistered';

            \Mail::to( auth()->user() ?? config('mail.from.address') )
                ->bcc($bcc)
                ->later( 
                    $when, 
                    new CategoryNotification($this, $type, $username)
                );
        }
    }

    /**
     * set visible
     * 
     * @return  Category $category
     */
    // public function setVisibleYo () {
    //     info(__METHOD__);
    //     info('$this->getOriginal(\'visible\') = ');
    //     info($this->getOriginal('visible'));
    //     if( $this->visible !== 'on' ) {
    //         $this->visible = $this->getOriginal('visible');
    //     }
    //     return $this;
    // }

    /**
     * WORKAROUND #1 depricated_parent_visible
     * устанавливает атрибут visible потомков в соответствии с переданным значением
     * 
     * ПЕРЕДЕЛАТЬ! Добиться использования аксессоров в builder! 
     *
     * @return  Category $category
     */
    public function setChildrenVisible () {
        info(__METHOD__);
        info('$this->visible = ');
        info($this->visible);
        // info($this);
        // info($this->title); 
        // info($this->visible); 
        // info($this->getDirty()); 
        // info($this->getOriginal());


        if ( $this->isDirty('visible') ) {
            info('$this->isDirty(\'visible\')');

            // // for category top-level
            // if ( $this->children->count() ) {
            //     foreach ( $this->children as $children_category ) {
            //         $children_category->depricated_parent_visible = $this->visible;
            //         $children_category->save();

            //         if ( $children_category->products->count() ) {
            //             foreach ( $children_category->products as $product ) {
            //                 $product->depricated_grandparent_visible = $this->visible;
            //                 $product->save();
            //             };
            //         }

            //     };

            // // for subcategory
            // } elseif ( $this->products->count() ) {
            //     foreach ( $this->products as $product ) {
            //         $product->depricated_parent_visible = $this->visible;
            //         $product->save();
            //     };
            // }
            // for category top-level
            if ( $this->children->count() ) {
                // foreach ( $this->children as $children_category ) {
                //     $children_category->depricated_parent_visible = $this->visible;
                //     $children_category->save();

                //     if ( $children_category->products->count() ) {
                //         foreach ( $children_category->products as $product ) {
                //             $product->depricated_grandparent_visible = $this->visible;
                //             $product->save();
                //         };
                //     }

                // };
                // $this->children->depricated_parent_visible->push($this->visible);
                // $this->children->products->depricated_grandparent_visible->push($this->visible);
                // dd($this->children(), $this->children);



                // $this->children()->update(['depricated_parent_visible' => $this->visible]);
                // info($this->children()->update(['depricated_parent_visible' => $this->visible]));
                $this->children->each(function ($item, $key) {
                    // dd($item, $key);
                    // info($item->depricated_parent_visible);
                    // info('$item->update([\'depricated_parent_visible\' => $this->visible])');
                    // info('$this->visible = ');
                    info('$this->visible = ');
                    info($this->visible);
                    // $item->depricated_parent_visible = $this->visible;
                    // $item->save();
                    // $item->update(['depricated_parent_visible' => $this->visible]);
                    // info($item->depricated_parent_visible);
                });

            // for subcategory
            } elseif ( $this->products->count() ) {
                // foreach ( $this->products as $product ) {
                //     $product->depricated_parent_visible = $this->visible;
                //     $product->save();
                // };
                // $this->products->depricated_parent_visible->push($this->visible);

            }
        } else {
            info('!$this->isDirty(\'visible\')');
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
        info($this); info($this->title); info($this->visible); info($this->getDirty()); info($this->getOriginal());

        if ( !$this->title ) { $this->title = $this->name; }
        info($this);
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

    /**
     * set slug from dirty fiedl slug or title
     * при одновременном изменении slug и title трансформирует поле slug.
     * 
     * @return  Category $category
     */
    public function setSlug () {
        info(__METHOD__);
        if ( $this->isDirty('slug') ) {
            $this->slug = Str::slug($this->slug, '-');
        } elseif ( $this->isDirty('title') ) {
            $this->slug = Str::slug($this->title, '-');
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

}
