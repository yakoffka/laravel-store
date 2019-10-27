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
     * in controller using snake-case: $category->parent_seeable!!!
     */
    public function getParentSeeableAttribute()
    {
        return $this->belongsTo(Category::class, 'parent_id')->get()->max('seeable');
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
     * set seeable
     * 
     * @return  Category $category
     */
    // public function setSeeable () {
    //     info(__METHOD__);
    //     info('$this->getOriginal(\'seeable\') = ');
    //     info($this->getOriginal('seeable'));
    //     if( $this->seeable !== 'on' ) {
    //         $this->seeable = $this->getOriginal('seeable');
    //     }
    //     return $this;
    // }

    /**
     * WORKAROUND #1 parent_seeable
     * устанавливает атрибут seeable потомков в соответствии с переданным значением
     * 
     * ПЕРЕДЕЛАТЬ! Добиться использования аксессоров в builder! 
     *
     * @return  Category $category
     */
    public function setChildrenSeeable () {
        info(__METHOD__);
        info('$this->seeable = ');
        info($this->seeable);
        // info($this);
        // info($this->title); 
        // info($this->seeable); 
        // info($this->getDirty()); 
        // info($this->getOriginal());


        if ( $this->isDirty('seeable') ) {
            info('$this->isDirty(\'seeable\')');

            // // for category top-level
            // if ( $this->children->count() ) {
            //     foreach ( $this->children as $children_category ) {
            //         $children_category->parent_seeable = $this->seeable;
            //         $children_category->save();

            //         if ( $children_category->products->count() ) {
            //             foreach ( $children_category->products as $product ) {
            //                 $product->depricated_grandparent_seeable = $this->seeable;
            //                 $product->save();
            //             };
            //         }

            //     };

            // // for subcategory
            // } elseif ( $this->products->count() ) {
            //     foreach ( $this->products as $product ) {
            //         $product->parent_seeable = $this->seeable;
            //         $product->save();
            //     };
            // }
            // for category top-level
            if ( $this->children->count() ) {
                // foreach ( $this->children as $children_category ) {
                //     $children_category->parent_seeable = $this->seeable;
                //     $children_category->save();

                //     if ( $children_category->products->count() ) {
                //         foreach ( $children_category->products as $product ) {
                //             $product->depricated_grandparent_seeable = $this->seeable;
                //             $product->save();
                //         };
                //     }

                // };
                // $this->children->parent_seeable->push($this->seeable);
                // $this->children->products->depricated_grandparent_seeable->push($this->seeable);
                // dd($this->children(), $this->children);



                // $this->children()->update(['parent_seeable' => $this->seeable]);
                // info($this->children()->update(['parent_seeable' => $this->seeable]));
                $this->children->each(function ($item, $key) {
                    // dd($item, $key);
                    // info($item->parent_seeable);
                    // info('$item->update([\'parent_seeable\' => $this->seeable])');
                    // info('$this->seeable = ');
                    info('$this->seeable = ');
                    info($this->seeable);
                    // $item->parent_seeable = $this->seeable;
                    // $item->save();
                    // $item->update(['parent_seeable' => $this->seeable]);
                    // info($item->parent_seeable);
                });

            // for subcategory
            } elseif ( $this->products->count() ) {
                // foreach ( $this->products as $product ) {
                //     $product->parent_seeable = $this->seeable;
                //     $product->save();
                // };
                // $this->products->parent_seeable->push($this->seeable);

            }
        } else {
            info('!$this->isDirty(\'seeable\')');
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
        info($this); info($this->title); info($this->seeable); info($this->getDirty()); info($this->getOriginal());

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
