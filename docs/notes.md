# Notes

## Property Hooks

## Data Filtering

- validation and sanitization
- FILTER_VALIDATE_*, FILTER_SANITIZE_*
- FILTER_FLAG_*

### Example on Data Filtering in PHP

``` php
    set (string $value) {
      $sanitized = filter_var($value, FILTER_SANITIZE_EMAIL);
      if (! filter_var($sanitized, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE)) {
        thow new InvalidArgumentException('Invalid Email');
      }
      echo $this->email = $sanitized;
    }
```
