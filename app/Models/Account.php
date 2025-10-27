<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Account extends Authenticatable implements JWTSubject
{
    //
    use Notifiable;
    protected $table = 'account';
    protected $primaryKey = 'id';
    protected $guarded = [];
    
    // Password hashing
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
   public function pegawai()
   {
       return $this->hasOne(Pegawai::class, 'kode_pegawai', 'pegawai_id');
   }


   public function getJWTIdentifier()
   {
       return $this->getKey();
   }

   public function getJWTCustomClaims()
   {
       return [];
   }
}
