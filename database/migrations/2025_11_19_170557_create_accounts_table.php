<?php

use App\Models\Master\Bank;
use App\Models\Master\Enums;
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
		Schema::create('accounts', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->text('desc')->nullable();
			$table->string('name', 100);
			$table->decimal('saldo', 17)->default(0);
			$table->decimal('saldo_awal', 17)->default(0);
			$table->decimal('saldo_batas', 17)->default(0);
			$table->decimal('saldo_mengendap', 17)->default(0);
			$table->date('tgl_transaksi')->nullable();
			$table->timestamps();

			$table->foreignIdFor(Bank::class, 'bank_id')->nullable()->constrained()->nullOnDelete();
			$table->foreignIdFor(User::class, 'created_by_id')->nullable()->constrained()->cascadeOnDelete();
			$table->foreignIdFor(Enums::class, 'type_id')->comment('Enum Type: ACTYPE')->constrained()
				->references('code')->restrictOnDelete();
			$table->foreignIdFor(User::class, 'updated_by_id')->nullable()->constrained()->nullOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('accounts');
	}
};
