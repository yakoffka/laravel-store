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
        $arr_categories = Category::all()->toArray();

        for ($i=0; $i<100; $i++) {

            $manufacturer = $manufacturers->random();
            $category = $arr_categories[ rand(0, count($arr_categories)-1) ]; // no parent category with id == 1
            $name = $category['title']
                . ' '
                // .  ucwords($category['name'])
                .  $manufacturer->title
                // . ' Guitar ' 
                . $a[rand(0, strlen($a)-1)] 
                . $a[rand(0, strlen($a)-1)] 
                . '-' 
                . rand(10, 215);
            $materials = $arrMaterial[rand(0, count($arrMaterial)-1)];


            DB::table('products')->insert([
                'name' => $name,
                'manufacturer_id' => $manufacturer->id,
                'visible' => rand(0, 5) ? 1 : 0,
                'category_id' => $category['id'],
                'materials' => $materials,
                'description' => 'lorem ipsum, quia dolor sit amet consectetur adipiscing velit, sed quia non-numquam do eius modi tempora incididunt, ut labore et dolore magnam aliquam quaerat voluptatem.',
                'year_manufacture' => '2018',
                'price' => rand(20000,32000),
                'added_by_user_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

    }
}
