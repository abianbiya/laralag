<?php
namespace Abianbiya\Laralag\Modules\User\Controllers;

use Form;
use Abianbiya\Laralag\Modules\User\Models\User;
use Abianbiya\Laralag\Helpers\Logger;
use Illuminate\Http\Request;
use Abianbiya\Laralag\Modules\Log\Models\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "User";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = User::with('roleUser.role', 'roleUser.scope');
		if($request->has('search')){
			$search = $request->get('search');
			$query->whereAny(['Username', 'Email', 'Name', 'Identitas'], 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('User::user', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			'username' => ['Username', html()->text("username", old("username"))->class("form-control")->placeholder("")->required()],
            'email' => ['Email', html()->text("email", old("email"))->class("form-control")->placeholder("")->required()],
            'name' => ['Name', html()->text("name", old("name"))->class("form-control")->placeholder("")->required()],
			'password' => ['Password', html()->text("password", '')->class("form-control")->placeholder("")],
            'identitas' => ['Nomor Identitas', html()->text("identitas", old("identitas"))->class("form-control")->placeholder("")],
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('User::user_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$request->validate([
			'username' => 'required|string',
			'email' => 'required|string',
			'name' => 'required|string',
			'password' => 'required|string',
			'identitas' => 'nullable|string',
		]);

		$user = new User();
		$user->username = $request->input("username");
        $user->email = $request->input("email");
        $user->name = $request->input("name");
        $user->password = bcrypt($request->input("password"));
        $user->identitas = $request->input("identitas");
        $user->save();

		$text = 'membuat '.$this->title; //' baru '.$user->what;
		$this->log($request, $text, ['user.id' => $user->id]);
		return redirect()->route($request->back ?? 'user.index')->with('message_success', 'User berhasil ditambahkan!');
	}

	public function show(Request $request, User $user)
	{
		$data['user'] = User::with(['roleUser.role', 'roleUser.scope'])->find($user->id);

		$text = 'melihat detail '.$this->title;//.' '.$user->what;
		$this->log($request, $text, ['user.id' => $user->id]);
		return view('User::user_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, User $user)
	{
		$data['user'] = $user;

		
		$data['forms'] = array(
			'username' => ['Username', html()->text("username", $user->username)->class("form-control")->placeholder("")->required()],
            'email' => ['Email', html()->text("email", $user->email)->class("form-control")->placeholder("")->required()],
            'name' => ['Name', html()->text("name", $user->name)->class("form-control")->placeholder("")->required()],
            'password' => ['Password', html()->text("password", '')->class("form-control")->placeholder("Kosongkan jika tidak ingin mengubah")],
            'identitas' => ['Nomor Identitas', html()->text("identitas", $user->identitas)->class("form-control")->placeholder("")],
		);

		$text = 'membuka form edit '.$this->title;//.' '.$user->what;
		$this->log($request, $text, ['user.id' => $user->id]);
		return view('User::user_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'username' => 'required|string',
			'email' => 'required|string',
			'name' => 'required|string',
			'password' => 'nullable|string',
			'identitas' => 'nullable|string',
		]);
		
		$user = User::find($id);
		$user->username = $request->input("username");
        $user->email = $request->input("email");
        $user->name = $request->input("name");
		if(filled($request->input("password"))) $user->password = bcrypt($request->input("password"));
        $user->identitas = $request->input("identitas");
        $user->save();


		$text = 'mengedit '.$this->title;//.' '.$user->what;
		$this->log($request, $text, ['user.id' => $user->id]);
		return redirect()->route($request->back ?? 'user.index')->with('message_success', 'User berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$user = User::find($id);
		$user->delete();

		$text = 'menghapus '.$this->title;//.' '.$user->what;
		$this->log($request, $text, ['user.id' => $user->id]);
		return back()->with('message_success', 'User berhasil dihapus!');
	}

}
