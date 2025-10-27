<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ticket_problem extends Model
{
    protected $table = 'ticket_problem';
    protected $primaryKey = 'ticket_number';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    // protected $fillable = [
    //     'ticket_number',
    //     'pelanggan_id',
    //     'judul_problem',
    //     'deskripsi_problem',
    //     'status',
    //     'prioritas',
    //     'created_by',
    //     'updated_by'
    // ];

    public function pelanggan()
    {
        return $this->belongsTo(pelanggan::class, 'pelanggan_id');
    }

    public function statusHistories()
    {
        return $this->hasMany(TicketStatusHistory::class, 'ticket_number', 'ticket_number')
            ->orderBy('created_at');
    }
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'kode_pegawai');
    }
}
