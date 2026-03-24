<!-- Progress bar -->
<div class="pres-progress" id="progress"><div class="pres-progress-bar" id="progressBar"></div></div>

<!-- Slide counter -->
<div class="pres-counter" id="counter">1 / 15</div>

<!-- Back to app -->
<a href="/" class="pres-back" title="Back to Vagabond (Esc)">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
</a>

<!-- Slides container -->
<div class="pres-deck" id="deck">

    <!-- 1. Title -->
    <section class="slide">
        <div class="slide-content slide-center">
            <p class="text-brand text-sm md:text-base font-semibold uppercase tracking-widest mb-5">TEKO Case Study</p>
            <h1 class="slide-heading font-black tracking-tight mb-5">Vagabond</h1>
            <p class="text-lg md:text-2xl text-gray-400 font-light">A Flight Booking System</p>
            <div class="mt-14 flex items-center gap-3 text-gray-600 text-sm tracking-wide">
                <span>Nick</span>
                <span class="text-gray-700">|</span>
                <span>2026</span>
            </div>
        </div>
    </section>

    <!-- 2. Aufgabenstellung -->
    <section class="slide">
        <div class="slide-content">
            <p class="slide-label">Aufgabenstellung</p>
            <h2 class="slide-heading">User Story</h2>
            <div class="slide-card">
                <p class="text-base md:text-lg leading-relaxed text-gray-300">
                    <span class="text-brand font-semibold">&ldquo;</span>
                    Als <strong class="text-white">Felix Huber</strong>, ein reisebegeisterter Kunde, möchte ich ein
                    benutzerfreundliches Online-Flugbuchungssystem nutzen, um schnell und einfach
                    <strong class="text-white">Flüge zu suchen</strong>, Preise und Verfügbarkeit zu vergleichen und
                    <strong class="text-white">Buchungen durchzuführen</strong>, damit ich meine Reisen effizient
                    planen und buchen kann.
                    <span class="text-brand font-semibold">&rdquo;</span>
                </p>
            </div>
        </div>
    </section>

    <!-- 3. Journey intro -->
    <section class="slide">
        <div class="slide-content slide-center">
            <p class="slide-label">Meine Journey</p>
            <h2 class="text-4xl md:text-6xl font-black tracking-tight mb-6">New Old School</h2>
            <figure class="max-w-3xl my-8 mx-auto text-center">
                <svg class="w-11 h-11 text-heading mb-4 mx-auto" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V8a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1Zm0 0v2a4 4 0 0 1-4 4H5m14-6V8a1 1 0 0 0-1-1h-3a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1Zm0 0v2a4 4 0 0 1-4 4h-1"/></svg>
                <blockquote>
                    <p class="text-3xl italic font-semibold tracking-tight text-heading">"What I cannot create, I do not understand."</p>
                </blockquote>
                <figcaption class="flex items-center justify-center mt-6 space-x-3 rtl:space-x-reverse">
                    <div class="flex items-center divide-x rtl:divide-x-reverse divide-default">
                        <cite class="pe-3 font-medium text-heading">Richard Feyman</cite>
                    </div>
                </figcaption>
            </figure>
            <p class="text-sm md:text-xl text-gray-600 mt-3">
                Vier Entscheidungen, die zu Vagabond geführt haben.
            </p>
        </div>
    </section>

    <!-- 4. Motivation 1: Das HTML-Problem -->
    <section class="slide">
        <div class="slide-content">
            <p class="slide-label">Ausgangspunkt</p>
            <h2 class="slide-heading">Das HTML-Problem</h2>
            <div class="slide-card">
                <p class="text-gray-300">
                    Im Modul <strong class="text-white">Web Engineering</strong> haben wir ganz am Anfang begonnen, die Fit-App mit purem HTML zu bauen.
                    Dabei bin ich auf eine fundamentale Limitation gestossen:
                </p>
            </div>
            <ul class="slide-list">
                <li>Kein komponenten-basiertes Bauen möglich</li>
                <li>Jede Page ein eigenes File &mdash; Navbar, Footer, alles kopiert</li>
                <li>Enorme Redundanz, schwer wartbar</li>
            </ul>
            <div class="slide-card slide-card-accent">
                <p class="text-gray-300">
                    Dann hat es <strong class="text-brand">Klick</strong> gemacht:
                    PHP löst genau dieses Problem. Backend-Code und HTML in einem.
                    Templates, Includes, Komponenten. Das war der erste Funke.
                </p>
            </div>
        </div>
    </section>

    <!-- 5. Motivation 2: PHP im Beruf -->
    <section class="slide">
        <div class="slide-content">
            <p class="slide-label">Beruflicher Kontext</p>
            <h2 class="slide-heading">PHP im Beruf</h2>
            <div class="slide-card">
                <p class="text-gray-300">
                    In meinem Unternehmen bauen wir die meisten Backends unserer Webapps
                    mit <strong class="text-white">Pure PHP</strong>,
                    <strong class="text-white">Laravel</strong> und
                    <strong class="text-white">Symfony</strong>.
                </p>
            </div>
            <div class="slide-card slide-card-accent">
                <p class="text-gray-300">
                    Die Case Study war die perfekte Gelegenheit,
                    <strong class="text-brand">PHP richtig zu lernen</strong> &mdash;
                    nicht nur Syntax, sondern die Sprache verstehen, Patterns anwenden
                    und ein Projekt von Grund auf damit aufbauen.
                </p>
            </div>
        </div>
    </section>

    <!-- 6. Motivation 3: Die Framework-Falle -->
    <section class="slide">
        <div class="slide-content">
            <p class="slide-label">Erkenntnis</p>
            <h2 class="slide-heading">Die Komplexität moderner Frontend-Frameworks</h2>
            <ul class="slide-list">
                <li>Schulprojekt: Backend mit Java gebaut, dann am Frontend mit Vue/Nuxt gescheitert</li>
                <li>Bei der Arbeit: Anpassungen an modernen JS-Frontends &mdash; überfordert von der Komplexität</li>
                <li>Gefühl: eingeschränkt in Kreativität und Fähigkeit, Dinge gut umzusetzen</li>
            </ul>
            <div class="slide-card slide-card-accent">
                <p class="text-gray-300">
                    <strong class="text-brand">Die Frage:</strong>
                    Wieso nutzen wir diese Frameworks? Wieso haben wir SSR durch CSR ersetzt, 
                    nur um jetzt mit Meta-Frameworks wie Next.js und Nuxt SSR wieder einzuführen?
                </p>
                <p class="text-gray-500 mt-3" style="font-size: inherit;">
                    Um das zu verstehen, wollte ich einen Schritt zurück machen.
                </p>
            </div>
        </div>
    </section>

    <!-- 7. Motivation 4: Hypermedia Systems -->
    <section class="slide">
        <div class="slide-content">
            <p class="slide-label">Inspiration</p>
            <h2 class="slide-heading">Hypermedia Systems</h2>
            <div class="slide-card">
                <p class="text-gray-500 text-xs uppercase tracking-widest mb-3" style="font-size: 0.75rem; line-height: 1;">Das Buch, das alles zusammengebracht hat</p>
                <p class="text-gray-300">
                    <strong class="text-white">hypermedia.systems</strong> hat mir gezeigt:
                    der einfachste Weg ist oft der richtige.
                </p>
            </div>
            <ul class="slide-list">
                <li>Server-Side Rendering als Fundament &mdash; HTML ist die App</li>
                <li>JavaScript nur dort einsetzen, wo echte Interaktivität nötig ist</li>
                <li>Kein Build-Step, kein virtuelles DOM, kein State Management</li>
                <li>HTMX als Brücke: SPA-ähnliche UX ohne die SPA-Komplexität</li>
            </ul>
            <div class="slide-card slide-card-accent">
                <p class="text-gray-300">
                    <strong class="text-brand">Mein Ansatz:</strong>
                    App mit SSR bauen. Dann partiell, genau dort wo nötig, Interaktivität
                    mit JavaScript hinzufügen.
                </p>
            </div>
        </div>
    </section>

    <!-- 8. Tech Stack -->
    <section class="slide">
        <div class="slide-content">
            <p class="slide-label">Technologien</p>
            <h2 class="slide-heading">Tech Stack</h2>
            <div class="slide-tech-grid">
                <div class="slide-card slide-card-compact mt-5">
                    <span class="text-3xl mb-1">🐘</span>
                    <strong class="text-white">PHP 8.5</strong>
                    <span>Backend, Routing, Templating</span>
                </div>
                <div class="slide-card slide-card-compact">
                    <span class="text-3xl mb-1">🗄️</span>
                    <strong class="text-white">SQLite</strong>
                    <span>Portable Datenbank</span>
                </div>
                <div class="slide-card slide-card-compact">
                    <span class="text-3xl mb-1">🎨</span>
                    <strong class="text-white">Tailwind CSS</strong>
                    <span>Utility-first Styling</span>
                </div>
                <div class="slide-card slide-card-compact">
                    <span class="text-3xl mb-1">⚡</span>
                    <strong class="text-white">HTMX</strong>
                    <span>Hypermedia-driven UX</span>
                </div>
                <div class="slide-card slide-card-compact">
                    <span class="text-3xl mb-1">🐳</span>
                    <strong class="text-white">Docker</strong>
                    <span>Nginx + PHP-FPM</span>
                </div>
                <div class="slide-card slide-card-compact">
                    <span class="text-3xl mb-1">🚀</span>
                    <strong class="text-white">Railway</strong>
                    <span>Cloud Deployment</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. Architecture -->
    <section class="slide">
        <div class="slide-content">
            <p class="slide-label">Architektur</p>
            <h2 class="slide-heading">Clean Architecture</h2>
            <p class="slide-subtitle">Kein Framework &mdash; eigener Router, Database Singleton, Schema &amp; Seeder, View Engine</p>
            <div class="arch-diagram">
                <div class="arch-layer arch-presentation">
                    <span class="arch-layer-label">Presentation</span>
                    <div class="arch-items">
                        <span>Router</span>
                        <span>Controller</span>
                        <span>View</span>
                        <span>Templates</span>
                    </div>
                </div>
                <div class="arch-arrow">&#8595;</div>
                <div class="arch-layer arch-application">
                    <span class="arch-layer-label">Application</span>
                    <div class="arch-items">
                        <span>Services</span>
                        <span>Ports (Interfaces)</span>
                    </div>
                </div>
                <div class="arch-arrow">&#8595;</div>
                <div class="arch-layer arch-domain">
                    <span class="arch-layer-label">Domain</span>
                    <div class="arch-items">
                        <span>Entities</span>
                        <span>Enums</span>
                    </div>
                </div>
                <div class="arch-arrow">&#8595;</div>
                <div class="arch-layer arch-infrastructure">
                    <span class="arch-layer-label">Infrastructure</span>
                    <div class="arch-items">
                        <span>SQLite Repositories</span>
                        <span>DB Singleton</span>
                        <span>Schema &amp; Seeder</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 10. Request Lifecycle -->
    <section class="slide">
        <div class="slide-content">
            <p class="slide-label">Request Lifecycle</p>
            <h2 class="slide-heading">Vom Browser zur Datenbank</h2>
            <div class="flow-pipeline">
                <div class="flow-divider"><span>Request</span></div>
                <div class="flow-stage flow-stage-amber">
                    <span class="flow-stage-name">Browser</span>
                    <span class="flow-stage-desc">HTTP Request, z.B. GET /flights?airline=LX</span>
                </div>
                <div class="flow-stage flow-stage-amber">
                    <span class="flow-stage-name">Nginx</span>
                    <span class="flow-stage-desc">Reverse Proxy &mdash; statische Files direkt, alles andere via FastCGI weiter</span>
                </div>
                <div class="flow-stage flow-stage-amber">
                    <span class="flow-stage-name">PHP-FPM</span>
                    <span class="flow-stage-desc">FastCGI Process Manager startet PHP-Worker</span>
                </div>
                <div class="flow-divider"><span>Application</span></div>
                <div class="flow-stage flow-stage-pink">
                    <span class="flow-stage-name">index.php</span>
                    <span class="flow-stage-desc">Bootstrap: Repos &rarr; Services &rarr; Controllers instanziieren, Routen registrieren</span>
                </div>
                <div class="flow-stage flow-stage-pink">
                    <span class="flow-stage-name">Router</span>
                    <span class="flow-stage-desc">dispatch() &mdash; Method + Path matchen, Controller-Methode aufrufen</span>
                </div>
                <div class="flow-stage flow-stage-blue">
                    <span class="flow-stage-name">Controller</span>
                    <span class="flow-stage-desc">Request-Daten auslesen, Service aufrufen, View erstellen</span>
                </div>
                <div class="flow-stage flow-stage-blue">
                    <span class="flow-stage-name">Service</span>
                    <span class="flow-stage-desc">Business-Logik, Validierung, Repository aufrufen</span>
                </div>
                <div class="flow-stage flow-stage-green">
                    <span class="flow-stage-name">Repository</span>
                    <span class="flow-stage-desc">SQL-Query bauen, via PDO auf SQLite zugreifen</span>
                </div>
                <div class="flow-divider"><span>Response</span></div>
                <div class="flow-stage flow-stage-purple">
                    <span class="flow-stage-name">View</span>
                    <span class="flow-stage-desc">HTMX? &rarr; render() (Partial) &mdash; sonst renderFull() (base.php + Page)</span>
                </div>
                <div class="flow-stage flow-stage-purple">
                    <span class="flow-stage-name">HTML</span>
                    <span class="flow-stage-desc">Server-gerenderte Response zurück an den Browser</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 11. Database Schema -->
    <section class="slide">
        <div class="slide-content">
            <p class="slide-label">Datenbank</p>
            <h2 class="slide-heading">Schema</h2>
            <div class="schema-grid">
                <div class="schema-table">
                    <div class="schema-table-name schema-t-green">airports</div>
                    <div class="schema-table-cols">
                        <span class="schema-pk">id  INTEGER PK</span>
                        <span>iata_code  TEXT UNIQUE</span>
                        <span>name  TEXT</span>
                        <span>city  TEXT</span>
                        <span>country  TEXT</span>
                    </div>
                </div>
                <div class="schema-table">
                    <div class="schema-table-name schema-t-green">airlines</div>
                    <div class="schema-table-cols">
                        <span class="schema-pk">id  INTEGER PK</span>
                        <span>iata_code  TEXT UNIQUE</span>
                        <span>name  TEXT</span>
                        <span>logo_url  TEXT</span>
                    </div>
                </div>
                <div class="schema-table">
                    <div class="schema-table-name schema-t-green">airplanes</div>
                    <div class="schema-table-cols">
                        <span class="schema-pk">id  INTEGER PK</span>
                        <span>model  TEXT</span>
                        <span>manufacturer  TEXT</span>
                        <span>capacity  INTEGER</span>
                        <span>range  TEXT</span>
                    </div>
                </div>
                <div class="schema-table">
                    <div class="schema-table-name schema-t-blue">flights</div>
                    <div class="schema-table-cols">
                        <span class="schema-pk">id  INTEGER PK</span>
                        <span>flight_number  TEXT</span>
                        <span class="schema-fk">airline_id &rarr; airlines</span>
                        <span class="schema-fk">airplane_id &rarr; airplanes</span>
                        <span class="schema-fk">departure_airport_id &rarr; airports</span>
                        <span class="schema-fk">arrival_airport_id &rarr; airports</span>
                        <span>departure_time / arrival_time</span>
                        <span>price  REAL</span>
                        <span>total_seats / available_seats</span>
                    </div>
                </div>
                <div class="schema-table">
                    <div class="schema-table-name schema-t-purple">users</div>
                    <div class="schema-table-cols">
                        <span class="schema-pk">id  INTEGER PK</span>
                        <span>first_name  TEXT</span>
                        <span>last_name  TEXT</span>
                        <span>email  TEXT UNIQUE</span>
                        <span>password  TEXT</span>
                        <span>created_at  TIMESTAMP</span>
                    </div>
                </div>
                <div class="schema-table">
                    <div class="schema-table-name schema-t-pink">bookings</div>
                    <div class="schema-table-cols">
                        <span class="schema-pk">id  INTEGER PK</span>
                        <span class="schema-fk">user_id &rarr; users</span>
                        <span class="schema-fk">flight_id &rarr; flights</span>
                        <span>num_tickets  INTEGER</span>
                        <span>total_price  REAL</span>
                        <span>status  TEXT</span>
                        <span>booking_date  TIMESTAMP</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 12. Class Diagram -->
    <section class="slide">
        <div class="slide-content">
            <p class="slide-label">Klassendiagramm</p>
            <h2 class="slide-heading">Ports &amp; Adapters</h2>
            <div class="class-diagram">
                <div class="class-group-label">Presentation &mdash; Controller</div>
                <div class="class-row">
                    <div class="class-box class-box-pink">
                        <span class="class-box-name">FlightController</span>
                        <span class="class-box-methods">index() book() processBooking()</span>
                    </div>
                    <div class="class-box class-box-pink">
                        <span class="class-box-name">BookingController</span>
                        <span class="class-box-methods">index()</span>
                    </div>
                    <div class="class-box class-box-pink">
                        <span class="class-box-name">AuthController</span>
                        <span class="class-box-methods">login() register() logout()</span>
                    </div>
                </div>
                <div class="class-connector">&#8595; inject Services</div>
                <div class="class-group-label">Application &mdash; Services &amp; Ports</div>
                <div class="class-row">
                    <div class="class-box class-box-blue">
                        <span class="class-box-name">FlightService</span>
                        <span class="class-box-methods">findAll() search() findById()</span>
                    </div>
                    <div class="class-box class-box-blue">
                        <span class="class-box-name">BookingService</span>
                        <span class="class-box-methods">getByUserId() createBooking()</span>
                    </div>
                    <div class="class-box class-box-blue">
                        <span class="class-box-name">AuthService</span>
                        <span class="class-box-methods">login() register() getUser()</span>
                    </div>
                </div>
                <div class="class-connector">&#8595; depend on Interfaces (Ports)</div>
                <div class="class-group-label">Application &mdash; Ports (Interfaces)</div>
                <div class="class-row">
                    <div class="class-box class-box-purple">
                        <span class="class-box-name">&laquo;interface&raquo; FlightRepository</span>
                        <span class="class-box-methods">findAll() findByFilters() countAll()</span>
                    </div>
                    <div class="class-box class-box-purple">
                        <span class="class-box-name">&laquo;interface&raquo; BookingRepository</span>
                        <span class="class-box-methods">findByUserId() create()</span>
                    </div>
                    <div class="class-box class-box-purple">
                        <span class="class-box-name">&laquo;interface&raquo; UserRepository</span>
                        <span class="class-box-methods">findByEmail() create() delete()</span>
                    </div>
                </div>
                <div class="class-connector">&#8593; implements</div>
                <div class="class-group-label">Infrastructure &mdash; Adapters</div>
                <div class="class-row">
                    <div class="class-box class-box-green">
                        <span class="class-box-name">SqliteFlightRepository</span>
                        <span class="class-box-methods">PDO &rarr; SQLite</span>
                    </div>
                    <div class="class-box class-box-green">
                        <span class="class-box-name">SqliteBookingRepository</span>
                        <span class="class-box-methods">PDO &rarr; SQLite</span>
                    </div>
                    <div class="class-box class-box-green">
                        <span class="class-box-name">SqliteUserRepository</span>
                        <span class="class-box-methods">PDO &rarr; SQLite</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 13. Die App -->
    <section class="slide">
        <div class="slide-content">
            <p class="slide-label">Features</p>
            <h2 class="slide-heading">Die App</h2>
            <div class="slide-feature-grid">
                <div class="slide-card">
                    <strong class="text-white">Flugsuche &amp; Filter</strong>
                    <span>Suche nach Abflug/Ziel, Filter nach Airline, Datum, Preis, Verfügbarkeit &mdash; HTMX-basiert ohne Reload</span>
                </div>
                <div class="slide-card">
                    <strong class="text-white">Buchungssystem</strong>
                    <span>Ticketauswahl, dynamische Preisberechnung, atomare Transaktionen mit Seat-Check</span>
                </div>
                <div class="slide-card">
                    <strong class="text-white">Buchungshistorie</strong>
                    <span>Persönliche Übersicht aller Buchungen mit Status, Route und Details</span>
                </div>
                <div class="slide-card">
                    <strong class="text-white">Auth &amp; Account</strong>
                    <span>Session-basierte Auth, Profilverwaltung, Password Hashing, Responsive Design</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 14. Reflexion -->
    <section class="slide">
        <div class="slide-content">
            <p class="slide-label">Reflexion</p>
            <h2 class="slide-heading">Was ich gelernt habe</h2>
            <ul class="slide-list">
                <li>PHP ist mehr als sein Ruf &mdash; mit Clean Architecture entsteht sauberer, wartbarer Code</li>
                <li>Frameworks lösen echte Probleme, aber man muss die Probleme zuerst selbst erlebt haben</li>
                <li>HTMX beweist: für viele Apps braucht man kein JavaScript-Framework</li>
                <li>Alles selbst zu bauen &mdash; Router, DB-Layer, View Engine &mdash; gibt ein tiefes Verständnis</li>
                <li>Der Schritt zurück zu SSR hat mir gezeigt, wohin die Reise in der Webentwicklung geht</li>
            </ul>
        </div>
    </section>

    <!-- 15. Live Demo -->
    <section class="slide">
        <div class="slide-content slide-center">
            <p class="slide-label">Live Demo</p>
            <h2 class="text-4xl md:text-7xl font-black tracking-tight mb-5">Vagabond in Action</h2>
            <p class="text-gray-500 text-base md:text-lg mb-10 font-light">Deployed via Docker on Railway</p>
            <a href="/" class="inline-block px-8 py-4 bg-brand text-white font-bold text-base uppercase tracking-wider hover:bg-brand-dark transition-colors">
                Open App &rarr;
            </a>
            <p class="text-gray-700 mt-20 text-sm tracking-wide">Vielen Dank!</p>
        </div>
    </section>

</div>

<script>
(function () {
    const deck = document.getElementById('deck');
    const slides = deck.querySelectorAll('.slide');
    const total = slides.length;
    const bar = document.getElementById('progressBar');
    const counter = document.getElementById('counter');
    let current = 0;

    function goTo(index) {
        if (index < 0 || index >= total) return;
        current = index;
        slides[current].scrollIntoView({ behavior: 'smooth' });
        update();
    }

    function update() {
        const pct = ((current + 1) / total) * 100;
        bar.style.width = pct + '%';
        counter.textContent = (current + 1) + ' / ' + total;
    }

    function next() { goTo(current + 1); }
    function prev() { goTo(current - 1); }

    // Keyboard navigation
    document.addEventListener('keydown', function (e) {
        if (e.key === 'ArrowDown' || e.key === 'ArrowRight' || e.key === ' ') {
            e.preventDefault();
            next();
        } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
            e.preventDefault();
            prev();
        } else if (e.key === 'Escape') {
            window.location.href = '/';
        }
    });

    // Click navigation (left half = prev, right half = next)
    deck.addEventListener('click', function (e) {
        if (e.target.closest('a, button')) return;
        if (e.clientX < window.innerWidth / 2) {
            prev();
        } else {
            next();
        }
    });

    // Sync current slide on scroll
    let scrollTimeout;
    deck.addEventListener('scroll', function () {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function () {
            const scrollTop = deck.scrollTop;
            const slideHeight = deck.clientHeight;
            const index = Math.round(scrollTop / slideHeight);
            if (index !== current && index >= 0 && index < total) {
                current = index;
                update();
            }
        }, 100);
    });

    update();
})();
</script>
