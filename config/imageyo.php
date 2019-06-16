<?php

return [

    /*
    *
    */
    'dirdst'        => env('IMG_YO_DIR_DESTINATION', '/app/public/images'),
    'dirdst_origin' => env('IMG_YO_DIR_DESTINATION_ORIGIN', '/app/uploads/images'),
    'color_fill'    => env('IMG_YO_FILLCOLOR', 0xffffff),
    // 'watermark'     => env('IMG_YO_WATERMARK', '/app/public/images/default/watermark.png'),
    // 'watermark'     => env('IMG_YO_WATERMARK', '/app/public/images/default/watermark_laravel_30.png'),
    'watermark'     => env('IMG_YO_WATERMARK', '/app/public/images/default/watermark_sparta_30.png'),
    'res_ext'           => env('IMG_YO_RES_EXT', '.png'), // only '.png'!!!

    'is_origin'          => env('IMG_YO_IS_ORIGIN', true),
    'origin_is_watermark'=> env('IMG_YO_ORIGIN_IS_WM', false),

    'is_l'          => env('IMG_YO_IS_LARGE', true),
    'l_is_watermark'=> env('IMG_YO_LARGE_IS_WM', true),
    'l_w'           => env('IMG_YO_WLARGE', '380'),
    'l_h'           => env('IMG_YO_HLARGE', '380'),

    'is_m'          => env('IMG_YO_IS_MEDIUM', true),
    'm_is_watermark'=> env('IMG_YO_MEDIUM_IS_WM', true),
    'm_w'           => env('IMG_YO_WMEDIUM', '180'),
    'm_h'           => env('IMG_YO_HMEDIUM', '180'),

    'is_s'          => env('IMG_YO_IS_SMALL', true),
    's_is_watermark'=> env('IMG_YO_SMALL_IS_WM', false),
    's_w'           => env('IMG_YO_WSMALL', '80'),
    's_h'           => env('IMG_YO_HSMALL', '80'),

];
