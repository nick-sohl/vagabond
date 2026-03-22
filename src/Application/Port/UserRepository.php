<?php

namespace FlightBookingSystem\Application\Port;

interface UserRepository
{
    public function findByEmail(string $email): ?array;

    public function findById(int $id): ?array;

    public function create(string $firstName, string $lastName, string $email, string $passwordHash): int;

    public function update(int $id, string $firstName, string $lastName, string $email): void;

    public function updatePassword(int $id, string $passwordHash): void;

    public function delete(int $id): void;
}
