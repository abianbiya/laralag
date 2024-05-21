<?php
namespace Abianbiya\Laralag\Modules\Module\Controllers;

use Form;
use Abianbiya\Laralag\Helpers\Logger;
use Illuminate\Http\Request;
use Abianbiya\Laralag\Modules\Log\Models\Log;
use Abianbiya\Laralag\Modules\Module\Models\Module;
use Abianbiya\Laralag\Modules\Menu\Models\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Module";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Module::query();
		if($request->has('search')){
			$search = $request->get('search');
			$query->whereAny(['Menu', 'Nama', 'Routing', 'Permission', 'Urutan', 'Is Tampil', ], 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Module::module', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_menu = Menu::all()->pluck('nama','id');
		$ref_is_tampil = ["1" => "Ya", "0" => "Tidak"];
		
		$data['forms'] = array(
			'menu_id' => ['Menu', html()->select("menu_id", $ref_menu, old("menu_id", $request->menu_id))->class("form-select select2")->required()],//->placeholder("-- Pilih Menu")],
            'nama' => ['Nama', html()->text("nama", old("nama", $ref_menu[$request->menu_id]))->class("form-control")->placeholder("")],
            'routing' => ['Routing', html()->text("routing", old("routing"))->class("form-control routing")->placeholder("contoh: user.index")],
            'permission' => ['Permission', html()->text("permission", old("permission"))->class("form-control permission")->placeholder("Harus sama dengan routing")->attribute("readonly", true)],
            'urutan' => ['Urutan', html()->text("urutan", old("urutan"))->class("form-control")->placeholder("")->required()],
            'is_tampil' => ['Is Tampil', html()->select("is_tampil", $ref_is_tampil, old("is_tampil"))->class("form-select")->required()],
            
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Module::module_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$request->validate([
			'menu_id' => 'required|string',
			'nama' => 'nullable|string',
			'routing' => 'nullable|string',
			'permission' => 'nullable|string',
			'urutan' => 'required|string',
			'is_tampil' => 'required|string',
			
		]);

		$module = new Module();
		$module->menu_id = $request->input("menu_id");
        $module->nama = $request->input("nama");
        $module->routing = $request->input("routing");
        $module->permission = $request->input("permission");
        $module->urutan = $request->input("urutan");
        $module->is_tampil = $request->input("is_tampil");
        $module->save();

		$text = 'membuat '.$this->title; //' baru '.$module->what;
		$this->log($request, $text, ['module.id' => $module->id]);
		return redirect()->route($request->back ?? 'module.index')->with('message_success', 'Module berhasil ditambahkan!');
	}

	public function show(Request $request, Module $module)
	{
		$data['module'] = $module;

		$text = 'melihat detail '.$this->title;//.' '.$module->what;
		$this->log($request, $text, ['module.id' => $module->id]);
		return view('Module::module_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Module $module)
	{
		$data['module'] = $module;

		$ref_menu = Menu::all()->pluck('nama','id');
		$ref_is_tampil = ["1" => "Ya", "0" => "Tidak"];
		
		$data['forms'] = array(
			'menu_id' => ['Menu', html()->select("menu_id", $ref_menu, $module->menu_id)->class("form-select select2")->required()],//->placeholder("-- Pilih Menu")],
            'nama' => ['Nama', html()->text("nama", $module->nama)->class("form-control")->placeholder("")],
            'routing' => ['Routing', html()->text("routing", $module->routing)->class("form-control")->placeholder("")],
            'permission' => ['Permission', html()->text("permission", $module->permission)->class("form-control")->placeholder("")],
            'urutan' => ['Urutan', html()->text("urutan", $module->urutan)->class("form-control")->placeholder("")->required()],
            'is_tampil' => ['Is Tampil', html()->select("is_tampil", $ref_is_tampil, $module->is_tampil)->class("form-select")->required()],
            
		);

		$text = 'membuka form edit '.$this->title;//.' '.$module->what;
		$this->log($request, $text, ['module.id' => $module->id]);
		return view('Module::module_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'menu_id' => 'required|string',
			'nama' => 'nullable|string',
			'routing' => 'nullable|string',
			'permission' => 'nullable|string',
			'urutan' => 'required|string',
			'is_tampil' => 'required|string',
			
		]);
		
		$module = Module::find($id);
		$module->menu_id = $request->input("menu_id");
        $module->nama = $request->input("nama");
        $module->routing = $request->input("routing");
        $module->permission = $request->input("permission");
        $module->urutan = $request->input("urutan");
        $module->is_tampil = $request->input("is_tampil");
        $module->save();


		$text = 'mengedit '.$this->title;//.' '.$module->what;
		$this->log($request, $text, ['module.id' => $module->id]);
		return redirect()->route($request->back ?? 'module.index')->with('message_success', 'Module berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$module = Module::find($id);
		$module->delete();

		$text = 'menghapus '.$this->title;//.' '.$module->what;
		$this->log($request, $text, ['module.id' => $module->id]);
		return back()->with('message_success', 'Module berhasil dihapus!');
	}

}
