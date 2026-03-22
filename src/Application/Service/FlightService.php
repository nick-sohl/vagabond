<?php

namespace FlightBookingSystem\Application\Service;

use FlightBookingSystem\Infrastructure\Repository\SqliteFlightRepository;

class FlightService
{
    private SqliteFlightRepository $sqliteFlightRepository;

    public function __construct(SqliteFlightRepository $sqliteFlightRepository)
    {
        $this->sqliteFlightRepository = $sqliteFlightRepository;
    }

    public function findAll(int $limit = 25, int $offset = 0): array
    {
        return $this->sqliteFlightRepository->findAll($limit, $offset);
    }

    public function countAll(): int
    {
        return $this->sqliteFlightRepository->countAll();
    }

    public function findByArguments(string $departure, string $arrival): array
    {
        return $this->sqliteFlightRepository->findByArguments($departure, $arrival);
    }

    public function findById(int $id): ?array
    {
        return $this->sqliteFlightRepository->findById($id);
    }

    public function search(array $filters, int $limit = 25, int $offset = 0): array
    {
        return $this->sqliteFlightRepository->findByFilters($filters, $limit, $offset);
    }

    public function countByFilters(array $filters): int
    {
        return $this->sqliteFlightRepository->countByFilters($filters);
    }
}
