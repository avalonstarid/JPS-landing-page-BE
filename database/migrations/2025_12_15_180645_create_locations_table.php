<?php

use App\Models\Settings\BusinessLine;
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
		Schema::create('locations', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->boolean('active')->default(true);
			$table->text('address');
			$table->json('desc')->nullable();
			$table->decimal('lat', 10, 8)->nullable();
			$table->decimal('lng', 11, 8)->nullable();
			$table->string('phone', 20);
			$table->timestamps();

			$table->foreignIdFor(BusinessLine::class, 'business_line_id')->constrained()->cascadeOnDelete();
			$table->foreignIdFor(User::class, 'created_by_id')->nullable()->constrained()->nullOnDelete();
			$table->foreignIdFor(User::class, 'updated_by_id')->nullable()->constrained()->nullOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('locations');
	}
};
