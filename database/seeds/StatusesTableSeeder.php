<?php

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['name' => 'processing',        'title' => 'title processing',        'description' => 'description processing',      'style' => 'danger'   ],
            ['name' => 'pending payment',   'title' => 'title pending payment',   'description' => 'description pending payment', 'style' => 'warning'  ],
            ['name' => 'shipped',           'title' => 'title shipped',           'description' => 'description shipped',         'style' => 'primary'  ],
            ['name' => 'complited',         'title' => 'title complited',         'description' => 'description complited',       'style' => 'success'  ],
            ['name' => 'on hold',           'title' => 'title on hold',           'description' => 'description on hold',         'style' => 'danger'   ],
            ['name' => 'canceled',          'title' => 'title canceled',          'description' => 'description canceled',        'style' => 'danger'   ],
            ['name' => 'refunded',          'title' => 'title refunded',          'description' => 'description refunded',        'style' => 'danger'   ],
            ['name' => 'failed',            'title' => 'title failed',            'description' => 'description failed',          'style' => 'danger'   ],
            ['name' => 'test',              'title' => 'title test',              'description' => 'description test',            'style' => 'secondary'],
        ];

        foreach ($statuses as $status) {
            DB::table('statuses')->insert([
                'name'          => $status['name'],
                'title'         => $status['title'],
                'description'   => $status['description'],
                'style'         => $status['style'],
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);    
        }
    }
}
