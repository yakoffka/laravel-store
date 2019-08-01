<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\Manufacturer;
use App\Product;
use App\Image;
use App\Traits\Yakoffka\ImageYoTrait; // Traits???


class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manufacturers = Manufacturer::all();
        $arrMaterial = ['Basswood', 'Maple', 'Birch', 'Cast iron', ];
        $a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        // $arr_categories = Category::all()->where('parent_id', '>', 0)->toArray();
        $categories = Category::all();

        foreach ( $categories as $key => $category ) {
            if ( $category->children()->count() ) {
                Arr::pull($categories, $key);
            }
        }

        $arr_categories = $categories->toArray();

        for ($i=0; $i<config('custom.products_seed'); $i++) {

            $manufacturer = $manufacturers->random();
            $category = $arr_categories[array_rand($arr_categories)];
            $name = $category['title']
                . ' '
                .  $manufacturer->title
                . ' '
                . $a[rand(0, strlen($a)-1)]
                . $a[rand(0, strlen($a)-1)]
                . '-' 
                . rand(10, 215);
            $materials = $arrMaterial[rand(0, count($arrMaterial)-1)];
            $images = [
                $category['slug'] . '_1',
                $category['slug'] . '_2',
                $category['slug'] . '_3',
            ];


            // DB::table('products')->insert([
            //     'name' => $name,
            //     'manufacturer_id' => $manufacturer->id,
            //     'visible' => rand(0, 5) ? 1 : 0,
            //     'category_id' => $category['id'],
            //     'materials' => $materials,
            //     'description' => 'lorem ipsum, quia dolor sit amet consectetur adipiscing velit, sed quia non-numquam do eius modi tempora incididunt, ut labore et dolore magnam aliquam quaerat voluptatem.',
            //     'image' => $image,
            //     'year_manufacture' => '2018',
            //     'price' => rand(20000,32000),
            //     'added_by_user_id' => 1,
            //     'created_at' => date('Y-m-d H:i:s'),
            //     'updated_at' => date('Y-m-d H:i:s'),
            // ]);
            $product = Product::create([
                'name' => $name,
                'slug' => Str::slug($name, '-'),
                'manufacturer_id' => $manufacturer->id,
                'visible' => rand(0, 5) ? 1 : 0,
                'category_id' => $category['id'],
                'materials' => $materials,
                'description' => 'lorem ipsum, quia dolor sit amet consectetur adipiscing velit, sed quia non-numquam do eius modi tempora incididunt, ut labore et dolore magnam aliquam quaerat voluptatem.',
                // 'image' => $image,
                'year_manufacture' => '2018',
                'price' => rand(20000,32000),
                'added_by_user_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            foreach($images as $image) {
                $def_pathname  = storage_path() . '/app/public/images/default/category/' . $image . config('imageyo.res_ext');
                $path  = '/images/products/' . $product->id;
    
                if ( is_file($def_pathname)) {
                    // $product->image = ImageYoTrait::saveImgSet($def_pathname, $product->id, 'seed');
                    // $product->update();
                    $image_name = ImageYoTrait::saveImgSet($def_pathname, $product->id, 'seed');
                    $image = Image::create([
                        'product_id' => $product->id,
                        'slug' => Str::slug($image_name, '-'),
                        'path' => $path,
                        'name' => $image_name,
                        'ext' => config('imageyo.res_ext'),
                        'alt' => 'seed',
                        // 'sort_order' => rand(1, 9),
                        'sort_order' => 9,
                        'orig_name' => 'seed',
                    ]);
                }
            }
            
        }

    }
}