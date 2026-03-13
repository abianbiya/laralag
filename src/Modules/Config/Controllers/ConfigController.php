<?php
namespace Abianbiya\Laralag\Modules\Config\Controllers;

use Abianbiya\Laralag\Helpers\Logger;
use Illuminate\Http\Request;
use Abianbiya\Laralag\Modules\Log\Models\Log;
use Abianbiya\Laralag\Modules\Config\Models\Config;
use Abianbiya\Laralag\Modules\ConfigGroup\Models\ConfigGroup;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class ConfigController extends Controller
{
    use Logger;
    protected $log;
    protected $title = "Pengaturan";

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function index(Request $request)
    {
        // Load all config_groups ordered by urutan, filtered by is_tampil = 1
        $allGroups = ConfigGroup::where('is_tampil', 1)->orderBy('urutan')->get();

        // Filter groups to only those the user can view
        $groups = $allGroups->filter(function($group) {
            return can("config-{$group->slug}.index");
        });

        // For each group, load configs ordered by urutan
        $groups->each(function($group) {
            $group->setRelation('configs', $group->configs()->visible()->get());
        });

        // Determine active tab from query param or first group
        $activeSlug = $request->get('tab', $groups->first()?->slug);

        $this->log($request, 'melihat halaman '.$this->title);
        return view('Config::config', [
            'title'      => $this->title,
            'groups'     => $groups,
            'activeSlug' => $activeSlug,
        ]);
    }

    public function update(Request $request, ConfigGroup $configGroup)
    {
        // Check permission
        if (!can("config-{$configGroup->slug}.update")) {
            abort(403);
        }

        // Load all config items for this group
        $configs = $configGroup->configs()->visible()->get();

        // Build validation rules dynamically from each item's validation_rules
        $rules = [];
        foreach ($configs as $config) {
            if (filled($config->validation_rules)) {
                $rules["config_{$config->id}"] = $config->validation_rules;
            }
        }
        $request->validate($rules);

        // Update current_value for each config item
        foreach ($configs as $config) {
            $inputKey = "config_{$config->id}";
            if ($request->has($inputKey)) {
                $value = $request->input($inputKey);
                // For is_multiple fields, JSON-encode arrays
                if ($config->is_multiple && is_array($value)) {
                    $value = json_encode($value);
                }
                $config->current_value = $value;
                $config->save();
            } elseif ($config->form_type === 'checkbox' || $config->form_type === 'toggle') {
                // Unchecked checkboxes/toggles are not sent in form — set to 0
                $config->current_value = '0';
                $config->save();
            }
        }

        // Clear config cache
        Cache::forget('laralag-config');

        $this->log($request, 'mengubah pengaturan '.$configGroup->nama, ['configGroup.id' => $configGroup->id]);
        return redirect()->route('config.index', ['tab' => $configGroup->slug])
                         ->with('message_success', 'Pengaturan berhasil disimpan!');
    }

    // ─── Config item CRUD ────────────────────────────────────────────────────

    public function createItem(Request $request)
    {
        $groups = ConfigGroup::orderBy('urutan')->pluck('nama', 'id');
        $selectedGroup = $request->get('config_group_id');
        $formTypes = [
            'text'     => 'Text',
            'number'   => 'Number',
            'email'    => 'Email',
            'password' => 'Password',
            'color'    => 'Color',
            'textarea' => 'Textarea',
            'select'   => 'Select',
            'checkbox' => 'Checkbox',
            'toggle'   => 'Toggle (on/off)',
            'file'     => 'File',
        ];
        $this->log($request, 'membuka form tambah Config Item');
        return view('Config::config_item_create', [
            'title'         => 'Tambah Config Item',
            'groups'        => $groups,
            'selectedGroup' => old('config_group_id', $selectedGroup),
            'formTypes'     => $formTypes,
        ]);
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'config_group_id'   => 'required|exists:config_group,id',
            'key'               => 'required|string|unique:config,key',
            'config_name'       => 'nullable|string',
            'default_value'     => 'nullable|string',
            'form_type'         => 'required|in:text,number,email,password,color,textarea,select,checkbox,toggle,file',
            'form_options'      => 'nullable|string',
            'is_multiple'       => 'nullable|in:0,1',
            'form_label'        => 'nullable|string',
            'form_placeholder'  => 'nullable|string',
            'form_help'         => 'nullable|string',
            'validation_rules'  => 'nullable|string',
            'urutan'            => 'nullable|integer',
            'is_tampil'         => 'nullable|in:0,1',
        ]);

        // Validate form_options JSON if provided
        $formOptions = null;
        if (filled($request->form_options)) {
            $decoded = json_decode($request->form_options, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withInput()->withErrors(['form_options' => 'Format JSON tidak valid.']);
            }
            $formOptions = $decoded;
        }

        $config = new Config();
        $config->config_group_id  = $request->config_group_id;
        $config->key               = $request->key;
        $config->config_name       = $request->config_name;
        $config->default_value     = $request->default_value;
        $config->form_type         = $request->form_type;
        $config->form_options      = $formOptions;
        $config->is_multiple       = $request->input('is_multiple', 0);
        $config->form_label        = $request->form_label;
        $config->form_placeholder  = $request->form_placeholder;
        $config->form_help         = $request->form_help;
        $config->validation_rules  = $request->validation_rules;
        $config->urutan            = $request->input('urutan', 0);
        $config->is_tampil         = $request->input('is_tampil', 1);
        $config->save();

        Cache::forget('laralag-config');

        $this->log($request, 'membuat Config Item', ['config.id' => $config->id]);
        return redirect()->route('config-group.show', $config->config_group_id)
                         ->with('message_success', 'Config Item berhasil ditambahkan!');
    }

    public function editItem(Request $request, Config $config)
    {
        $groups = ConfigGroup::orderBy('urutan')->pluck('nama', 'id');
        $formTypes = [
            'text'     => 'Text',
            'number'   => 'Number',
            'email'    => 'Email',
            'password' => 'Password',
            'color'    => 'Color',
            'textarea' => 'Textarea',
            'select'   => 'Select',
            'checkbox' => 'Checkbox',
            'toggle'   => 'Toggle (on/off)',
            'file'     => 'File',
        ];
        $this->log($request, 'membuka form edit Config Item', ['config.id' => $config->id]);
        return view('Config::config_item_edit', [
            'title'     => 'Edit Config Item',
            'config'    => $config,
            'groups'    => $groups,
            'formTypes' => $formTypes,
        ]);
    }

    public function updateItem(Request $request, Config $config)
    {
        $request->validate([
            'config_group_id'   => 'required|exists:config_group,id',
            'key'               => ['required', 'string', Rule::unique('config', 'key')->ignore($config->id)],
            'config_name'       => 'nullable|string',
            'default_value'     => 'nullable|string',
            'form_type'         => 'required|in:text,number,email,password,color,textarea,select,checkbox,toggle,file',
            'form_options'      => 'nullable|string',
            'is_multiple'       => 'nullable|in:0,1',
            'form_label'        => 'nullable|string',
            'form_placeholder'  => 'nullable|string',
            'form_help'         => 'nullable|string',
            'validation_rules'  => 'nullable|string',
            'urutan'            => 'nullable|integer',
            'is_tampil'         => 'nullable|in:0,1',
        ]);

        $formOptions = null;
        if (filled($request->form_options)) {
            $decoded = json_decode($request->form_options, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withInput()->withErrors(['form_options' => 'Format JSON tidak valid.']);
            }
            $formOptions = $decoded;
        }

        $config->config_group_id  = $request->config_group_id;
        $config->key               = $request->key;
        $config->config_name       = $request->config_name;
        $config->default_value     = $request->default_value;
        $config->form_type         = $request->form_type;
        $config->form_options      = $formOptions;
        $config->is_multiple       = $request->input('is_multiple', 0);
        $config->form_label        = $request->form_label;
        $config->form_placeholder  = $request->form_placeholder;
        $config->form_help         = $request->form_help;
        $config->validation_rules  = $request->validation_rules;
        $config->urutan            = $request->input('urutan', 0);
        $config->is_tampil         = $request->input('is_tampil', 1);
        $config->save();

        Cache::forget('laralag-config');

        $this->log($request, 'mengedit Config Item', ['config.id' => $config->id]);
        return redirect()->route('config-group.show', $config->config_group_id)
                         ->with('message_success', 'Config Item berhasil diubah!');
    }

    public function destroyItem(Request $request, Config $config)
    {
        $groupId = $config->config_group_id;
        $config->delete();
        Cache::forget('laralag-config');
        $this->log($request, 'menghapus Config Item', ['config.id' => $config->id]);
        return redirect()->route('config-group.show', $groupId)
                         ->with('message_success', 'Config Item berhasil dihapus!');
    }
}
