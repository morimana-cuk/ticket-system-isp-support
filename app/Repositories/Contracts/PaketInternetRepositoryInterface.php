<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;
use App\Models\paket_internet;

interface PaketInternetRepositoryInterface
{
    /** @return Collection<int, paket_internet> */
    public function all(): \Illuminate\Support\Collection;

    public function findByKode(string $kodePaket): ?paket_internet;
}

