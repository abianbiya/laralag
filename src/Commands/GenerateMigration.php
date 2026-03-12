<?php

namespace Abianbiya\Laralag\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class GenerateMigration extends Command
{
    protected $files;

    protected $signature = 'lag:migration {name : The model/module name in StudlyCase (e.g. ProductCategory)}
                            {--table= : Override the inferred table name}';

    protected $description = 'Generate a migration with UUID primary key, timestamps, soft deletes, and audit trail columns';

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): void
    {
        $name  = trim($this->argument('name'));
        $table = $this->option('table') ?? Str::snake(Str::plural($name));

        $migrationsPath = $this->laravel->databasePath('migrations');

        // Check if migration already exists
        $existing = glob($migrationsPath . "/*_create_{$table}_table.php");
        if (!empty($existing)) {
            $this->error("A migration for table [{$table}] already exists:");
            $this->line('  ' . basename($existing[0]));
            $this->line('Run <comment>php artisan migrate</comment> then <comment>php artisan lag:module ' . $name . '</comment>');
            return;
        }

        $stub     = $this->getStub();
        $content  = str_replace('{{ table }}', $table, $stub);
        $filename = date('Y_m_d_His') . '_create_' . $table . '_table.php';
        $path     = $migrationsPath . '/' . $filename;

        $this->files->put($path, $content);

        $this->info("Migration created: <comment>database/migrations/{$filename}</comment>");
        $this->newLine();
        $this->line('Next steps:');
        $this->line('  1. Open the migration and add your columns in the <comment>// TODO</comment> section');
        $this->line('  2. Run <comment>php artisan migrate</comment>');
        $this->line('  3. Run <comment>php artisan lag:module ' . $name . '</comment>');
    }

    protected function getStub(): string
    {
        $stubPath = __DIR__ . '/../../resources/stubs/migration.create.stub';
        return $this->files->get($stubPath);
    }
}
