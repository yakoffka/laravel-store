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
        'ni_u' => ['name' => 'ni_u', 'title' => 'not important and urgent'/*  , 'description' => 'description' */, 'style' => 'orange'],
        '_inu' => ['name' => '_inu', 'title' => 'important and not urgent'/*  , 'description' => 'description' */, 'style' => 'swatch-yellow'],
        'ninu' => ['name' => 'ninu', 'title' => 'not important and not urgent'/*  , 'description' => 'description' */, 'style' => 'success'],
    ],

];
