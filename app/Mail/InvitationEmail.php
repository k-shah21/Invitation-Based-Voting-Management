<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Invitation $invitation;
    public string $votingUrl;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
        $this->votingUrl  = url('/vote/' . $invitation->token);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Voting Invitation – ' . $this->invitation->session->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invitation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
