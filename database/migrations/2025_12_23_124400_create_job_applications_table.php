<?php

use App\Models\Master\Enums;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('job_applications', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->integer('age');
			$table->string('email', 100);
			$table->string('jurusan', 100);
			$table->string('name', 100);
			$table->string('phone', 20);
			$table->string('school_name', 100);
			$table->text('reason');
			$table->timestamps();

			$table->string('gender_id', 20)->comment('Enum Type: JK');
			$table->foreignIdFor(Enums::class, 'status_kawin_id')->comment('Enum Type: STSKWN')->constrained()
				->restrictOnDelete();

			$table->foreign('gender_id')->references('code')->on('enums')->restrictOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('job_applications');
	}
};
