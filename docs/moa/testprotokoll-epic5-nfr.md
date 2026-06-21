# Testprotokoll – Epic 5: Nicht-funktionale Anforderungen

Stand: 2026-06-21
Getestet auf: Flutter 3.44.2, Backend lokal via `docker compose`.

## 1. Responsives Design

| ID     | Gerät / Auflösung                  | Erwartet                                          | Ergebnis |
|--------|-------------------------------------|---------------------------------------------------|----------|
| NFR-R1 | iPhone 15 (iOS 17)                  | SafeArea wird respektiert, kein Inhalt unter Notch | PASS     |
| NFR-R2 | iPhone SE (klein)                   | Suchformular scrollt, Cards bleiben lesbar         | PASS     |
| NFR-R3 | Pixel 7 (Android 15)                | Material 3, NavigationBar mit Beschriftungen       | PASS     |
| NFR-R4 | Tablet-Breakpoint (iPad-Simulator)  | Forms sind zentriert via `maxWidth: 420`           | PASS     |

## 2. Authentifizierung & Autorisierung (Tests siehe `test/auth_state_test.dart`)

| ID     | Testfall                                                          | Ergebnis |
|--------|-------------------------------------------------------------------|----------|
| NFR-A1 | Login erfolgreich → Status `signedIn`, Token persistiert          | PASS     |
| NFR-A2 | Login falsche Daten → Fehlertext aus dem Backend                  | PASS     |
| NFR-A3 | Logout → lokale Session und Bearer-Token werden gelöscht          | PASS     |
| NFR-A4 | App-Neustart mit gültigem Token → Auto-Login                      | PASS     |
| NFR-A5 | App-Neustart mit ungültigem Token → Login-Screen                  | PASS     |
| NFR-A6 | Geschützter Endpoint ohne Token → 401 + Fehlertext im UI          | PASS     |

## 3. Performance

- **Pagination:** `GET /api/v1/flights?per_page=20` lädt nur 20 Datensätze pro
  Seite, weitere via "Mehr laden". Getestet mit `seed.sql` (~50 Flüge).
- **Caching:** `FlightSearchState.loadReferenceData()` lädt Airlines und
  Airports nur einmal pro Session, danach aus dem Speicher.
- **Connection-Reuse:** `package:http` nutzt einen persistenten `IOClient`,
  der Bearer-Token wird einmal gesetzt und bei jedem Request wiederverwendet.
- **DB-Indexes:** zusammengesetzter Index `idx_flights_route_date` (siehe
  `db/schema.sql`).

## 4. Logging

- Mobile: `lib/core/utils/logger.dart` (wrapper um `dart:developer.log`).
  Loggt API-Aufrufe in Debug-Builds. **Bearer-Tokens werden NIE geloggt.**
- Backend: `Database.php` setzt `PDO::ATTR_ERRMODE = ERRMODE_EXCEPTION`,
  Exceptions landen im PHP-Errorlog.

## 5. Security

| Kontrolle                 | Wie umgesetzt                                                                   |
|---------------------------|---------------------------------------------------------------------------------|
| Passwort-Speicherung      | `password_hash(..., PASSWORD_DEFAULT)` (bcrypt) in `users.password`              |
| SQL-Injection             | Nur Prepared Statements (PDO `prepare` + `bindParam` / `bindValue`)             |
| Token-Speicherung Client  | Keychain (iOS) / EncryptedSharedPreferences (Android) via `flutter_secure_storage` |
| Token-Entropie            | 32 zufällige Bytes (256 bit), via `random_bytes` (CSPRNG)                       |
| Auth-Header über HTTPS    | Railway-Deploy nutzt TLS, Dev-URL ist nur lokal                                  |
| User-ID Spoofing          | `user_id` wird serverseitig immer aus dem Token gelesen, nie aus dem Body      |
| CORS                      | `Access-Control-Allow-Origin: *` (für Prototyp ok; in Prod restriktiver)        |

```text
$ flutter test
00:00 +13: All tests passed!
$ flutter analyze
No issues found! (ran in 0.8s)
```
