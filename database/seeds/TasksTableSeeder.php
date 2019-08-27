<?php

use Illuminate\Database\Seeder;
use App\User;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // seeder tasks
        $count_users = User::count();
        // $arr_status = [
        //     'opened',
        //     'done',
        //     'prorogue',
        //     'reopened',
        //     'closed',
        // ];
        // $arr_priority = [
        //     'important and urgent',
        //     'not important and urgent',
        //     'important and not urgent',
        //     'not important and not urgent',
        // ];
        $statuses = array_column(config('task.statuses'), 'name');
        $priorities = array_column(config('task.priorities'), 'name');

        $lorem = 'lorem ipsum, quia dolor sit amet consectetur adipiscing velit, sed quia non-numquam do eius modi tempora incididunt, ut labore et dolore magnam aliquam quaerat voluptatem.';

        for ( $i = 1; $i < 25; $i++ ) {

            $master = rand(1, $count_users) + 1;
            $slave  = rand($master - 1, $count_users) + 1;
            $title = 'Title test task #' . $i;

            DB::table('tasks')->insert([
                'master_user_id' => $master,
                'slave_user_id' => $slave,
                'title' => $title,
                'slug' => Str::slug($title, '-'),
                'description' => 'Description for task #' . $i . '. ' . $lorem ,
                'status' => $statuses[rand(0, count($statuses)-1)],
                'priority' => $priorities[rand(0, count($priorities)-1)],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // task for owner
        $arr_task_for_owner = [
            [
                'title' => 'Исправить валидацию в контроллерах',
                'description' => 'Добавить required_with, exists, unique:table,column,except,idColumn, sometimes и прочее (см http://laravel.su/docs/5.0/validation#controller-validation).',
                'status' => 'opened',
                'priority' => 'important and not urgent',
            ],

            [
                'title' => 'Учесть субординацию в постановке задач',
                'description' => 'Учесть субординацию в постановке задач.',
                'status' => 'opened',
                'priority' => 'important and not urgent',
            ],

            [
                'title' => 'продумать модальную форму',
                'description' => 'public_html/resources/views/includes/modalForm.blade.php.',
                'status' => 'opened',
                'priority' => 'important and not urgent',
            ],

            [
                'title' => 'Заменить Status на OrderStatus',
                'description' => 'Заменить Status на OrderStatus, StatusesTableSeeder на OrderStatusesTableSeeder, CreateStatusesTable на CreateOrderStatusesTable, etc.',
                'status' => 'opened',
                'priority' => 'important and not urgent',
            ],

            // [
            //     'title' => 'rrrrrr',
            //     'description' => 'rrrrrrr.',
            //     'status' => 'opened',
            //     'priority' => 'important and not urgent',
            // ],

            // [
            //     'title' => 'rrrrrr',
            //     'description' => 'rrrrrrr.',
            //     'status' => 'opened',
            //     'priority' => 'important and not urgent',
            // ],

            // [
            //     'title' => 'rrrrrr',
            //     'description' => 'rrrrrrr.',
            //     'status' => 'opened',
            //     'priority' => 'important and not urgent',
            // ],

            // [
            //     'title' => 'rrrrrr',
            //     'description' => 'rrrrrrr.',
            //     'status' => 'opened',
            //     'priority' => 'important and not urgent',
            // ],

        ];

        foreach ( $arr_task_for_owner as $i => $task ) {

            $master = rand(0, $count_users) + 1;
            $slave  = rand(0, $master - 1 ) + 1;
            $title = 'Title test task #' . $i;

            DB::table('tasks')->insert([
                'master_user_id' => 2,
                'slave_user_id' => 2,
                'title' => $task['title'] ?? Str::limit($task->description, 20),
                'slug' => Str::slug($title, '-'),
                'description' => $task['description'],
                'status' => $task['status'],
                'priority' => $task['priority'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
