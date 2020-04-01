<?php

return [

    /*
    * Configuration for AdaptationImageService
    */

    'set' => [
        'store_product' => ['origin', 'l', 'm', 's',],
        'seed'          => ['origin', 'l', 'm', 's',],
        'previews'      => ['origin', 'l', 'm', 's',],
        'import'        => ['l', 'm', 's',],
        'rwm_previews'  => ['l', 'm', 's',],
        'rewatermark'   => ['l', 'm', 's',],
    ],

    'rel_path_category_img'        => env('REL_PATH_CATEGORY_IMG', '/images/categories'),

    'dir_dst'        => env('IMG_YO_DIR_DESTINATION', '/app/public/images/products'),
    'dirdst_origin' => env('IMG_YO_DIR_DESTINATION_ORIGIN', '/app/uploads/images/products'),
    'color_fill'    => env('IMG_YO_FILLCOLOR', 0xffffff),

    // 'watermark'     => env('IMG_YO_WATERMARK', '/app/public/images/default/watermark.png'),
    // 'watermark'     => env('IMG_YO_WATERMARK', '/app/public/images/default/watermark_20.png'),
    // 'watermark'     => env('IMG_YO_WATERMARK', '/app/public/images/default/watermark_10.png'),
    'watermark'     => env('IMG_YO_WATERMARK', '/app/public/images/default/watermark_100.png'),
    'default_img'   => env('DEFAULT_IMG', '/images/default/noimg_l.png'),

    // 'res_ext'       => env('IMG_YO_RES_EXT', '.png'), // only '.png'!!!
    'res_ext'       => env('IMG_YO_RES_EXT', '.jpeg'), // only '.png'!!!

    'is_origin'          => env('IMG_YO_IS_ORIGIN', true),
    'origin_is_watermark'=> env('IMG_YO_ORIGIN_IS_WM', false),

    'is_l'          => env('IMG_YO_IS_LARGE', true),
    'l_is_watermark'=> env('IMG_YO_LARGE_IS_WM', true),
    'l_w'           => env('IMG_YO_WLARGE', '540'),
    'l_h'           => env('IMG_YO_HLARGE', '540'),

    'is_m'          => env('IMG_YO_IS_MEDIUM', true),
    'm_is_watermark'=> env('IMG_YO_MEDIUM_IS_WM', true),
    'm_w'           => env('IMG_YO_WMEDIUM', '380'),
    'm_h'           => env('IMG_YO_HMEDIUM', '380'),

    'is_s'          => env('IMG_YO_IS_SMALL', true),
    's_is_watermark'=> env('IMG_YO_SMALL_IS_WM', false),
    's_w'           => env('IMG_YO_WSMALL', '180'),
    's_h'           => env('IMG_YO_HSMALL', '180'),

];
