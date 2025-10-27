<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pelanggan extends Model
{
    //
    protected $table = 'pelanggan';
    protected $primaryKey = 'kode_pelanggan';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];

    public function paketInternet()
    {
        return $this->belongsTo(paket_internet::class, 'paket_id_internet');
    }
}
