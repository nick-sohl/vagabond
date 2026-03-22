<?php

namespace FlightBookingSystem\Presentation\Controller;

use FlightBookingSystem\Application\Service\AuthService;
use FlightBookingSystem\Presentation\Utility\Htmx;
use FlightBookingSystem\Presentation\View\View;

class AccountController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }

        $user = $this->authService->getUser((int) $_SESSION['user_id']);
        $profileSuccess = null;
        $profileError = null;
        $passwordSuccess = null;
        $passwordError = null;
        $deleteError = null;

        $view = new View('account', 'index', [
            'user' => $user,
            'profileSuccess' => $profileSuccess,
            'profileError' => $profileError,
            'passwordSuccess' => $passwordSuccess,
            'passwordError' => $passwordError,
            'deleteError' => $deleteError,
        ]);

        if (Htmx::isHtmxRequest()) {
            $view->render();
        } else {
            $view->renderFull();
        }
    }

    public function updateProfile(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('HX-Redirect: /auth/login');
            return;
        }

        $userId = (int) $_SESSION['user_id'];
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';

        $result = $this->authService->updateProfile($userId, $firstName, $lastName, $email);

        $user = $this->authService->getUser($userId);

        if ($result['success']) {
            $_SESSION['user_name'] = trim($firstName);
            $_SESSION['user_email'] = trim($email);
        }

        $view = new View('account', 'index', [
            'user' => $user,
            'profileSuccess' => $result['success'] ? 'Profile updated successfully.' : null,
            'profileError' => $result['error'] ?? null,
            'passwordSuccess' => null,
            'passwordError' => null,
            'deleteError' => null,
        ]);
        $view->render();
    }

    public function changePassword(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('HX-Redirect: /auth/login');
            return;
        }

        $userId = (int) $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $result = $this->authService->changePassword($userId, $currentPassword, $newPassword, $confirmPassword);

        $user = $this->authService->getUser($userId);

        $view = new View('account', 'index', [
            'user' => $user,
            'profileSuccess' => null,
            'profileError' => null,
            'passwordSuccess' => $result['success'] ? 'Password changed successfully.' : null,
            'passwordError' => $result['error'] ?? null,
            'deleteError' => null,
        ]);
        $view->render();
    }

    public function deleteAccount(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('HX-Redirect: /auth/login');
            return;
        }

        $userId = (int) $_SESSION['user_id'];
        $password = $_POST['password'] ?? '';

        $result = $this->authService->deleteAccount($userId, $password);

        if ($result['success']) {
            session_destroy();
            header('HX-Redirect: /');
            return;
        }

        $user = $this->authService->getUser($userId);

        $view = new View('account', 'index', [
            'user' => $user,
            'profileSuccess' => null,
            'profileError' => null,
            'passwordSuccess' => null,
            'passwordError' => null,
            'deleteError' => $result['error'] ?? null,
        ]);
        $view->render();
    }
}
