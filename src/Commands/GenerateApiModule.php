<?php

namespace Abianbiya\Laralag\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Abianbiya\Laralag\Traits\IntrospectTable;

class GenerateApiModule extends Command
{
    use IntrospectTable;

    protected $files;

    public $baseDir = __DIR__.'/../../resources/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lag:api {module} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate API controller and route for a module';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = $this->getNameInput();
        $modulePath = base_path('app/Modules/'.$module);
        $controllerName = $module.'ApiController';
        $controllerPath = $modulePath.'/Controllers/'.$controllerName.'.php';
        $routePath = $modulePath.'/routes.php';

        if (! is_dir($modulePath)) {
            $this->error('Module does not exist!');

            return false;
        }

        if ($this->files->exists($controllerPath) && !$this->option('force')) {
            $this->error('API Controller already exists! Use --force to overwrite.');

            return false;
        }

        if ($this->files->exists($controllerPath) && $this->option('force')) {
            $this->warn("Overwriting existing API controller: $controllerName");
        }

        $this->makeDirectory($controllerPath);
        $this->files->put($controllerPath, $this->buildClassController($module, $controllerName));

        $routeDefinition = $this->buildClassRoute($module, $controllerName);
        if (! empty($routeDefinition)) {
            if ($this->files->exists($routePath)) {
                $this->files->append($routePath, $routeDefinition);
            } else {
                $this->files->put($routePath, $routeDefinition);
            }
        }

        $this->info('API Controller and Route for module '.$module.' created successfully.');
    }

    protected function getNameInput()
    {
        return trim($this->argument('module'));
    }

    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    protected function buildClassController($module, $controllerName)
    {
        $stub = $this->files->get($this->baseDir.'stubs/controller.api.stub');
        $namespace = 'App\\Modules\\'.$module.'\\Controllers';
        $model = Str::studly($module);
        $slug = Str::kebab($module);
        $modelCamel = Str::camel($module);
        $moduleTitle = Str::title($module);

        $stub = str_replace([
            'DummyNamespace',
            'DummyRootNamespace',
            'DummyClass',
            'dummy-slug',
            'DummyModel',
            'dummyModel',
            'moduleTitle',
        ], [
            $namespace,
            'App\\',
            $controllerName,
            $slug,
            $model,
            $modelCamel,
            $moduleTitle,
        ], $stub);

        $fields = $this->getTableInfo($module);

        $modelField = '';
        $formValidation = '';

        $exceptField = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

        foreach ($fields['kolom'] as $key => $value) {
            if (! in_array($key, $exceptField)) {
                // Type-aware and nullability-aware validation
                $nullable = $value['is_nullable'] ? 'nullable' : 'required';
                $type = match($value['tipe_data']) {
                    'integer', 'bigint', 'tinyint', 'smallint' => 'numeric',
                    'date'     => 'date',
                    'datetime' => 'date',
                    default    => 'string',
                };
                $formValidation .= "'$key' => '$nullable|$type',\n            ";

                // Model field assignment
                $modelField .= "\$$modelCamel->$key = \$request->input('$key');\n        ";
            }
        }

        $stub = str_replace('//ModelField//', $modelField, $stub);
        $stub = str_replace('//FormValidation//', $formValidation, $stub);

        return $this->formatCode($stub);
    }

    protected function buildClassRoute($module, $controllerName)
    {
        $stub = $this->files->get($this->baseDir.'stubs/route.api.stub');
        $slug = Str::kebab($module);

        $routeDefinition = str_replace([
            'dummy-slug',
            'DummyClass',
            'DummyNamespace',
        ], [
            $slug,
            $controllerName,
            'App\\Modules\\'.$module.'\\Controllers',
        ], $stub);

        $routePath = base_path('app/Modules/'.$module.'/routes.php');

        if ($this->files->exists($routePath)) {
            $existingRoutes = $this->files->get($routePath);

            // Check if the route already exists in the file
            if (strpos($existingRoutes, $routeDefinition) !== false) {
                $this->info('Route already exists in '.$routePath);

                return '';
            }
        }

        return $routeDefinition;
    }

    protected function formatCode($code)
    {
        // You can use a package like `matt-allan/laravel-code-style` for automatic formatting
        // or integrate a PHP CS Fixer configuration to format your code properly.
        // This is just a placeholder to indicate where you'd format the code.
        return $code;
    }
}
