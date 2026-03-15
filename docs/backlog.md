# Backlog of future imporvements

## Email Confirmation

### 1. The Overall Flow

1. User submits registration form
2. Your PHP generates a unique token, stores it in the DB alongside the user (marked as unverified)
3. You send an email containing a verification link with that token
4. User clicks the link → browser sends a GET request to your server (e.g. verify.php?token=abc123)
5. Your PHP looks up the token, marks the user as verified, invalidates the token

### 2. The Verification Token

- A cryptographically random string — use bin2hex(random_bytes(32)) in PHP
- Must be unique per user and stored in your database
- Should have an expiry time (e.g. 24 hours) — store a token_expires_at timestamp
- Once used, it should be deleted or nulled out so it can't be reused

### 3. Database Schema

Users table needs a few extra columns:

- is_verified      TINYINT(1) DEFAULT 0
- verify_token     VARCHAR(64) NULL
- token_expires_at DATETIME NULL

### 4. Sending the Email

PHP has a built-in `mail()` function, but in practice you should use a library. **PHPMailer** or **Symfony Mailer** are the standard choices.

### 5. Handling the Confirmation Click

When the user clicks the link, the browser sends a **GET request** to your server. Your `verify.php` then:

1. Read $_GET['token']
2. Query DB: find user where verify_token = that token
3. Check: does the user exist? Is the token not expired?
4. If valid → set is_verified = 1, clear the token
5. Redirect user with a success/failure message

### 6. Security Considerations

ConcernSolutionToken guessabilityUse random_bytes(), never rand() or uniqid()Token reuseDelete/null the token after successful verificationExpired tokensCheck token_expires_at and reject stale onesEmail enumerationDon't reveal whether an email already exists in error messagesHTTPSAlways serve your verify URL over HTTPS so the token isn't exposed in transit

### 7. Edge Cases to Handle

User never confirms — decide if unverified users can log in (usually restricted)
Resend verification — generate a new token and resend if requested
Expired token — show a friendly error with a "resend" option
Already verified — handle gracefully if the link is clicked twice

### Recommended Stack

PHPMailer (via Composer) for sending mail
PDO with prepared statements for all DB queries (prevents SQL injection)
An SMTP service like Mailgun, SendGrid, or even Gmail for reliable delivery (PHP's native mail() often gets flagged as s
