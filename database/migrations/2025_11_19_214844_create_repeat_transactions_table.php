<?php

use App\Models\Account;
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
		Schema::create('repeat_transactions', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->decimal('amount', 17)->default(0);
			$table->integer('current_transaction')->default(0);
			$table->text('desc')->nullable();
			$table->date('expired_date')->nullable();
			$table->dateTime('last_execute')->nullable();
			$table->integer('max_transaction')->nullable();
			$table->enum('method', ['MT', 'ET'])->comment('MT = Max Transaction, ET = Expired Time');
			$table->json('repeat')->comment('Repeat transaction in JSON format');
			$table->enum('type', ['I', 'E', 'T'])->comment('I: Income, E: Expense, T: Transfer');
			$table->timestamps();

			$table->foreignIdFor(Category::class, 'category_id')->nullable()->constrained()->nullOnDelete();
			$table->foreignIdFor(User::class, 'created_by_id')->nullable()->constrained()->cascadeOnDelete();
			$table->foreignIdFor(Account::class, 'from_account_id')->constrained()->cascadeOnDelete();
			$table->foreignIdFor(Account::class, 'to_account_id')->nullable()->constrained()->cascadeOnDelete();
			$table->foreignIdFor(User::class, 'updated_by_id')->nullable()->constrained()->nullOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('repeat_transactions');
	}
};
