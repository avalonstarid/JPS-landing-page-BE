<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('historical_timelines', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->json('desc');
			$table->string('icon', 100);
			$table->boolean('icon_custom')->default(false);
			$table->json('title');
			$table->integer('year');
			$table->timestamps();

			$table->foreignIdFor(User::class, 'created_by_id')->nullable()->constrained()->nullOnDelete();
			$table->foreignIdFor(User::class, 'updated_by_id')->nullable()->constrained()->nullOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('historical_timelines');
	}
};
