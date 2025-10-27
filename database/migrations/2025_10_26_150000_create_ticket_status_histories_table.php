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
		Schema::create('ticket_status_histories', function (Blueprint $table) {
			$table->id();
			$table->string('ticket_number');
			$table->unsignedTinyInteger('status_from')->nullable();
			$table->unsignedTinyInteger('status_to');
			$table->unsignedBigInteger('changed_by')->nullable();
			$table->text('notes')->nullable();
			$table->timestamps();

			$table->foreign('ticket_number')
				->references('ticket_number')
				->on('ticket_problem')
				->cascadeOnDelete();

			$table->foreign('changed_by')
				->references('id')
				->on('account')
				->nullOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('ticket_status_histories');
	}
};
