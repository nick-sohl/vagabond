# Vagabond REST API – v1 (MOA)

Base URL (local dev): `http://localhost:8080/api/v1`
Base URL (production): `https://vagabond.up.railway.app/api/v1`

All responses are JSON (`Content-Type: application/json; charset=utf-8`).
Protected endpoints want `Authorization: Bearer <token>`. Errors come back
as `{"error": "..."}` with an HTTP status that fits.

## Auth

### `POST /auth/login`

```json
{
  "email": "felix.huber@example.ch",
  "password": "password"
}
```

→ **200**
```json
{
  "token": "9528f5fee5cb…",
  "user": {
    "id": 1,
    "first_name": "Felix",
    "last_name": "Huber",
    "email": "felix.huber@example.ch"
  }
}
```
→ **401** `{ "error": "Invalid email or password." }`

### `POST /auth/register`

```json
{
  "first_name": "…",
  "last_name": "…",
  "email": "…",
  "password": "…",
  "confirm_password": "…"
}
```

→ **201** same shape as `/auth/login`
→ **422** on validation errors

### `POST /auth/logout`

Needs `Authorization`. Drops the token from the DB.

→ **200** `{ "success": true }`

### `GET /auth/me`

Needs `Authorization`. Returns the current user. Used by the app on start
to check if the saved token is still valid.

## Flights

### `GET /flights`

Query parameters (all optional):

| Param          | What                                              |
|----------------|---------------------------------------------------|
| `departure`    | IATA code, e.g. `ZRH`                             |
| `arrival`      | IATA code, e.g. `LHR`                             |
| `depart_date`  | `YYYY-MM-DD`                                      |
| `depart_time`  | `HH:MM` – earliest departure                      |
| `airline`      | airline IATA code, e.g. `LX`                      |
| `max_price`    | float, in CHF                                     |
| `availability` | `1` -> only flights with seats left               |
| `country`      | arrival country (string)                          |
| `page`         | integer, default `1`                              |
| `per_page`     | integer, default `20`, max `100`                  |

→ **200**
```json
{
  "data": [
    {
      "id": 1,
      "flight_number": "LX318",
      "departure_time": "2026-06-15 07:30:00",
      "arrival_time":   "2026-06-15 08:45:00",
      "price": 245.5,
      "available_seats": 36,
      "total_seats": 180,
      "departure": { "iata": "ZRH", "city": "Zürich", "country": "Schweiz" },
      "arrival":   { "iata": "LHR", "city": "London", "country": "Vereinigtes Königreich" },
      "airline":   { "iata": "LX",  "name": "Swiss International Air Lines" },
      "airplane":  { "model": "A320neo", "manufacturer": "airbus" }
    }
  ],
  "pagination": { "page": 1, "per_page": 20, "total": 42, "total_pages": 3 }
}
```

### `GET /flights/show?id=<id>`

→ **200** `{ "data": Flight }`
→ **404** `{ "error": "Flight not found." }`

> Note: I went with `/flights/show?id=...` instead of a path param like
> `/flights/{id}`. The reason is that the existing router from the WEE
> project does only exact-match routes and I didn't want to touch its
> dispatch logic. Functionally the same thing.

### `GET /airlines`

→ **200** `{ "data": [{ "id": …, "iata_code": "LX", "name": "Swiss …" }, …] }`

### `GET /airports[?q=<query>]`

Without `q`: all airports. With `q`: matches the city or IATA prefix
(used by the autocomplete in the search form).

## Bookings

### `GET /bookings` (auth)

Optional `airline=<iata>` and `max_price=<float>` filters.

→ **200**
```json
{
  "data": [
    {
      "id": 18,
      "booking_date": "2026-06-21 14:02:11",
      "num_tickets": 1,
      "total_price": 245.5,
      "status": "confirmed",
      "flight": {
        "id": 1,
        "flight_number": "LX318",
        "departure_time": "2026-06-15 07:30:00",
        "arrival_time":   "2026-06-15 08:45:00",
        "price": 245.5,
        "departure": { "iata": "ZRH", "city": "Zürich" },
        "arrival":   { "iata": "LHR", "city": "London" },
        "airline":   { "iata": "LX",  "name": "Swiss International Air Lines" }
      }
    }
  ]
}
```

> Note: the assignment shows `/bookings/{userId}`. I'm using just
> `/bookings` and read the user from the bearer token instead. Reason:
> if I trust a `userId` from the URL, a user could just request someone
> else's bookings.

### `POST /bookings` (auth)

```json
{ "flight_id": 1, "num_tickets": 2 }
```

→ **201** `{ "booking_id": 18, "success": true }`
→ **422** `{ "error": "Not enough seats available." }`

The `user_id` is read from the token here too, never from the body.

## Auth / Security

- Tokens are 32 random bytes hex encoded (256 bit). Stored in `api_tokens`,
  indexed by `user_id`.
- Passwords are hashed with `password_hash(..., PASSWORD_DEFAULT)` (bcrypt).
- All SQL goes through prepared statements (PDO) – no string concatenation
  -> safe against SQL injection.
- On the mobile side the token is in the Keychain (iOS) /
  EncryptedSharedPreferences (Android) via `flutter_secure_storage`.

## CORS

`OPTIONS /api/*` returns the allow-headers and a 204. `Allow-Origin` is
`*` which is fine for a prototype but should be tighter in production.
