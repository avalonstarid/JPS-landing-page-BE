<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menus', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->boolean('active')->default(true);
			$table->string('icon', 50)->nullable();
			$table->smallInteger('order');
			$table->string('title', 50)->nullable();
			$table->string('to', 50)->nullable();
			$table->timestamps();

			$table->foreignUuid('parent_id')->nullable()->constrained('menus')->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('menus');
	}
};
