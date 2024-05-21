<?php

namespace Abianbiya\Laralag\Commands;

use Abianbiya\Laralag\Seeders\LaralagSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunLaralagSeeder extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'lag:seed';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Seed laralag';

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$this->info('Running Laralag package seeder...');

		$seeder = new LaralagSeeder();
		$seeder->run();

		$this->info('Laralag package seeder completed.');
	}
}
