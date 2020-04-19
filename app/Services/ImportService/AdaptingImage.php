<?php

namespace App\Services\ImportService;

use Illuminate\Support\Str;
use RuntimeException;
use Storage;

class AdaptingImage
{
    public string $srcImagePath; // путь к исходному изображению
    public int $productId; // идентификатор товара
    public string $mode; // режим преобразования изображений. 'store_product', 'rewatermark', 'seed', 'create/update'
    private string $dstImageNameWE; // имя получаемого файла изображения без расширения ($image->name) 'image-name'
    private string $dstImageName; // ?? имя получаемого файла изображения 'image-name-l.png'
    public string $dstImagePath; // путь к получаемому изображению
    public int $src_w; // ширина исходного изображения
    public int $src_h; // высота исходного изображения
    public int $src_x; // x-координата исходного изображения
    public int $src_y; // y-координата исходного изображения
    public int $dstImageW; // ширина результирующего изображения (ширина холста)
    public int $dstImageH; // высота результирующего изображения (высота холста)
    private int $dst_w; // ширина результирующего изображения (накладываемого)
    private int $dst_h; // высота результирующего изображения (накладываемого)
    private int $dst_x; // x-координата результирующего изображения
    private int $dst_y; // y-координата результирующего изображения

    /**
     * AdaptingImage constructor.
     * @param string $srcImagePath
     * @param int $productId
     * @param string $mode
     */
    public function __construct(string $srcImagePath, int $productId, string $mode = 'store_product')
    {
        $this->srcImagePath = $srcImagePath;
        $this->productId = $productId;
        $this->mode = $mode;
    }

    /**
     * @param string $imageType
     * @return string
     */
    public function remake(string $imageType): string
    {
        if (!is_file($this->srcImagePath)) {
            return '';
        }

        $icFunc = $this->getNameFunc($this->srcImagePath);
        $this->setDstImageAttributes($imageType);
        if ($this->mode !== 'rewatermark' && is_file($this->dstImageName)) {
            return $this->dstImageNameWE;
        }
        $this->setReSampleAttributes($this->srcImagePath, $imageType);
        $this->createImage($imageType, $icFunc);

        return $this->dstImageNameWE;
    }

    /**
     * @param $srcImagePath
     * @return string
     */
    private function getNameFunc($srcImagePath): string
    {
        $icFunc = 'imagecreatefrom' . pathinfo(getimagesize($srcImagePath)['mime'], PATHINFO_FILENAME);
        if (!function_exists($icFunc)) {
            throw new RuntimeException(sprintf('Функция "%s" не существует', $icFunc));
        }
        return $icFunc;
    }

    /**
     * @param string $imageType
     * @return void
     */
    private function setDstImageAttributes(string $imageType): void
    {
        $dst_dir = $this->createdDstDir();
        $this->dstImageNameWE = Str::slug(pathinfo($this->srcImagePath, PATHINFO_FILENAME), '-');
        $this->dstImageName = $this->dstImageNameWE . '-' . $imageType;
        $this->dstImagePath = "$dst_dir/$this->dstImageName";
    }

    /**
     * получение параметров исходного и результирующего изображений в зависимости от режима
     * (rewatermark, seed, false(create, update))
     *
     * @param string $srcImagePath
     * @param string $imageType
     * @return void
     */
    private function setReSampleAttributes(string $srcImagePath, string $imageType): void
    {
        // получение параметров исходного изображения
        $src_size = getimagesize($srcImagePath);
        $this->src_w = $src_size[0];
        $this->src_h = $src_size[1];

        // получение размеров результирующего выражения
        $this->dstImageW = config('adaptation_image_service.' . $imageType . '_w');
        $this->dstImageH = config('adaptation_image_service.' . $imageType . '_h');

        // узнаем коэффициент масштабирования (по горизонтали и вертикали и выбираем наибольший) < 1 при уменьшении
        $ratio_w = $this->dstImageW / $this->src_w;
        $ratio_h = $this->dstImageH / $this->src_h;
        $ratio = ($ratio_w >= $ratio_h ? $ratio_h : $ratio_w);

        // Результирующие ширина и высота
        $this->dst_w = round($this->src_w * $ratio);
        $this->dst_h = round($this->src_h * $ratio);

        // получаем смещения
        $this->dst_x = round(($this->dstImageW - $this->dst_w) / 2);    // x-координата результирующего изображения.
        $this->dst_y = round(($this->dstImageH - $this->dst_h) / 2);    // y-координата результирующего изображения.
        $this->src_x = 0;    // x-координата исходного изображения.
        $this->src_y = 0;    // y-координата исходного изображения.
    }

    /**
     * @param string $imageType
     * @param string $icFunc
     */
    private function createImage(string $imageType, string $icFunc): void
    {
        $pngPath = $this->dstImagePath . '.png';
        $jpegPath = $this->dstImagePath . '.jpeg';

        if (config('adaptation_image_service.' . $imageType . '_is_watermark')) {
            $this->createJpegThroughPng($imageType, $icFunc, $pngPath, $jpegPath);
        } else {
            $this->createJpeg($icFunc, $jpegPath);
        }
    }

    /**
     * удаление артефактов GD, образующихся при наложении прозрачного фона с частичной потерей качества:
     * fefefe(16711422) -> ffffff; ???fdfdfd(16645629) -> ffffff; fcfcfc(16579836) -> ffffff;
     *
     * @param $dst_image
     * @param int $color_fill
     * @return mixed
     */
    private function deleteArtifacts($dst_image, int $color_fill)
    {
        for ($y = 0; $y < ($this->dstImageH); ++$y) {
            for ($x = 0; $x < ($this->dstImageW); ++$x) {
                $rgb = imagecolorat($dst_image, $x, $y);
                if ($rgb === 16711422 || $rgb === 16579836) {
                    imagesetpixel($dst_image, $x, $y, $color_fill);
                }
            }
        }
        return $dst_image;
    }

    /**
     * @return string
     */
    private function createdDstDir(): string
    {
        $dst_dir = Storage::disk('public')->path(config('adaptation_image_service.product_images_path') . '/' . $this->productId);

        if (!is_dir($dst_dir) && !mkdir($dst_dir, 0777, true) && !is_dir($dst_dir)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $dst_dir));
        }
        return $dst_dir;
    }

    /**
     * @param string $imageType
     * @param $dst_image
     * @return mixed
     */
    private function overlayWatermark(string $imageType, $dst_image)
    {
        $watermarkPath = storage_path() . config('adaptation_image_service.watermark');
        $icFunc = $this->getNameFunc($watermarkPath);
        $this->setReSampleAttributes($watermarkPath, $imageType); // @todo: и как быть?

        $src_image = $icFunc($watermarkPath);
        $color_fill = imagecolorallocate($dst_image, 255, 255, 255);
        imagecopyresampled($dst_image, $src_image, $this->dst_x, $this->dst_y, $this->src_x, $this->src_y,
            $this->dst_w, $this->dst_h, $this->src_w, $this->src_h);
        $dst_image = $this->deleteArtifacts($dst_image, $color_fill);
        return $dst_image;
    }

    /**
     * создание изображения *.jpeg с водяным знаком через промежуточный файл *.png
     *
     * @param string $imageType
     * @param string $icFunc
     * @param string $pngPath
     * @param string $jpegPath
     */
    private function createJpegThroughPng(string $imageType, string $icFunc, string $pngPath, string $jpegPath): void
    {
        $this->createPng($imageType, $icFunc, $pngPath);
        $this->conversionPngToJpeg($pngPath, $jpegPath);
    }

    /**
     * @param string $imageType
     * @param string $icFunc
     * @param string $pngPath
     * @return void
     */
    private function createPng(string $imageType, string $icFunc, string $pngPath): void
    {
        list($dst_image, $src_image) = $this->createAndFillImage($icFunc);
        imagecopyresampled($dst_image, $src_image, $this->dst_x, $this->dst_y, $this->src_x, $this->src_y,
            $this->dst_w, $this->dst_h, $this->src_w, $this->src_h);
        $dst_image = $this->overlayWatermark($imageType, $dst_image);
        imagepng($dst_image, $pngPath, 1); // @todo: добавить проверку успешности выполнения!
        imagedestroy($dst_image);
        imagedestroy($src_image);
    }

    /**
     * @param string $pngPath
     * @param string $jpegPath
     */
    private function conversionPngToJpeg(string $pngPath, string $jpegPath): void
    {
        $dst_image = imagecreatetruecolor($this->dstImageW, $this->dstImageH);
        $j_src_image = imagecreatefrompng($pngPath);
        imagecopyresampled($dst_image, $j_src_image, 0, 0, 0, 0,
            $this->dst_w, $this->dst_h, $this->dst_w, $this->dst_h);
        imagejpeg($dst_image, $jpegPath);// @todo: добавить проверку успешности выполнения!
        imagedestroy($dst_image);
        imagedestroy($j_src_image);
        unlink($pngPath);
    }

    /**
     * @param string $icFunc
     * @param string $jpegPath
     */
    private function createJpeg(string $icFunc, string $jpegPath): void
    {
        list($dst_image, $src_image) = $this->createAndFillImage($icFunc);
        imagecopyresampled($dst_image, $src_image, $this->dst_x, $this->dst_y, $this->src_x, $this->src_y,
            $this->dst_w, $this->dst_h, $this->src_w, $this->src_h);
        imagejpeg($dst_image, $jpegPath); // @todo: добавить проверку!
        imagedestroy($dst_image);
        imagedestroy($src_image);
    }

    /**
     * @param string $icFunc
     * @return array
     */
    private function createAndFillImage(string $icFunc): array
    {
        $dst_image = imagecreatetruecolor($this->dstImageW, $this->dstImageH);
        $color_fill = imagecolorallocate($dst_image, 255, 255, 255);
        imagefill($dst_image, 0, 0, $color_fill);
        $src_image = $icFunc($this->srcImagePath);
        return array($dst_image, $src_image);
    }

}

/*       FOUR RECTANGLES http://php.net/manual/ru/function.imagecopyresampled.php
*
*                     $src_image                                   $dst_image
*
*    |<-------------------------------------------->|   |<--------- $dstImageW ---------------->|
*    |                                              |   |                                       |
*    +------------+---------------------------------+   +------------+--------------------------+
*    |            |                                 |   |            |                          |
*    |            |                                 |   |         $dst_y                        |
*    |            |                                 |   |            |                          |
*    |         $src_y                               |   +-- $dst_x --+------$dst_w------+       |
*    |            |                                 |   |            |                  |       |
*    |            |                                 |   |            |                  |       |
*    |            |                                 |   |            |     Resampled    |   $dstImageH
*    +-- $src_x --+-------- $src_w --------+        |   |         $dst_h                |       |
*    |            |                        |        |   |            |                  |       |
*    |            |                        |        |   |            |                  |       |
*    |            |                        |        |   |            +------------------+       |
*    |            |        Sample          |        |   |                                       |
*    |            |                        |        |   |                                       |
*    |            |                        |        |   |                                       |
*    |         $src_h                      |        |   |                                       |
*    |            |                        |        |   +---------------------------------------+
*    |            |                        |        |
*    |            +------------------------+        |
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
