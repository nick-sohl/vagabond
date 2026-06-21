# Testprotokoll – Epic 1: Flugübersicht & Filter

**User Story:** "Als Felix möchte ich Flüge nach Kriterien filtern, um passende Angebote schnell zu finden."

Stand: 2026-06-21
Getestet auf: Flutter 3.44.2 (Dart 3.12.2), iOS-Simulator (iPhone 15) und
Android-Emulator (Pixel 7 API 35). Backend lief lokal via `docker compose`.

## 1. UI-Tests (`flutter test`)

Die Tests prüfen den Such-Flow ohne echten Netzwerk-Aufruf, mit einer Fake
Repository (siehe `flutter_application/test/flight_search_state_test.dart`):

| ID    | Testfall                                                  | Erwartet                                                    | Ergebnis |
|-------|-----------------------------------------------------------|-------------------------------------------------------------|----------|
| E1-T1 | Suche mit Abreiseort + Zielort                            | Repo bekommt `departure=ZRH, arrival=LHR`, 1 Flug zurück    | PASS     |
| E1-T2 | Schalter "Nur verfügbare Tickets"                         | Flüge mit 0 freien Sitzen werden gefiltert                  | PASS     |
| E1-T3 | Max. Preis 300 CHF                                        | Nur Flüge mit `price <= 300` sichtbar                       | PASS     |
| E1-T4 | API-Fehler                                                | `SearchStatus.error` + Nachricht aus dem Backend            | PASS     |

```text
$ flutter test test/flight_search_state_test.dart
00:00 +4: All tests passed!
```

## 2. UI-Tests (manuell, iOS Simulator + Android Emulator)

| ID    | Schritt                                                                    | Erwartet                                                      | Ergebnis |
|-------|----------------------------------------------------------------------------|---------------------------------------------------------------|----------|
| E1-M1 | App starten → Tab "Suchen" → "Suchen" antippen (keine Filter)              | Liste mit allen Flügen, Paginierung sichtbar                  | PASS     |
| E1-M2 | Abreiseort `ZRH` + Zielort `LHR` setzen → Suchen                           | Nur Flüge auf dieser Strecke                                  | PASS     |
| E1-M3 | Datum auf morgen setzen → Suchen                                           | Nur Flüge mit `DATE(departure_time) = morgen`                 | PASS     |
| E1-M4 | Zeit `08:00` eingeben → Suchen                                             | Nur Flüge mit Abflug `>= 08:00`                               | PASS     |
| E1-M5 | Fluggesellschaft "Swiss International Air Lines" wählen                    | Nur LX-Flüge                                                  | PASS     |
| E1-M6 | Max. Preis `200` → Suchen                                                  | Nur Flüge `price <= 200`                                      | PASS     |
| E1-M7 | "Nur verfügbare Tickets" aktivieren → Suchen                               | Ausgebuchte Flüge fallen raus                                 | PASS     |
| E1-M8 | "Mehr laden" antippen                                                      | Nächste 20 Flüge werden unten angehängt                       | PASS     |
| E1-M9 | "Zurücksetzen" antippen                                                    | Filter sind leer, Liste leert sich                            | PASS     |
| E1-M10| Pull-to-refresh                                                            | Suche mit den aktuellen Filtern wird neu ausgelöst            | PASS     |

## 3. API-Tests (curl)

```bash
TOKEN=$(curl -sS -X POST http://localhost:8080/api/v1/auth/login \
  -H 'Content-Type: application/json' \
  -d '{"email":"felix.huber@example.ch","password":"password"}' \
  | python3 -c 'import json,sys;print(json.load(sys.stdin)["token"])')

curl -sS "http://localhost:8080/api/v1/flights?departure=ZRH&arrival=LHR&availability=1&per_page=2"
```

Antwort (gekürzt):
```json
{ "data": [
    { "id": 1, "flight_number": "LX318",
      "departure": { "iata": "ZRH" }, "arrival": { "iata": "LHR" },
      "price": 245.5, "available_seats": 36 } ],
  "pagination": { "page": 1, "per_page": 2, "total": 1, "total_pages": 1 } }
```

## Fazit

Alle Akzeptanzkriterien aus Epic 1 (Filter nach Abreiseort, Zielort, Datum,
Zeit, Fluggesellschaft, Preis, Ticketverfügbarkeit; Anzeige als
übersichtliche Liste) sind umgesetzt und mit automatisierten und manuellen
Tests abgedeckt.
