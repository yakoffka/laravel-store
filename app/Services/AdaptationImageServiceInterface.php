<?php

namespace App\Services;

interface AdaptationImageServiceInterface
{
    public function createSet(string $imagePath, int $productId, string $mode = 'store_product');
    // public function remake(string $imagePath, int $productId, string $mode, string $imageType);
}
