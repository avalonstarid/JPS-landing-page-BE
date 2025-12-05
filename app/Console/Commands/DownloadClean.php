<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DownloadClean extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'download:clean';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clean Temporary Download File From Export';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$storage = Storage::disk('download');
		foreach ($storage->files() as $file) {
			if ($storage->lastModified($file) < now()->subMinutes(35)->getTimestamp()) {
				$storage->delete($file);
			}
		}
	}
}
