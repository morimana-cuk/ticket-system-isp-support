<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use App\Models\pelanggan;

interface PelangganRepositoryInterface
{
    public function queryForDataTable(): Builder;

    public function findByKode(string $kodePelanggan): ?pelanggan;

    public function createWithAutoKode(array $attributes): pelanggan;

    public function updateByKode(string $kodePelanggan, array $attributes): bool;

    public function deleteByKode(string $kodePelanggan): bool;
}

