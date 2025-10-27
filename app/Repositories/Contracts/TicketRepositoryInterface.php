<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use App\Models\ticket_problem;

interface TicketRepositoryInterface
{
    public function queryForDataTable(): Builder;

    public function createWithAutoNumber(array $attributes): ticket_problem;

    public function findByTicketNumber(string $ticketNumber): ?ticket_problem;

    /**
     * Find ticket with given relations eager loaded.
     * @param string $ticketNumber
     * @param array<string> $relations
     */
    public function findWithRelations(string $ticketNumber, array $relations): ?ticket_problem;

    public function updateByTicketNumber(string $ticketNumber, array $attributes): bool;

    public function deleteByTicketNumber(string $ticketNumber): bool;
}

