<?php

namespace App\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $status;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $status, $user)
    {
        $this->order = $order;
        $this->status = $status;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->markdown('emails.order.status-changed')
            ->subject('Изменение статуса заказа №' . $this->order->id);
    }
}
