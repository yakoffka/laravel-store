<?php

return [

    /*
    *
    */
    // 'dirdst'        => env('IMG_MANIPUL_DIR_DESTINATION', '/upload'),
    'dirdst'        => env('IMG_MANIPUL_DIR_DESTINATION', '/app/public/images'),
    'color_fill'    => env('IMG_MANIPUL_FILLCOLOR', 0xffffff),
    'watermark'     => env('IMG_MANIPUL_WATERMARK', '/app/public/images/default/watermark.png'),

    'is_l'          => env('IMG_MANIPUL_LARGE', true),
    'is_l_watermark'=> env('IMG_MANIPUL_LARGE', true),
    'l_w'           => env('IMG_MANIPUL_WLARGE', '380'),
    'l_h'           => env('IMG_MANIPUL_HLARGE', '380'),

    'is_m'          => env('IMG_MANIPUL_WMEDIUM', true),
    'is_m_watermark'=> env('IMG_MANIPUL_WMEDIUM', true),
    'm_w'           => env('IMG_MANIPUL_WMEDIUM', '180'),
    'm_h'           => env('IMG_MANIPUL_HMEDIUM', '180'),

    'is_s'          => env('IMG_MANIPUL_WSMALL', true),
    'is_s_watermark'=> env('IMG_MANIPUL_WSMALL', false),
    's_w'           => env('IMG_MANIPUL_WSMALL', '80'),
    's_h'           => env('IMG_MANIPUL_HSMALL', '80'),

];
