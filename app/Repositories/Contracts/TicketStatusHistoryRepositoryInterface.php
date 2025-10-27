<?php

namespace App\Repositories\Contracts;

use App\Models\TicketStatusHistory;

interface TicketStatusHistoryRepositoryInterface
{
    public function create(array $attributes): TicketStatusHistory;
}

