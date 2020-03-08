<?php

namespace App\Observers;

use App\Order;
use Session;

class OrderObserver
{
    /**
     * Handle the order "creating" event.
     *
     * @param Order $order
     * @return void
     */
    public function creating(Order $order): void
    {
        $order->createFromCart()->setCustomer();
    }

    /**
     * Handle the order "created" event.
     *
     * @param Order $order
     * @return void
     */
    public function created(Order $order): void
    {
        Session::forget('cart');
        $order->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the order "updating" event.
     *
     * @param Order $order
     * @return void
     */
    public function updating(Order $order): void
    {
        $order->setCustomer();
    }

    /**
     * Handle the order "updated" event.
     *
     * @param Order $order
     * @return void
     */
    public function updated(Order $order): void
    {
        $order->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the order "deleting" event.
     *
     * @param Order $order
     * @return void
     */
    public function deleting(Order $order): void
    {
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function deleted(Order $order): void
    {
        $order->createCustomevent()->sendEmailNotification()->setFlashMess();
    }


    /**
     * Handle the order "restored" event.
     *
     * @param Order $order
     * @return void
     */
    public function restored(Order $order): void
    {
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param Order $order
     * @return void
     */
    public function forceDeleted(Order $order): void
    {
    }
}
