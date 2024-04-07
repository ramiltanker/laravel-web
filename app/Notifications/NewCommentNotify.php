<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Article;

class NewCommentNotify extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     */
    public function __construct(public Article $article)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

     // Основной метод, который будет отправлять нотиф в базу данных
    public function toDatabase(object $notifiable): array
    {
        return [
            'article' => $this->article,
        ];
    }
}