<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailNotification extends Notification
{
    use Queueable;

    public function __construct($message, $link)
    {
        $this->message = $message;
        $this->link = $link;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->subject('Thông báo mới')
            ->line('Có một thông báo mới.')
            ->line($this->message)
            ->action('Xem thêm', url($this->link));
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
