<?php
namespace Abianbiya\Laralag\Modules\RoleUser\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Abianbiya\Laralag\Helpers\Logger;
use Abianbiya\Laralag\Modules\Log\Models\Log;

use Abianbiya\Laralag\Modules\Role\Models\Role;
use Abianbiya\Laralag\Modules\User\Models\User;
use Abianbiya\Laralag\Modules\Scope\Models\Scope;
use Abianbiya\Laralag\Modules\RoleUser\Models\RoleUser;

class RoleUserController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Role User";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = RoleUser::with('role');
		if($request->has('search')){
			$search = $request->get('search');
			$query->whereAny(['Role', 'User', 'Scope', ], 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('RoleUser::roleuser', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		$ref_role = Role::all()->pluck('slug','id');
		$ref_scope = Scope::all()->pluck('slug','id');

		if($request->has('user_id')){
			$user_id = $request->get('user_id');
			$user = User::find($user_id);
		}else{
			return redirect()->back()->with('message_danger', 'User ID tidak ditemukan!');
		}
		
		$data['forms'] = array(
            'user_id' => ['', html()->hidden("user_id", $user->id)],
            'user_id_name' => ['User', html()->text("user_id", $user->name)->class("form-control")->placeholder("")->disabled()->isReadonly(true)],
			'role_id' => ['Role', html()->select("role_id", $ref_role, old("role_id"))->class("form-select")->required()],//->placeholder("-- Pilih Role")],
            'scope_id' => ['Scope', html()->select("scope_id", $ref_scope, old("scope_id"))->class("form-select select2")],//->placeholder("-- Pilih Scope")],
            
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('RoleUser::roleuser_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$request->validate([
			'role_id' => 'required|string',
			'user_id' => 'required|string',
			'scope_id' => 'nullable|string',
			
		]);

		$roleuser = RoleUser::firstOrNew([
			'role_id' => $request->input("role_id"),
			'user_id' => $request->input("user_id"),
			'scope_id' => $request->input("scope_id"),
		]);
		$roleuser->role_id = $request->input("role_id");
        $roleuser->user_id = $request->input("user_id");
        $roleuser->scope_id = $request->input("scope_id");
        $roleuser->save();

		$text = 'membuat '.$this->title; //' baru '.$roleuser->what;
		$this->log($request, $text, ['roleuser.id' => $roleuser->id]);
		return redirect()->route($request->back ?? 'roleuser.index')->with('message_success', 'Role User berhasil ditambahkan!');
	}

	public function show(Request $request, RoleUser $roleuser)
	{
		$data['roleuser'] = $roleuser;

		$text = 'melihat detail '.$this->title;//.' '.$roleuser->what;
		$this->log($request, $text, ['roleuser.id' => $roleuser->id]);
		return view('RoleUser::roleuser_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, RoleUser $roleuser)
	{
		$data['roleuser'] = $roleuser;

		$ref_role = Role::all()->pluck('slug','id');
		$ref_scope = Scope::all()->pluck('slug','id');
		
		$data['forms'] = array(
			'role_id' => ['Role', html()->select("role_id", $ref_role, $roleuser->role_id)->class("form-select select2")->required()],//->placeholder("-- Pilih Role")],
            'user_id' => ['User', html()->text("user_id", $roleuser->user_id)->class("form-control")->placeholder("")->required()],
            'scope_id' => ['Scope', html()->select("scope_id", $ref_scope, $roleuser->scope_id)->class("form-select select2")],//->placeholder("-- Pilih Scope")],
            
		);

		$text = 'membuka form edit '.$this->title;//.' '.$roleuser->what;
		$this->log($request, $text, ['roleuser.id' => $roleuser->id]);
		return view('RoleUser::roleuser_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'role_id' => 'required|string',
			'user_id' => 'required|string',
			'scope_id' => 'nullable|string',
			
		]);
		
		$roleuser = RoleUser::find($id);
		$roleuser->role_id = $request->input("role_id");
        $roleuser->user_id = $request->input("user_id");
        $roleuser->scope_id = $request->input("scope_id");
        $roleuser->save();


		$text = 'mengedit '.$this->title;//.' '.$roleuser->what;
		$this->log($request, $text, ['roleuser.id' => $roleuser->id]);
		return redirect()->route($request->back ?? 'roleuser.index')->with('message_success', 'Role User berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		// $roleuser = RoleUser::whereUserId($userId)->whereRoleId($roleId)->when($request->filled('scopeId'), function($q){
		// 	$q->where('scope_id', request('scopeId'));
		// })->first();
		$roleuser = RoleUser::find($id);
		$roleuser->delete();

		$text = 'menghapus '.$this->title;//.' '.$roleuser->what;
		$this->log($request, $text, ['roleuser.id' => $roleuser->id]);
		return back()->with('message_success', 'Role User berhasil dihapus!');
	}

}
