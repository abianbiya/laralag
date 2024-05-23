<?php

namespace Abianbiya\Laralag\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Abianbiya\Laralag\Modules\Menu\Models\Menu;
use Abianbiya\Laralag\Modules\Permission\Models\Permission;

class GenerateModule extends Command
{
    protected $files;
    protected $signature = 'lag:module {name} {--menu} {--api} {--table=}';
    protected $description = 'Make you fuckin crud from just a table name';
    protected $resourcePath;
    private $fields = [];
    private $types = [];
    private $module = '';

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
        $this->generate($module);

        $this->createMenuAndPermissions($module);

        $withApi = $this->option('api');
        if ($withApi) {
            Artisan::call('lag:api ' . $module);
        }
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

        // Optionally link these permissions to a menu item if --menu was specified
        if ($this->option('menu')) {
            $this->createOrUpdateMenu($moduleName, $permissions);
        }

        $this->info('Permissions (and optionally menu) for ' . $module . ' have been created.');
    }

    protected function createOrUpdateMenu($moduleName, $permissions)
    {
        // Assuming you have a Menu model where you want to link these permissions
        $menu = Menu::firstOrCreate([
            'name' => $moduleName
        ], [
            'display_name' => ucwords(str_replace('_', ' ', $moduleName)),
            'icon' => 'default-icon', // Specify the default icon or make it configurable
        ]);

        // Linking the permissions to the menu
        foreach ($permissions as $perm) {
            $menu->permissions()->attach(Permission::where('name', $perm)->first());
        }
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


        if ($this->alreadyExists($module)) {
            $this->error($this->type . ' already exists!');
            return false;
        }

        // buat folder
        $this->makeDirectory($pathController);
        $this->makeDirectory($pathModel);
        $this->makeDirectory($pathView);
        $this->makeDirectory($pathRoute);

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
        $nominalSanitizer= '';
        $counter            = 0;

        $fields = $this->getTableInfo($module);

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
                    $addRef .= '$ref_'.$key . ' = ["1" => "Ya", "0" => "Tidak"];' . PHP_EOL . "		";

                }
                $this->fields[] = $key;
                $this->types[$key] = $value['tipe_data'];
                $label = Str::title(str_replace('_', ' ', str_replace('_id', '', $key)));
                $column_title .= "'" . $label . "', ";
                $ajax_field .= "'" . $key . "', ";
                $shown_column .= $counter . ", ";
                $counter++;
                if ($value['tipe_data'] == 'bigint') {
                $model_field .= "$" . $slug . "->" . $key . ' = str($request->input("' . $key . '"))->replace(".", "");
		';
                $type = "numeric";
                }elseif($value['tipe_data'] == 'date'){
                    $model_field .= "$" . $slug . "->" . $key . ' = date("Y-m-d", strtotime($request->input("' . $key . '")));
        ';
                $type = "date";
                }else{
                    $model_field .= "$" . $slug . "->" . $key . ' = $request->input("' . $key . '");
        ';
                $type = "string";
                }
                
                $form_validation .= "'" . $key . "' => '". ($value['is_nullable'] ? 'nullable' : 'required') ."|".$type."'," . PHP_EOL . "			";
            }
        }

        if ($counter < 2) {
            $column_title .= "'Dibuat pada', ";
            $ajax_field .= "'created_at', ";
            $shown_column .= ($counter + 1) . ", ";
        }
        
        $stub = str_replace('//Forms//', $form_add, $stub);
        $stub = str_replace('//FormsEdit//', $form_edit, $stub);
        $stub = str_replace('//ModelField//', $model_field, $stub);
        $stub = str_replace('"ColumnJudul"', $column_title, $stub);
        $stub = str_replace('"AjaxField"', $ajax_field, $stub);
        $stub = str_replace('SearchableColumn', $column_title, $stub);
        $stub = str_replace('//FormValidation//', $form_validation, $stub);
        $stub = str_replace('//FormReference//', $addRef, $stub);
        $stub = str_replace('//ImportReference//', $useRef, $stub);

        return $stub;
    }

    protected function buildClassModel($name)
    {
        $stub = $this->files->get($this->resourcePath . '/model.plain.stub');
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        $fillables = '';
        $fields = $this->getTableInfo($this->module);

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

        $fields = '';
        $columns = '';
        // dd($this->types);
        foreach ($this->types as $key => $value) {
            $isi = Str::of($key)->trim();
            if(str($isi)->endsWith('_id')){
                $database = env('DB_DATABASE') ?: config('database.connections.' . config('database.default') . '.database');
                $isi = str($isi)->replace('_id', '');
                $relatedDescColumn = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$isi' AND DATA_TYPE IN ('varchar', 'char') AND COLUMN_NAME <> 'id' LIMIT 1");
                $relatedDescColumn = $relatedDescColumn[0]->COLUMN_NAME ?? 'nama_'.$isi;
                $isi = $isi->camel()->append('->')->append($relatedDescColumn);
            }
            if($value == 'date'){
                $fields .= "<td>{{ tanggal(\$item->" . $isi . ") }}</td>" . PHP_EOL . "				";
            }elseif($value == 'text'){
                $fields .= "<td>{!! \$item->" . $isi . " !!}</td>" . PHP_EOL . "				";
            }elseif($value == 'bigint'){
                $fields .= "<td>{!! rupiah(\$item->" . $isi . ") !!}</td>" . PHP_EOL . "				";
            }else{
                $fields .= "<td>{{ \$item->" . $isi . " }}</td>" . PHP_EOL . "				";
            }
            $columns .= "<th scope=\"col\">" . Str::title(str_replace('_', ' ', str_replace('_id', '', $key))) . "</th>" . PHP_EOL . "			    ";
        }

        $stub = str_replace('NamaModule', strtolower($class), $stub);
        $stub = str_replace('JudulKolom', $columns, $stub);
        $stub = str_replace('IsiKolom', $fields, $stub);
        $stub = str_replace('JmlKolom', count($this->fields) + 2, $stub);
        $stub = str_replace('', strtolower($class), $stub);
        return $stub;
    }

    protected function buildClassDetailView($name)
    {
        $stub = $this->files->get($this->resourcePath . '/detail.plain.stub');
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        $labelData = '';
        $valueData = '';
        $detailData = '';
        foreach ($this->fields as $key => $value) {
            $label = $value;
            if (Str::contains($value, '_id')) {
                // ubah kalo foreign key ke relation
                $label = str_replace('_id', '', $value);
                $value = Str::camel($label) . "->id";
            }
            $labelData = "<th width='25%'>" . str_replace('_', ' ', Str::title($label)) . "</th>\n";
            $valueData = "                  <td>{{ $" . strtolower($class) . "->$value }}</td>";
            $detailData .= "<tr>
                    ".$labelData . $valueData . "\n             </tr>" . PHP_EOL . "				";
        }

        $stub = str_replace('NamaModule', strtolower($class), $stub);
        $stub = str_replace('DetailData', $detailData, $stub);
        return $stub;
    }

    protected function buildClassFormView($name, $action)
    {
        $stub = $this->files->get($this->resourcePath . '/form.plain.stub');
        $class = str_replace($this->getNamespace($name) . '\\', '', $name);

        $stub = str_replace('NamaModule', strtolower($class), $stub);
        $stub = str_replace('AksiForm', $action == 'create' ? 'Tambah' : 'Edit', $stub);
        $stub = str_replace('FormAction', $action == 'create' ? 'store' : 'update', $stub);
        $stub = str_replace('FormParam', $action == 'create' ? '' : ', $' . strtolower($class) . '->id', $stub);
        $stub = str_replace('FormMethod', $action == 'create' ? '' : "@method('patch')", $stub);

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

    private function getTableInfo($namaModel)
    {
        $tabel = Str::snake($namaModel);
        $database = env('DB_DATABASE') ?: config('database.connections.' . config('database.default') . '.database');
        $ret['tabel'] = $tabel;

        $q = DB::select("select * from information_schema.COLUMNS where TABLE_SCHEMA='" . $database . "' and TABLE_NAME='" . $tabel . "'");

        if (empty($q)) {
            if($this->option('table')){
                $tabel = $this->option('table');
                $q = DB::select("select * from information_schema.COLUMNS where TABLE_SCHEMA='" . $database . "' and TABLE_NAME='" . $tabel . "'");
            }else{
                die("Table $tabel not found. \n");
            }
        }
        // dd(collect($q)->pluck('COLUMN_NAME'));

        $primary_key = [];
        $counter_primary = 0;

        foreach ($q as $row) {
            $ret['kolom'][$row->COLUMN_NAME] = [
                'is_nullable' => ($row->IS_NULLABLE == "NO" ? false : true),
                'tipe_data' => $row->DATA_TYPE,
                'length' => $row->CHARACTER_MAXIMUM_LENGTH,
                'catatan' => $row->COLUMN_COMMENT,
                'is_primary' => $row->COLUMN_KEY == "PRI",
                'referensi' => null,
                'referensi_kolom' => null,
                'referensi_desc_kolom' => null
            ];

            // Check if the column is potentially a foreign key based on its suffix
            if (Str::endsWith($row->COLUMN_NAME, ['_id', '_slug'])) {
                $relatedModel = Str::camel(Str::beforeLast($row->COLUMN_NAME, '_')); // Assumes the related table model is the prefix of '_id' or '_slug'
                $relatedTable = Str::snake($relatedModel);
                // Check if related table exists
                $checkTableExist = DB::select("SELECT * FROM information_schema.tables WHERE table_schema = '$database' AND table_name = '$relatedTable' LIMIT 1");
                if(empty($checkTableExist)) {
                    $relatedModel = Str::camel(Str::beforeLast($row->COLUMN_NAME, '_id')); // Assumes the related table model is the prefix of '_id'
                    $relatedTable = Str::snake($relatedModel.'_'.$row->COLUMN_NAME);
                    $checkTableExist = DB::select("SELECT * FROM information_schema.tables WHERE table_schema = '$database' AND table_name = '$relatedTable' LIMIT 1");
                }
                
                if (!empty($checkTableExist)) {
                    $ret['kolom'][$row->COLUMN_NAME]['referensi'] = $relatedTable;
                    // Assuming that the primary key of the related table is 'id' and description column could be 'name' or similar
                    $ret['kolom'][$row->COLUMN_NAME]['referensi_kolom'] = 'id';
                    $relatedDescColumn = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$relatedTable' AND DATA_TYPE IN ('varchar', 'char') ORDER BY CHARACTER_MAXIMUM_LENGTH DESC LIMIT 1");
                    $ret['kolom'][$row->COLUMN_NAME]['referensi_desc_kolom'] = $relatedDescColumn[0]->COLUMN_NAME ?? 'name';
                }
            }
        }

        return $ret;
    }


    public function genInputForm($field_name, $attributes, $is_edit = false, $module = NULL)
    {
        $except_field = ['id', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];
        if (in_array($field_name, $except_field)) return '';

        $formBody = '';
        $formSize = 8;

        $input_value = $is_edit ? "$" . $module . "->" . $field_name : 'old("' . $field_name . '")';
        $is_required = $attributes->is_nullable ? [] : ['required' => true];
        $label = Str::title(str_replace('_', ' ', str_replace('_id', '', $field_name)));

        if ($attributes->referensi != null) {
            $addRef = 'ref_';
            $formBody = 'html()->select("' . $field_name . '", $' . $addRef . $attributes->referensi . ', '.$input_value.')->class("form-select select2")'. ($is_required ? '->required()' : '') . '],//->placeholder("-- Pilih ' . $label . '")';
        } else {
            switch ($attributes->tipe_data) {
                case 'integer':
                    $formBody = 'html()->number("' . $field_name . '", ' . $input_value . ')->class("form-control")' . ($is_required ? '->required()' : '');
                    break;

                case 'text':
                    $formBody = 'html()->textarea("' . $field_name . '", ' . $input_value . ')->class("form-control rich-editor")';
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

                case 'varchar':
                case 'char':
                    $formBody = 'html()->text("' . $field_name . '", ' . $input_value . ')->class("form-control")->placeholder("' . $attributes->catatan . '")' . ($is_required ? '->required()' : '');
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
