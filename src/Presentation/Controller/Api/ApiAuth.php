<?php

namespace FlightBookingSystem\Presentation\Controller\Api;

use FlightBookingSystem\Application\Port\ApiTokenRepository;
use FlightBookingSystem\Presentation\Utility\Json;

// "auth gate" for every /api/v1 endpoint that needs a logged-in user
// gives back the user id, or null when we already sent a 401.
// the calling controller has to early-return on null!
class ApiAuth
{
    public static function requireUser(ApiTokenRepository $tokenRepository): ?int
    {
        $token = Json::bearerToken();
        if ($token === null) {
            Json::error('Missing bearer token.', 401);
            return null;
        }
        $userId = $tokenRepository->findUserIdByToken($token);
        if ($userId === null) {
            Json::error('Invalid or expired token.', 401);
            return null;
        }
        return $userId;
    }
}
