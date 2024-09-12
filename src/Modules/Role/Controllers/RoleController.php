<?php
namespace Abianbiya\Laralag\Modules\Role\Controllers;

use Form;
use Abianbiya\Laralag\Helpers\Logger;
use Abianbiya\Laralag\Modules\Permission\Models\Permission;
use Illuminate\Http\Request;
use Abianbiya\Laralag\Modules\Log\Models\Log;

use Abianbiya\Laralag\Modules\Role\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Abianbiya\Laralag\Modules\Module\Models\Module;

class RoleController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Role";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Role::query();
		if($request->has('search')){
			$search = $request->get('search');
			$query->whereAny(['Slug', 'Nama', 'Tags', ], 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Role::role', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			'slug' => ['Slug', html()->text("slug", old("slug"))->class("form-control")->placeholder("")->required()],
            'nama' => ['Nama', html()->text("nama", old("nama"))->class("form-control")->placeholder("")],
            'tags' => ['Tags', html()->text("tags", old("tags"))->class("form-control")->placeholder("")],
            
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Role::role_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$request->validate([
			'slug' => 'required|string',
			'nama' => 'nullable|string',
			'tags' => 'nullable|string',
			
		]);

		$role = new Role();
		$role->slug = $request->input("slug");
        $role->nama = $request->input("nama");
        $role->tags = $request->input("tags");
        $role->save();

		$text = 'membuat '.$this->title; //' baru '.$role->what;
		$this->log($request, $text, ['role.id' => $role->id]);
		return redirect()->route('role.index')->with('message_success', 'Role berhasil ditambahkan!');
	}

	public function show(Request $request, Role $role)
	{
		$data['role'] = $role;
		$data['permissions'] = Permission::get(['id', 'slug', 'nama', 'action', 'group'])->groupBy('group');
		// dd($data['permissions']);

		$text = 'melihat detail '.$this->title;//.' '.$role->what;
		$this->log($request, $text, ['role.id' => $role->id]);
		return view('Role::role_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Role $role)
	{
		$data['role'] = $role;

		
		$data['forms'] = array(
			'slug' => ['Slug', html()->text("slug", $role->slug)->class("form-control")->placeholder("")->required()],
            'nama' => ['Nama', html()->text("nama", $role->nama)->class("form-control")->placeholder("")],
            'tags' => ['Tags', html()->text("tags", $role->tags)->class("form-control")->placeholder("")],
            
		);

		$text = 'membuka form edit '.$this->title;//.' '.$role->what;
		$this->log($request, $text, ['role.id' => $role->id]);
		return view('Role::role_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'slug' => 'required|string',
			'nama' => 'nullable|string',
			'tags' => 'nullable|string',
			
		]);
		
		$role = Role::find($id);
		$role->slug = $request->input("slug");
        $role->nama = $request->input("nama");
        $role->tags = $request->input("tags");
        $role->save();


		$text = 'mengedit '.$this->title;//.' '.$role->what;
		$this->log($request, $text, ['role.id' => $role->id]);
		return redirect()->route('role.index')->with('message_success', 'Role berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$role = Role::find($id);
		$role->delete();

		$text = 'menghapus '.$this->title;//.' '.$role->what;
		$this->log($request, $text, ['role.id' => $role->id]);
		return back()->with('message_success', 'Role berhasil dihapus!');
	}

	public function updatePermission(Request $request)
	{
		$request->validate([
			'role_id' => 'required',
			'permissions' => 'nullable|array',
		]);

		$permissions = $request->permissions ?? [];
		$role = Role::find($request->role_id);
		$role->permission()->sync(array_keys($permissions));

		Auth::user()->syncPermission($request->role_id);

		return back()->with('message_success', 'Role berhasil disimpan!');
	}
	
}
