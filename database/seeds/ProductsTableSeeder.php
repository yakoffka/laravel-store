<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrManuf = ['Cort', 'Fernandes', 'Epiphone', 'Fender', ];
        $arrType = ['Bass', 'Acoustic', 'Electric', ];
        $arrMaterial = ['Basswood', 'Maple', 'Birch', 'Cast iron', ];
        $a = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        for ($i=0; $i<24; $i++) {

            $manufacturer = $arrManuf[rand(0, count($arrManuf)-1)];
            $name = $manufacturer
                . ' '
                . $arrType[rand(0, count($arrType)-1)] 
                . ' Guitar ' 
                . $a[rand(0, strlen($a)-1)] 
                . $a[rand(0, strlen($a)-1)] 
                . '-' 
                . rand(5, 215);
            $materials = $arrMaterial[rand(0, count($arrMaterial)-1)];


            DB::table('products')->insert([
                'name' => $name,
                'manufacturer' => $manufacturer,
                'materials' => $materials,
                'description' => 'lorem ipsum, quia dolor sit amet consectetur adipiscing velit, sed quia non-numquam do eius modi tempora incididunt, ut labore et dolore magnam aliquam quaerat voluptatem.',
                'year_manufacture' => '2018',
                'price' => rand(20000,32000),
                'added_by_user_id' => 1,
            ]);
        }

    }
}
