<?php

namespace App\Http\Middleware;

use App\Services\InvitationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateVotingToken
{
    protected $invitationService;

    public function __construct(InvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->route('token');
        
        if (!$token) {
            abort(404);
        }

        $invitation = $this->invitationService->validateToken($token);

        if (!$invitation) {
            return response()->view('voting.invalid', [], 403);
        }

        // Share the invitation with the request/controller
        $request->attributes->set('invitation', $invitation);

        return $next($request);
    }
}
