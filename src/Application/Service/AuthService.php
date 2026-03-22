<?php

namespace FlightBookingSystem\Application\Service;

use FlightBookingSystem\Application\Port\UserRepository;

class AuthService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return array{success: bool, error?: string, user?: array}
     */
    public function login(string $email, string $password): array
    {
        $email = trim($email);

        if ($email === '' || $password === '') {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        $user = $this->userRepository->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        return ['success' => true, 'user' => $user];
    }

    /**
     * @return array{success: bool, error?: string, user_id?: int}
     */
    public function register(string $firstName, string $lastName, string $email, string $password, string $confirmPassword): array
    {
        $firstName = trim($firstName);
        $lastName = trim($lastName);
        $email = trim($email);

        if ($firstName === '' || $lastName === '' || $email === '' || $password === '') {
            return ['success' => false, 'error' => 'All fields are required.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Please enter a valid email address.'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'error' => 'Password must be at least 6 characters.'];
        }

        if ($password !== $confirmPassword) {
            return ['success' => false, 'error' => 'Passwords do not match.'];
        }

        $existing = $this->userRepository->findByEmail($email);
        if ($existing) {
            return ['success' => false, 'error' => 'An account with this email already exists.'];
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userRepository->create($firstName, $lastName, $email, $passwordHash);

        return ['success' => true, 'user_id' => $userId, 'first_name' => $firstName, 'email' => $email];
    }

    public function getUser(int $id): ?array
    {
        return $this->userRepository->findById($id);
    }

    public function updateProfile(int $id, string $firstName, string $lastName, string $email): array
    {
        $firstName = trim($firstName);
        $lastName = trim($lastName);
        $email = trim($email);

        if ($firstName === '' || $lastName === '' || $email === '') {
            return ['success' => false, 'error' => 'All fields are required.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Please enter a valid email address.'];
        }

        $existing = $this->userRepository->findByEmail($email);
        if ($existing && (int) $existing['id'] !== $id) {
            return ['success' => false, 'error' => 'This email is already taken.'];
        }

        $this->userRepository->update($id, $firstName, $lastName, $email);

        return ['success' => true];
    }

    public function changePassword(int $id, string $currentPassword, string $newPassword, string $confirmPassword): array
    {
        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            return ['success' => false, 'error' => 'All password fields are required.'];
        }

        $user = $this->userRepository->findById($id);
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            return ['success' => false, 'error' => 'Current password is incorrect.'];
        }

        if (strlen($newPassword) < 6) {
            return ['success' => false, 'error' => 'New password must be at least 6 characters.'];
        }

        if ($newPassword !== $confirmPassword) {
            return ['success' => false, 'error' => 'New passwords do not match.'];
        }

        $this->userRepository->updatePassword($id, password_hash($newPassword, PASSWORD_DEFAULT));

        return ['success' => true];
    }

    public function deleteAccount(int $id, string $password): array
    {
        $user = $this->userRepository->findById($id);
        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'error' => 'Password is incorrect.'];
        }

        $this->userRepository->delete($id);

        return ['success' => true];
    }
}
