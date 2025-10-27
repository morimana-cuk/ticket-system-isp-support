<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('account')->insert([
            [
                'email'       => 'admin@example.com',
                'password'    => Hash::make('password123'), // bcrypt, cost 10 by default
                'role'        => 'Admin',
                'pegawai_id'  => 'PGW001',
            ],
            [
                'email'       => 'noc@example.com',
                'password'    => Hash::make('password123'),
                'role'        => 'NOC',
                'pegawai_id'  => 'PGW002',
            ],
            [
                'email'       => 'cs@example.com',
                'password'    => Hash::make('password123'),
                'role'        => 'CS',
                'pegawai_id'  => 'PGW003',
            ],
        ]);
    }
}
