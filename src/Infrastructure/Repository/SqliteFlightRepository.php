<?php

namespace FlightBookingSystem\Infrastructure\Repository;

use FlightBookingSystem\Application\Port\FlightRepository;
use FlightBookingSystem\Infrastructure\Database\Database;

// Adapter
class SqliteFlightRepository implements FlightRepository
{
    private \PDO $pdo;

    private string $baseQuery = "
        SELECT
            f.id, f.flight_number, f.departure_time, f.arrival_time,
            f.price, f.available_seats, f.total_seats,
            dep.iata_code AS dep_iata, dep.city AS dep_city, dep.country AS dep_country,
            arr.iata_code AS arr_iata, arr.city AS arr_city, arr.country AS arr_country,
            al.name AS airline_name, al.iata_code AS airline_iata,
            ap.model AS airplane_model, ap.manufacturer AS airplane_manufacturer
        FROM flights f
        JOIN airports dep ON f.departure_airport_id = dep.id
        JOIN airports arr ON f.arrival_airport_id = arr.id
        JOIN airlines al  ON f.airline_id = al.id
        JOIN airplanes ap ON f.airplane_id = ap.id
    ";

    public function __construct()
    {
        // Get DB connection
        $this->pdo = Database::getInstance();
    }

    public function findAll(int $limit = 25, int $offset = 0): array
    {
        $sql = $this->baseQuery . " ORDER BY f.departure_time ASC LIMIT :limit OFFSET :offset";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countAll(): int
    {
        $statement = $this->pdo->query("SELECT COUNT(*) FROM flights");
        return (int) $statement->fetchColumn();
    }

    public function findById(int $id): ?array
    {
        $sql = $this->baseQuery . " WHERE f.id = :id";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':id', $id, \PDO::PARAM_INT);
        $statement->execute();

        $flight = $statement->fetch();

        return $flight ?: null;
    }

    public function findByArguments(string $departure, string $arrival): array
    {
        $sql = $this->baseQuery . " WHERE dep.iata_code = :departure AND arr.iata_code = :arrival";
        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':departure', $departure);
        $statement->bindParam(':arrival', $arrival);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function findByFilters(array $filters, int $limit = 25, int $offset = 0): array
    {
        [$whereClause, $params] = $this->buildFilterConditions($filters);

        $sql = $this->baseQuery . $whereClause . " ORDER BY f.departure_time ASC LIMIT :limit OFFSET :offset";
        $statement = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countByFilters(array $filters): int
    {
        [$whereClause, $params] = $this->buildFilterConditions($filters);

        $sql = "SELECT COUNT(*) FROM flights f
            JOIN airports dep ON f.departure_airport_id = dep.id
            JOIN airports arr ON f.arrival_airport_id = arr.id
            JOIN airlines al  ON f.airline_id = al.id
            JOIN airplanes ap ON f.airplane_id = ap.id"
            . $whereClause;

        $statement = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    private function buildFilterConditions(array $filters): array
    {
        $conditions = [];
        $params = [];

        if (!empty($filters['departure'])) {
            $iata = $this->extractIata($filters['departure']);
            $conditions[] = 'dep.iata_code = :departure';
            $params[':departure'] = $iata;
        }

        if (!empty($filters['arrival'])) {
            $iata = $this->extractIata($filters['arrival']);
            $conditions[] = 'arr.iata_code = :arrival';
            $params[':arrival'] = $iata;
        }

        if (!empty($filters['depart_date'])) {
            $conditions[] = 'DATE(f.departure_time) = :depart_date';
            $params[':depart_date'] = $filters['depart_date'];
        }

        if (!empty($filters['depart_time'])) {
            $conditions[] = 'TIME(f.departure_time) >= :depart_time';
            $params[':depart_time'] = $filters['depart_time'];
        }

        if (!empty($filters['airline'])) {
            $conditions[] = 'al.iata_code = :airline';
            $params[':airline'] = $filters['airline'];
        }

        if (!empty($filters['availability'])) {
            $conditions[] = 'f.available_seats > 0';
        }

        if (!empty($filters['max_price'])) {
            $conditions[] = 'f.price <= :max_price';
            $params[':max_price'] = (float) $filters['max_price'];
        }

        if (!empty($filters['country'])) {
            $conditions[] = 'arr.country = :country';
            $params[':country'] = $filters['country'];
        }

        $whereClause = $conditions ? ' WHERE ' . implode(' AND ', $conditions) : '';

        return [$whereClause, $params];
    }

    /**
     * Extract IATA code from autocomplete format "ZRH — Zurich" or plain "ZRH"
     */
    private function extractIata(string $input): string
    {
        $input = trim($input);
        if (str_contains($input, '—')) {
            return trim(explode('—', $input)[0]);
        }
        return strtoupper(substr($input, 0, 3));
    }
}
