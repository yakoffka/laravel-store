<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ImportNotification extends Notification
{
    use Queueable;
    private $mess;

    /**
     * Create a new notification instance.
     *
     * @param string $mess
     */
    public function __construct(string $mess)
    {
        $this->mess = $mess;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * @param User $notifiable
     * @return SlackMessage
     */
    public function toSlack(User $notifiable): SlackMessage {
        return (new SlackMessage)->content($this->mess . ' [' . now()->format('Y.m.d H:i:s') . ']');
    }
}
