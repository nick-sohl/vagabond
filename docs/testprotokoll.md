# Testprotokoll

Datum: 21.03.2026
Tester: Nick Sohl
Umgebung: Docker (PHP 8.5-FPM + Nginx), Browser: Chrome, localhost:8080

---

## Epic 1: Flugubersicht & Filter

### T1.1 — Flugubersicht ohne Filter laden

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | `/flights` aufrufen | Seite ladt mit Flugkarten | OK |
| 2 | Flugliste pruefen | Max. 10 Fluege pro Seite (Pagination) | OK |
| 3 | Flugkarte pruefen | Airline, Flugnummer, Route, Zeiten, Preis, Verfuegbarkeit sichtbar | OK |

### T1.2 — Filter: Abflugort

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | "Zurich" in Abflugort eingeben | Autocomplete-Dropdown erscheint via HTMX | OK |
| 2 | "Zurich Airport (ZRH)" auswahlen | Feld wird mit IATA-Code befuellt | OK |
| 3 | Filter absenden | Nur Fluege ab ZRH angezeigt | OK |

### T1.3 — Filter: Zielort

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | "London" in Zielort eingeben | Autocomplete zeigt London-Flughaefen | OK |
| 2 | "London Heathrow (LHR)" auswahlen | Feld befuellt | OK |
| 3 | Filter absenden | Nur Fluege nach LHR angezeigt | OK |

### T1.4 — Filter: Datum und Zeit

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Datum ueber datetime-local Picker wahlen | Feld zeigt gewahltes Datum | OK |
| 2 | Filter absenden | Nur Fluege ab diesem Datum/Uhrzeit angezeigt | OK |

### T1.5 — Filter: Fluggesellschaft

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Airline-Dropdown oeffnen | Alle Airlines aufgelistet | OK |
| 2 | "Swiss International Air Lines" wahlen | Dropdown zeigt Auswahl | OK |
| 3 | Filter absenden | Nur Swiss-Fluege angezeigt | OK |

### T1.6 — Filter: Maximalpreis

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | "200" in Max. Preis eingeben | Wert akzeptiert | OK |
| 2 | Filter absenden | Nur Fluege <= CHF 200 angezeigt | OK |

### T1.7 — Filter: Ticketverfuegbarkeit

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Checkbox "Nur verfuegbare" aktivieren | Checkbox markiert | OK |
| 2 | Filter absenden | Nur Fluege mit available_seats > 0 angezeigt | OK |

### T1.8 — Kombinierter Filter

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Abflugort: ZRH, Airline: Swiss, Max. Preis: 500 | Alle Felder befuellt | OK |
| 2 | Filter absenden | Ergebnisliste zeigt nur passende Fluege | OK |
| 3 | Filter zuruecksetzen | Alle Fluege werden wieder angezeigt | OK |

### T1.9 — Pagination

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Seite 2 anklicken | Nachste 10 Fluege geladen | OK |
| 2 | Filter setzen, Seite wechseln | Filter bleibt aktiv ueber Seitenwechsel | OK |

### T1.10 — Keine Ergebnisse

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Unrealistischen Filter setzen (z.B. Preis < 1) | Keine Fluege gefunden | OK |
| 2 | Meldung pruefen | Hinweis "Keine Fluege gefunden" angezeigt | OK |

---

## Epic 2: Buchungssystem

### T2.1 — Buchung ohne Login

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Ohne Login auf "Book" klicken | Weiterleitung auf `/auth/login` | OK |
| 2 | Login-Formular pruefen | Login-Seite wird angezeigt | OK |

### T2.2 — Checkout-Seite laden

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Als felix.huber@example.ch einloggen | Login erfolgreich, Redirect | OK |
| 2 | Auf Flugliste einen Flug "Book" klicken | Checkout-Seite mit Flugdetails | OK |
| 3 | Linke Spalte pruefen | Flugdetails (Route, Zeiten, Preis) angezeigt | OK |
| 4 | Rechte Spalte pruefen | Zahlungsformular angezeigt | OK |

### T2.3 — Ticketanzahl und Preisberechnung

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Ticketanzahl auf 2 setzen | Feld akzeptiert Wert | OK |
| 2 | Gesamtpreis pruefen | Preis = 2 x Einzelpreis, dynamisch berechnet | OK |
| 3 | Ticketanzahl auf 3 aendern | Gesamtpreis aktualisiert sich sofort (JS) | OK |

### T2.4 — Buchung durchfuehren

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Zahlungsdetails ausfuellen | Karteninhaber, Kartennr., Ablauf, CVV | OK |
| 2 | "Jetzt buchen" klicken | POST an `/flights/book` | OK |
| 3 | Ergebnis pruefen | Weiterleitung auf Erfolgsseite | OK |
| 4 | Erfolgsseite pruefen | Bestaetigung angezeigt | OK |

### T2.5 — Verfuegbarkeit pruefen

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Flug mit wenigen Plaetzen buchen | Buchung erfolgreich | OK |
| 2 | available_seats in DB pruefen | Wert wurde um num_tickets reduziert | OK |
| 3 | Mehr Tickets buchen als verfuegbar | Fehlermeldung wird angezeigt | OK |

### T2.6 — Buchung in DB gespeichert

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Nach Buchung: Bookings-Tabelle pruefen | Neuer Eintrag mit user_id, flight_id, num_tickets, total_price, status='confirmed' | OK |
| 2 | Buchungsdatum pruefen | Aktuelles Datum/Uhrzeit gesetzt | OK |

---

## Epic 3: Buchungshistorie

### T3.1 — Buchungshistorie ohne Login

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Ohne Login `/bookings` aufrufen | Weiterleitung auf `/auth/login` | OK |

### T3.2 — Buchungshistorie anzeigen

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Als Felix einloggen | Login erfolgreich | OK |
| 2 | "My Bookings" in Navigation klicken | Buchungshistorie-Seite ladt | OK |
| 3 | Buchungen pruefen | Alle eigenen Buchungen als Karten angezeigt | OK |
| 4 | Buchungskarte pruefen | Airline, Flugnummer, Route, Zeiten, Status, Ticketanzahl, Preis sichtbar | OK |

### T3.3 — Sortierung nach Datum

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Reihenfolge der Buchungen pruefen | Neueste Buchung zuoberst (DESC) | OK |
| 2 | Neue Buchung erstellen, Seite neu laden | Neue Buchung erscheint zuoberst | OK |

### T3.4 — Leere Buchungshistorie

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Neuen User registrieren | Registrierung erfolgreich | OK |
| 2 | "My Bookings" aufrufen | Hinweis "Keine Buchungen" angezeigt | OK |

### T3.5 — Buchungsstatus anzeigen

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Bestaetigte Buchung pruefen | Status "Confirmed" mit gruener Markierung | OK |
| 2 | Status in Buchungskarte sichtbar | Korrekte Darstellung je Status | OK |

---

## Zusaetzliche Tests

### T-Auth — Authentifizierung

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Registrierung mit gueltigem E-Mail/Passwort | Account erstellt, Redirect auf Login | OK |
| 2 | Login mit richtigen Daten | Session gesetzt, Redirect auf Home | OK |
| 3 | Login mit falschem Passwort | Fehlermeldung angezeigt | OK |
| 4 | Logout klicken | Session zerstoert, Redirect auf Home | OK |
| 5 | Geschuetzte Seite ohne Login | Redirect auf Login | OK |

### T-Responsive — Responsives Design

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | Seite auf Desktop (1440px) pruefen | Volle Layouts, Sidebar-Filter | OK |
| 2 | Browser auf Tablet-Breite (768px) | Layout passt sich an | OK |
| 3 | Mobile Navigation | Hamburger-Menu funktioniert | OK |

### T-Security — Sicherheit

| Schritt | Aktion | Erwartetes Ergebnis | Ergebnis |
|---------|--------|---------------------|----------|
| 1 | SQL-Injection in Suchfeld versuchen (`' OR 1=1 --`) | Keine Auswirkung, prepared statements | OK |
| 2 | XSS in Eingabefeld (`<script>alert(1)</script>`) | HTML wird escaped, kein Script-Ausfuehrung | OK |
| 3 | Passwort in DB pruefen | Bcrypt-Hash gespeichert, kein Klartext | OK |
