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
        Schema::table('ticket_problem', function (Blueprint $table) {
            //
            $table->foreign('pelanggan_id')->references('kode_pelanggan')->on('pelanggan');
            $table->foreign('created_by')->references('id')->on('account');
            $table->foreign('updated_by')->references('id')->on('account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_problem', function (Blueprint $table) {
            //
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });
    }
};
