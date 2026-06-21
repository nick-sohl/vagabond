# Testprotokoll – Epic 2: Buchungssystem

**User Story:** "Als Felix möchte ich einen Flug direkt buchen, damit ich meine Reise sichern kann."

Stand: 2026-06-21
Getestet auf: Flutter 3.44.2 (Dart 3.12.2), iOS-Simulator (iPhone 15),
Android-Emulator (Pixel 7 API 35). Backend lokal via `docker compose`.

## 1. End-to-End Widget-Tests (`flutter test`)

Datei: `flutter_application/test/checkout_flow_test.dart`. Steuert die
`CheckoutScreen` mit einer Fake `BookingRepository` (im Speicher).

| ID    | Testfall                                                  | Erwartet                                                        | Ergebnis |
|-------|-----------------------------------------------------------|-----------------------------------------------------------------|----------|
| E2-T1 | Happy Path: Karte ausfüllen → bezahlen                    | `BookingRepository.create` mit `flight_id=1, num_tickets=2`     | PASS     |
| E2-T2 | Server meldet "Not enough seats available."               | Fehler erscheint auf der Checkout-Seite                         | PASS     |
| E2-T3 | Kartennummer `123` (zu kurz)                              | Validator blockt Submit, `create` wird nicht aufgerufen          | PASS     |

```text
$ flutter test test/checkout_flow_test.dart
00:00 +3: All tests passed!
```

## 2. Manuelle End-to-End-Tests (auf echten Geräten, echtes Backend)

| ID    | Schritt                                                                                | Erwartet                                                                | Ergebnis |
|-------|----------------------------------------------------------------------------------------|-------------------------------------------------------------------------|----------|
| E2-M1 | Flug in der Liste antippen → Detail öffnen                                             | Detailseite mit Flugdaten, +/- für Tickets, Gesamtpreis                 | PASS     |
| E2-M2 | "Zur Kasse" antippen                                                                   | Checkout-Seite mit Bestellübersicht erscheint                           | PASS     |
| E2-M3 | Karteninhaber leer lassen → "Buchen" antippen                                          | Fehler "Pflichtfeld" beim Feld                                          | PASS     |
| E2-M4 | Karte ausfüllen, Kartennummer `4111111111111111`, Ablauf `08/27`, CVV `123` → buchen   | `POST /api/v1/bookings`, Erfolgsseite mit Buchungsnummer                | PASS     |
| E2-M5 | `available_seats` per DB auf 0 setzen, nochmals buchen                                 | Backend 422 "Not enough seats available.", Fehler im UI                 | PASS     |
| E2-M6 | Nach Buchung Tab "Buchungen" öffnen                                                    | Neue Buchung steht ganz oben (Sortierung: Datum desc)                   | PASS     |

## 3. Backend-Validierung (curl)

Buchung mit gültigem Token und genug Plätzen:
```bash
curl -sS -X POST http://localhost:8080/api/v1/bookings \
  -H "Authorization: Bearer $TOKEN" \
  -H 'Content-Type: application/json' \
  -d '{"flight_id":1,"num_tickets":1}'
```
→ `{"booking_id":18,"success":true}` (201)

Ohne Token:
```bash
curl -sS -o /dev/null -w '%{http_code}\n' \
  -X POST http://localhost:8080/api/v1/bookings \
  -H 'Content-Type: application/json' \
  -d '{"flight_id":1,"num_tickets":1}'
```
→ `401`

`BookingService::createBooking` prüft `available_seats >= num_tickets`
**innerhalb der gleichen DB-Transaktion** wie das Insert, damit
gleichzeitige Buchungen keine Überbuchung erzeugen können.

## Fazit

Akzeptanzkriterien aus Epic 2 erfüllt:
- "Buchen"-Button und Formular für Zahlungsdetails sind da.
- Backend-Endpunkt `/api/v1/bookings` ist angebunden.
- Verfügbarkeit wird vor und während der Buchung serverseitig geprüft.
- End-to-End-Tests decken Happy Path, Validierung und Backend-Fehler ab.
