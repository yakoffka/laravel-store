<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $comment;
    public $username;
    public $type;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($comment, $type, $username)
    {
        info(__METHOD__);
        $this->comment = $comment;
        $this->username = $username;
        $this->type = $type;
        $this->subject = $this->username . ' ' . $this->type . ' комментарий к товару.';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        info(__METHOD__);
        $markdown = 'emails.comment';

        return $this // markdown, from, subject, view, attach
            ->markdown($markdown)
            ->subject($this->subject);
    }
}
