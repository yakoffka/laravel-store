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
        $manufacturers = [
            ['name' => 'noname',    'title' => 'NoName',    'description' => 'description for NoName',   ],
            ['name' => 'cort',      'title' => 'Cort',      'description' => 'description for Cort',     ],
            ['name' => 'fernandes', 'title' => 'Fernandes', 'description' => 'description for Fernandes',],
            ['name' => 'epiphone',  'title' => 'Epiphone',  'description' => 'description for Epiphone', ],
            ['name' => 'fender',    'title' => 'Fender',    'description' => 'description for Fender',   ],
        ];

        foreach ( $manufacturers as $manufacturer ) {
            DB::table('manufacturers')->insert([
                'name'          => $manufacturer['name'],
                'title'         => $manufacturer['title'],
                'description'   => $manufacturer['description'],
            ]);
        }
    }
}
