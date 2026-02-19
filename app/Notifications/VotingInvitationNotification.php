<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Bus\Queueable;
class VotingInvitationNotification extends Notification
{use Queueable;
    public $invitation;

    public function __construct($invitation)
    {
        $this->invitation = $invitation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        
        $voteUrl = URL::signedRoute('vote.show', $this->invitation->token);

        return (new MailMessage)
            ->subject('You Are Invited to Vote')
            ->greeting('Hello ' . $notifiable->name)
            ->line('You have been invited to participate in a voting session.')
            ->action('Cast Your Vote', $voteUrl)
            ->line('This link can only be used once.')
            ->line('Thank you.');
    }
}
