<?php

namespace Abianbiya\Laralag\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class LaralagInstall extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'lag:install';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Install laralag';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$this->info('Installing Laralag CRUD Generator...');

		// Run migrations
		Artisan::call('migrate');
		$this->info('Ran migrations.');

		// Publish assets
		$this->call('vendor:publish', [
			'--tag' => 'laralag_assets',
			'--force' => true,
		]);
		$this->info('Copied assets.');

		// Publish config
		$this->call('vendor:publish', [
			'--tag' => 'laralag_config',
			'--force' => true,
		]);
		$this->info('Copied config.');

		// Publish Modules
		// $this->call('vendor:publish', [
		// 	'--tag' => 'laralag_modules',
		// 	'--force' => true,
		// ]);
		// $this->info('Copied modules.');

		// // Run seeders
		Artisan::call('lag:seed');
		$this->info('Ran seeders.');

		$this->info('Laralag CRUD Generator installation complete.');
	}
}
