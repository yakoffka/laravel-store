<?php

namespace App\Observers;

use App\Manufacturer;

class ManufacturerObserver
{
    /**
     * Handle the manufacturer "creating" event.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return void
     */
    public function creating(Manufacturer $manufacturer)
    {
        $manufacturer->setUuid()->setTitle()->setSlug()->attachSingleImage()->setCreator();
    }

    /**
     * Handle the manufacturer "created" event.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return void
     */
    public function created(Manufacturer $manufacturer)
    {
        $manufacturer->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the manufacturer "updating" event.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return void
     */
    public function updating(Manufacturer $manufacturer)
    {
        $manufacturer->setTitle()->setSlug()->attachSingleImage()->setEditor();
    }

    /**
     * Handle the manufacturer "updated" event.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return void
     */
    public function updated(Manufacturer $manufacturer)
    {
        $manufacturer->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the manufacturer "deleting" event.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return void
     */
    public function deleting(Manufacturer $manufacturer)
    {
    }

    /**
     * Handle the manufacturer "deleted" event.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return void
     */
    public function deleted(Manufacturer $manufacturer)
    {
        $manufacturer->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the manufacturer "restored" event.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return void
     */
    public function restored(Manufacturer $manufacturer)
    {
    }

    /**
     * Handle the manufacturer "force deleted" event.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return void
     */
    public function forceDeleted(Manufacturer $manufacturer)
    {
    }
}
