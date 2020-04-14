<?php

namespace App\Observers;

use App\Image;

class ImageObserver
{
    /**
     * Handle the product "deleting" event.
     *
     * @param Image $image
     * @return void
     */
    public function deleting(Image $image): void
    {
        $image->deleteImageFile();
    }
}
