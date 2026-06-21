<?php

namespace FlightBookingSystem\Application\Port;

interface ApiTokenRepository
{
    public function issue(int $userId): string;

    public function findUserIdByToken(string $token): ?int;

    public function revoke(string $token): void;
}
