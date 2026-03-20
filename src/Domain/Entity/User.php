<?php

namespace FlightBookingSystem\Domain\Entity;

class User
{
    // properties
    public int $userId {
        get => $this->userId;
        set => uniqid();
    }
    public string $firstname {
        get => $this->firstname;
        set(string $value) {
            $this->firstname;
        }
    }
    public string $lastname {
        set(string $value) {
            $this->lastname;
        }
    }
    public string $fullname {
        get => $this->fullname . ' ' . $this->lastname;
    }
    // INFO: https://www.zend.com/blog/php-8-4-property-hooks
    // TODO: Add Email Confirmation: Send Email to Address including a link to a verification endpoint
    public string $email {
        get => $this->email;
        set(string $value) {
            $sanitized = filter_var($value, FILTER_SANITIZE_EMAIL);
            if (!filter_var($sanitized, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE)) {
                throw new \InvalidArgumentException('Invalid Email');
            }
            $this->email = $sanitized;
        }
    }
    public string $password {
        get => $this->password;
        set(string $value) {
            $this->password = password_hash($value, PASSWORD_DEFAULT);
        }
    }

    // constructor
    public function __construct(string $firstname, string $lastname, string $email, string $password)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
    }
}
