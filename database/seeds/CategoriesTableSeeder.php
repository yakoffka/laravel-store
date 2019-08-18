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
            
            // catalog
            ['name' => 'catalog',          'parent_id' => 1  ],
            
            // category
            ['name' => 'guitars',          'parent_id' => 1  ],
            ['name' => 'keys',             'parent_id' => 1  ],
            ['name' => 'drums',            'parent_id' => 1  ],
            ['name' => 'lighting',         'parent_id' => 1  ],
            ['name' => 'accessories',      'parent_id' => 1  ],

            // subcategory
            ['name' => 'bass guitars',             'parent_id' => 2 ],
            ['name' => 'electric guitars',         'parent_id' => 2 ],
            ['name' => 'acoustic guitars',         'parent_id' => 2 ],
            
            ['name' => 'acoustic pianos',  'parent_id' => 3 ],
            ['name' => 'grand pianos',     'parent_id' => 3 ],
            ['name' => 'digital pianos',   'parent_id' => 3 ],
            
            ['name' => 'strings',          'parent_id' => 6 ],
            ['name' => 'mediators',        'parent_id' => 6 ],
            ['name' => 'tools',            'parent_id' => 6 ],
            ['name' => 'notes',            'parent_id' => 6 ],
        ];

        $key = 1;
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => Str::slug($category['name'], '-'),
                'sort_order' => $category['parent_id'] ? 0 : $key++,
                'title' => ucwords($category['name']),
                'description' => 'Description ' . ucwords($category['name']),
                'visible' => true,
                'parent_id' => $category['parent_id'],
                'added_by_user_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

    }
}
