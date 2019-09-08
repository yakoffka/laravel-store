<?php

use Illuminate\Database\Seeder;

class TasksstatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            /* 1 */ ['name' => 'open',      'display_name' => 'открыто',    'title' => 'открыто',     'description' => 'description', 'class' => 'status_open'      ],
            /* 2 */ ['name' => 'in_work',   'display_name' => 'в работе',   'title' => 'в работе',    'description' => 'description', 'class' => 'status_in_work'   ],
            /* 3 */ ['name' => 'done',      'display_name' => 'сделано',    'title' => 'сделано',     'description' => 'description', 'class' => 'status_done'      ],
            /* 4 */ ['name' => 'prorogue',  'display_name' => 'отложено',   'title' => 'отложено',    'description' => 'description', 'class' => 'status_prorogue'  ],
            /* 5 */ ['name' => 'reopened',  'display_name' => 'переоткрыто','title' => 'переоткрыто', 'description' => 'description', 'class' => 'status_reopened'  ],
            /* 6 */ ['name' => 'closed',    'display_name' => 'закрыто',    'title' => 'закрыто',     'description' => 'description', 'class' => 'status_closed'    ],
        ];

        foreach ($statuses as $status) {

            if (DB::table('tasksstatuses')->insert([
                'name' => $status['name'],
                'slug' => Str::slug($status['display_name'], '-'),
                'display_name' => $status['display_name'],
                'description' => $status['description'],
                'title' => $status['title'],
                'class' => $status['class'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ])) {
                echo '    status "' . $status['name'] . '" created.' . "\n";
            } else {
                echo '    err! status "' . $status['name'] . '" not created!' . "\n";
            };

        }
    }
}
