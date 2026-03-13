<?php
namespace Abianbiya\Laralag\Modules\ConfigGroup\Controllers;

use Abianbiya\Laralag\Helpers\Logger;
use Illuminate\Http\Request;
use Abianbiya\Laralag\Modules\Log\Models\Log;
use Abianbiya\Laralag\Modules\ConfigGroup\Models\ConfigGroup;
use Abianbiya\Laralag\Modules\Permission\Models\Permission;
use App\Http\Controllers\Controller;

class ConfigGroupController extends Controller
{
    use Logger;
    protected $log;
    protected $title = "Config Group";

    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function index(Request $request)
    {
        $query = ConfigGroup::query();
        if($request->has('search')){
            $search = $request->get('search');
            $query->whereAny(['slug', 'nama'], 'like', "%$search%");
        }
        $data['data'] = $query->paginate(10)->withQueryString();
        $this->log($request, 'melihat halaman manajemen data '.$this->title);
        return view('ConfigGroup::config_group', array_merge($data, ['title' => $this->title]));
    }

    public function create(Request $request)
    {
        $data['forms'] = array(
            'slug'      => ['Slug', html()->text("slug", old("slug"))->class("form-control")->placeholder("")->required()],
            'nama'      => ['Nama', html()->text("nama", old("nama"))->class("form-control")->placeholder("")],
            'urutan'    => ['Urutan', html()->number("urutan", old("urutan"))->class("form-control")->placeholder("")],
            'icon'      => ['Icon', html()->text("icon", old("icon"))->class("form-control")->placeholder("")],
            'is_tampil' => ['Tampil', html()->select("is_tampil", [1 => 'Ya', 0 => 'Tidak'], old("is_tampil", 1))->class("form-control")],
        );
        $this->log($request, 'membuka form tambah '.$this->title);
        return view('ConfigGroup::config_group_create', array_merge($data, ['title' => $this->title]));
    }

    public function store(Request $request)
    {
        $request->validate([
            'slug'      => 'required|string|unique:config_group,slug',
            'nama'      => 'nullable|string',
            'urutan'    => 'nullable|integer',
            'icon'      => 'nullable|string',
            'is_tampil' => 'nullable|in:0,1',
        ]);
        $configGroup = new ConfigGroup();
        $configGroup->slug      = $request->input("slug");
        $configGroup->nama      = $request->input("nama");
        $configGroup->urutan    = $request->input("urutan");
        $configGroup->icon      = $request->input("icon");
        $configGroup->is_tampil = $request->input("is_tampil");
        $configGroup->save();

        // Auto-generate permissions for this config group
        $slug = $configGroup->slug;
        $permIndex = Permission::firstOrCreate(
            ['slug' => "config-{$slug}.index"],
            ['nama' => "Melihat pengaturan {$configGroup->nama}", 'action' => 'index', 'group' => "config-{$slug}"]
        );
        $permUpdate = Permission::firstOrCreate(
            ['slug' => "config-{$slug}.update"],
            ['nama' => "Mengubah pengaturan {$configGroup->nama}", 'action' => 'update', 'group' => "config-{$slug}"]
        );
        // Assign to root role (find root role by slug='root')
        $rootRole = \Abianbiya\Laralag\Modules\Role\Models\Role::where('slug', 'root')->first();
        if ($rootRole) {
            $rootRole->permission()->syncWithoutDetaching([$permIndex->id, $permUpdate->id]);
        }

        $this->log($request, 'membuat '.$this->title, ['configGroup.id' => $configGroup->id]);
        return redirect()->route('config-group.index')->with('message_success', 'Config Group berhasil ditambahkan!');
    }

    public function show(Request $request, ConfigGroup $configGroup)
    {
        $data['configGroup'] = $configGroup;
        $this->log($request, 'melihat detail '.$this->title, ['configGroup.id' => $configGroup->id]);
        return view('ConfigGroup::config_group_detail', array_merge($data, ['title' => $this->title]));
    }

    public function edit(Request $request, ConfigGroup $configGroup)
    {
        $data['configGroup'] = $configGroup;
        $data['forms'] = array(
            'slug'      => ['Slug', html()->text("slug", $configGroup->slug)->class("form-control")->required()],
            'nama'      => ['Nama', html()->text("nama", $configGroup->nama)->class("form-control")],
            'urutan'    => ['Urutan', html()->number("urutan", $configGroup->urutan)->class("form-control")],
            'icon'      => ['Icon', html()->text("icon", $configGroup->icon)->class("form-control")],
            'is_tampil' => ['Tampil', html()->select("is_tampil", [1 => 'Ya', 0 => 'Tidak'], $configGroup->is_tampil)->class("form-control")],
        );
        $this->log($request, 'membuka form edit '.$this->title, ['configGroup.id' => $configGroup->id]);
        return view('ConfigGroup::config_group_update', array_merge($data, ['title' => $this->title]));
    }

    public function update(Request $request, ConfigGroup $configGroup)
    {
        $request->validate([
            'slug'      => 'required|string|unique:config_group,slug,'.$configGroup->id,
            'nama'      => 'nullable|string',
            'urutan'    => 'nullable|integer',
            'icon'      => 'nullable|string',
            'is_tampil' => 'nullable|in:0,1',
        ]);
        $configGroup->slug      = $request->input("slug");
        $configGroup->nama      = $request->input("nama");
        $configGroup->urutan    = $request->input("urutan");
        $configGroup->icon      = $request->input("icon");
        $configGroup->is_tampil = $request->input("is_tampil");
        $configGroup->save();
        $this->log($request, 'mengedit '.$this->title, ['configGroup.id' => $configGroup->id]);
        return redirect()->route('config-group.index')->with('message_success', 'Config Group berhasil diubah!');
    }

    public function destroy(Request $request, ConfigGroup $configGroup)
    {
        $configGroup->configs()->delete();
        // Remove associated permissions from permission_role and soft-delete them
        $slugPrefix = "config-{$configGroup->slug}.";
        Permission::where('slug', 'like', $slugPrefix . '%')->delete();
        $configGroup->delete();
        $this->log($request, 'menghapus '.$this->title, ['configGroup.id' => $configGroup->id]);
        return back()->with('message_success', 'Config Group berhasil dihapus!');
    }
}
