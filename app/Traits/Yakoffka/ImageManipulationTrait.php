<?php

namespace App\Traits\Yakoffka;

use Illuminate\Support\Str;

trait ImageManipulationTrait
{

    public static function saveImgSet($image, $product_id)
    {

        // dd(pathinfo($image));
        // проверяем загруженное изображение (размер, тип)
        if( $image->clientExtension() !== $image->extension()){ // $image->error
            return false;  
        }

        // создание изображений
        $res = true;

        // large
        $size_preview = 'l';
        if ( $res and config('imagemanipulation.is_' . $size_preview) ) {
            $name_dst_image = ImageManipulationTrait::saveImg($image, $product_id, $size_preview);
        }

        // medium
        $size_preview = 'm';
        if ( $res and config('imagemanipulation.is_' . $size_preview) ) {
            $name_dst_image = ImageManipulationTrait::saveImg($image, $product_id, $size_preview);
        }

        // small
        $size_preview = 's';
        if ( $res and config('imagemanipulation.is_' . $size_preview) ) {
            $name_dst_image = ImageManipulationTrait::saveImg($image, $product_id, $size_preview);
        }

        return $name_dst_image;
    }


    public static function saveImg($image, $product_id, $size_preview)
    {

        // получение параметров исходного (переданного) изображения
        $src_name = $image->getClientOriginalName();
        $name_dst_image_without_ext = str_replace( strrchr($src_name, '.'), '', $src_name);
        $src_path = $image->path();
        $src_size = getimagesize($image);
        $src_w = $src_size[0];
        $src_h = $src_size[1];


        // получение параметров из конфигурационного файла
        $dst_dir    = storage_path() . config('imagemanipulation.dirdst') . '/products/' . $product_id;
        // $name_dst_image  = Str::random(10) . '_' . $src_name;
        $name_dst_image  = $name_dst_image_without_ext . '_' . $size_preview . '.png';
        $path_dst_image  = $dst_dir . '/' . $name_dst_image;
        $color_fill = config('imagemanipulation.color_fill');
        $dstimage_w = config('imagemanipulation.' . $size_preview . '_w');
        $dstimage_h = config('imagemanipulation.' . $size_preview . '_h');

        // создание директории при необходимости
        if ( !is_dir($dst_dir) ) {
            mkdir($dst_dir, 0777, true);
        }

        //определение функции соответственно типу загруженного файла
        // $type = strtolower(substr($src_size['mime'], strpos($src_size['mime'], '/')+1)); //определяем тип файла
        $type = $image->extension(); //определяем тип файла
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
        $dst_image=imagecreatetruecolor($dstimage_w, $dstimage_h);
        imagefill($dst_image,0,0,$color_fill);
    
        // получаем ресурс исходного изображения
        $src_image = $icfunc($src_path);

        // копируем на него преобразованное изображение с изменением размера
        $copy = imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        // накладываем водяной знак
        if ( config('imagemanipulation.is_' . $size_preview . '_watermark') ) {
            $path_watermark = storage_path() . config('imagemanipulation.watermark');
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
            $copy = imagecopyresampled($dst_image, $src_image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

            
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