-- ============================================================
-- 1. Airports
-- ============================================================
CREATE TABLE IF NOT EXISTS airports (
    id        INTEGER PRIMARY KEY AUTOINCREMENT,
    iata_code TEXT    NOT NULL UNIQUE,    -- 3-letter code: 'ZRH', 'LHR'
    name      TEXT    NOT NULL,            -- 'Zürich Airport'
    city      TEXT    NOT NULL,            -- 'Zürich'
    country   TEXT    NOT NULL             -- 'Schweiz'
);

-- ============================================================
-- 2. Airlines
-- ============================================================
CREATE TABLE IF NOT EXISTS airlines (
    id        INTEGER PRIMARY KEY AUTOINCREMENT,
    iata_code TEXT    NOT NULL UNIQUE,    -- 2-letter code: 'LX', 'BA'
    name      TEXT    NOT NULL,            -- 'Swiss International Air Lines'
    logo_url  TEXT    DEFAULT NULL         -- nullable, path to logo image
);

-- ============================================================
-- 3. Airplanes
-- ============================================================
CREATE TABLE IF NOT EXISTS airplanes (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    model        TEXT    NOT NULL,          -- 'A320neo', '777-300ER'
    manufacturer TEXT    NOT NULL,          -- 'airbus', 'boeing' (enum value)
    capacity     INTEGER NOT NULL CHECK (capacity > 0),  -- seat count
    range        TEXT    NOT NULL           -- 'short_haul', 'medium_haul', 'long_haul'
);

-- ============================================================
-- 4. Users
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
    id     INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name   TEXT NOT NULL,
    last_name    TEXT NOT NULL,
    email       TEXT NOT NULL UNIQUE CHECK (email LIKE '%_@_%.__%'),
    password    TEXT NOT NULL,
    created_at  TEXT NOT NULL DEFAULT current_timestamp
);

-- ============================================================
-- 5. Flights
-- ============================================================
CREATE TABLE IF NOT EXISTS flights (
    id                    INTEGER PRIMARY KEY AUTOINCREMENT,
    flight_number         TEXT    NOT NULL,     -- 'LX1234'
    airline_id            INTEGER NOT NULL,
    airplane_id           INTEGER NOT NULL,
    departure_airport_id  INTEGER NOT NULL,
    arrival_airport_id    INTEGER NOT NULL,
    departure_time        TEXT    NOT NULL,     -- ISO 8601: '2026-06-15 08:30:00'
    arrival_time          TEXT    NOT NULL,     -- ISO 8601: '2026-06-15 10:45:00'
    price                 REAL    NOT NULL CHECK (price >= 0),
    total_seats           INTEGER NOT NULL CHECK (total_seats > 0),
    available_seats       INTEGER NOT NULL CHECK (available_seats >= 0),
 
    -- Cross-column CHECK: available can't exceed total
    CHECK (available_seats <= total_seats),
 
    -- Cross-column CHECK: arrival must be after departure
    CHECK (arrival_time > departure_time),
 
    -- Foreign keys with referential integrity rules
    FOREIGN KEY (airline_id)           REFERENCES airlines(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
 
    FOREIGN KEY (airplane_id)          REFERENCES airplanes(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
 
    FOREIGN KEY (departure_airport_id) REFERENCES airports(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
 
    FOREIGN KEY (arrival_airport_id)   REFERENCES airports(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- INDEXES for the filter criteria from Epic 1:
-- Felix filters by: departure, arrival, date, airline, price, availability
 
CREATE INDEX IF NOT EXISTS idx_flights_departure
    ON flights(departure_airport_id);
 
CREATE INDEX IF NOT EXISTS idx_flights_arrival
    ON flights(arrival_airport_id);
 
CREATE INDEX IF NOT EXISTS idx_flights_airline
    ON flights(airline_id);
 
CREATE INDEX IF NOT EXISTS idx_flights_departure_time
    ON flights(departure_time);
 
CREATE INDEX IF NOT EXISTS idx_flights_price
    ON flights(price);
 
-- Composite index for the most common filter combination:
-- "Flights from X to Y on date Z"
CREATE INDEX IF NOT EXISTS idx_flights_route_date
    ON flights(departure_airport_id, arrival_airport_id, departure_time);

-- ============================================================
-- 6. BOOKINGS (Buchungen)
-- ============================================================
CREATE TABLE IF NOT EXISTS bookings (
    id           INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id      INTEGER NOT NULL,
    flight_id    INTEGER NOT NULL,
    booking_date TEXT    NOT NULL DEFAULT current_timestamp,
    num_tickets  INTEGER NOT NULL CHECK (num_tickets > 0),
    total_price  REAL    NOT NULL CHECK (total_price >= 0),
    status       TEXT    NOT NULL DEFAULT 'confirmed',
 
    FOREIGN KEY (user_id)  REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
 
    FOREIGN KEY (flight_id) REFERENCES flights(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);
 
-- Index for Epic 3: "My bookings" — sorted by date
CREATE INDEX IF NOT EXISTS idx_bookings_user
    ON bookings(user_id, booking_date DESC);
 
-- Index for checking bookings per flight (validation)
CREATE INDEX IF NOT EXISTS idx_bookings_flight
    ON bookings(flight_id);
