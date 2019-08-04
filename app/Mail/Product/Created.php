<?php

namespace App\Mail\Product;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Created extends Mailable
{
    use Queueable, SerializesModels;

    public $product;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($product)
    {
        $this->product = $product;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Создан товар "' . $this->product->name . '"';

        // from, subject, view, attach
        return $this
            ->markdown('emails.product.created')
            // ->from(config('mail.mail_info'), config('mail.name_info'))// ??
            ->from(config('mail.mail_info'))
            ->subject($subject);
    }
}
