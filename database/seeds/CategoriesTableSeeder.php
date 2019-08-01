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
            
            // category
            ['name' => 'guitars',          'parent_id' => null  ],
            ['name' => 'keys',             'parent_id' => null  ],
            ['name' => 'drums',            'parent_id' => null  ],
            ['name' => 'lighting',         'parent_id' => null  ],
            ['name' => 'accessories',      'parent_id' => null  ],

            // subcategory
            ['name' => 'bass guitars',             'parent_id' => '1' ],
            ['name' => 'electric guitars',         'parent_id' => '1' ],
            ['name' => 'acoustic guitars',         'parent_id' => '1' ],
            
            ['name' => 'acoustic pianos',  'parent_id' => '2' ],
            ['name' => 'grand pianos',     'parent_id' => '2' ],
            ['name' => 'digital pianos',   'parent_id' => '2' ],
            
            ['name' => 'strings',          'parent_id' => '5' ],
            ['name' => 'mediators',        'parent_id' => '5' ],
            ['name' => 'tools',            'parent_id' => '5' ],
            ['name' => 'notes',            'parent_id' => '5' ],
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
