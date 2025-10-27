<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pegawai extends Model
{
    //
    protected $table = 'pegawai';
    protected $primaryKey = 'kode_pegawai';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];


    // public function account(){

    //     return $this->belongsTo(Account::class);

    // }
}
