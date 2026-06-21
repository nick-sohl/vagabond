# Vagabond Mobile (MOA)

Flutter app for the Vagabond flight booking system. Runs on Android and iPhone
from the same codebase.

This is the deliverable for the **MOA (Z-TIA-23-T-a)** case study. The app
talks to the existing PHP middleware (from the WEE case study) over a JSON
REST API at `/api/v1/*`.

## Software versions

| What                  | Version                                    |
|-----------------------|--------------------------------------------|
| Flutter SDK           | 3.44.2 (stable)                            |
| Dart SDK              | 3.12.2                                     |
| Android compile/target| 35 (Android 15)                            |
| Android minSdk        | flutter default (21)                       |
| iOS deployment target | 13.0                                       |
| Backend               | PHP 8.5 + SQLite (Vagabond WEE)            |

Packages – see [`pubspec.yaml`](./pubspec.yaml):

- `http` – calls to the REST API
- `provider` – state management (with `ChangeNotifier`)
- `flutter_secure_storage` – Keychain / EncryptedSharedPreferences for the token
- `intl` – german (de_CH) formatting
- `mocktail` – fakes for the tests

## What you need

1. **Flutter SDK 3.44+** (Dart 3.12+). Check with `flutter --version`.
2. **Backend running.** Either:
   - locally with docker (the easiest): `docker compose up -d --build` in the
     repo root -> API on `http://localhost:8080/api/v1`. The compose stack
     creates the sqlite DB from `db/schema.sql` and seeds it from
     `db/seed.sql` on first start.
   - or the deployed one on Railway: `https://vagabond.up.railway.app`.
3. **Android Studio** with the Android SDK, and/or **Xcode 15+** with an
   iOS Simulator.

## Database

The backend uses SQLite. Everything lives in:

- [`../db/schema.sql`](../db/schema.sql) – tables, indexes, foreign keys
- [`../db/seed.sql`](../db/seed.sql) – 40 airports, 20 airlines, 50 flights,
  test users

On the first `docker compose up` the schema + seed run automatically. To
start over from scratch:

```bash
docker compose down -v        # also drops the sqlite volume
docker compose up -d --build  # builds fresh + seeds
```

## Test login

| Field    | Value                      |
|----------|----------------------------|
| Email    | `felix.huber@example.ch`   |
| Password | `password`                 |

## Running the app

```bash
cd flutter_application
flutter pub get
```

### Android emulator

Android emulator can't reach the host's `localhost` directly. Instead it has
its own `10.0.2.2` which points to the host. The app does this rewrite
automatically when it sees it's running on Android in debug mode, so no
extra config needed.

```bash
flutter emulators                 # list installed emulators
flutter emulators --launch <id>   # start one
flutter run -d emulator-5554      # or any other -d <device>
```

### iOS simulator

```bash
open -a Simulator                 # boot a simulator
flutter run -d <simulator-id>
```

`localhost:8080` just works on the iOS simulator.

### Different backend URL

```bash
flutter run --dart-define=VAGABOND_DEV_BASE_URL=http://192.168.1.10:8080
# or for a release build pointing at the deployed one:
flutter run --release --dart-define=VAGABOND_API_BASE_URL=https://vagabond.up.railway.app
```

## Tests

```bash
flutter test          # unit + widget tests
flutter analyze       # static analysis (should be clean)
```

The test protocols for each Epic live in [`../docs/moa/`](../docs/moa/).

## Folder structure

```
lib/
├── core/                 # constants, theme, format helpers
├── data/
│   ├── api/              # ApiClient, exceptions, token storage
│   ├── models/           # Flight, Airline, Airport, Booking, AppUser
│   └── repositories/     # Auth, Flight, Booking - they use ApiClient
└── presentation/
    ├── screens/          # auth, flights, bookings, home shell
    ├── state/            # ChangeNotifiers (AuthState, FlightSearchState, …)
    └── widgets/          # shared widgets like FlightCard
```

The screens never call `http` directly, they only talk to the repositories.
That way the tests can swap in fake repos (see `test/_fakes.dart`).

## REST API

Documented in [`../docs/moa/api-reference.md`](../docs/moa/api-reference.md).
Everything under `/api/v1/*` is JSON. The protected endpoints want
`Authorization: Bearer <token>`.
