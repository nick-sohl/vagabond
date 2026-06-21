<?php

namespace FlightBookingSystem\Presentation\Controller\Api;

use FlightBookingSystem\Application\Port\ApiTokenRepository;
use FlightBookingSystem\Application\Service\AuthService;
use FlightBookingSystem\Presentation\Utility\Json;

// auth endpoints for the mobile app (login/register/me/logout)
// we just wrap the existing AuthService here and hand out a token,
// because PHP sessions/cookies are a pain on mobile
class ApiAuthController
{
    private AuthService $authService;
    private ApiTokenRepository $tokenRepository;

    public function __construct(AuthService $authService, ApiTokenRepository $tokenRepository)
    {
        $this->authService = $authService;
        $this->tokenRepository = $tokenRepository;
    }

    public function login(): void
    {
        $body = Json::body();
        $email = (string) ($body['email'] ?? '');
        $password = (string) ($body['password'] ?? '');

        $result = $this->authService->login($email, $password);

        if (!$result['success']) {
            Json::error($result['error'], 401);
            return;
        }

        $user = $result['user'];
        $token = $this->tokenRepository->issue((int) $user['id']);

        Json::send([
            'token' => $token,
            'user'  => $this->presentUser($user),
        ]);
    }

    public function register(): void
    {
        $body = Json::body();
        $firstName = (string) ($body['first_name'] ?? '');
        $lastName = (string) ($body['last_name'] ?? '');
        $email = (string) ($body['email'] ?? '');
        $password = (string) ($body['password'] ?? '');
        $confirm = (string) ($body['confirm_password'] ?? $password);

        $result = $this->authService->register($firstName, $lastName, $email, $password, $confirm);

        if (!$result['success']) {
            Json::error($result['error'], 422);
            return;
        }

        $userId = (int) $result['user_id'];
        $user = $this->authService->getUser($userId) ?? [
            'id'         => $userId,
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
        ];
        $token = $this->tokenRepository->issue($userId);

        Json::send([
            'token' => $token,
            'user'  => $this->presentUser($user),
        ], 201);
    }

    public function me(): void
    {
        $userId = ApiAuth::requireUser($this->tokenRepository);
        if ($userId === null) {
            return;
        }
        $user = $this->authService->getUser($userId);
        if (!$user) {
            Json::error('User not found.', 404);
            return;
        }
        Json::send(['user' => $this->presentUser($user)]);
    }

    public function logout(): void
    {
        $token = Json::bearerToken();
        if ($token !== null) {
            $this->tokenRepository->revoke($token);
        }
        Json::send(['success' => true]);
    }

    private function presentUser(array $user): array
    {
        return [
            'id'         => (int) $user['id'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'email'      => $user['email'],
        ];
    }
}
