# MOA – Mobile Apps Case Study (Z-TIA-23-T-a)

Übersicht über die Abgabedokumente.

| Dokument                                                    | Inhalt                                                |
|-------------------------------------------------------------|-------------------------------------------------------|
| [`../Aufgabenstellung_Case_Study_WEE.pdf`](../Aufgabenstellung_Case_Study_WEE.pdf)| Vorgängerprojekt (Middleware)         |
| [`api-reference.md`](./api-reference.md)                    | JSON REST API für die Mobile App                       |
| [`testprotokoll-epic1.md`](./testprotokoll-epic1.md)        | Tests Epic 1 – Flugübersicht & Filter                  |
| [`testprotokoll-epic2.md`](./testprotokoll-epic2.md)        | Tests Epic 2 – Buchungssystem                          |
| [`testprotokoll-epic3.md`](./testprotokoll-epic3.md)        | Tests Epic 3 – Buchungshistorie                        |
| [`testprotokoll-epic5-nfr.md`](./testprotokoll-epic5-nfr.md)| Tests Epic 5 – Nicht-funktionale Anforderungen          |
| [`reflexion.md`](./reflexion.md)                            | Reflexion (~½ Seite)                                   |
| [`../../flutter_application/README.md`](../../flutter_application/README.md) | Installationsanleitung für die Flutter App |

Tech-Stack:
- Flutter 3.44.2 / Dart 3.12.2 (Android + iOS)
- Backend: PHP 8.5 + SQLite (bestehende Vagabond-Middleware aus WEE)
- Auth: Bearer-Token (siehe `api-reference.md`)
