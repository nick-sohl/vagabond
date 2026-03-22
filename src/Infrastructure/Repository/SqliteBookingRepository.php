<?php

namespace FlightBookingSystem\Infrastructure\Repository;

use FlightBookingSystem\Application\Port\BookingRepository;
use FlightBookingSystem\Infrastructure\Database\Database;

class SqliteBookingRepository implements BookingRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findByUserId(int $userId): array
    {
        $sql = "
            SELECT
                b.id AS booking_id, b.booking_date, b.num_tickets, b.total_price, b.status,
                f.id AS flight_id, f.flight_number, f.departure_time, f.arrival_time, f.price,
                dep.iata_code AS dep_iata, dep.city AS dep_city,
                arr.iata_code AS arr_iata, arr.city AS arr_city,
                al.name AS airline_name, al.iata_code AS airline_iata,
                ap.model AS airplane_model, ap.manufacturer AS airplane_manufacturer
            FROM bookings b
            JOIN flights f ON b.flight_id = f.id
            JOIN airports dep ON f.departure_airport_id = dep.id
            JOIN airports arr ON f.arrival_airport_id = arr.id
            JOIN airlines al ON f.airline_id = al.id
            JOIN airplanes ap ON f.airplane_id = ap.id
            WHERE b.user_id = :user_id
            ORDER BY b.booking_date DESC
        ";

        $statement = $this->pdo->prepare($sql);
        $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function create(int $userId, int $flightId, int $numTickets, float $totalPrice): int
    {
        $this->pdo->beginTransaction();

        try {
            $statement = $this->pdo->prepare(
                'INSERT INTO bookings (user_id, flight_id, num_tickets, total_price) VALUES (:user_id, :flight_id, :num_tickets, :total_price)'
            );
            $statement->bindParam(':user_id', $userId, \PDO::PARAM_INT);
            $statement->bindParam(':flight_id', $flightId, \PDO::PARAM_INT);
            $statement->bindParam(':num_tickets', $numTickets, \PDO::PARAM_INT);
            $statement->bindParam(':total_price', $totalPrice);
            $statement->execute();

            $bookingId = (int) $this->pdo->lastInsertId();

            $statement = $this->pdo->prepare(
                'UPDATE flights SET available_seats = available_seats - :num_tickets WHERE id = :flight_id'
            );
            $statement->bindParam(':num_tickets', $numTickets, \PDO::PARAM_INT);
            $statement->bindParam(':flight_id', $flightId, \PDO::PARAM_INT);
            $statement->execute();

            $this->pdo->commit();

            return $bookingId;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
