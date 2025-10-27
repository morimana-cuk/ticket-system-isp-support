<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;
use App\Models\pegawai;

interface PegawaiRepositoryInterface
{
    /** @return Collection<int, pegawai> */
    public function getByLevel(string $level): \Illuminate\Support\Collection;
}

