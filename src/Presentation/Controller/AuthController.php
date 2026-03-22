<?php

namespace FlightBookingSystem\Presentation\Controller;

use FlightBookingSystem\Application\Service\AuthService;
use FlightBookingSystem\Presentation\Utility\Htmx;
use FlightBookingSystem\Presentation\View\View;

class AuthController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function loginPage(): void
    {
        $error = null;
        $view = new View('auth', 'login', ['error' => $error]);

        if (Htmx::isHtmxRequest()) {
            $view->render();
        } else {
            $view->renderFull();
        }
    }

    public function registerPage(): void
    {
        $error = null;
        $view = new View('auth', 'register', ['error' => $error]);

        if (Htmx::isHtmxRequest()) {
            $view->render();
        } else {
            $view->renderFull();
        }
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $result = $this->authService->login($email, $password);

        if ($result['success']) {
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['user_name'] = $result['user']['first_name'];
            $_SESSION['user_email'] = $result['user']['email'];

            header('HX-Redirect: /');
            return;
        }

        $error = $result['error'];
        $view = new View('auth', 'login', ['error' => $error]);
        $view->render();
    }

    public function register(): void
    {
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $result = $this->authService->register($firstName, $lastName, $email, $password, $confirmPassword);

        if ($result['success']) {
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['user_name'] = $result['first_name'];
            $_SESSION['user_email'] = $result['email'];

            header('HX-Redirect: /');
            return;
        }

        $error = $result['error'];
        $view = new View('auth', 'register', ['error' => $error]);
        $view->render();
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}
