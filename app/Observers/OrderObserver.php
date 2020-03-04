<?php

namespace App\Observers;

use App\Order;

class OrderObserver
{
    /**
     * Handle the order "creating" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function creating(Order $order)
    {
        $order->createFromCart()->setCustomer();
    }

    /**
     * Handle the order "created" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        $order->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the order "updating" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function updating(Order $order)
    {
        $order->setCustomer();
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        $order->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the order "deleting" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function deleting(Order $order)
    {
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        $order->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
    }
}
