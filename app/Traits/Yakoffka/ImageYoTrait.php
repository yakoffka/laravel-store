<?php

namespace App\Traits\Yakoffka;

use Illuminate\Support\Str;

trait ImageYoTrait
{

    public static function saveImgSet($image, $product_id, $rewatermark = false)
    {
        // создание изображений
        $res = true;
        $name_dst_image = false;

        if ($rewatermark) {
            $previews = ['l', 'm', 's'];
        } else {
            $previews = ['origin', 'l', 'm', 's'];
        }

        foreach ( $previews as $type_preview ) {
            if ( $res and config('imageyo.is_' . $type_preview) ) {
                $name_dst_image = ImageYoTrait::saveImg($image, $product_id, $type_preview, $rewatermark);
            }
        }

        return $name_dst_image;
    }


    public static function saveImg($image, $product_id, $type_preview, $rewatermark)
    {

        // получение параметров исходного (переданного) изображения        
        $src_size = getimagesize($image);
        $src_w = $src_size[0];
        $src_h = $src_size[1];
        if ( $rewatermark ) {
            $src_img_name = pathinfo($image)['basename'];
            $src_path = pathinfo($image)['dirname'] . '/' . pathinfo($image)['basename'];
            $type = strtolower(substr($src_size['mime'], strpos($src_size['mime'], '/')+1)); //определяем тип файла
            $name_dst_image_without_ext = str_replace( strrchr($src_img_name, '.'), '', $src_img_name);
            $name_dst_image_without_ext = str_replace('_origin' , '', $name_dst_image_without_ext);
        } else {
            $src_img_name = $image->getClientOriginalName();
            $src_path = $image->path();
            $type = $image->extension(); //определяем тип файла
            $name_dst_image_without_ext = str_replace( strrchr($src_img_name, '.'), '', $src_img_name);
        }
        

        // получение параметров из конфигурационного файла
        if ( $type_preview === 'origin' ) {
            $dstimage_w = $src_w;
            $dstimage_h = $src_h;    
            $dst_dir    = storage_path() . config('imageyo.dirdst_origin') . '/products/' . $product_id;
        } else {
            $dstimage_w = config('imageyo.' . $type_preview . '_w');
            $dstimage_h = config('imageyo.' . $type_preview . '_h');
            $dst_dir    = storage_path() . config('imageyo.dirdst') . '/products/' . $product_id;
        }

        $name_dst_image  = $name_dst_image_without_ext . '_' . $type_preview . config('imageyo.res_ext');
        $path_dst_image  = $dst_dir . '/' . $name_dst_image;
        $color_fill = config('imageyo.color_fill');


        // создание директории при необходимости
        if ( !is_dir($dst_dir) ) {
            if ( !mkdir($dst_dir, 0777, true) ) {
                dd($dst_dir);
                return false;
            }
        }

        //определение функции соответственно типу загруженного файла
        $icfunc = "imagecreatefrom".$type;
        if(!function_exists($icfunc)){//если нет такой функции - прекращаем работу скрипта
            // err
            return false;  
        }

        // узнаем коэффициент масштабирования (по горизонтали и вертикали и выбираем наибольший) < 1 при уменьшении
        $ratio_w = $dstimage_w / $src_w;
        $ratio_h = $dstimage_h / $src_h;
        $ratio = $ratio_w >= $ratio_h ? $ratio_h : $ratio_w;


        // получаем смещения
        $dst_w = round($src_w * $ratio);   // Результирующая ширина.
        $dst_h = round($src_h * $ratio);   // Результирующая высота.
        $dst_x = round(( $dstimage_w - $dst_w ) / 2);    // x-координата результирующего изображения.
        $dst_y = round(( $dstimage_h - $dst_h ) / 2);    // y-координата результирующего изображения.
        $src_x = 0;    // x-координата исходного изображения.
        $src_y = 0;    // y-координата исходного изображения.


        // создаем пустое изображение
        $dst_image = imagecreatetruecolor($dstimage_w, $dstimage_h);
        imagefill($dst_image, 0, 0, $color_fill);

        // получаем ресурс исходного изображения
        $src_image = $icfunc($src_path);

        // копируем на него преобразованное изображение с изменением размера
        imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        // накладываем водяной знак
        if ( config('imageyo.' . $type_preview . '_is_watermark') ) {
            $path_watermark = storage_path() . config('imageyo.watermark');
            $src_size = getimagesize($path_watermark);
            $src_w = $src_size[0];
            $src_h = $src_size[1];
    
            //определение функции соответственно типу файла водяного знака
            $type = strtolower(substr($src_size['mime'], strpos($src_size['mime'], '/')+1)); //определяем тип файла
            $icfunc = "imagecreatefrom".$type;
            if(!function_exists($icfunc)) {//если нет такой функции - прекращаем работу скрипта
                // err
                return false;  
            }

            // узнаем коэффициент масштабирования (по горизонтали и вертикали и выбираем наибольший) < 1 при уменьшении
            $ratio_w = $dstimage_w / $src_w;
            $ratio_h = $dstimage_h / $src_h;
            $ratio = $ratio_w >= $ratio_h ? $ratio_h : $ratio_w;


            // получаем смещения
            $dst_w = round($src_w * $ratio);   // Результирующая ширина.
            $dst_h = round($src_h * $ratio);   // Результирующая высота.
            $dst_x = round(( $dstimage_w - $dst_w ) / 2);    // x-координата результирующего изображения.
            $dst_y = round(( $dstimage_h - $dst_h ) / 2);    // y-координата результирующего изображения.
            $src_x = 0;    // x-координата исходного изображения.
            $src_y = 0;    // y-координата исходного изображения.

            // получаем ресурс изображения водяного знака
            $src_image = $icfunc($path_watermark);
            imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

            
        }

        // сохраняем превью
        // if(imagejpeg($dst_image,$path_resize_img,100)){
        if ( !imagepng($dst_image, $path_dst_image) ) {
            // err
            $name_dst_image_without_ext = false;
        }

        // Очищаем память после выполнения скрипта
        imagedestroy($dst_image);
        imagedestroy($src_image);

        // dd('name_dst_image', $name_dst_image, 'dst_image', $dst_image, 'icfunc', $icfunc, '$image', $image, '$src_size', $src_size, '$color_fill', $color_fill);
        return $name_dst_image_without_ext;


    /*
    *
    *    FOUR RECTANGLES http://php.net/manual/ru/function.imagecopyresampled.php
    *   
    *
    *                     $src_image                                   $dst_image
    *
    *
    *    |<-------------------------------------------->|   |<--------- $dstimage_w --------->|
    *    |                                              |   |                                 |
    *    +------------+---------------------------------+   +------------+--------------------+
    *    |            |                                 |   |            |                    |
    *    |            |                                 |   |         $dst_y                  |
    *    |            |                                 |   |            |                    |
    *    |         $src_y                               |   +-- $dst_x --+------$dst_w------+ |
    *    |            |                                 |   |            |                  | |
    *    |            |                                 |   |            |                  | |
    *    |            |                                 |   |            |     Resampled    | |
    *    +-- $src_x --+-------- $src_w --------+        |   |         $dst_h                | |
    *    |            |                        |        |   |            |                  | |
    *    |            |                        |        |   |            |                  | |
    *    |            |                        |        |   |            |                  | |
    *    |            |                        |        |   |            +------------------+ |
    *    |            |        Sample          |        |   |                                 |
    *    |            |                        |        |   |                                 |
    *    |            |                        |        |   |                                 |
    *    |         $src_h                      |        |   |                                 |
    *    |            |                        |        |   +---------------------------------+
    *    |            |                        |        |
    *    |            |                        |        |
    *    |            +------------------------+        |
    *    |                                              |
    *    |                                              |
    *    +----------------------------------------------+
    *
    *   dst_image - Ресурс целевого изображения.
    *   src_image - Ресурс исходного изображения.
    *   dst_x     - x-координата результирующего изображения.
    *   dst_y     - y-координата результирующего изображения.
    *   src_x     - x-координата исходного изображения.
    *   src_y     - y-координата исходного изображения.
    *   dst_w     - Результирующая ширина.
    *   dst_h     - Результирующая высота.
    *   src_w     - Ширина исходного изображения.
    *   src_h     - Высота исходного изображения.
    */

    }
}