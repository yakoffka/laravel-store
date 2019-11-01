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
        info(__METHOD__);
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
        info(__METHOD__);
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
        info(__METHOD__);
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
        info(__METHOD__);
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
        info(__METHOD__);
    }

    /**
     * Handle the manufacturer "deleted" event.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return void
     */
    public function deleted(Manufacturer $manufacturer)
    {
        info(__METHOD__);
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
        info(__METHOD__);
    }

    /**
     * Handle the manufacturer "force deleted" event.
     *
     * @param  \App\Manufacturer  $manufacturer
     * @return void
     */
    public function forceDeleted(Manufacturer $manufacturer)
    {
        info(__METHOD__);
    }
}
