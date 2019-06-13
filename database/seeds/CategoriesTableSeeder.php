<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'main',          'title' => 'Catalog',           'description' => 'Parent category',],
            ['name' => 'bass',          'title' => 'Bass Guitar',       'description' => 'Bass guitar description',],
            ['name' => 'electric',      'title' => 'Electric Guitar',   'description' => 'Electric guitar description',],
            ['name' => 'acoustic',      'title' => 'Acoustic Guitar',   'description' => 'Acoustic guitar description',],
            ['name' => 'accessories',   'title' => 'Accessories',       'description' => 'Accessories description',],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'title' => $category['title'],
                'description' => $category['description'] . ' lorem ipsum, quia dolor sit amet consectetur adipiscing velit, sed quia non-numquam do eius modi tempora incididunt, ut labore et dolore magnam aliquam quaerat voluptatem.',
                'visible' => true,
                'parent_id' => 1,
                'added_by_user_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

    }
}
