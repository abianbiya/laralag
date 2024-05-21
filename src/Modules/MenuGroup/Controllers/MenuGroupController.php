<?php
namespace Abianbiya\Laralag\Modules\MenuGroup\Controllers;

use Form;
use Abianbiya\Laralag\Helpers\Logger;
use Illuminate\Http\Request;
use Abianbiya\Laralag\Modules\Log\Models\Log;
use Abianbiya\Laralag\Modules\MenuGroup\Models\MenuGroup;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MenuGroupController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Menu Group";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = MenuGroup::query();
		if($request->has('search')){
			$search = $request->get('search');
			$query->whereAny(['Nama', 'Nama En', 'Urutan', 'Is Tampil', ], 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('MenuGroup::menugroup', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_is_tampil = ["1" => "Ya", "0" => "Tidak"];
		
		$data['forms'] = array(
			'nama' => ['Nama', html()->text("nama", old("nama"))->class("form-control")->placeholder("")],
            'nama_en' => ['Nama En', html()->text("nama_en", old("nama_en"))->class("form-control")->placeholder("")],
            'urutan' => ['Urutan', html()->text("urutan", old("urutan"))->class("form-control")->placeholder("")->required()],
            'is_tampil' => ['Is Tampil', html()->select("is_tampil", $ref_is_tampil, old("is_tampil"))->class("form-select")->required()],
            
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('MenuGroup::menugroup_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$request->validate([
			'nama' => 'nullable|string',
			'nama_en' => 'nullable|string',
			'urutan' => 'required|string',
			'is_tampil' => 'required|string',
			
		]);

		$menugroup = new MenuGroup();
		$menugroup->nama = $request->input("nama");
        $menugroup->nama_en = $request->input("nama_en");
        $menugroup->urutan = $request->input("urutan");
        $menugroup->is_tampil = $request->input("is_tampil");
        $menugroup->save();

		$text = 'membuat '.$this->title; //' baru '.$menugroup->what;
		$this->log($request, $text, ['menugroup.id' => $menugroup->id]);
		return redirect()->route($request->back ?? 'menugroup.index')->with('message_success', 'Menu Group berhasil ditambahkan!');
	}

	public function show(Request $request, MenuGroup $menugroup)
	{
		$data['menugroup'] = $menugroup;

		$text = 'melihat detail '.$this->title;//.' '.$menugroup->what;
		$this->log($request, $text, ['menugroup.id' => $menugroup->id]);
		return view('MenuGroup::menugroup_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, MenuGroup $menugroup)
	{
		$data['menugroup'] = $menugroup;

		$ref_is_tampil = ["1" => "Ya", "0" => "Tidak"];
		
		$data['forms'] = array(
			'nama' => ['Nama', html()->text("nama", $menugroup->nama)->class("form-control")->placeholder("")],
            'nama_en' => ['Nama En', html()->text("nama_en", $menugroup->nama_en)->class("form-control")->placeholder("")],
            'urutan' => ['Urutan', html()->text("urutan", $menugroup->urutan)->class("form-control")->placeholder("")->required()],
            'is_tampil' => ['Is Tampil', html()->select("is_tampil", $ref_is_tampil, $menugroup->is_tampil)->class("form-select")->required()],
            
		);

		$text = 'membuka form edit '.$this->title;//.' '.$menugroup->what;
		$this->log($request, $text, ['menugroup.id' => $menugroup->id]);
		return view('MenuGroup::menugroup_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'nama' => 'nullable|string',
			'nama_en' => 'nullable|string',
			'urutan' => 'required|string',
			'is_tampil' => 'required|string',
			
		]);
		
		$menugroup = MenuGroup::find($id);
		$menugroup->nama = $request->input("nama");
        $menugroup->nama_en = $request->input("nama_en");
        $menugroup->urutan = $request->input("urutan");
        $menugroup->is_tampil = $request->input("is_tampil");
        $menugroup->save();


		$text = 'mengedit '.$this->title;//.' '.$menugroup->what;
		$this->log($request, $text, ['menugroup.id' => $menugroup->id]);
		return redirect()->route($request->back ?? 'menugroup.index')->with('message_success', 'Menu Group berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$menugroup = MenuGroup::find($id);
		$menugroup->delete();

		$text = 'menghapus '.$this->title;//.' '.$menugroup->what;
		$this->log($request, $text, ['menugroup.id' => $menugroup->id]);
		return back()->with('message_success', 'Menu Group berhasil dihapus!');
	}

}
