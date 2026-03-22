<?php

namespace FlightBookingSystem\Application\Port;

interface FlightRepository
{
    /**
      * @return array
      */
    public function findAll(int $limit = 25, int $offset = 0): array;

    public function countAll(): int;

    /**
      * @return array|null
      */
    public function findById(int $id): ?array;

    /**
     * @return array
     */
    public function findByArguments(string $departure, string $arrival): array;

    /**
     * @param array<string, mixed> $filters
     * @return array
     */
    public function findByFilters(array $filters, int $limit = 25, int $offset = 0): array;

    public function countByFilters(array $filters): int;
}
