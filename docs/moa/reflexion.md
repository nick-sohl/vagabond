# Reflexion: MOA Case Study

Die Aufgabe war eine plattformunabhängige Mobile App zu bauen, die auf der
bestehenden Middleware aus der WEE Case Study läuft. Das Backend lieferte
bis jetzt nur HTML und HTMX Fragmente aus. Ich habe deshalb entschieden,
die bestehenden Services und Web Routen gar nicht anzufassen, sondern eine
dünne JSON Schicht unter /api/v1/* dazu zu bauen. So bleibt die Clean
Architecture (Domain, Application, Infrastructure, Presentation) intakt,
die Web Variante läuft unverändert weiter, und der Mobile Teil ist client
stateless. Statt PHP Sessions setze ich Bearer Token ein, weil Cookies auf
dem Smartphone mühsam und auch weniger sicher sind.

Auf der Flutter Seite habe ich bewusst provider mit ChangeNotifier
verwendet statt einem grossen Framework wie Riverpod oder Bloc. Drei
States (AuthState, FlightSearchState, BookingHistoryState) reichen für die
Akzeptanzkriterien locker, und sind viel einfacher zu lesen. Die
Repositories trennen das UI sauber von der HTTP Schicht. Das hat erst die
Widget Tests möglich gemacht. FakeFlightRepository und FakeBookingRepository
simulieren die Filter im Speicher, ohne dass dazu ein Backend laufen muss.

Etwas knifflig war der Endpunkt aus Epic 3 (/bookings/{userId}). Wenn ich
die userId direkt aus der URL nehme, könnte ein User auch die Buchungen
von anderen Usern sehen. Ich habe darum GET /api/v1/bookings gemacht und
lese den User aus dem Bearer Token. Funktional ist es dasselbe, aber
sicherer. Das ist in docs/moa/api-reference.md dokumentiert. Ähnlich
pragmatisch ist flights/show?id=... statt flights/{id}, weil der Router
aus der WEE nur Exact Match kann, und ich den nicht extra anpassen wollte.

Das Projekt zeigt für mich, dass eine gute Backend Architektur sich ohne
grossen Umbau auf andere Plattformen erweitern lässt. Mit flutter test
(13 grüne Tests), flutter analyze (clean) und manuellen Tests auf iOS und
Android sind aus meiner Sicht alle funktionalen und nicht funktionalen
Akzeptanzkriterien erfüllt.
