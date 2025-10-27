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
        Schema::create('ticket_problem', function (Blueprint $table) {
            // $table->id();
            $table->string('ticket_number')->primary();
            $table->string('pelanggan_id')->nullable();
            $table->string('judul_problem');
            $table->text('deskripsi_problem');
            $table->integer('status')->default(1)->comment('1: Open, 2: In Progress, 3: Closed');
            $table->integer('prioritas')->default(1)->comment('1: Low, 2: Medium, 3: High');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_problem');
    }
};
