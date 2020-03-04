<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $model;
    public $model_id;
    public $model_name;
    public $username;
    public $event_type;
    public $product_id;
    public $comment_body;

    public $subject;
    public $body;
    public $url;


    /**
     * Create a new message instance.
     *
     * @param $model
     * @param $model_id
     * @param $model_name
     * @param $username
     * @param $event_type
     * @param $product_id
     * @param $comment_body
     */
    public function __construct($model, $model_id, $model_name, $username, $event_type, $product_id, $comment_body)
    {
        $this->model = $model;
        $this->model_id = $model_id;
        $this->model_name = $model_name;
        $this->username = $username;
        $this->event_type = $event_type;
        $this->product_id = $product_id;
        $this->comment_body = $comment_body;

        $this->subject = __('subject_notification', [
            'descr' => __($event_type.'_'.$model),
            'name' => $model_name
        ]);
        $this->body = __('body_notification', [
            'descr' => __($event_type.'_'.$model),
            'name' => $model_name,
            'username' => $username
        ]);

        $this->url = '';
        if ( $event_type !== 'deleted') {
            $this->url = route('products.show', ['product' => $this->product_id]);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $markdown = 'emails.'.$this->model;

        return $this // markdown, from, subject, view, attach
            ->markdown($markdown)
            ->subject($this->subject);
    }
}
