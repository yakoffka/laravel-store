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
            ['name' => 'opened',    'display_name' => 'открыто',    'title' => 'открыто',     'description' => 'description', 'style' => 'primary'],
            ['name' => 'done',      'display_name' => 'сделано',    'title' => 'сделано',     'description' => 'description', 'style' => 'success'],
            ['name' => 'prorogue',  'display_name' => 'отложено',   'title' => 'отложено',    'description' => 'description', 'style' => 'primary'],
            ['name' => 'reopened',  'display_name' => 'переоткрыто','title' => 'переоткрыто', 'description' => 'description', 'style' => 'danger'],
            ['name' => 'closed',    'display_name' => 'закрыто',    'title' => 'закрыто',     'description' => 'description', 'style' => 'secondary'],
        ];

        foreach ($statuses as $status) {

            if (DB::table('tasksstatuses')->insert([
                'name' => $status['name'],
                'slug' => Str::slug($status['display_name'], '-'),
                'display_name' => $status['display_name'],
                'description' => $status['description'],
                'title' => $status['title'],
                'style' => $status['style'],
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
