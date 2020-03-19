<?php

use Illuminate\Database\Seeder;
use App\{Product, Image};
use App\Traits\Yakoffka\ImageYoTrait;

class ImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // only for 'MUSIC' template
        if ( config('custom.store_theme') === 'MUSIC' ) {

            $products = Product::all();

            foreach ( $products as $i => $product ) {

                $images = [
                    $product->category['slug'] . '_1',
                    $product->category['slug'] . '_2',
                    $product->category['slug'] . '_3',
                    $product->category['slug'] . '_4',
                ];

                foreach($images as $j => $image) {
                    $def_pathname  = storage_path() . '/app/public/images/default/category/' . $image . config('imageyo.res_ext');
                    $path  = '/images/products/' . $product->id;

                    if ( is_file($def_pathname) ) {
                        $image_name = ImageYoTrait::saveImgSet($def_pathname, $product->id, 'seed');
                        $image = Image::create([
                            'product_id' => $product->id,
                            'slug' => Str::slug($image_name, '-'),
                            'path' => $path,
                            'name' => $image_name,
                            'ext' => config('imageyo.res_ext'),
                            'alt' => 'seed',
                            'sort_order' => rand(1, 9),
                            'orig_name' => 'seed',
                        ]);
                    }

                    // progress
                    $all_items = config('custom.num_products_seed') * count($images);
                    $percent = 100 / $all_items;
                    $quantity = ( $i * count($images) + $j + 1 );
                    $progress = round($percent * $quantity, 2);
                    $str_progress = (string)$progress;

                    if (!strpos($str_progress, '.')) {
                        $str_progress .= '.00';
                    } elseif ( strpos($str_progress, '.') === 2 && strlen($str_progress) === 4 ) {
                        $str_progress = str_pad($str_progress, 5, '0');
                    } else {
                        $str_progress = str_pad($str_progress, 4, '0');
                    }

                    $str_progress = str_pad($str_progress, 5, '0', STR_PAD_LEFT);
                    echo '    image conversion: ' . $str_progress . "% completed\n";
                }
            }
            echo '    exit from ' . __method__ . ";\n";
        }
    }
}
