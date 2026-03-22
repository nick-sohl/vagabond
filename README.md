# Vagabond - Flight Booking System

## Description

This project is part of my studies at TEKO Zurich in the field of Computer Science with specialization in Software Engineering in the class of Web Engineering.
The goal is a classic client-server 3-tier layered-architecture consisting of a frontend, backend and a relational database.

[Link to the task description](./docs/Aufgabenstellung_Case_Study_WEE.pdf)

## Tech Stack

| Layer      | Technology                              |
|------------|-----------------------------------------|
| Frontend   | HTML5, CSS3, Tailwind CSS 4.2, HTMX 2.0 |
| Backend    | PHP 8.5 (FPM)                           |
| Database   | SQLite 3 (PDO)                          |
| Web Server | Nginx (Alpine)                          |
| Container  | Docker / Docker Compose                 |

## Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (includes Docker Compose)
- [Node.js](https://nodejs.org/) >= 18 with [pnpm](https://pnpm.io/) (for Tailwind CSS build, development only)

## Installation & Setup

### 1. Clone the repository

```bash
git clone <repo-url>
cd vagabond
```

### 2. Start the application

```bash
docker compose up -d --build
```

This starts two containers:
- **app** — PHP 8.5-FPM with Composer dependencies and SQLite
- **nginx** — Serves the app on port `8080`

### 3. Initialize the database

On first run, create and seed the database:

```bash
# Open a shell in the PHP container
docker compose exec app bash

# Inside the container:
sqlite3 /var/www/html/database/flight_booking_system.sqlite < /var/www/html/db/schema.sql
sqlite3 /var/www/html/database/flight_booking_system.sqlite < /var/www/html/db/seed.sql
exit
```

### 4. Open the application

Visit [http://localhost:8080](http://localhost:8080)

### 5. Build CSS (development only)

If you modify Tailwind styles, rebuild the CSS:

```bash
pnpm install
pnpm dev    # watches for changes
```

Or one-off build:

```bash
pnpm dlx @tailwindcss/cli -i ./resources/css/style.css -o ./public/css/output.css
```

## Test Login

| Field    | Value                      |
|----------|----------------------------|
| Email    | `felix.huber@example.ch`   |
| Password | `password`                 |

You can also register a new account via the registration page.

## Project Structure

```
vagabond/
├── public/                     # Web root (Nginx document root)
│   └── index.php               # Entry point — composition root & routing
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
│   └── default.conf            # Nginx config with PHP routing & static caching
├── docs/                       # Documentation & assignment
├── Dockerfile                  # PHP-FPM image with SQLite & Composer
└── compose.yaml                # Docker Compose (app + nginx)
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

- [Aufgabenstellung](./docs/Aufgabenstellung_Case_Study_WEE.pdf) — Assignment description
- [Testprotokoll](./docs/testprotokoll.md) — Test protocols for Epics 1-3
- [Reflexion](./docs/reflexion.md) — Project reflection
