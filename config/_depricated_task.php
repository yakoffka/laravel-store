<?php

return [

    'statuses' => [
        // 'name' => ['name' => 'name', 'title' => 'title'/*  , 'description' => 'description' */, 'style' => 'style'],
        'opened'   => ['name' => 'opened', 'title' => 'opened'/*  , 'description' => 'description' */, 'style' => 'primary'],
        'done'     => ['name' => 'done', 'title' => 'done'/*  , 'description' => 'description' */, 'style' => 'success'],
        'prorogue' => ['name' => 'prorogue', 'title' => 'prorogue'/*  , 'description' => 'description' */, 'style' => 'primary'],
        'reopened' => ['name' => 'reopened', 'title' => 'reopened'/*  , 'description' => 'description' */, 'style' => 'danger'],
        'closed'   => ['name' => 'closed', 'title' => 'closed'/*  , 'description' => 'description' */, 'style' => 'secondary'],
    ],

    'priorities' => [
        // 'name' => ['name' => 'name', 'title' => 'title'/*  , 'description' => 'description' */, 'style' => 'style'],
        '_i_u' => ['name' => '_i_u', 'title' => 'important and urgent'/*  , 'description' => 'description' */, 'style' => 'swatch-red'],
    ],

];


// SQLSTATE[22007]: Invalid datetime format: 1366 
// Incorrect integer value: 'reopened' for column 'tasksstatuses_id' at row 1 
//     (SQL: insert into `tasks` (
//         `master_user_id`, `slave_user_id`, `title`,             `slug`,             `description`,   `tasksstatuses_id`, `taskspriorities_id`, `created_at`, `updated_at`)
//          5,                 5,              Title test task 1,   title-test-task-1, Description ,    reopened,           i_u,                  2019-08-27 12:01:41, 2019-08-27 12:01:41))
