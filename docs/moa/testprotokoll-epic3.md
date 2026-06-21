# Testprotokoll – Epic 3: Buchungshistorie

**User Story:** "Als Felix möchte ich meine bisherigen Buchungen sehen, um meine Reisen im Überblick zu behalten."

Stand: 2026-06-21
Getestet auf: Flutter 3.44.2 (Dart 3.12.2), iOS-Simulator (iPhone 15),
Android-Emulator (Pixel 7 API 35). Backend lokal via `docker compose`.

## 1. Unit-Tests des State (`flutter test`)

Datei: `flutter_application/test/booking_history_state_test.dart`.

| ID    | Testfall                                                       | Erwartet                                                  | Ergebnis |
|-------|----------------------------------------------------------------|-----------------------------------------------------------|----------|
| E3-T1 | Standard-Sortierung Datum desc                                 | Reihenfolge `[3, 2, 1]` (neueste zuerst)                  | PASS     |
| E3-T2 | Filter `airline=LX`                                            | Nur Swiss-Buchungen sichtbar                              | PASS     |
| E3-T3 | Max. Preis 500 + Sortierung "Preis aufsteigend"                | Reihenfolge `[1, 3]`                                      | PASS     |

```text
$ flutter test test/booking_history_state_test.dart
00:00 +3: All tests passed!
```

## 2. API-Tests (curl)

```bash
TOKEN=$(curl -sS -X POST http://localhost:8080/api/v1/auth/login \
  -H 'Content-Type: application/json' \
  -d '{"email":"felix.huber@example.ch","password":"password"}' \
  | python3 -c 'import json,sys;print(json.load(sys.stdin)["token"])')

curl -sS "http://localhost:8080/api/v1/bookings" \
  -H "Authorization: Bearer $TOKEN"
```

Antwort (gekürzt):
```json
{ "data": [
    { "id": 5, "booking_date": "2026-03-15 18:45:00",
      "num_tickets": 1, "total_price": 79, "status": "pending",
      "flight": { "flight_number": "EW9876", "airline": { "iata": "EW" } } },
    { "id": 4, "booking_date": "2026-03-15 11:00:00",
      "num_tickets": 2, "total_price": 1780, "status": "confirmed", "...": "..." }
] }
```

Filter direkt am Endpoint:
```bash
curl -sS "http://localhost:8080/api/v1/bookings?airline=LX&max_price=500" \
  -H "Authorization: Bearer $TOKEN"
```

## 3. UI-Tests (manuell)

| ID    | Schritt                                                | Erwartet                                                                   | Ergebnis |
|-------|--------------------------------------------------------|----------------------------------------------------------------------------|----------|
| E3-M1 | Login → Tab "Buchungen"                                | Buchungen werden geladen, neueste zuerst                                   | PASS     |
| E3-M2 | Sortierung auf "Preis (hoch → niedrig)"                | Liste sortiert sich sofort neu                                             | PASS     |
| E3-M3 | Airline-Filter `LX` setzen                              | Nur Swiss-Buchungen                                                        | PASS     |
| E3-M4 | Max. Preis `300` eingeben                               | Nur Buchungen mit `total_price <= 300`                                     | PASS     |
| E3-M5 | Pull-to-refresh                                         | API-Aufruf wird wiederholt, Spinner erscheint                              | PASS     |
| E3-M6 | Backend offline → Tab erneut öffnen                     | Fehlertext aus der `ApiException` wird angezeigt                           | PASS     |
| E3-M7 | User hat keine Buchungen → leere Antwort                | Leer-Zustand mit Icon und Hinweistext                                      | PASS     |

## Fazit

Akzeptanzkriterien aus Epic 3 erfüllt:
- Seite "Meine Buchungen" zeigt alle Buchungen des eingeloggten Users.
- Endpoint `/api/v1/bookings` ist angebunden (statt `/bookings/{userId}` —
  der `userId` kommt aus dem Token, das ist sicherer; siehe
  `docs/moa/reflexion.md`).
- Sortierung nach Datum + Filter nach Fluggesellschaft und Preis
  funktionieren.
- Tests für API und UI dokumentiert.
