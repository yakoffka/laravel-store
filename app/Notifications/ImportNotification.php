<?php

namespace App\Notifications;

use App\Http\Controllers\Import\ImportController;
use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Storage;

class ImportNotification extends Notification
{
    use Queueable;

    private string $filesPath;
    private Carbon $started_at;

    /**
     * Create a new notification instance.
     * @param $filesPath
     * @param $started_at
     */
    public function __construct($filesPath, $started_at)
    {
        $this->filesPath = $filesPath;
        $this->started_at = $started_at;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }

    /**
     * Получить Slack-представление уведомления.
     *
     * @param mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack(User $notifiable): SlackMessage
    {
        $logPath = $this->filesPath . ImportController::LOG;
        $e_logPath = $this->filesPath . ImportController::E_LOG;
        $log = $e_log = '';

        if (Storage::disk('public')->exists($logPath)) {
            $log = "\n\tдетали импорта: <" . config('app.url') . Storage::url($logPath) . '|Click>';
        }
        if (Storage::disk('public')->exists($e_logPath)) {
            $e_log = "\n\tошибки импорта: <" . config('app.url') . Storage::url($e_logPath) . '|Click>';
        }

        Storage::allFiles($this->filesPath);
        return (new SlackMessage)
            ->content("$notifiable->name "
                . ' осуществил импорт товаров из программы 1С.'
                . "\nНачало импорта: "
                . $this->started_at->format('Y.m.d H:i:s')
                . "\nОкончание импорта: "
                . now()->format('Y.m.d H:i:s')
                . "\nВремя выполнения импорта: "
                . $this->started_at->diffAsCarbonInterval(now())->__toString()
                . $log . $e_log
            );
    }
}
