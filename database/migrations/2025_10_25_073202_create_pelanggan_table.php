<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            // $table->id();
            $table->string('kode_pelanggan')->primary();
            $table->string('nama_pelanggan');
            $table->string('alamat');
            $table->string('no_telp');
            $table->string('email');
            $table->string('paket_id_internet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
