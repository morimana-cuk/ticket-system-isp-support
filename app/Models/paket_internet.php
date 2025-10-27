<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class paket_internet extends Model
{
    //
    protected $table = 'paket_internet';
    protected $primaryKey = 'kode_paket';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function pelanggan()
    {
        return $this->hasMany(pelanggan::class, 'paket_id_internet');
    }   
}
