<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CategoryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $category;
    public $username;
    public $type;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($category, $type, $username)
    {
        info(__METHOD__);
        $this->category = $category;
        $this->username = $username;
        $this->type = $type;
        $this->subject = $this->username . ' ' . $this->type . ' категорию.';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        info(__METHOD__);
        $markdown = 'emails.category';

        return $this // markdown, from, subject, view, attach
            ->markdown($markdown)
            ->subject($this->subject);
    }
}
