<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use function Laravel\Prompts\table;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('pegawai')->insert([
            [
                'kode_pegawai'   => 'PGW001',
                'nama_pegawai'   => 'Andi Setiawan',
                'alamat'         => 'Jl. Mawar No. 12, Surabaya',
                'no_telp'        => '081234567890',
                'level'          => 'staff',
            ],
            [
                'kode_pegawai'   => 'PGW002',
                'nama_pegawai'   => 'Siti Rahmawati',
                'alamat'         => 'Jl. Melati No. 21, Malang',
                'no_telp'        => '082345678901',
                'level'          => 'staff',
            ],
            [
                'kode_pegawai'   => 'PGW003',
                'nama_pegawai'   => 'Budi Hartono',
                'alamat'         => 'Jl. Kenanga No. 3, Jember',
                'no_telp'        => '083456789012',
                'level'          => 'staff',
            ],
        ]);
    }
}
