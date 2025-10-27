<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaketInternetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         DB::table('paket_internet')->insert([
            [
                'kode_paket' => 'PKT001',
                'nama_paket' => 'Basic 10 Mbps',
                'kecepatan' => '10 Mbps',
                'harga' => 150000,

            ],
            [
                'kode_paket' => 'PKT002',
                'nama_paket' => 'Standard 20 Mbps',
                'kecepatan' => '20 Mbps',
                'harga' => 250000,

            ],
            [
                'kode_paket' => 'PKT003',
                'nama_paket' => 'Premium 50 Mbps',
                'kecepatan' => '50 Mbps',
                'harga' => 400000,

            ],
            [
                'kode_paket' => 'PKT004',
                'nama_paket' => 'Ultimate 100 Mbps',
                'kecepatan' => '100 Mbps',
                'harga' => 700000,
            ],
        ]);
    }
}
