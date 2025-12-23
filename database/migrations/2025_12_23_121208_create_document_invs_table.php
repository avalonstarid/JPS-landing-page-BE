<?php

use App\Models\Master\Category;
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
		Schema::create('document_invs', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->string('slug')->unique();
			$table->json('title');
			$table->timestamps();

			$table->foreignIdFor(Category::class, 'category_id')->constrained()->restrictOnDelete();
			$table->foreignIdFor(User::class, 'created_by_id')->nullable()->constrained()->nullOnDelete();
			$table->foreignIdFor(User::class, 'updated_by_id')->nullable()->constrained()->nullOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('document_invs');
	}
};
