<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Mail\BareMail;

class PasswordResetNotification extends Notification
{
    use Queueable;

    public $token;
    public $mail;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $token, BareMail $mail)
    {
        $this->token = $token;
        $this->mail = $mail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');

        return $this->mail
          ->from(config('mail.from.address'), config('mail.from.name'))
          ->to($notifiable->email)
          ->subject('[memo]パスワード再設定')
          ->text('email.password_reset')
          ->with([
            'url' => route('password.reset',[
              'token' => $this->token,
              'email' => $notifiable->email,
            ]),
            'count' => config('auth.passwords.'.
            config('auth.defaults.passwords'). '.expire'),
          ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
