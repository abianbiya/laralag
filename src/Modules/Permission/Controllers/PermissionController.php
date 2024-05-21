<?php
namespace Abianbiya\Laralag\Modules\Permission\Controllers;

use Form;
use Abianbiya\Laralag\Helpers\Logger;
use Illuminate\Http\Request;
use Abianbiya\Laralag\Modules\Log\Models\Log;
use Abianbiya\Laralag\Modules\Permission\Models\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Permission";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Permission::query();
		if($request->has('search')){
			$search = $request->get('search');
			$query->whereAny(['Slug', 'Nama', 'Action', ], 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Permission::permission', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			'slug' => ['Slug', html()->text("slug", old("slug"))->class("form-control")->placeholder("")->required()],
            'nama' => ['Nama', html()->text("nama", old("nama"))->class("form-control")->placeholder("")],
            'action' => ['Action', html()->text("action", old("action"))->class("form-control")->placeholder("")],
            
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Permission::permission_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$request->validate([
			'slug' => 'required|string',
			'nama' => 'nullable|string',
			'action' => 'nullable|string',
			
		]);

		$permission = new Permission();
		$permission->slug = $request->input("slug");
        $permission->nama = $request->input("nama");
        $permission->action = $request->input("action");
        $permission->save();

		$text = 'membuat '.$this->title; //' baru '.$permission->what;
		$this->log($request, $text, ['permission.id' => $permission->id]);
		return redirect()->route($request->back ?? 'permission.index')->with('message_success', 'Permission berhasil ditambahkan!');
	}

	public function show(Request $request, Permission $permission)
	{
		$data['permission'] = $permission;

		$text = 'melihat detail '.$this->title;//.' '.$permission->what;
		$this->log($request, $text, ['permission.id' => $permission->id]);
		return view('Permission::permission_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Permission $permission)
	{
		$data['permission'] = $permission;

		
		$data['forms'] = array(
			'slug' => ['Slug', html()->text("slug", $permission->slug)->class("form-control")->placeholder("")->required()],
            'nama' => ['Nama', html()->text("nama", $permission->nama)->class("form-control")->placeholder("")],
            'action' => ['Action', html()->text("action", $permission->action)->class("form-control")->placeholder("")],
            
		);

		$text = 'membuka form edit '.$this->title;//.' '.$permission->what;
		$this->log($request, $text, ['permission.id' => $permission->id]);
		return view('Permission::permission_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'slug' => 'required|string',
			'nama' => 'nullable|string',
			'action' => 'nullable|string',
			
		]);
		
		$permission = Permission::find($id);
		$permission->slug = $request->input("slug");
        $permission->nama = $request->input("nama");
        $permission->action = $request->input("action");
        $permission->save();


		$text = 'mengedit '.$this->title;//.' '.$permission->what;
		$this->log($request, $text, ['permission.id' => $permission->id]);
		return redirect()->route($request->back ?? 'permission.index')->with('message_success', 'Permission berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$permission = Permission::find($id);
		$permission->delete();

		$text = 'menghapus '.$this->title;//.' '.$permission->what;
		$this->log($request, $text, ['permission.id' => $permission->id]);
		return back()->with('message_success', 'Permission berhasil dihapus!');
	}

}
