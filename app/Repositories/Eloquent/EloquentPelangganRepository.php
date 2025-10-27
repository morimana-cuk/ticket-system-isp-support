<?php

namespace App\Repositories\Eloquent;

use App\Models\pelanggan;
use App\Repositories\Contracts\PelangganRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class EloquentPelangganRepository implements PelangganRepositoryInterface
{
    public function queryForDataTable(): Builder
    {
        return pelanggan::with('paketInternet')->orderBy('created_at', 'DESC');
    }

    public function findByKode(string $kodePelanggan): ?pelanggan
    {
        return pelanggan::where('kode_pelanggan', $kodePelanggan)->first();
    }

    public function createWithAutoKode(array $attributes): pelanggan
    {
        $lastPelanggan = pelanggan::orderBy('kode_pelanggan', 'DESC')->first();
        $nextNumber = 1;
        if ($lastPelanggan) {
            $lastNumber = intval(substr($lastPelanggan->kode_pelanggan, 3));
            $nextNumber = $lastNumber + 1;
        }
        $kodePelanggan = 'PLG' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $data = array_merge($attributes, [
            'kode_pelanggan' => $kodePelanggan,
        ]);

        return pelanggan::create($data);
    }

    public function updateByKode(string $kodePelanggan, array $attributes): bool
    {
        $pelanggan = $this->findByKode($kodePelanggan);
        if (!$pelanggan) {
            return false;
        }
        return (bool) $pelanggan->update($attributes);
    }

    public function deleteByKode(string $kodePelanggan): bool
    {
        $pelanggan = $this->findByKode($kodePelanggan);
        if (!$pelanggan) {
            return false;
        }
        return (bool) $pelanggan->delete();
    }
}

