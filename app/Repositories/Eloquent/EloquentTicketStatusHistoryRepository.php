<?php

namespace App\Repositories\Eloquent;

use App\Models\TicketStatusHistory;
use App\Repositories\Contracts\TicketStatusHistoryRepositoryInterface;

class EloquentTicketStatusHistoryRepository implements TicketStatusHistoryRepositoryInterface
{
    public function create(array $attributes): TicketStatusHistory
    {
        return TicketStatusHistory::create($attributes);
    }
}

