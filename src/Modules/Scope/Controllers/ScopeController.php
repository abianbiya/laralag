<?php
namespace Abianbiya\Laralag\Modules\Scope\Controllers;

use Form;
use Abianbiya\Laralag\Helpers\Logger;
use Illuminate\Http\Request;
use Abianbiya\Laralag\Modules\Log\Models\Log;
use Abianbiya\Laralag\Modules\Scope\Models\Scope;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ScopeController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Scope";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Scope::query();
		if($request->has('search')){
			$search = $request->get('search');
			$query->whereAny(['Slug', 'Nama', 'Akronim', 'Kode', ], 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Scope::scope', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			'slug' => ['Slug', html()->text("slug", old("slug"))->class("form-control")->placeholder("")->required()],
            'nama' => ['Nama', html()->text("nama", old("nama"))->class("form-control")->placeholder("")],
            'akronim' => ['Akronim', html()->text("akronim", old("akronim"))->class("form-control")->placeholder("")],
            'kode' => ['Kode', html()->text("kode", old("kode"))->class("form-control")->placeholder("")],
            
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Scope::scope_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$request->validate([
			'slug' => 'required|string',
			'nama' => 'nullable|string',
			'akronim' => 'nullable|string',
			'kode' => 'nullable|string',
			
		]);

		$scope = new Scope();
		$scope->slug = $request->input("slug");
        $scope->nama = $request->input("nama");
        $scope->akronim = $request->input("akronim");
        $scope->kode = $request->input("kode");
        $scope->save();

		$text = 'membuat '.$this->title; //' baru '.$scope->what;
		$this->log($request, $text, ['scope.id' => $scope->id]);
		return redirect()->route($request->back ?? 'scope.index')->with('message_success', 'Scope berhasil ditambahkan!');
	}

	public function show(Request $request, Scope $scope)
	{
		$data['scope'] = $scope;

		$text = 'melihat detail '.$this->title;//.' '.$scope->what;
		$this->log($request, $text, ['scope.id' => $scope->id]);
		return view('Scope::scope_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Scope $scope)
	{
		$data['scope'] = $scope;

		
		$data['forms'] = array(
			'slug' => ['Slug', html()->text("slug", $scope->slug)->class("form-control")->placeholder("")->required()],
            'nama' => ['Nama', html()->text("nama", $scope->nama)->class("form-control")->placeholder("")],
            'akronim' => ['Akronim', html()->text("akronim", $scope->akronim)->class("form-control")->placeholder("")],
            'kode' => ['Kode', html()->text("kode", $scope->kode)->class("form-control")->placeholder("")],
            
		);

		$text = 'membuka form edit '.$this->title;//.' '.$scope->what;
		$this->log($request, $text, ['scope.id' => $scope->id]);
		return view('Scope::scope_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'slug' => 'required|string',
			'nama' => 'nullable|string',
			'akronim' => 'nullable|string',
			'kode' => 'nullable|string',
			
		]);
		
		$scope = Scope::find($id);
		$scope->slug = $request->input("slug");
        $scope->nama = $request->input("nama");
        $scope->akronim = $request->input("akronim");
        $scope->kode = $request->input("kode");
        $scope->save();


		$text = 'mengedit '.$this->title;//.' '.$scope->what;
		$this->log($request, $text, ['scope.id' => $scope->id]);
		return redirect()->route($request->back ?? 'scope.index')->with('message_success', 'Scope berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$scope = Scope::find($id);
		$scope->delete();

		$text = 'menghapus '.$this->title;//.' '.$scope->what;
		$this->log($request, $text, ['scope.id' => $scope->id]);
		return back()->with('message_success', 'Scope berhasil dihapus!');
	}

}
