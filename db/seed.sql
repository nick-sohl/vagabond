-- ============================================================
-- Seed Data — Testdaten für das Flugbuchungssystem
-- ============================================================
-- Run AFTER schema.sql
-- Contains worldwide airports, major airlines, and a rich
-- variety of flights for thorough filter testing.
--
-- ID REFERENCE (AUTOINCREMENT starts at 1 in insertion order):
--   Airports:  1-40
--   Airlines:  1-20
--   Airplanes: 1-12
--   Users:     1
--   Flights:   1-50
--   Bookings:  1-5
-- ============================================================


-- ============================================================
-- AIRPORTS (40 airports worldwide)
-- ============================================================
-- IDs 1-5: Switzerland
-- IDs 6-13: Western Europe
-- IDs 14-17: Southern Europe
-- IDs 18-20: Northern & Eastern Europe
-- IDs 21-26: North America
-- IDs 27-28: South America
-- IDs 29-31: Middle East
-- IDs 32-36: Asia
-- IDs 37-38: Oceania
-- IDs 39-40: Africa
-- ============================================================

INSERT INTO airports (iata_code, name, city, country) VALUES
    -- Switzerland (IDs 1-5)
    ('ZRH', 'Flughafen Zürich',           'Zürich',       'Schweiz'),
    ('GVA', 'Aéroport de Genève',         'Genf',         'Schweiz'),
    ('BSL', 'EuroAirport Basel-Mulhouse',  'Basel',        'Schweiz'),
    ('BRN', 'Flughafen Bern-Belp',        'Bern',         'Schweiz'),
    ('LUG', 'Aeroporto di Lugano',        'Lugano',       'Schweiz'),

    -- Western Europe (IDs 6-13)
    ('LHR', 'Heathrow Airport',            'London',       'Vereinigtes Königreich'),
    ('LGW', 'Gatwick Airport',             'London',       'Vereinigtes Königreich'),
    ('CDG', 'Charles de Gaulle',           'Paris',        'Frankreich'),
    ('FRA', 'Frankfurt Airport',           'Frankfurt',    'Deutschland'),
    ('MUC', 'Flughafen München',           'München',      'Deutschland'),
    ('AMS', 'Schiphol Airport',            'Amsterdam',    'Niederlande'),
    ('BRU', 'Brussels Airport',            'Brüssel',      'Belgien'),
    ('DUB', 'Dublin Airport',              'Dublin',       'Irland'),

    -- Southern Europe (IDs 14-17)
    ('BCN', 'El Prat Airport',             'Barcelona',    'Spanien'),
    ('MAD', 'Adolfo Suárez Madrid-Barajas','Madrid',       'Spanien'),
    ('FCO', 'Fiumicino Airport',           'Rom',          'Italien'),
    ('MXP', 'Malpensa Airport',            'Mailand',      'Italien'),

    -- Northern & Eastern Europe (IDs 18-20)
    ('VIE', 'Flughafen Wien',              'Wien',         'Österreich'),
    ('CPH', 'Copenhagen Airport',          'Kopenhagen',   'Dänemark'),
    ('IST', 'Istanbul Airport',            'Istanbul',     'Türkei'),

    -- North America (IDs 21-26)
    ('JFK', 'John F. Kennedy Intl.',       'New York',     'USA'),
    ('LAX', 'Los Angeles Intl.',           'Los Angeles',  'USA'),
    ('ORD', 'O''Hare Intl.',              'Chicago',      'USA'),
    ('MIA', 'Miami Intl.',                 'Miami',        'USA'),
    ('YYZ', 'Toronto Pearson Intl.',       'Toronto',      'Kanada'),
    ('MEX', 'Mexico City Intl.',           'Mexiko-Stadt', 'Mexiko'),

    -- South America (IDs 27-28)
    ('GRU', 'Guarulhos Intl.',             'São Paulo',    'Brasilien'),
    ('EZE', 'Ministro Pistarini Intl.',    'Buenos Aires', 'Argentinien'),

    -- Middle East (IDs 29-31)
    ('DXB', 'Dubai Intl.',                 'Dubai',        'Vereinigte Arabische Emirate'),
    ('DOH', 'Hamad Intl.',                 'Doha',         'Katar'),
    ('TLV', 'Ben Gurion Airport',          'Tel Aviv',     'Israel'),

    -- Asia (IDs 32-36)
    ('NRT', 'Narita Intl.',                'Tokio',        'Japan'),
    ('HND', 'Haneda Airport',              'Tokio',        'Japan'),
    ('SIN', 'Changi Airport',              'Singapur',     'Singapur'),
    ('BKK', 'Suvarnabhumi Airport',        'Bangkok',      'Thailand'),
    ('HKG', 'Hong Kong Intl.',             'Hongkong',     'China'),

    -- Oceania (IDs 37-38)
    ('SYD', 'Sydney Kingsford Smith',      'Sydney',       'Australien'),
    ('AKL', 'Auckland Airport',            'Auckland',     'Neuseeland'),

    -- Africa (IDs 39-40)
    ('JNB', 'O.R. Tambo Intl.',            'Johannesburg', 'Südafrika'),
    ('CAI', 'Cairo Intl.',                 'Kairo',        'Ägypten');


-- ============================================================
-- AIRLINES (20 airlines worldwide)
-- ============================================================

INSERT INTO airlines (iata_code, name, logo_url) VALUES
    -- European (IDs 1-10)
    ('LX', 'Swiss International Air Lines', '/images/airlines/swiss.png'),
    ('BA', 'British Airways',               '/images/airlines/ba.png'),
    ('LH', 'Lufthansa',                     '/images/airlines/lufthansa.png'),
    ('AF', 'Air France',                     '/images/airlines/airfrance.png'),
    ('EW', 'Eurowings',                      '/images/airlines/eurowings.png'),
    ('KL', 'KLM Royal Dutch Airlines',      '/images/airlines/klm.png'),
    ('IB', 'Iberia',                         '/images/airlines/iberia.png'),
    ('AZ', 'ITA Airways',                    '/images/airlines/ita.png'),
    ('OS', 'Austrian Airlines',              '/images/airlines/austrian.png'),
    ('TK', 'Turkish Airlines',               '/images/airlines/turkish.png'),

    -- North American (IDs 11-14)
    ('AA', 'American Airlines',              '/images/airlines/american.png'),
    ('UA', 'United Airlines',                '/images/airlines/united.png'),
    ('DL', 'Delta Air Lines',                '/images/airlines/delta.png'),
    ('AC', 'Air Canada',                     '/images/airlines/aircanada.png'),

    -- Middle Eastern (IDs 15-17)
    ('EK', 'Emirates',                       '/images/airlines/emirates.png'),
    ('QR', 'Qatar Airways',                  '/images/airlines/qatar.png'),
    ('EY', 'Etihad Airways',                 '/images/airlines/etihad.png'),

    -- Asian & Oceanian (IDs 18-20)
    ('SQ', 'Singapore Airlines',             '/images/airlines/singapore.png'),
    ('NH', 'All Nippon Airways',             '/images/airlines/ana.png'),
    ('QF', 'Qantas',                         '/images/airlines/qantas.png');


-- ============================================================
-- AIRPLANES (12 aircraft types)
-- ============================================================

INSERT INTO airplanes (model, manufacturer, capacity, range) VALUES
    -- Short-haul (IDs 1-4)
    ('A320neo',     'airbus',      180, 'short_haul'),
    ('737 MAX 8',   'boeing',      189, 'short_haul'),
    ('E190-E2',     'embraer',      97, 'short_haul'),
    ('CRJ-900',     'bombardier',   90, 'short_haul'),

    -- Medium-haul (IDs 5-7)
    ('A321neo',     'airbus',      220, 'medium_haul'),
    ('A321XLR',     'airbus',      244, 'medium_haul'),
    ('737 MAX 10',  'boeing',      230, 'medium_haul'),

    -- Long-haul (IDs 8-12)
    ('A330-300',    'airbus',      300, 'long_haul'),
    ('A350-900',    'airbus',      325, 'long_haul'),
    ('777-300ER',   'boeing',      396, 'long_haul'),
    ('787-9',       'boeing',      296, 'long_haul'),
    ('A380-800',    'airbus',      555, 'long_haul');


-- ============================================================
-- TEST USER
-- ============================================================
-- Password hash below is bcrypt of 'password' (dev only!)
-- Generate a real one in PHP: password_hash('Test1234!', PASSWORD_DEFAULT)

INSERT INTO users (first_name, last_name, email, password) VALUES
    ('Felix', 'Huber', 'felix.huber@example.ch',
     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');


-- ============================================================
-- FLIGHTS (50 flights)
-- ============================================================

INSERT INTO flights (flight_number, airline_id, airplane_id,
    departure_airport_id, arrival_airport_id,
    departure_time, arrival_time,
    price, total_seats, available_seats) VALUES

    -- SWISS (LX=1), hub: ZRH=1
    ('LX318',  1, 1,  1,  6,  '2026-06-15 07:30:00', '2026-06-15 08:45:00',  245.50, 180,  42),  -- 1
    ('LX1072', 1, 5,  1, 14,  '2026-06-15 09:15:00', '2026-06-15 11:30:00',  189.00, 220,  85),  -- 2
    ('LX1734', 1, 1,  1, 16,  '2026-06-15 11:00:00', '2026-06-15 12:50:00',  215.00, 180, 120),  -- 3
    ('LX4601', 1, 8,  1,  8,  '2026-06-16 06:45:00', '2026-06-16 08:15:00',  175.00, 300, 200),  -- 4
    ('LX962',  1, 5,  1, 18,  '2026-06-16 14:20:00', '2026-06-16 15:40:00',  155.00, 220, 180),  -- 5
    ('LX40',   1, 9,  1, 21,  '2026-06-17 10:00:00', '2026-06-17 13:15:00',  890.00, 325,  78),  -- 6
    ('LX8',    1,11,  1, 34,  '2026-06-18 22:30:00', '2026-06-19 16:45:00', 1250.00, 296,  44),  -- 7
    ('LX2200', 1, 1,  1, 11,  '2026-06-18 08:00:00', '2026-06-18 09:40:00',  199.00, 180,   0),  -- 8 sold out

    -- Lufthansa (LH=3), hubs: FRA=9, MUC=10
    ('LH1188', 3, 2,  9,  1,  '2026-06-15 08:00:00', '2026-06-15 09:10:00',  165.00, 189,  55),  -- 9
    ('LH372',  3, 1,  9,  6,  '2026-06-16 10:30:00', '2026-06-16 11:45:00',  210.00, 180,  30),  -- 10
    ('LH400',  3,10,  9, 21,  '2026-06-16 13:00:00', '2026-06-16 16:30:00',  780.00, 396, 120),  -- 11
    ('LH710',  3, 9, 10, 32,  '2026-06-17 14:00:00', '2026-06-18 08:30:00', 1120.00, 325,  95),  -- 12
    ('LH1820', 3, 1, 10, 18,  '2026-06-17 07:15:00', '2026-06-17 08:25:00',  135.00, 180, 145),  -- 13

    -- British Airways (BA=2), hub: LHR=6
    ('BA724',  2,10,  6,  1,  '2026-06-15 12:00:00', '2026-06-15 14:45:00',  320.00, 396, 150),  -- 14
    ('BA456',  2, 1,  6, 14,  '2026-06-17 09:00:00', '2026-06-17 12:15:00',  275.50, 180,  90),  -- 15
    ('BA115',  2,10,  6, 21,  '2026-06-16 09:00:00', '2026-06-16 12:00:00',  850.00, 396, 200),  -- 16
    ('BA15',   2,11,  6, 34,  '2026-06-18 21:00:00', '2026-06-19 15:30:00', 1180.00, 296,  65),  -- 17
    ('BA265',  2, 9,  6, 29,  '2026-06-19 08:00:00', '2026-06-19 18:30:00',  680.00, 325, 110),  -- 18

    -- Air France (AF=4), hub: CDG=8
    ('AF1115', 4, 5,  8,  1,  '2026-06-15 16:00:00', '2026-06-15 17:20:00',  195.00, 220, 110),  -- 19
    ('AF1642', 4, 1,  8, 16,  '2026-06-16 07:00:00', '2026-06-16 09:10:00',  230.00, 180,  65),  -- 20
    ('AF006',  4,10,  8, 21,  '2026-06-17 10:30:00', '2026-06-17 13:15:00',  920.00, 396, 180),  -- 21
    ('AF662',  4, 9,  8, 27,  '2026-06-18 22:00:00', '2026-06-19 06:30:00', 1050.00, 325,  88),  -- 22

    -- Eurowings (EW=5), budget
    ('EW9876', 5, 3,  1, 18,  '2026-06-17 06:00:00', '2026-06-17 07:15:00',   79.00,  97,  12),  -- 23
    ('EW5432', 5, 4,  1,  9,  '2026-06-17 18:30:00', '2026-06-17 19:30:00',   59.90,  90,   0),  -- 24 sold out
    ('EW3210', 5, 1,  9, 14,  '2026-06-18 06:15:00', '2026-06-18 08:45:00',   89.00, 180, 140),  -- 25

    -- KLM (KL=6), hub: AMS=11
    ('KL1952', 6, 1, 11,  1,  '2026-06-16 11:00:00', '2026-06-16 12:25:00',  175.00, 180,  70),  -- 26
    ('KL641',  6,11, 11, 21,  '2026-06-17 09:30:00', '2026-06-17 12:00:00',  810.00, 296, 130),  -- 27
    ('KL835',  6,10, 11, 32,  '2026-06-18 14:00:00', '2026-06-19 08:15:00', 1080.00, 396, 160),  -- 28

    -- Turkish Airlines (TK=10), hub: IST=20
    ('TK1908',10, 1, 20,  1,  '2026-06-16 05:30:00', '2026-06-16 07:50:00',  220.00, 180,  95),  -- 29
    ('TK77',  10,10, 20, 21,  '2026-06-17 01:30:00', '2026-06-17 05:45:00',  640.00, 396, 250),  -- 30
    ('TK54',  10, 9, 20, 34,  '2026-06-18 02:00:00', '2026-06-18 17:30:00',  720.00, 325, 200),  -- 31
    ('TK1723',10, 5, 20, 14,  '2026-06-19 08:00:00', '2026-06-19 11:15:00',  195.00, 220, 175),  -- 32

    -- Emirates (EK=15), hub: DXB=29
    ('EK87',  15,12, 29,  6,  '2026-06-16 02:30:00', '2026-06-16 07:00:00',  720.00, 555, 310),  -- 33
    ('EK201', 15,12, 29, 21,  '2026-06-17 03:00:00', '2026-06-17 08:30:00',  950.00, 555, 220),  -- 34
    ('EK404', 15,10, 29, 34,  '2026-06-18 09:00:00', '2026-06-18 20:15:00',  580.00, 396, 180),  -- 35
    ('EK412', 15,12, 29, 37,  '2026-06-19 03:30:00', '2026-06-19 22:00:00', 1350.00, 555, 280),  -- 36

    -- Qatar Airways (QR=16), hub: DOH=30
    ('QR94',  16, 9, 30,  8,  '2026-06-17 01:00:00', '2026-06-17 06:30:00',  690.00, 325, 145),  -- 37
    ('QR844', 16,11, 30, 34,  '2026-06-18 08:15:00', '2026-06-18 20:00:00',  520.00, 296, 110),  -- 38

    -- Singapore Airlines (SQ=18), hub: SIN=34
    ('SQ345', 18, 9, 34,  1,  '2026-06-17 08:00:00', '2026-06-17 14:30:00', 1100.00, 325,  55),  -- 39
    ('SQ26',  18,12, 34, 21,  '2026-06-18 01:00:00', '2026-06-18 06:00:00', 1400.00, 555, 190),  -- 40
    ('SQ222', 18, 9, 34, 37,  '2026-06-19 09:30:00', '2026-06-19 19:45:00',  680.00, 325, 200),  -- 41

    -- American Airlines (AA=11), hubs: JFK=21, MIA=24
    ('AA100', 11,10, 21,  6,  '2026-06-16 18:00:00', '2026-06-17 06:15:00',  820.00, 396, 175),  -- 42
    ('AA945', 11,11, 24, 27,  '2026-06-17 22:00:00', '2026-06-18 06:30:00',  550.00, 296, 130),  -- 43

    -- United Airlines (UA=12), hub: ORD=23
    ('UA880', 12,11, 23,  9,  '2026-06-17 17:30:00', '2026-06-18 08:00:00',  760.00, 296, 100),  -- 44
    ('UA837', 12,10, 22, 32,  '2026-06-18 11:00:00', '2026-06-19 14:30:00',  990.00, 396, 210),  -- 45

    -- Qantas (QF=20), hub: SYD=37
    ('QF1',   20,10, 37, 34,  '2026-06-18 15:00:00', '2026-06-18 21:30:00',  480.00, 396, 240),  -- 46
    ('QF11',  20,11, 37,  6,  '2026-06-19 17:00:00', '2026-06-20 05:30:00', 1500.00, 296,  60),  -- 47

    -- Austrian Airlines (OS=9), hub: VIE=18
    ('OS561',  9, 1, 18,  1,  '2026-06-16 06:30:00', '2026-06-16 08:10:00',  160.00, 180, 105),  -- 48
    ('OS51',   9,11, 18, 21,  '2026-06-18 10:30:00', '2026-06-18 14:00:00',  830.00, 296,  80),  -- 49

    -- Iberia (IB=7), hub: MAD=15
    ('IB3476', 7, 5, 15,  1,  '2026-06-19 12:00:00', '2026-06-19 14:30:00',  205.00, 220, 155); -- 50


-- ============================================================
-- SAMPLE BOOKINGS (Felix's booking history)
-- ============================================================

INSERT INTO bookings (user_id, flight_id, booking_date, num_tickets, total_price, status) VALUES
    (1,  1, '2026-03-10 14:30:00', 2,  491.00, 'confirmed'),   -- LX318 ZRH->LHR
    (1,  3, '2026-03-12 09:15:00', 1,  215.00, 'confirmed'),   -- LX1734 ZRH->FCO
    (1, 14, '2026-03-14 20:00:00', 1,  320.00, 'cancelled'),   -- BA724 LHR->ZRH
    (1,  6, '2026-03-15 11:00:00', 2, 1780.00, 'confirmed'),   -- LX40 ZRH->JFK
    (1, 23, '2026-03-15 18:45:00', 1,   79.00, 'pending');      -- EW9876 ZRH->VIE
