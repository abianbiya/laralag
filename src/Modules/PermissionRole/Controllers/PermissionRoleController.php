<?php
namespace Abianbiya\Laralag\Modules\PermissionRole\Controllers;

use Form;
use Abianbiya\Laralag\Helpers\Logger;
use Illuminate\Http\Request;
use Abianbiya\Laralag\Modules\Log\Models\Log;
use Abianbiya\Laralag\Modules\PermissionRole\Models\PermissionRole;
use Abianbiya\Laralag\Modules\Permission\Models\Permission;
use Abianbiya\Laralag\Modules\Role\Models\Role;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PermissionRoleController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Permission Role";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = PermissionRole::query();
		if($request->has('search')){
			$search = $request->get('search');
			$query->whereAny(['Permission', 'Role', ], 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('PermissionRole::permissionrole', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_permission = Permission::all()->pluck('slug','id');
		$ref_role = Role::all()->pluck('slug','id');
		
		$data['forms'] = array(
			'permission_id' => ['Permission', html()->select("permission_id", $ref_permission, old("permission_id"))->class("form-select select2")->required()],//->placeholder("-- Pilih Permission")],
            'role_id' => ['Role', html()->select("role_id", $ref_role, old("role_id"))->class("form-select select2")->required()],//->placeholder("-- Pilih Role")],
            
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('PermissionRole::permissionrole_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$request->validate([
			'permission_id' => 'required|string',
			'role_id' => 'required|string',
			
		]);

		$permissionrole = new PermissionRole();
		$permissionrole->permission_id = $request->input("permission_id");
        $permissionrole->role_id = $request->input("role_id");
        $permissionrole->save();

		$text = 'membuat '.$this->title; //' baru '.$permissionrole->what;
		$this->log($request, $text, ['permissionrole.id' => $permissionrole->id]);
		return redirect()->route($request->back ?? 'permissionrole.index')->with('message_success', 'Permission Role berhasil ditambahkan!');
	}

	public function show(Request $request, PermissionRole $permissionrole)
	{
		$data['permissionrole'] = $permissionrole;

		$text = 'melihat detail '.$this->title;//.' '.$permissionrole->what;
		$this->log($request, $text, ['permissionrole.id' => $permissionrole->id]);
		return view('PermissionRole::permissionrole_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, PermissionRole $permissionrole)
	{
		$data['permissionrole'] = $permissionrole;

		$ref_permission = Permission::all()->pluck('slug','id');
		$ref_role = Role::all()->pluck('slug','id');
		
		$data['forms'] = array(
			'permission_id' => ['Permission', html()->select("permission_id", $ref_permission, $permissionrole->permission_id)->class("form-select select2")->required()],//->placeholder("-- Pilih Permission")],
            'role_id' => ['Role', html()->select("role_id", $ref_role, $permissionrole->role_id)->class("form-select select2")->required()],//->placeholder("-- Pilih Role")],
            
		);

		$text = 'membuka form edit '.$this->title;//.' '.$permissionrole->what;
		$this->log($request, $text, ['permissionrole.id' => $permissionrole->id]);
		return view('PermissionRole::permissionrole_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'permission_id' => 'required|string',
			'role_id' => 'required|string',
			
		]);
		
		$permissionrole = PermissionRole::find($id);
		$permissionrole->permission_id = $request->input("permission_id");
        $permissionrole->role_id = $request->input("role_id");
        $permissionrole->save();


		$text = 'mengedit '.$this->title;//.' '.$permissionrole->what;
		$this->log($request, $text, ['permissionrole.id' => $permissionrole->id]);
		return redirect()->route($request->back ?? 'permissionrole.index')->with('message_success', 'Permission Role berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$permissionrole = PermissionRole::find($id);
		$permissionrole->delete();

		$text = 'menghapus '.$this->title;//.' '.$permissionrole->what;
		$this->log($request, $text, ['permissionrole.id' => $permissionrole->id]);
		return back()->with('message_success', 'Permission Role berhasil dihapus!');
	}

}
