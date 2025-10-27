<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PegawaiSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('pegawai')->insert([
            [
                'kode_pegawai'   => 'PGW004',
                'nama_pegawai'   => 'Lina Marlina',
                'alamat'         => 'Jl. Anggrek No. 45, Banyuwangi',
                'no_telp'        => '084567890123',
                'level'          => 'direktur',
            ],
            [
                'kode_pegawai'   => 'PGW005',
                'nama_pegawai'   => 'Rudi Susanto',
                'alamat'         => 'Jl. Cempaka No. 67, Kediri',
                'no_telp'        => '085678901234',
                'level'          => 'manager',
            ],
            [
                'kode_pegawai'   => 'PGW006',
                'nama_pegawai'   => 'Maya Sari',
                'alamat'         => 'Jl. Dahlia No. 89, Blitar',
                'no_telp'        => '086789012345',
                'level'          => 'supervisor',
            ],
            [
                'kode_pegawai'   => 'PGW007',
                'nama_pegawai'   => 'Ade Gunawan',
                'alamat'         => 'Jl. Elang No. 101, Tulungagung',
                'no_telp'        => '087890123456',
                'level'          => 'supervisor',
            ],
            [
                'kode_pegawai'   => 'PGW008',
                'nama_pegawai'   => 'Siti Nurhaliza',
                'alamat'         => 'Jl. Flamboyan No. 123, Jombang',
                'no_telp'        => '088901234567',
                'level'          => 'staff',
            ],
            [
                'kode_pegawai'   => 'PGW009',
                'nama_pegawai'   => 'Bambang Hermanto',
                'alamat'         => 'Jl. Gajah No. 145, Nganjuk',
                'no_telp'        => '089012345678',
                'level'          => 'teknisi',
            ],
            [
                'kode_pegawai'   => 'PGW010',
                'nama_pegawai'   => 'Eka Putri Wijaya',
                'alamat'         => 'Jl. Harum No. 167, Madiun',
                'no_telp'        => '081123456789',
                'level'          => 'teknisi',
            ],
            [
                'kode_pegawai'   => 'PGW011',
                'nama_pegawai'   => 'Ferdy Hermawan',
                'alamat'         => 'Jl. Iris No. 189, Ngawi',
                'no_telp'        => '082234567890',
                'level'          => 'staff',
            ],
            [
                'kode_pegawai'   => 'PGW012',
                'nama_pegawai'   => 'Gina Kusuma',
                'alamat'         => 'Jl. Jasmine No. 201, Bojonegoro',
                'no_telp'        => '083345678901',
                'level'          => 'teknisi',
            ],
            [
                'kode_pegawai'   => 'PGW013',
                'nama_pegawai'   => 'Hendrik Wijaya',
                'alamat'         => 'Jl. Kamboja No. 223, Tuban',
                'no_telp'        => '084456789012',
                'level'          => 'staff',
            ],
            [
                'kode_pegawai'   => 'PGW014',
                'nama_pegawai'   => 'Intan Permata',
                'alamat'         => 'Jl. Lili No. 245, Lamongan',
                'no_telp'        => '085567890123',
                'level'          => 'magang',
            ],
            [
                'kode_pegawai'   => 'PGW015',
                'nama_pegawai'   => 'Jaka Surya',
                'alamat'         => 'Jl. Mawar No. 267, Gresik',
                'no_telp'        => '086678901234',
                'level'          => 'magang',
            ],
            [
                'kode_pegawai'   => 'PGW016',
                'nama_pegawai'   => 'Kiki Amelia',
                'alamat'         => 'Jl. Nusa No. 289, Sidoarjo',
                'no_telp'        => '087789012345',
                'level'          => 'staff',
            ],
            [
                'kode_pegawai'   => 'PGW017',
                'nama_pegawai'   => 'Lena Sutrisno',
                'alamat'         => 'Jl. Orchid No. 301, Mojokerto',
                'no_telp'        => '088890123456',
                'level'          => 'teknisi',
            ],
            [
                'kode_pegawai'   => 'PGW018',
                'nama_pegawai'   => 'Mara Dinata',
                'alamat'         => 'Jl. Peony No. 323, Pasuruan',
                'no_telp'        => '089901234567',
                'level'          => 'magang',
            ],
        ]);
    }
}
