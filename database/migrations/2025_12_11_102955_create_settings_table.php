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
		Schema::create('settings', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->string('group', 100)->index();
			$table->string('key', 100)->index();
			$table->string('type')->default('string')
				->comment("Helper untuk casting: 'string', 'boolean', 'int', 'json'");
			$table->text('value')->nullable();
			$table->timestamps();

			$table->foreignIdFor(User::class, 'created_by_id')->nullable()->constrained()->nullOnDelete();
			$table->foreignIdFor(User::class, 'updated_by_id')->nullable()->constrained()->nullOnDelete();

			$table->unique(['group', 'key']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('settings');
	}
};
