<?php

namespace App\Observers;

use App\Product;

class ProductObserver
{
    /**
     * Handle the product "creating" event.
     *
     * @param Product $product
     * @return void
     */
    public function creating(Product $product): void
    {
        $product->setTitle()->setSlug()->cleanSrcCodeTables()->setCreator();
    }

    /**
     * Handle the product "created" event.
     *
     * @param Product $product
     * @return void
     */
    public function created(Product $product): void
    {
        $product->additionallyIfCopy()->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the product "updating" event.
     *
     * @param Product $product
     * @return void
     */
    public function updating(Product $product): void
    {
        $product->setTitle()->setSlug()->cleanSrcCodeTables()->setEditor();
    }

    /**
     * Handle the product "updated" event.
     *
     * @param Product $product
     * @return void
     */
    public function updated(Product $product): void
    {
        $product->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the product "deleting" event.
     *
     * @param Product $product
     * @return void
     */
    public function deleting(Product $product): void
    {
        $product->deleteComments()->deleteImages();
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param Product $product
     * @return void
     */
    public function deleted(Product $product): void
    {
        $product->createCustomevent()->sendEmailNotification()->setFlashMess();
    }
}
