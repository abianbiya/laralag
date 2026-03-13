<?php

namespace Abianbiya\Laralag\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Abianbiya\Laralag\Modules\Menu\Models\Menu;
use Abianbiya\Laralag\Modules\Module\Models\Module;
use Abianbiya\Laralag\Modules\MenuGroup\Models\MenuGroup;
use Abianbiya\Laralag\Modules\Permission\Models\Permission;
use Abianbiya\Laralag\Modules\PermissionRole\Models\PermissionRole;
use Abianbiya\Laralag\Modules\Role\Models\Role;
use Abianbiya\Laralag\Traits\IntrospectTable;

use function Laravel\Prompts\text;
use function Laravel\Prompts\select;
use function Laravel\Prompts\confirm;

class GenerateModule extends Command
{
    use IntrospectTable;

    protected $files;
    protected $signature = 'lag:module {name} {--no-menu} {--menuOnly} {--api} {--table=} {--hasFile} {--force} {--columns=} {--searchable=} {--textarea=}';
    protected $description = 'Generate a full CRUD module (controller, model, views, routes, permissions) from a database table';
    protected $resourcePath;
    private $fields = [];
    private $types = [];
    private $lengths = [];
    private $module = '';

    private $hasFile = false;

    public $baseDir = __DIR__ . '/../../';

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
        $this->resourcePath = $this->baseDir.'resources/stubs';
    }

    public function handle()
    {
        $module = trim($this->argument('name'));
        $this->module = $module;

        if($this->option('hasFile')){
            $this->hasFile = true;
        }

        // Check if we only want to create menu entries
        if ($this->option('menuOnly')) {
            $this->createMenuAndPermissions($module);
            $this->info('Menu and permissions for ' . $module . ' have been created successfully.');
            return;
        }

        $this->generate($module);

        $this->createMenuAndPermissions($module);

        $withApi = $this->option('api');
        if ($withApi) {
            Artisan::call('lag:api ' . $module);
        }

        $this->info('Running optimize...');
        Artisan::call('optimize');
        $this->info(Artisan::output());
    }

    protected function createMenuAndPermissions($module)
    {
        $moduleName = strtolower($module);

        $permissions = [
            'index' => "$moduleName.index",
            'create' => "$moduleName.create",
            'store' => "$moduleName.store",
            'show' => "$moduleName.show",
            'edit' => "$moduleName.edit",
            'update' => "$moduleName.update",
            'destroy' => "$moduleName.destroy"
        ];

        $group = $moduleName;
        $nama = '';
        // Example of how to insert permissions into a database (assuming you have a Permission model)
        foreach ($permissions as $action => $permName) {
            // Check if permission exists, if not, create it
            switch ($action) {
                case 'index':
                     $nama = 'Melihat daftar ' . $group;
                    break;
                case 'create':
                     $nama = 'Menampilkan form tambah ' . $group;
                    break;
                case 'show':
                     $nama = 'Menampilkan detail ' . $group;
                    break;
                case 'store':
                     $nama = 'Menyimpan form tambah ' . $group;
                    break;
                case 'edit':
                     $nama = 'Menampilkan form edit ' . $group;
                    break;
                case 'update':
                     $nama = 'Menyimpan form edit ' . $group;
                    break;
                case 'destroy':
                     $nama = 'Menghapus ' . $group;
                    break;
                case 'menu':
                     $nama = 'Menampilkan menu ' . $group;
                    break;
                default:
                     $nama = $action . ' ' . $group;
                    break;
            }
            Permission::firstOrCreate([
                'slug' => $permName
            ], [
                'nama' => $nama,
                'group' => $group,
                'action' => $action
            ]);
        }

        // Assign all new permissions to the Root role
        $rootRole = Role::where('slug', 'root')->first();
        if ($rootRole) {
            foreach ($permissions as $permSlug) {
                $permission = Permission::where('slug', $permSlug)->first();
                if ($permission) {
                    PermissionRole::firstOrCreate([
                        'permission_id' => $permission->id,
                        'role_id' => $rootRole->id,
                    ]);
                }
            }
            $this->info('Permissions assigned to Root role.');
        }

        // Create menu by default, skip only if --no-menu is specified
        if (!$this->option('no-menu')) {
            $this->createOrUpdateMenu($moduleName, $permissions);
        }

        $this->info('Permissions (and menu) for ' . $module . ' have been created.');
    }

    protected function createOrUpdateMenu($moduleName, $permissions)
    {
        // Get all menu groups for selection
        $menuGroups = MenuGroup::all()->pluck('nama', 'id')->toArray();
        $menuGroupId = select('Pilih grup menu untuk modul ini:', $menuGroups);

        // Ask if user wants to create a new menu or use existing
        $createNewMenu = confirm('Buat sebagai menu baru?', true);

        if ($createNewMenu) {
            // Get menu details
            $menuName = text('Masukkan nama menu:', ucwords(str_replace('_', ' ', $moduleName)), ucwords(str_replace('_', ' ', $moduleName)));
            $menuNameEn = text('Masukkan nama menu (English):', ucwords(str_replace('_', ' ', $moduleName)), ucwords(str_replace('_', ' ', $moduleName)));
            $icon = text('Masukkan icon menu:', 'bi bi-list', 'bi bi-list');

            // Get max order value
            $maxMenuOrder = Menu::where('menu_group_id', $menuGroupId)->max('urutan') ?? 0;

            // Create the menu
            $menu = Menu::create([
                'menu_group_id' => $menuGroupId,
                'nama' => $menuName,
                'nama_en' => $menuNameEn,
                'icon' => $icon,
                'urutan' => $maxMenuOrder + 1,
                'is_tampil' => 1,
            ]);
        } else {
            // Get existing menus for the selected group
            $menus = Menu::where('menu_group_id', $menuGroupId)->get()->pluck('nama', 'id')->toArray();
            $menuId = select('Pilih menu untuk modul ini:', $menus);
            $menu = Menu::find($menuId);
        }

        // Get routing for the module
        $routing = text('Masukkan routing untuk modul ini:', $moduleName . '.index', $moduleName . '.index');

        // Create the module entry with the index permission
        $maxModuleOrder = Module::where('menu_id', $menu->id)->max('urutan') ?? 0;

        // Use the index permission as the main permission for display
        $mainPermission = $permissions['index'];

        // Create module entry
        Module::create([
            'menu_id' => $menu->id,
            'nama' => ucwords(str_replace('_', ' ', $moduleName)),
            'routing' => $routing,
            'permission' => $mainPermission,
            'urutan' => $maxModuleOrder + 1,
            'is_tampil' => 1,
        ]);

        $this->info('Menu dan module untuk ' . $moduleName . ' telah dibuat.');
    }

    public function generate($module)
    {
        // Ambil nama-nama file
        $name = $this->qualifyClass($module);
        $nameController = $name . 'Controller';
        $nameModel = str_replace('Controllers', 'Models', $name);
        $nameView = str_replace($module . '.', strtolower($module), str_replace('Controllers', 'Views', $name) . '.');
        $nameRoute = str_replace('\\Controllers\\' . $module, '\\routes', $name);

        //Ambil path
        $pathController = $this->getPath($nameController) . '.php';
        $pathModel = $this->getPath($nameModel) . '.php';
        $pathView = $this->getPath($nameView) . '.blade.php';
        $pathDetailView = $this->getPath($nameView) . '_detail.blade.php';
        $pathCreateView = $this->getPath($nameView) . '_create.blade.php';
        $pathUpdateView = $this->getPath($nameView) . '_update.blade.php';
        $pathRoute = $this->getPath($nameRoute) . '.php';


        if ($this->alreadyExists($module) && !$this->option('force')) {
            $this->error($this->type . ' already exists! Use --force to overwrite.');
            return false;
        }

        // buat folder
        $this->makeDirectory($pathController);
        $this->makeDirectory($pathModel);
        $this->makeDirectory($pathView);
        $this->makeDirectory($pathRoute);

        if ($this->option('force')) {
            $this->warn("Overwriting existing module: $module");
        }

        // simpan files
        $this->files->put($pathController, $this->buildClassController($nameController));
        $this->files->put($pathModel, $this->buildClassModel($nameModel));
        $this->files->put($pathView, $this->buildClassView($nameView));
        $this->files->put($pathDetailView, $this->buildClassDetailView($nameView));
        $this->files->put($pathCreateView, $this->buildClassFormView($nameView, 'create'));
        $this->files->put($pathUpdateView, $this->buildClassFormView($nameView, 'update'));
        $this->files->put($pathRoute, $this->buildClassRoute($nameRoute, $name, $module));


        $this->info('Hellyeah! ' . $module . ' module was successfully created.');
    }

    protected function qualifyClass($name)
    {
        $rootNamespace = $this->rootNamespace();
        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }
        $name = str_replace('/', '\\', $name);
        return $this->qualifyClass($this->getDefaultNamespace(trim($rootNamespace, '\\'), $name) . '\\' . $name);
    }

    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return $this->laravel['path'] . '/' . str_replace('\\', '/', $name);
    }

    protected function alreadyExists($rawName)
    {
        return $this->files->exists($this->getPath($this->qualifyClass($rawName)));
    }

    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
        return $path;
    }

    protected function buildClassController($name)
    {
        $stub = $this->files->get($this->resourcePath . '/controller.plain.stub');
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);
        $module = str_replace('Controller', '', $class);
        $slug = strtolower($module);

        $titel = Str::title(str_replace('_', ' ', Str::snake($module)));

        $stub = str_replace('DummyNamespace', $this->getNamespace($name), $stub);
        $stub = str_replace('DummyRootNamespace', $this->rootNamespace(), $stub);
        $stub = str_replace('DummyClass', $class, $stub);
        $stub = str_replace('selug', $slug, $stub);
        $stub = str_replace('Kelas', $module, $stub);
        $stub = str_replace('Title', $titel, $stub);

        $form_add        = '';
        $form_edit       = '';
        $model_field     = '';
        $column_title    = '';
        $ajax_field      = '';
        $shown_column    = '';
        $form_validation = '';
        $addRef          = '';
        $useRef          = '';
        $counter         = 0;

        // Phase 3.4: resolve searchable columns
        $searchableOption = $this->option('searchable');
        $searchableColumns = $searchableOption
            ? array_map('trim', explode(',', $searchableOption))
            : null;

        $fields = $this->getTableInfo($module, $this->option('table'));

        if (empty($fields)) {
            return $stub;
        }

        $except_field = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

        foreach ($fields['kolom'] as $key => $value) {
            if (!in_array($key, $except_field)) {
                $form_add .= $this->genInputForm($key, (object) $value, false, $slug);
                $form_edit .= $this->genInputForm($key, (object) $value, true, $slug);

                if ($value['referensi'] != null) {
                    $kelasRef = Str::studly($value['referensi']);
                    $addRef .= '$ref_' . $value['referensi'] . ' = ' . $kelasRef . "::all()->pluck('" . $value['referensi_desc_kolom'] . "','id');" . PHP_EOL . "		";
                    $useRef .= "use App\Modules" . "\\" . $kelasRef . "\Models" . "\\" . $kelasRef . ";" . PHP_EOL;
                }
                if ($value['tipe_data'] == 'tinyint') {
                    // Phase 4.2: use config for boolean labels
                    $addRef .= '$ref_'.$key . ' = config("laralag.boolean_labels", ["1" => "Ya", "0" => "Tidak"]);' . PHP_EOL . "		";
                }
                // Phase 2.3: enum reference options
                if ($value['tipe_data'] == 'enum' && !empty($value['column_type_raw'])) {
                    preg_match_all("/'([^']+)'/", $value['column_type_raw'], $matches);
                    if (!empty($matches[1])) {
                        $enumOptions = [];
                        foreach ($matches[1] as $enumVal) {
                            $enumOptions[$enumVal] = Str::title(str_replace('_', ' ', $enumVal));
                        }
                        $optionsStr = var_export($enumOptions, true);
                        $addRef .= '$ref_'.$key . ' = ' . $optionsStr . ';' . PHP_EOL . "		";
                    }
                }

                $this->fields[] = $key;
                $this->types[$key] = $value['tipe_data'];
                // Phase 3.3: store lengths
                $this->lengths[$key] = $value['length'] ?? null;

                $label = Str::title(str_replace('_', ' ', str_replace('_id', '', $key)));
                $column_title .= "'" . $label . "', ";
                $ajax_field .= "'" . $key . "', ";
                $shown_column .= $counter . ", ";
                $counter++;

                if ($value['tipe_data'] == 'bigint') {
                    $model_field .= "$" . $slug . "->" . $key . ' = str($request->input("' . $key . '"))->replace(".", "");
		';
                    $v_type = "numeric";
                } elseif ($value['tipe_data'] == 'date' || $value['tipe_data'] == 'datetime') {
                    $model_field .= "$" . $slug . "->" . $key . ' = date("Y-m-d", strtotime($request->input("' . $key . '")));
        ';
                    $v_type = "date";
                } else {
                    $model_field .= "$" . $slug . "->" . $key . ' = $request->input("' . $key . '");
        ';
                    $v_type = "string";
                }

                // Phase 4.1: enhanced validation rules
                $v_rules = $value['is_nullable'] ? 'nullable' : 'required';
                $v_rules .= '|' . $this->resolveValidationType($key, $value);
                $form_validation .= "'" . $key . "' => '" . $v_rules . "'," . PHP_EOL . "			";
            }
        }

        if ($counter < 2) {
            $column_title .= "'Dibuat pada', ";
            $ajax_field .= "'created_at', ";
            $shown_column .= ($counter + 1) . ", ";
        }

        if ($this->hasFile) {
            $formBody = 'html()->file("file")->class("form-control form-file")';
            $form_add .= "'file' => ['File', " . $formBody . "],";
            $form_edit .= "'file' => ['File', " . $formBody . "],";
        }

        // Phase 3.4: resolve searchable field list for whereAny()
        if ($searchableColumns) {
            $searchableStr = implode(', ', array_map(fn($c) => "'$c'", $searchableColumns));
        } else {
            $searchableStr = $ajax_field;
        }

        $stub = str_replace('//Forms//', $form_add, $stub);
        $stub = str_replace('//FormsEdit//', $form_edit, $stub);
        $stub = str_replace('//ModelField//', $model_field, $stub);
        $stub = str_replace('"ColumnJudul"', $column_title, $stub);
        $stub = str_replace('"AjaxField"', $ajax_field, $stub);
        $stub = str_replace('SearchableColumn', $searchableStr, $stub);
        $stub = str_replace('//FormValidation//', $form_validation, $stub);
        $stub = str_replace('//FormReference//', $addRef, $stub);
        $stub = str_replace('//ImportReference//', $useRef, $stub);

        return $stub;
    }

    /**
     * Phase 4.1: Resolve the validation type string for a field.
     */
    protected function resolveValidationType(string $fieldName, array $fieldInfo): string
    {
        $type = $fieldInfo['tipe_data'];
        $length = $fieldInfo['length'] ?? null;

        switch ($type) {
            case 'tinyint':
                return 'boolean';
            case 'date':
            case 'datetime':
                return 'date';
            case 'bigint':
            case 'integer':
                return 'numeric';
            case 'text':
                return 'string';
            case 'enum':
                // Extract enum values for in: rule
                if (!empty($fieldInfo['column_type_raw'])) {
                    preg_match_all("/'([^']+)'/", $fieldInfo['column_type_raw'], $matches);
                    if (!empty($matches[1])) {
                        return 'string|in:' . implode(',', $matches[1]);
                    }
                }
                return 'string';
            case 'varchar':
            case 'char':
            default:
                $rules = [];
                // Phase 4.1: email heuristic
                if (str_contains(strtolower($fieldName), 'email')) {
                    $rules[] = 'email';
                } else {
                    $rules[] = 'string';
                }
                // Phase 4.1: max length from column definition
                if ($length) {
                    $rules[] = 'max:' . $length;
                }
                return implode('|', $rules);
        }
    }

    /**
     * Phase 2.2: Resolve HTML5 input type based on field name pattern.
     * Returns ['method' => string, 'type_attr' => string|null].
     * 'type_attr' is set when the Spatie method name differs from the HTML type
     * (e.g. html()->url() creates <a>, so we use html()->text()->attribute('type','url')).
     */
    protected function resolveInputMethod(string $fieldName): array
    {
        // Always use html()->text() as the base element since Spatie HTML's
        // type-specific methods (email, tel, url, password) don't all support
        // ->placeholder() and ->maxlength(). HTML5 type is set via ->attribute().
        $lower = strtolower($fieldName);
        if (str_contains($lower, 'email')) {
            return ['method' => 'text', 'type_attr' => 'email'];
        }
        if (str_contains($lower, 'password') || str_contains($lower, 'passwd')) {
            return ['method' => 'text', 'type_attr' => 'password'];
        }
        if (str_contains($lower, 'url') || str_contains($lower, 'website') || str_contains($lower, 'link')) {
            return ['method' => 'text', 'type_attr' => 'url'];
        }
        if (str_contains($lower, 'phone') || str_contains($lower, 'telp') || str_contains($lower, 'no_hp') || str_contains($lower, 'hp')) {
            return ['method' => 'text', 'type_attr' => 'tel'];
        }
        return ['method' => 'text', 'type_attr' => null];
    }

    protected function buildClassModel($name)
    {
        $stub = $this->files->get($this->resourcePath . '/model.plain.stub');
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        $fillables = '';
        $fields = $this->getTableInfo($this->module, $this->option('table') ?? null);

        $except_field = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];
        foreach ($fields['kolom'] as $key => $value) {
            if (!in_array($key, $except_field)) {
                $fillables .= "'" . $key . "',";
            }
        }
        $fillables = substr($fillables, 0, -1);

        // buat relasi tabel
        $namespaceRelationalTabel = '';
        $relationalTabel = '';
        foreach ($this->fields as $value) {
            if (Str::endsWith($value, '_id')) {
                $model = Str::studly(Str::replace('_id', '', $value));
                $namespaceRelationalTabel .= "use App\Modules\\" . $model . "\Models\\" . $model . ";" . PHP_EOL;
                $relationalTabel .= 'public function ' . Str::camel($model) . '(){
		return $this->belongsTo(' . $model . '::class,"' . $value . '","id");
	}' . PHP_EOL;
            }
        }

        $stub = str_replace('DummyNamespace', $this->getNamespace($name), $stub);
        $stub = str_replace('DummyRootNamespace', $this->rootNamespace(), $stub);
        $stub = str_replace('DummyClass', $class, $stub);
        $stub = str_replace('NamaModel', Str::snake($class), $stub);
        $stub = str_replace('NamespaceRelationalTabel', $namespaceRelationalTabel, $stub);
        $stub = str_replace('RelationalTabel', $relationalTabel, $stub);
        $stub = str_replace('Fillables', $fillables, $stub);

        return $stub;
    }

    protected function buildClassView($name)
    {
        $stub = $this->files->get($this->resourcePath . '/blade.plain.stub');
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        // Phase 3.1: resolve which columns to display
        $columnsOption = $this->option('columns');
        $displayColumns = $columnsOption
            ? array_flip(array_map('trim', explode(',', $columnsOption)))
            : null;

        $fields = '';
        $columns = '';

        foreach ($this->types as $key => $value) {
            // Phase 3.1: skip columns not in --columns list
            if ($displayColumns !== null && !isset($displayColumns[$key])) {
                continue;
            }

            $isi = Str::of($key)->trim();
            if (str($isi)->endsWith('_id')) {
                $database = env('DB_DATABASE') ?: config('database.connections.' . config('database.default') . '.database');
                $isi = str($isi)->replace('_id', '');
                $relatedDescColumn = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND DATA_TYPE IN ('varchar', 'char') AND COLUMN_NAME <> 'id' LIMIT 1", [$database, (string) $isi]);
                $relatedDescColumn = $relatedDescColumn[0]->COLUMN_NAME ?? 'nama_'.$isi;
                $isi = $isi->camel()->append('->')->append($relatedDescColumn);
            }

            if ($value == 'date') {
                $fields .= "<td>{{ tanggal(\$item->" . $isi . ") }}</td>" . PHP_EOL . "				";
            } elseif ($value == 'text') {
                // Phase 1.2: XSS fix — strip HTML and truncate
                $fields .= "<td>{{ Str::limit(strip_tags(\$item->" . $isi . "), 80) }}</td>" . PHP_EOL . "				";
            } elseif ($value == 'bigint') {
                $fields .= "<td>{!! rupiah(\$item->" . $isi . ") !!}</td>" . PHP_EOL . "				";
            } elseif ($value == 'tinyint') {
                // Phase 1.4: show Ya/Tidak instead of 0/1
                $fields .= "<td>{{ \$item->" . $isi . " ? 'Ya' : 'Tidak' }}</td>" . PHP_EOL . "				";
            } elseif ($value == 'enum') {
                $fields .= "<td>{{ \$item->" . $isi . " }}</td>" . PHP_EOL . "				";
            } else {
                // Phase 3.3: truncate long varchar fields
                $colLength = $this->lengths[$key] ?? null;
                if ($colLength && $colLength > 100) {
                    $fields .= "<td>{{ Str::limit(\$item->" . $isi . ", 60) }}</td>" . PHP_EOL . "				";
                } else {
                    $fields .= "<td>{{ \$item->" . $isi . " }}</td>" . PHP_EOL . "				";
                }
            }
            $columns .= "<th scope=\"col\">" . Str::title(str_replace('_', ' ', str_replace('_id', '', $key))) . "</th>" . PHP_EOL . "			    ";
        }

        // Count actual displayed columns for colspan
        $displayedCount = $displayColumns !== null
            ? count(array_intersect_key($this->types, $displayColumns))
            : count($this->fields);

        $stub = str_replace('NamaModule', strtolower($class), $stub);
        $stub = str_replace('JudulKolom', $columns, $stub);
        $stub = str_replace('IsiKolom', $fields, $stub);
        $stub = str_replace('JmlKolom', $displayedCount + 2, $stub);
        $stub = str_replace('', strtolower($class), $stub);
        return $stub;
    }

    protected function buildClassDetailView($name)
    {
        $stub = $this->files->get($this->resourcePath . '/detail.plain.stub');
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);
        $varName = strtolower($class);

        $detailData = '';
        $fields = $this->getTableInfo($this->module, $this->option('table') ?? null);

        if (empty($fields)) {
            $stub = str_replace('NamaModule', $varName, $stub);
            $stub = str_replace('DetailData', '', $stub);
            return $stub;
        }

        $except_field = ['created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

        foreach ($fields['kolom'] as $fieldName => $fieldInfo) {
            if (in_array($fieldName, $except_field)) continue;

            $label = Str::title(str_replace('_', ' ', str_replace('_id', '', $fieldName)));

            // Resolve display value
            if ($fieldInfo['referensi'] !== null) {
                // Use the Eloquent relation method + description column
                $relationMethod = Str::camel(str_replace('_id', '', $fieldName));
                $descCol = $fieldInfo['referensi_desc_kolom'] ?? 'name';
                $displayValue = "\$$varName->{$relationMethod}->{$descCol}";
            } elseif ($fieldInfo['tipe_data'] === 'date') {
                $displayValue = "{{ tanggal(\$$varName->$fieldName) }}";
            } elseif ($fieldInfo['tipe_data'] === 'bigint') {
                $displayValue = "{!! rupiah(\$$varName->$fieldName) !!}";
            } elseif ($fieldInfo['tipe_data'] === 'tinyint') {
                // Phase 1.5: show Ya/Tidak instead of 0/1
                $displayValue = "{{ \$$varName->$fieldName ? 'Ya' : 'Tidak' }}";
            } else {
                $displayValue = "{{ \$$varName->$fieldName }}";
            }

            // Wrap non-directive values in {{ }}
            if (!Str::startsWith($displayValue, ['{{', '{!!'])) {
                $displayValue = "{{ $displayValue }}";
            }

            $detailData .= "<tr>\n                    <th width='25%'>$label</th>\n                    <td>$displayValue</td>\n                </tr>" . PHP_EOL . "\t\t\t\t";
        }

        $stub = str_replace('NamaModule', $varName, $stub);
        $stub = str_replace('DetailData', $detailData, $stub);
        return $stub;
    }

    protected function buildClassFormView($name, $action)
    {
        $stub = $this->files->get($this->resourcePath . '/form.plain.stub');
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        // Phase 1.3: set enctype for file uploads
        $enctype = $this->hasFile ? 'enctype="multipart/form-data"' : '';

        $stub = str_replace('NamaModule', strtolower($class), $stub);
        $stub = str_replace('AksiForm', $action == 'create' ? 'Tambah' : 'Edit', $stub);
        $stub = str_replace('FormAction', $action == 'create' ? 'store' : 'update', $stub);
        $stub = str_replace('FormParam', $action == 'create' ? '' : ', $' . strtolower($class) . '->id', $stub);
        $stub = str_replace('FormMethod', $action == 'create' ? '' : "@method('patch')", $stub);
        $stub = str_replace('EncType', $enctype, $stub);

        return $stub;
    }

    protected function buildClassRoute($name, $class, $module)
    {
        $stub = $this->files->get($this->resourcePath . '/route.plain.stub');

        $stub = str_replace('DummyClass', $class . 'Controller', $stub);
        $stub = str_replace('selug', strtolower($module), $stub);
        $stub = str_replace('Kelas', $module, $stub);

        return $stub;
    }

    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    protected function getDefaultNamespace($rootNamespace, $name)
    {
        return $rootNamespace . '\Modules\\' . $name . '\\Controllers';
    }


    public function genInputForm($field_name, $attributes, $is_edit = false, $module = NULL)
    {
        $except_field = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];
        if (in_array($field_name, $except_field)) return '';

        $formBody = '';

        // Phase 1.1: always use old() with model fallback on edit forms
        $input_value = $is_edit
            ? 'old("' . $field_name . '", $' . $module . '->' . $field_name . ')'
            : 'old("' . $field_name . '")';

        $is_required = $attributes->is_nullable ? [] : ['required' => true];
        $label = Str::title(str_replace('_', ' ', str_replace('_id', '', $field_name)));

        // Phase 2.4: resolve plain textarea fields from --textarea option
        $plainTextareas = [];
        if ($this->option('textarea')) {
            $plainTextareas = array_map('trim', explode(',', $this->option('textarea')));
        }

        if ($attributes->referensi != null) {
            $addRef = 'ref_';
            $formBody = 'html()->select("' . $field_name . '", $' . $addRef . $attributes->referensi . ', '.$input_value.')->class("form-select select2")'. ($is_required ? '->required()' : '') . '],//->placeholder("-- Pilih ' . $label . '")';
        } else {
            switch ($attributes->tipe_data) {
                case 'integer':
                    $formBody = 'html()->number("' . $field_name . '", ' . $input_value . ')->class("form-control")' . ($is_required ? '->required()' : '');
                    break;

                case 'text':
                    // Phase 2.4: plain textarea if field is in --textarea list
                    $editorClass = in_array($field_name, $plainTextareas) ? 'form-control' : 'form-control rich-editor';
                    $formBody = 'html()->textarea("' . $field_name . '", ' . $input_value . ')->class("' . $editorClass . '")';
                    break;

                case 'bigint':
                    $formBody = 'html()->text("' . $field_name . '", ' . $input_value . ')->class("form-control nominal")';
                    break;

                case 'tinyint':
                    $formBody = 'html()->select("' . $field_name . '", $ref_' . $field_name . ', ' . $input_value . ')->class("form-select")' . ($is_required ? '->required()' : '');
                    break;

                case 'date':
                    $formBody = 'html()->text("' . $field_name . '", ' . $input_value . ')->class("form-control datepicker")' . ($is_required ? '->required()' : '');
                    break;

                case 'datetime':
                    $formBody = 'html()->text("' . $field_name . '", ' . $input_value . ')->class("form-control datetimepicker")' . ($is_required ? '->required()' : '');
                    break;

                case 'enum':
                    // Phase 2.3: enum support — options pre-built in addRef, referenced via $ref_fieldname
                    $formBody = 'html()->select("' . $field_name . '", $ref_' . $field_name . ', ' . $input_value . ')->class("form-select")' . ($is_required ? '->required()' : '');
                    break;

                case 'varchar':
                case 'char':
                    // Phase 2.1: maxlength from column length
                    $maxLengthAttr = $attributes->length ? '->maxlength(' . $attributes->length . ')' : '';
                    // Phase 2.2: HTML5 input type heuristics
                    $resolved = $this->resolveInputMethod($field_name);
                    $typeAttr = $resolved['type_attr'] ? '->attribute("type", "' . $resolved['type_attr'] . '")' : '';
                    $formBody = 'html()->' . $resolved['method'] . '("' . $field_name . '", ' . $input_value . ')->class("form-control")->placeholder("' . $attributes->catatan . '")' . $typeAttr . $maxLengthAttr . ($is_required ? '->required()' : '');
                    break;

                default:
                    $formBody = 'html()->text("' . $field_name . '", ' . $input_value . ')->class("form-control")->placeholder("' . $attributes->catatan . '")' . ($is_required ? '->required()' : '');
                    break;
            }
        }

        $formBody = "'" . $field_name . "' => ['" . $label . "', " . $formBody . "],
            ";

        return $formBody;
    }

}
