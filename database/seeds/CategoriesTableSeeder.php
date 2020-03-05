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

        if ( config('custom.store_theme') == 'MUSIC' ) {

            $categories = [

                // catalog
                ['name' => 'catalog',          'parent_id' => null  ],

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

        } else {

            $categories = [

                // catalog
                ['parent_id' => 1, 'slug' => 'catalog', 'name' => 'Каталог',],


                // categories
                ['parent_id' => 1, 'slug' => 'slings',          'name' => 'Стропы',],
                ['parent_id' => 1, 'slug' => 'grips',           'name' => 'СКГ',                'title' => 'Системы крепления грузов'       ],
                ['parent_id' => 1, 'slug' => 'components',      'name' => 'Комплектующие',],
                ['parent_id' => 1, 'slug' => 'capture',         'name' => 'Захваты',],
                ['parent_id' => 1, 'slug' => 'mounting_blocks', 'name' => 'БМ',    'title' => 'Блоки монтажные',               ],
                ['parent_id' => 1, 'slug' => 'jacks',           'name' => 'Домкраты',],
                ['parent_id' => 1, 'slug' => 'traverse',        'name' => 'Траверсы',],
                ['parent_id' => 1, 'slug' => 'winch',           'name' => 'Лебедки',],
                ['parent_id' => 1, 'slug' => 'hoists',          'name' => 'Тали',],
                ['parent_id' => 1, 'slug' => 'mech_hoists',     'name' => 'МПТ',                'title' => 'Механизмы передвижения талей'   ],


                // subcategories
                ['parent_id' => 2, 'slug' => 'textile_tape',        'name' => 'ТЛС',                    'title' => 'Текстильные ленточные стропы',],
                ['parent_id' => 2, 'slug' => 'round_strand',        'name' => 'КС',                     'title' => 'Круглопрядные стропы',],
                ['parent_id' => 2, 'slug' => 'chain_slings',        'name' => 'ЦС',                     'title' => 'Цепные стропы',],
                ['parent_id' => 2, 'slug' => 'rope',                'name' => 'КаС',                    'title' => 'Канатные стропы',],
                ['parent_id' => 2, 'slug' => 'leveler_blocks',      'name' => 'СУБ',                    'title' => 'Стропы с уравнительными блоками',],
                ['parent_id' => 2, 'slug' => 'mounting_soft_towels','name' => 'ММП',                    'title' => 'Мягкие монтажные полотенца',],

                ['parent_id' => 3, 'slug' => 'ratchet',             'name' => 'Рэтчеты',                'title' => '(Цепные стяжки)',],
                ['parent_id' => 3, 'slug' => 'tie_down_straps',     'name' => 'Стяжные ремни',          'title' => 'Круглопрядные стропы',],
                ['parent_id' => 3, 'slug' => 'tie_down_straps_ring','name' => 'СРК',                    'title' => 'Стяжные ремни кольцевые',],
                ['parent_id' => 3, 'slug' => 'turnbuckle',          'name' => 'Талрепы',],

                ['parent_id' => 4, 'slug' => 'chain',       'name' => 'Цепи',],
                ['parent_id' => 4, 'slug' => 'tape',        'name' => 'Ленты',],
                ['parent_id' => 4, 'slug' => 'links',       'name' => 'Звенья',],
                ['parent_id' => 4, 'slug' => 'hook',        'name' => 'Крюки',],
                ['parent_id' => 4, 'slug' => 'bracket',     'name' => 'Скобы',],
                ['parent_id' => 4, 'slug' => 'thimble',     'name' => 'Коуша',],
                ['parent_id' => 4, 'slug' => 'sleeve',      'name' => 'Втулки',],
                ['parent_id' => 4, 'slug' => 'ring_bolt',   'name' => 'Рымы',],
                ['parent_id' => 4, 'slug' => 'clamp',       'name' => 'Зажимы',],

                ['parent_id' => 5, 'slug' => 'for_concrete',        'name' => 'Для бетонных изделий',],
                ['parent_id' => 5, 'slug' => 'for_sandwich_panels', 'name' => 'Для сендвич-панелей',],
                ['parent_id' => 5, 'slug' => 'for_sheet_metal',     'name' => 'Для листового металла',],
                ['parent_id' => 5, 'slug' => 'for_rails',           'name' => 'Для сортового проката и рельс',],
                ['parent_id' => 5, 'slug' => 'for_tubes',           'name' => 'Для труб и грузов круглой формы',],
                ['parent_id' => 5, 'slug' => 'for_pallets',         'name' => 'Для поддонов',],
                ['parent_id' => 5, 'slug' => 'for_rolls',           'name' => 'Для рулонов стали и бухт',],
                ['parent_id' => 5, 'slug' => 'for_barrels',         'name' => 'Для бочек',],
                ['parent_id' => 5, 'slug' => 'for_cable_drums',     'name' => 'Для кабельных барабанов',],

                ['parent_id' => 6, 'slug' => 'mounting_blocks_closed',          'name' => 'Блоки закрытые',],
                ['parent_id' => 6, 'slug' => 'mounting_blocks_opened',          'name' => 'Блоки открытые',],
                ['parent_id' => 6, 'slug' => 'mounting_blocks_light_weight',    'name' => 'Блоки облегченные',],
                ['parent_id' => 6, 'slug' => 'blocks_with_mounting_platform',   'name' => 'Блоки с площадкой крепления',],
                ['parent_id' => 6, 'slug' => 'hook_suspension',                 'name' => 'Крюковая подвеска',],
                ['parent_id' => 6, 'slug' => 'manifest_blocks',                 'name' => 'Конифаст-блоки HQGK',],
                ['parent_id' => 6, 'slug' => 'mounting_blocks_under_the_rope',  'name' => 'Блоки монтажные под веревку',],

                ['parent_id' => 7, 'slug' => 'jacks_screw',     'name' => 'Домкраты винтовые',],
                ['parent_id' => 7, 'slug' => 'jacks_hydraulic', 'name' => 'Домкраты гидравлические',],
                ['parent_id' => 7, 'slug' => 'jacks_rack',      'name' => 'Домкраты реечные',],

                // Symfony\Component\Debug\Exception\FatalThrowableError  : Invalid numeric literal
                // ['parent_id' => 8, 'slug' => 'traverse_center', 'name' => 'линейные с подвесом за центр',],
                // ['parent_id' => 8, 'slug' => 'traverse_with_2', 'name' => 'линейные с двумя точками крепления',],
                // ['parent_id' => 8, 'slug' => 'traverse_h', 'name' => 'Н-образные',],
                // ['parent_id' => 8, 'slug' => 'frame_traverse', 'name' => 'рамные',],
                ['parent_id' => 8, 'slug' => 'traverse_center',     'name' => 'Линейные с подвесом за центр',],
                ['parent_id' => 8, 'slug' => 'traverse_with_2',     'name' => 'Линейные с двумя точками крепления',],
                ['parent_id' => 8, 'slug' => 'traverse_h',          'name' => 'Н-образные',],
                ['parent_id' => 8, 'slug' => 'frame_traverse',      'name' => 'Рамные',],

                ['parent_id' => 9, 'slug' => 'wml', 'name' => 'Лебедки ручные рычажные, МТМ',],
                ['parent_id' => 9, 'slug' => 'wmw', 'name' => 'Лебедки ручные червячные',],
                ['parent_id' => 9, 'slug' => 'wmd', 'name' => 'Лебедки ручные барабанные',],
                ['parent_id' => 9, 'slug' => 'we',  'name' => 'Лебедки электрические',],

                ['parent_id' => 10, 'slug' => 'electric_wire_rope', 'name' => 'Электрические',],
                ['parent_id' => 10, 'slug' => 'worm_gear_manual',   'name' => 'Ручные червячные',],
                ['parent_id' => 10, 'slug' => 'manual_lever',       'name' => 'Ручные рычажные',],
                ['parent_id' => 10, 'slug' => 'gear_manual',        'name' => 'Ручные шестеренные',],

                ['parent_id' => 11, 'slug' => 'mech_hoists_mobile', 'name' => 'Тавровые подвески подвижные',],
                ['parent_id' => 11, 'slug' => 'mech_hoists_still',  'name' => 'Тавровые подвески неподвижные',],
                ['parent_id' => 11, 'slug' => 'trolleys_for_hoists','name' => 'Тележки для тали GCL GCT',],

            ];

        }


        $key = 1;
        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'uuid' => Str::uuid(),
                'name' => $category['name'],
                'slug' => $category['slug'] ?? Str::slug($category['name'], '-'),
                'sort_order' => 5,
                'title' => $category['title'] ?? ucwords($category['name']),
                'description' => 'Description ' . ucwords($category['name']),
                'seeable' => true,
                'parent_id' => $category['parent_id'],
                'added_by_user_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
