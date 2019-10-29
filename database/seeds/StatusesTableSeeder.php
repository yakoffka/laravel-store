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
            ['name' => 'processing',        'title' => 'title_processing',        'description' => 'description_processing',      'style' => 'danger'   ],
            ['name' => 'pending payment',   'title' => 'title_pending payment',   'description' => 'description_pending payment', 'style' => 'warning'  ],
            ['name' => 'shipped',           'title' => 'title_shipped',           'description' => 'description_shipped',         'style' => 'primary'  ],
            ['name' => 'complited',         'title' => 'title_complited',         'description' => 'description_complited',       'style' => 'success'  ],
            ['name' => 'on hold',           'title' => 'title_on hold',           'description' => 'description_on hold',         'style' => 'danger'   ],
            ['name' => 'canceled',          'title' => 'title_canceled',          'description' => 'description_canceled',        'style' => 'danger'   ],
            ['name' => 'refunded',          'title' => 'title_refunded',          'description' => 'description_refunded',        'style' => 'danger'   ],
            ['name' => 'failed',            'title' => 'title_failed',            'description' => 'description_failed',          'style' => 'danger'   ],
            ['name' => 'test',              'title' => 'title_test',              'description' => 'description_test',            'style' => 'secondary'],
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
