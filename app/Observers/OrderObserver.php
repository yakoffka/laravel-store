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
        info(__METHOD__);
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
        info(__METHOD__);
        $order->setName()->createCustomevent()->sendEmailNotification();
        session()->flash('message', __('success_operation'));
    }


    /**
     * Handle the order "updating" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function updating(Order $order)
    {
        info(__METHOD__);
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
        info(__METHOD__);
        $order->setName()->createCustomevent()->sendEmailNotification();
        session()->flash('message', __('success_operation'));
    }


    /**
     * Handle the order "deleting" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function deleting(Order $order)
    {
        info(__METHOD__);
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        info(__METHOD__);
        $order->createCustomevent();
        // $category->sendEmailNotification(); 
        session()->flash('message', __('success_operation'));
    }


    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        info(__METHOD__);
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        info(__METHOD__);
    }
}
