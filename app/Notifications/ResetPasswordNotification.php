<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends ResetPassword
{
    use Queueable;

    public $company_id;
    public $token;
    public function __construct($company_id,$token)
    {
        parent::__construct($token);
        $this->company_id = $company_id;
        $this->token = $token;
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


    public function toMail($notifiable)
    {
        //Todo: send company id with reset token
        $link = env('FRONTEND_URL')."/reset-password/".$this->token;

        return ( new MailMessage )
            ->subject('Reset Password Notification')
            ->line("Hello! You are receiving this email because we received a password reset request for your account.")
            ->action('Reset Password', $link)
            ->line("If you did not request a password reset, no further action is required.");
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
