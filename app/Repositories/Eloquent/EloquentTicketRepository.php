<?php

namespace App\Repositories\Eloquent;

use App\Models\ticket_problem;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class EloquentTicketRepository implements TicketRepositoryInterface
{
    public function queryForDataTable(): Builder
    {
        return ticket_problem::with('pelanggan')->orderBy('created_at', 'DESC');
    }

    public function createWithAutoNumber(array $attributes): ticket_problem
    {
        $lastTicket = ticket_problem::orderBy('ticket_number', 'DESC')->first();
        $nextNumber = 1;
        if ($lastTicket) {
            $lastNumber = intval(substr($lastTicket->ticket_number, 3));
            $nextNumber = $lastNumber + 1;
        }
        $ticketNumber = 'TKT' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $data = array_merge([
            'ticket_number' => $ticketNumber,
        ], $attributes);

        return ticket_problem::create($data);
    }

    public function findByTicketNumber(string $ticketNumber): ?ticket_problem
    {
        return ticket_problem::where('ticket_number', $ticketNumber)->first();
    }

    public function findWithRelations(string $ticketNumber, array $relations): ?ticket_problem
    {
        return ticket_problem::with($relations)->where('ticket_number', $ticketNumber)->first();
    }

    public function updateByTicketNumber(string $ticketNumber, array $attributes): bool
    {
        $ticket = $this->findByTicketNumber($ticketNumber);
        if (!$ticket) {
            return false;
        }
        return (bool) $ticket->update($attributes);
    }

    public function deleteByTicketNumber(string $ticketNumber): bool
    {
        $ticket = $this->findByTicketNumber($ticketNumber);
        if (!$ticket) {
            return false;
        }
        return (bool) $ticket->delete();
    }
}

