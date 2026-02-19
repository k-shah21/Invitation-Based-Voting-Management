<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Voting Invitation</title>
    <style>
        body { margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Arial, sans-serif; }
        .wrapper { max-width: 560px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.07); }
        .header { background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); padding: 40px 40px 32px; text-align: center; }
        .header svg { display: block; margin: 0 auto 14px; }
        .header h1 { margin: 0; color: #ffffff; font-size: 22px; font-weight: 700; letter-spacing: -0.3px; }
        .header p { margin: 6px 0 0; color: #bfdbfe; font-size: 14px; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 18px; font-weight: 600; color: #1e293b; margin: 0 0 12px; }
        .text { font-size: 15px; color: #475569; line-height: 1.7; margin: 0 0 24px; }
        .session-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin-bottom: 28px; }
        .session-box .label { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.8px; margin: 0 0 4px; }
        .session-box .value { font-size: 15px; font-weight: 600; color: #1e293b; margin: 0; }
        .btn-wrap { text-align: center; margin: 8px 0 28px; }
        .btn { display: inline-block; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff !important; text-decoration: none; padding: 14px 36px; border-radius: 10px; font-size: 15px; font-weight: 700; letter-spacing: 0.2px; }
        .divider { border: none; border-top: 1px solid #f1f5f9; margin: 28px 0; }
        .link-fallback { font-size: 13px; color: #94a3b8; text-align: center; line-height: 1.6; }
        .link-fallback a { color: #2563eb; word-break: break-all; }
        .footer { background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 20px 40px; text-align: center; }
        .footer p { margin: 0; font-size: 12px; color: #94a3b8; line-height: 1.7; }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <div class="header">
        <svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="22" cy="22" r="22" fill="rgba(255,255,255,0.15)"/>
            <path d="M14 22l6 6 10-12" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <h1>VoteAdmin</h1>
        <p>Secure Online Voting System</p>
    </div>

    <!-- Body -->
    <div class="body">
        <p class="greeting">Hello, {{ $invitation->voter->name }}!</p>
        <p class="text">
            You have been invited to participate in a voting session. Please use your secure, unique link below to cast your vote. This link is private and should not be shared with anyone.
        </p>

        <div class="session-box">
            <p class="label">Voting Session</p>
            <p class="value">{{ $invitation->session->title }}</p>
        </div>

        <div class="btn-wrap">
            <a href="{{ $votingUrl }}" class="btn">Cast Your Vote →</a>
        </div>

        <hr class="divider">

        <p class="link-fallback">
            If the button above doesn't work, copy and paste this link into your browser:<br>
            <a href="{{ $votingUrl }}">{{ $votingUrl }}</a>
        </p>

        @if($invitation->expires_at)
        <hr class="divider">
        <p class="link-fallback">
            ⏰ This invitation expires on <strong>{{ $invitation->expires_at->format('F j, Y') }}</strong>.
        </p>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            This email was sent by VoteAdmin on behalf of the voting organiser.<br>
            If you believe you received this by mistake, please ignore it.
        </p>
    </div>

</div>
</body>
</html>
