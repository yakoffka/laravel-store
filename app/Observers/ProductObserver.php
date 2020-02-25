<?php

namespace App\Observers;

use App\Product;

class ProductObserver
{
    /**
     * Handle the product "creating" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function creating(Product $product)
    {
        $product->setTitle()->setSlug()->cleanSrcCodeTables()->setCreator();
    }

    /**
     * Handle the product "created" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        $product->additionallyIfCopy()->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the product "updating" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function updating(Product $product)
    {
        $product->setTitle()->setSlug()->cleanSrcCodeTables()->setEditor();
    }

    /**
     * Handle the product "updated" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function updated(Product $product)
    {
        $product->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the product "deleting" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function deleting(Product $product)
    {
        $product->deleteComments()->deleteImages();
    }

    /**
     * Handle the product "deleted" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function deleted(Product $product)
    {
        $product->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the product "restored" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the product "force deleted" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
