<?php

namespace App\Repositories\Eloquent;

use App\Models\pegawai;
use App\Repositories\Contracts\PegawaiRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentPegawaiRepository implements PegawaiRepositoryInterface
{
    public function getByLevel(string $level): Collection
    {
        return pegawai::where('level', $level)->get();
    }
}

