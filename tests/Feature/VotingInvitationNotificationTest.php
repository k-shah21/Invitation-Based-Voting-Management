<?php

use App\Notifications\VotingInvitationNotification;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Feature tests for VotingInvitationNotification using Pest.
 * We place these in Feature so the Tests\\TestCase bootstraps Laravel per tests/Pest.php.
 */

it('uses only mail channel via()', function () {
    $invitation = (object) ['token' => 'test-token-123'];
    $notification = new VotingInvitationNotification($invitation);

    $channels = $notification->via((object) []);

    expect($channels)->toBe(['mail']);
});

it('builds a MailMessage with the expected subject', function () {
    $invitation = (object) ['token' => 'subject-token'];
    $notification = new VotingInvitationNotification($invitation);
    $notifiable = (object) ['name' => 'Alice'];

    $mail = $notification->toMail($notifiable);

    expect($mail)->toBeInstanceOf(MailMessage::class)
        ->and($mail->subject)->toBe('You Are Invited to Vote');
});

it('includes a personalized greeting with the notifiable name', function () {
    $invitation = (object) ['token' => 'greet-token'];
    $notification = new VotingInvitationNotification($invitation);
    $notifiable = (object) ['name' => 'Bob'];

    $mail = $notification->toMail($notifiable);

    expect($mail->greeting)->toBe('Hello Bob');
});

it('sets the correct action text and URL containing the invitation token', function () {
    $token = 'abc123TOKEN';
    $invitation = (object) ['token' => $token];
    $notification = new VotingInvitationNotification($invitation);
    $notifiable = (object) ['name' => 'Carol'];

    $mail = $notification->toMail($notifiable);

    // The helper url('/vote/{token}') should produce a full URL. We'll just assert it ends with the expected path & contains the token.
    expect($mail->actionText)->toBe('Cast Your Vote')
        ->and($mail->actionUrl)->toEndWith("/vote/{$token}")
        ->and(str_contains($mail->actionUrl, $token))->toBeTrue();
});

it('includes the expected informational lines', function () {
    $invitation = (object) ['token' => 'lines-token'];
    $notification = new VotingInvitationNotification($invitation);
    $notifiable = (object) ['name' => 'Dana'];

    $mail = $notification->toMail($notifiable);

    // Combine intro and outro lines for assertion convenience
    $intro = $mail->introLines ?? [];
    $outro = $mail->outroLines ?? [];

    expect($intro)->toContain('You have been invited to participate in a voting session.')
        ->and($outro)->toContain('This link can only be used once.')
        ->and($outro)->toContain('Thank you.');
});
