<?php
namespace Abianbiya\Laralag\Modules\Menu\Controllers;

use Form;
use Abianbiya\Laralag\Helpers\Logger;
use Illuminate\Http\Request;
use Abianbiya\Laralag\Modules\Log\Models\Log;
use Abianbiya\Laralag\Modules\Menu\Models\Menu;
use Abianbiya\Laralag\Modules\MenuGroup\Models\MenuGroup;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Menu";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$data['menuGroups'] = MenuGroup::with('menu.module')->orderBy('urutan')->get();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Menu::menu', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_menu_group = MenuGroup::all()->pluck('nama','id');
		$ref_is_tampil = ["1" => "Ya", "0" => "Tidak"];
		
		$data['forms'] = array(
			'menu_group_id' => ['Menu Group', html()->select("menu_group_id", $ref_menu_group, old("menu_group_id", $request->menuGroup))->class("form-select select2")->required()],//->placeholder("-- Pilih Menu Group")],
            'nama' => ['Nama', html()->text("nama", old("nama"))->class("form-control")->placeholder("")],
            'nama_en' => ['Nama En', html()->text("nama_en", old("nama_en"))->class("form-control")->placeholder("")],
            'icon' => ['Icon', html()->text("icon", old("icon"))->class("form-control")->placeholder("")],
            'urutan' => ['Urutan', html()->text("urutan", old("urutan"))->class("form-control")->placeholder("")->required()],
            'is_tampil' => ['Is Tampil', html()->select("is_tampil", $ref_is_tampil, old("is_tampil"))->class("form-select")->required()],
            
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Menu::menu_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$request->validate([
			'menu_group_id' => 'required|string',
			'nama' => 'nullable|string',
			'nama_en' => 'nullable|string',
			'icon' => 'nullable|string',
			'urutan' => 'required|string',
			'is_tampil' => 'required|string',
			
		]);

		$menu = new Menu();
		$menu->menu_group_id = $request->input("menu_group_id");
        $menu->nama = $request->input("nama");
        $menu->nama_en = $request->input("nama_en");
        $menu->icon = $request->input("icon");
        $menu->urutan = $request->input("urutan");
        $menu->is_tampil = $request->input("is_tampil");
        $menu->save();

		$text = 'membuat '.$this->title; //' baru '.$menu->what;
		$this->log($request, $text, ['menu.id' => $menu->id]);
		return redirect()->route('menu.index')->with('message_success', 'Menu berhasil ditambahkan!');
	}

	public function show(Request $request, Menu $menu)
	{
		$data['menu'] = $menu;

		$text = 'melihat detail '.$this->title;//.' '.$menu->what;
		$this->log($request, $text, ['menu.id' => $menu->id]);
		return view('Menu::menu_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Menu $menu)
	{
		$data['menu'] = $menu;

		$ref_menu_group = MenuGroup::all()->pluck('nama','id');
		$ref_is_tampil = ["1" => "Ya", "0" => "Tidak"];
		
		$data['forms'] = array(
			'menu_group_id' => ['Menu Group', html()->select("menu_group_id", $ref_menu_group, $menu->menu_group_id)->class("form-select select2")->required()],//->placeholder("-- Pilih Menu Group")],
            'nama' => ['Nama', html()->text("nama", $menu->nama)->class("form-control")->placeholder("")],
            'nama_en' => ['Nama En', html()->text("nama_en", $menu->nama_en)->class("form-control")->placeholder("")],
            'icon' => ['Icon', html()->text("icon", $menu->icon)->class("form-control")->placeholder("")],
            'urutan' => ['Urutan', html()->text("urutan", $menu->urutan)->class("form-control")->placeholder("")->required()],
            'is_tampil' => ['Is Tampil', html()->select("is_tampil", $ref_is_tampil, $menu->is_tampil)->class("form-select")->required()],
            
		);

		$text = 'membuka form edit '.$this->title;//.' '.$menu->what;
		$this->log($request, $text, ['menu.id' => $menu->id]);
		return view('Menu::menu_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'menu_group_id' => 'required|string',
			'nama' => 'nullable|string',
			'nama_en' => 'nullable|string',
			'icon' => 'nullable|string',
			'urutan' => 'required|string',
			'is_tampil' => 'required|string',
			
		]);
		
		$menu = Menu::find($id);
		$menu->menu_group_id = $request->input("menu_group_id");
        $menu->nama = $request->input("nama");
        $menu->nama_en = $request->input("nama_en");
        $menu->icon = $request->input("icon");
        $menu->urutan = $request->input("urutan");
        $menu->is_tampil = $request->input("is_tampil");
        $menu->save();


		$text = 'mengedit '.$this->title;//.' '.$menu->what;
		$this->log($request, $text, ['menu.id' => $menu->id]);
		return redirect()->route('menu.index')->with('message_success', 'Menu berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$menu = Menu::find($id);
		$menu->delete();

		$text = 'menghapus '.$this->title;//.' '.$menu->what;
		$this->log($request, $text, ['menu.id' => $menu->id]);
		return back()->with('message_success', 'Menu berhasil dihapus!');
	}

}
