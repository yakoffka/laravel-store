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
        info(__METHOD__);
        $product->setTitle()->setSlug()->cleanSrcCodeTables()->additionallyIfCopy()->setCreator();
    }

    /**
     * Handle the product "created" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function created(Product $product)
    {
        info(__METHOD__);
        $product->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the product "updating" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function updating(Product $product)
    {
        info(__METHOD__);
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
        info(__METHOD__);
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
        info(__METHOD__);
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
        info(__METHOD__);
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
        info(__METHOD__);
    }

    /**
     * Handle the product "force deleted" event.
     *
     * @param  \App\Product  $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        info(__METHOD__);
    }
}
