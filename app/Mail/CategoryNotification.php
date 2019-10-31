<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CategoryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $model;
    public $model_id;
    public $model_name;
    public $username;
    public $event_type;

    public $subject;
    public $body;
    public $url;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($model, $model_id, $model_name, $username, $event_type)
    {
        info(__METHOD__);
        $this->model = $model;
        $this->model_id = $model_id;
        $this->model_name = $model_name;
        $this->username = $username;
        $this->event_type = $event_type;

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
            $this->url = route($model.'.adminshow', [$model => $model_id]);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        info(__METHOD__);
        $markdown = 'emails.'.$this->model;

        return $this // markdown, from, subject, view, attach
            ->markdown($markdown)
            ->subject($this->subject);
    }
}
