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
		Schema::create('financial_reports', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->decimal('arus_kas_bersih', 19);
			$table->decimal('ekuitas', 19);
			$table->decimal('laba_bersih', 19);
			$table->decimal('liabilitas', 19);
			$table->json('name');
			$table->decimal('penjualan', 19);
			$table->integer('tahun');
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
		Schema::dropIfExists('financial_reports');
	}
};
