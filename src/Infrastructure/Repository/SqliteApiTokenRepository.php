<?php

namespace FlightBookingSystem\Infrastructure\Repository;

use FlightBookingSystem\Application\Port\ApiTokenRepository;
use FlightBookingSystem\Infrastructure\Database\Database;

class SqliteApiTokenRepository implements ApiTokenRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();

        // create the api_tokens table if it doesn't exist yet.
        // needed because Database::getInstance() only runs schema.sql when
        // the .sqlite file is brand-new -> existing DBs from WEE won't have it
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS api_tokens (
                token      TEXT    PRIMARY KEY,
                user_id    INTEGER NOT NULL,
                created_at TEXT    NOT NULL DEFAULT current_timestamp,
                FOREIGN KEY (user_id) REFERENCES users(id)
                    ON DELETE CASCADE ON UPDATE CASCADE
            )
        ');
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS idx_api_tokens_user ON api_tokens(user_id)');
    }

    public function issue(int $userId): string
    {
        // 32 random bytes -> 64 hex chars. random_bytes is cryptographically
        // secure so the token can't be guessed
        $token = bin2hex(random_bytes(32));
        $statement = $this->pdo->prepare(
            'INSERT INTO api_tokens (token, user_id) VALUES (:token, :user_id)'
        );
        $statement->bindParam(':token', $token);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();

        return $token;
    }

    public function findUserIdByToken(string $token): ?int
    {
        $statement = $this->pdo->prepare('SELECT user_id FROM api_tokens WHERE token = :token');
        $statement->bindParam(':token', $token);
        $statement->execute();

        $row = $statement->fetch();
        return $row ? (int) $row['user_id'] : null;
    }

    public function revoke(string $token): void
    {
        $statement = $this->pdo->prepare('DELETE FROM api_tokens WHERE token = :token');
        $statement->bindParam(':token', $token);
        $statement->execute();
    }
}
