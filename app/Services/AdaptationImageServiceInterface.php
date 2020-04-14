<?php

namespace App\Services;

interface AdaptationImageServiceInterface
{
    /**
     * @param string $imagePath
     * @param int $productId
     * @param string $mode
     * @return mixed
     */
    public function createSet(string $imagePath, int $productId, string $mode = 'store_product');
}
