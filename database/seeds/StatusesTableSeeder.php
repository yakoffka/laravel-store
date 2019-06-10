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
            ['name' => 'pending payment',   'title' => 'title pending payment',   'description' => 'description pending payment',   ],
            ['name' => 'processing',        'title' => 'title processing',        'description' => 'description processing',        ],
            ['name' => 'on hold',           'title' => 'title on hold',           'description' => 'description on hold',           ],
            ['name' => 'complited',         'title' => 'title complited',         'description' => 'description complited',         ],
            ['name' => 'canceled',          'title' => 'title canceled',          'description' => 'description canceled',          ],
            ['name' => 'refunded',          'title' => 'title refunded',          'description' => 'description refunded',          ],
            ['name' => 'failed',            'title' => 'title failed',            'description' => 'description failed',            ],
            ['name' => 'shipped',           'title' => 'title shipped',           'description' => 'description shipped',           ],
            ['name' => 'test',              'title' => 'title test',              'description' => 'description test',              ],
        ];

        foreach ($statuses as $status) {
            DB::table('statuses')->insert([
                'name'          => $status['name'],
                'title'         => $status['title'],
                'description'   => $status['description'],
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);    
        }
    }
}
