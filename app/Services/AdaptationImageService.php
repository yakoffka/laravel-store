<?php


namespace App\Services;

use App\Services\ImportService\AdaptingImage;

class AdaptationImageService implements AdaptationImageServiceInterface
{
    /**
     * @param string $imagePath
     * @param int $productId
     * @param string $mode
     * @return string
     */
    public function createSet(string $imagePath, int $productId, string $mode = 'store_product'): string
    {
        $AdaptingImage = new AdaptingImage($imagePath, $productId, $mode);
        $imageNameWE = '';

        $aImageSet = config('adaptation_image_service.set.' . $mode);
        foreach ( $aImageSet as $imageType ) {
            $imageNameWE = $AdaptingImage->remake($imageType);
        }

        return $imageNameWE;
    }
}
