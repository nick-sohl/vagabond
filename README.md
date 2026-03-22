# Vagabond - Flight Booking System

## Description

This project is part of my studies at TEKO Zurich in the field of Computer Science with specialization in Software Engineering in the class of Web Engineering.
The goal is a classic client-server 3-tier layered-architecture consisting of a frontend, backend and a relational database.

[Link to the task description](./docs/Aufgabenstellung_Case_Study_WEE.pdf)

## Tech Stack

| Layer      | Technology                              |
|------------|-----------------------------------------|
| Frontend   | HTML5, CSS3, Tailwind CSS 4.2, HTMX 2.0 |
| Backend    | PHP 8.5 (CLI)                           |
| Database   | SQLite 3 (PDO)                          |
| Container  | Docker / Docker Compose                 |
| Hosting    | Railway                                 |

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

This builds the image (installs dependencies, builds CSS, initializes the database) and starts the app on port `8080`.

### 3. Open the application

Visit [http://localhost:8080](http://localhost:8080)

### 4. Build CSS (development only)

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
├── public/                     # Web root (document root)
│   ├── index.php               # Entry point — composition root & routing
│   └── router.php              # Router for PHP built-in server
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
│   └── default.conf            # Nginx config (for local dev with Nginx if needed)
├── docs/                       # Documentation & assignment
├── Dockerfile                  # PHP-CLI image with SQLite, Composer & Tailwind build
└── compose.yaml                # Docker Compose (single container)
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
