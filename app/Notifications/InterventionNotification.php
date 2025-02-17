<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InterventionNotification extends Notification
{
    use Queueable;
    protected $details;
    /**
     * Create a new notification instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // return (new MailMessage)

        //     ->greeting('Bonjour')
        //     ->subject($this->details['title'])
        //     ->line($this->details['body'])
        //     ->salutation('Cordialement, Zenith');
        return (new MailMessage)
        ->subject($this->details['title'])
        ->view('email', ['body' => $this->details['body']]);

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //mel je t'en prie faut que je te parle j'ai passé ma journée dans le noir mel je le sens je le sais il se fou de moi 
            //vi arrete ton mec assure ton mec assume wei ton mecc déchire
        ];
    }
}
