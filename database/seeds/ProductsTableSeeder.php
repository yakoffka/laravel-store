<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\Manufacturer;

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
        $arr_categories = Category::all()->where('parent_id', '>', 1)->toArray();


        for ($i=0; $i < config('custom.num_products_seed'); $i++) {

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
            $modification = !rand(0, 1) ? '' : '<table class="param"><tbody><tr><th>Г/п, т</th><th>Ширина ленты, мм</th><th>Минимальная длина L, м</th><th>Длина петли l, мм</th></tr><tr><td>1,0</td><td>30</td><td>1,0</td><td>250</td></tr><tr><td>2,0</td><td>60</td><td>1,0</td><td>350</td></tr><tr><td>3,0</td><td>90</td><td>1,0</td><td>400</td></tr><tr><td>4,0</td><td>120</td><td>1,5</td><td>450</td></tr><tr><td>5,0</td><td>150</td><td>1,5</td><td>450</td></tr><tr><td>6,0</td><td>180</td><td>1,5</td><td>500</td></tr><tr><td>8,0</td><td>240</td><td>2,0</td><td>500</td></tr><tr><td>10,0</td><td>300</td><td>2,0</td><td>550</td></tr><tr><td>12,0</td><td>300</td><td>2,0</td><td>600</td></tr><tr><td>15,0</td><td>300</td><td>2,5</td><td>600</td></tr><tr><td>20,0</td><td>300/600</td><td>2,5</td><td>600</td></tr><tr><td>25,0</td><td>300/600</td><td>2,5</td><td>600</td></tr><tr><td>30,0</td><td>300/600</td><td>6,0</td><td>600</td></tr></tbody></table>';

            DB::table('products')->insert([
                'name' => $name,
                'slug' => Str::slug($name, '-'),
                'manufacturer_id' => $manufacturer->id,
                'visible' => rand(0, 5) ? 1 : 0,
                'category_id' => $category['id'],
                'materials' => $materials,
                'description' => 'Description for product "' . $name . '". Lorem ipsum, quia dolor sit amet consectetur adipiscing velit, sed quia non-numquam do eius modi tempora incididunt, ut labore et dolore magnam aliquam quaerat voluptatem.',
                'modification' => $modification,
                'year_manufacture' => '2018',
                'price' => rand(20000,32000),
                'added_by_user_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

        }
    }
}