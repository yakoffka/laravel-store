<?php

use Illuminate\Database\Seeder;
// use Str;

class TasksprioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priorities = [
            ['name' => '_i_u', 'display_name' => 'важно и срочно',      'title' => 'важно и срочно',     'description' => 'description', 'class' => 'priority__i_u'],
            ['name' => '_inu', 'display_name' => 'неважно, но срочно',  'title' => 'неважно, но срочно', 'description' => 'description', 'class' => 'priority__inu'],
            ['name' => 'ni_u', 'display_name' => 'важно, но несрочно',  'title' => 'важно, но несрочно', 'description' => 'description', 'class' => 'priority_ni_u'],
            ['name' => 'ninu', 'display_name' => 'неважно и несрочно',  'title' => 'неважно и несрочно', 'description' => 'description', 'class' => 'priority_ninu'],
        ];

        foreach ($priorities as $priority) {
            if (DB::table('taskspriorities')->insert([
                'name' => $priority['name'],
                'slug' => Str::slug($priority['display_name'], '-'),
                'display_name' => $priority['display_name'],
                'description' => $priority['description'],
                'title' => $priority['title'],
                'class' => $priority['class'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ])) {
                echo '    priority "' . $priority['name'] . '" created.' . "\n";
            } else {
                echo '    err! priority "' . $priority['name'] . '" not created!' . "\n";
            };

        }
    }
}
