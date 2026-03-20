<?php

declare(strict_types=1);

namespace FlightBookingSystem\Infrastructure\Database;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * Paths to database file and SQL scripts.
     */
    private const DB_PATH = __DIR__ . "/../../../db/flight_booking_system.sqlite";
    private const SCHEMA_PATH = __DIR__ . "/../../../db/schema.sql";
    private const SEED_PATH = __DIR__ . "/../../../db/seed.sql";

    /**
     * Get the singleton PDO instance.
     * If the database doesn't exist yet, it auto-initializes.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $needsInit = !file_exists(self::DB_PATH);
            self::$instance = self::createConnection();
            if ($needsInit) {
                self::initializeDatabase(self::$instance);
            }
        }
        return self::$instance;
    }

    private static function initializeDatabase(PDO $pdo): void
    {
        // Run schema
        if (file_exists(self::SCHEMA_PATH)) {
            $schema = file_get_contents(self::SCHEMA_PATH);
            $pdo->exec($schema);
        }
 
        // Run seed data
        if (file_exists(self::SEED_PATH)) {
            $seed = file_get_contents(self::SEED_PATH);
            $pdo->exec($seed);
        }
    }

    /**
     * Create a new PDO connection with all SQLite best practices.
     */
    private static function createConnection(): PDO
    {
        $dbPath = self::DB_PATH;

        // Ensure the directory exists
        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        try {
            $pdo = new PDO("sqlite:{$dbPath}");
 
            // --- CRITICAL: Error mode ---
            // Without this, PDO silently swallows errors!
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
            // Return associative arrays by default (not numeric + assoc)
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
 
            // --- SQLite PRAGMAs (must run on every connection) ---
 
            // 1. WAL mode: concurrent reads, better write performance
            $pdo->exec('PRAGMA journal_mode = WAL');
 
            // 2. Foreign keys: enforce relationships (OFF by default in SQLite!)
            $pdo->exec('PRAGMA foreign_keys = ON');
 
            // 3. Busy timeout: wait 5 seconds for locks instead of failing
            $pdo->exec('PRAGMA busy_timeout = 5000');
 
            // 4. Synchronous NORMAL: good speed/durability trade-off with WAL
            $pdo->exec('PRAGMA synchronous = NORMAL');
 
            // 5. Cache size: 64MB (negative = kilobytes)
            $pdo->exec('PRAGMA cache_size = -64000');
 
            // 6. Temp store in memory: faster temp tables
            $pdo->exec('PRAGMA temp_store = MEMORY');
 
            return $pdo;
 
        } catch (PDOException $e) {
            throw new PDOException(
                "Database connection failed: " . $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }
    }

    /**
     * Close connection (useful for testing).
     */
    public static function close(): void
    {
        self::$instance = null;
    }

    // Prevent instantiation and cloning
    private function __construct() {}
    private function __clone() {}
}
