<?php

namespace App\Repositories\Eloquent;

use App\Models\paket_internet;
use App\Repositories\Contracts\PaketInternetRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentPaketInternetRepository implements PaketInternetRepositoryInterface
{
    public function all(): Collection
    {
        return paket_internet::all();
    }

    public function findByKode(string $kodePaket): ?paket_internet
    {
        return paket_internet::where('kode_paket', $kodePaket)->first();
    }
}

