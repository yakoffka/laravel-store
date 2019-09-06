<?php

use Illuminate\Database\Seeder;

class ManufacturersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ( config('custom.store_theme') == 'MUSIC' ) {

            $manufacturers = [
                ['name' => 'noname',    'title' => 'NoName',    'description' => 'description for NoName',   ],
                ['name' => 'cort',      'title' => 'Cort',      'description' => 'description for Cort',     ],
                ['name' => 'fernandes', 'title' => 'Fernandes', 'description' => 'description for Fernandes',],
                ['name' => 'epiphone',  'title' => 'Epiphone',  'description' => 'description for Epiphone', ],
                ['name' => 'fender',    'title' => 'Fender',    'description' => 'description for Fender',   ],
            ];

        } else {
            
            $manufacturers = [
                ['name' => 'noname',    'title' => 'NoName',     'description' => 'description for NoName',           ],
                ['name' => 'Еврокран',  'title' => 'Еврокран',   'description' => 'description for Еврокран',         ],
                ['name' => 'ЮКЗ',       'title' => 'ЮКЗ',        'description' => 'Южный крановый завод «ЮКЗ»',       ],
                ['name' => 'Гертек',    'title' => 'Гертек',     'description' => 'Казанский крановый завод «Гертек»',],
                ['name' => 'ДКЗ',       'title' => 'ДКЗ',        'description' => 'Димитровградский крановый завод',  ],
            ];
        }


        foreach ( $manufacturers as $manufacturer ) {
            DB::table('manufacturers')->insert([
                'name'          => $manufacturer['name'],
                'slug'          => Str::slug($manufacturer['name'], '-'),
                'title'         => $manufacturer['title'],
                'description'   => $manufacturer['description'],
            ]);
        }
    }
}
