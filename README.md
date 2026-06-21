# Vagabond - Flight Booking System

## Description

This project is part of my studies at TEKO Zurich in the field of Computer Science with specialization in Software Engineering in the class of Web Engineering.
The goal is a classic client-server 3-tier layered-architecture consisting of a frontend, backend and a relational database.

[Link to the task description](./docs/Aufgabenstellung_Case_Study_WEE.pdf)

## Tech Stack

| Layer      | Technology                              |
|------------|-----------------------------------------|
| Frontend   | HTML5, CSS3, Tailwind CSS 4.2, HTMX 2.0 |
| Backend    | PHP 8.5 (FPM for dev, CLI for deploy)   |
| Database   | SQLite 3 (PDO)                          |
| Web Server | Nginx (development)                     |
| Container  | Docker / Docker Compose                 |
| Hosting    | Railway                                 |

## Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (includes Docker Compose)
- [Node.js](https://nodejs.org/) >= 18 with [pnpm](https://pnpm.io/) (for Tailwind CSS build)

## Development

### 1. Clone the repository

```bash
git clone <repo-url>
cd vagabond
```

### 2. Start the dev environment

```bash
docker compose up -d --build
```

This starts two containers:
- **app** — PHP 8.5-FPM with Composer dependencies and SQLite
- **nginx** — Reverse proxy serving the app on port `8080`

The source code is mounted as a volume, so any changes to PHP or view files are reflected immediately on refresh.

### 3. Start the CSS watcher

In a separate terminal:

```bash
pnpm install
pnpm dev
```

This watches for Tailwind CSS changes and rebuilds `public/css/output.css` automatically.

### 4. Open the application

Visit [http://localhost:8080](http://localhost:8080)

### Typical workflow

```bash
# Terminal 1 — start containers (once)
docker compose up -d --build

# Terminal 2 — watch CSS (keep running)
pnpm dev

# Edit PHP, views, or CSS → refresh browser
# Stop when done:
docker compose down
```

### Reset the database

If you need a fresh database:

```bash
docker compose down -v        # removes the sqlite volume
docker compose up -d --build  # rebuilds with fresh schema + seed data
```

## Deployment (Railway)

Railway auto-deploys from the `main` branch using the root `Dockerfile` (PHP-CLI with built-in server). The Tailwind CSS build and database initialization happen during the Docker build step.

No manual setup needed — push to `main` and Railway handles the rest.

## Test Login

| Field    | Value                      |
|----------|----------------------------|
| Email    | `felix.huber@example.ch`   |
| Password | `password`                 |

You can also register a new account via the registration page.

## Project Structure

```
vagabond/
├── public/                     # Web root (document root)
│   ├── index.php               # Entry point — composition root & routing
│   └── router.php              # Router for PHP built-in server (deploy only)
├── src/
│   ├── Domain/Entity/          # Domain entities (Flight, Airport, etc.)
│   ├── Application/
│   │   ├── Port/               # Repository interfaces (ports)
│   │   └── Service/            # Business logic services
│   ├── Infrastructure/
│   │   └── Repository/         # SQLite repository implementations (adapters)
│   └── Presentation/
│       ├── Controller/         # Request handlers
│       └── Router/             # Simple route registration & dispatch
├── views/
│   ├── templates/              # Base HTML layout (base.php)
│   ├── pages/                  # Full page views (home, flights, bookings, auth, account)
│   ├── components/             # Reusable UI components (navbar, cards, forms)
│   └── elements/               # Small UI primitives (button, alert)
├── resources/css/              # Source CSS (Tailwind + base utilities)
├── db/
│   ├── schema.sql              # Database schema (6 tables with indexes)
│   └── seed.sql                # Test data (40 airports, 20 airlines, 50 flights)
├── nginx/
│   └── default.conf            # Nginx config for local development
├── docs/                       # Documentation & assignment
├── Dockerfile                  # Deploy image (PHP-CLI, builds CSS, seeds DB)
├── Dockerfile.dev              # Dev image (PHP-FPM for use with Nginx)
└── compose.yaml                # Docker Compose for local development
```

## Architecture

The application follows **Clean Architecture** with clear separation of concerns:

- **Domain** — Plain PHP entities, no framework dependencies
- **Application** — Services contain business logic, Ports define repository interfaces
- **Infrastructure** — SQLite implementations of the repository ports (adapters)
- **Presentation** — Controllers handle HTTP, views render HTML

Dependencies point inward: Presentation -> Application -> Domain. Infrastructure implements Application ports.

## Database Schema

6 tables: `airports`, `airlines`, `airplanes`, `users`, `flights`, `bookings`

See [db/schema.sql](./db/schema.sql) for the full schema with constraints, foreign keys, and indexes.

## Documentation

- [Aufgabenstellung](./docs/Aufgabenstellung_Case_Study_WEE.pdf) — WEE assignment description
- [Testprotokoll](./docs/testprotokoll.md) — WEE test protocols for Epics 1-3
- [Reflexion](./docs/reflexion.md) — WEE project reflection

### MOA (Mobile App)

The Flutter mobile client lives in [`flutter_application/`](./flutter_application/)
and consumes a JSON REST API exposed under `/api/v1/*` by this same backend.

- [MOA documentation index](./docs/moa/README.md)
- [Mobile app README & install guide](./flutter_application/README.md)
- [REST API reference](./docs/moa/api-reference.md)
- Test protocols: [Epic 1](./docs/moa/testprotokoll-epic1.md),
  [Epic 2](./docs/moa/testprotokoll-epic2.md),
  [Epic 3](./docs/moa/testprotokoll-epic3.md),
  [Epic 5 NFR](./docs/moa/testprotokoll-epic5-nfr.md)
- [MOA reflexion](./docs/moa/reflexion.md)
