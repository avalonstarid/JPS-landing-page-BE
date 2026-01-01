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
		Schema::create('job_postings', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->text('address')->nullable();
			$table->timestamp('closed_at')->nullable();
			$table->json('desc');
			$table->json('desc_short');
			$table->string('location', 100)->index();
			$table->timestamp('published_at')->nullable();
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
		Schema::dropIfExists('job_postings');
	}
};
