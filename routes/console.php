<?php

use App\Models\TemporaryUpload;
use Illuminate\Support\Facades\Schedule;
use Spatie\OneTimePasswords\Models\OneTimePassword;

Schedule::command('activitylog:clean', ['--force'])->daily();
Schedule::command('auth:clear-resets')->daily();
Schedule::command('download:clean')->daily();
Schedule::command('model:prune', [
	'--model' => [OneTimePassword::class],
])->daily();
Schedule::command('sanctum:prune-expired --hours=24')->daily();
Schedule::command('sitemap:generate')->daily();
Schedule::command('telescope:prune --hours=72')->daily();
Schedule::call(function () {
	$staleUploads = TemporaryUpload::where('created_at', '<', now()->subDay())->get();

	foreach ($staleUploads as $temp) {
		$temp->clearMediaCollection();
		$temp->delete();
	}
})->daily();

// Daily At
Schedule::command('backup:clean')->daily()->at('01:00');
Schedule::command('backup:run')->daily()->at('01:15');
