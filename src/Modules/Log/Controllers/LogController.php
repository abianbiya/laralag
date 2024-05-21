<?php
namespace Abianbiya\Laralag\Modules\Log\Controllers;

use Form;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Abianbiya\Laralag\Helpers\Logger;
use Abianbiya\Laralag\Modules\Log\Models\Log;

class LogController extends Controller
{
	use Logger;
	protected $log;
	protected $title = "Log";
	
	public function __construct(Log $log)
	{
		$this->log = $log;
	}

	public function index(Request $request)
	{
		$query = Log::query();
		if($request->has('search')){
			$search = $request->get('search');
			$query->whereAny(['User', 'Name', 'Aktivitas', 'Route', 'Action', 'Context', 'Data From', 'Data To', 'Ip Address', 'User Agent', ], 'like', "%$search%");
		}
		$data['data'] = $query->paginate(10)->withQueryString();

		$this->log($request, 'melihat halaman manajemen data '.$this->title);
		return view('Log::log', array_merge($data, ['title' => $this->title]));
	}

	public function create(Request $request)
	{
		
		$data['forms'] = array(
			'user_id' => ['User', html()->text("user_id", old("user_id"))->class("form-control")->placeholder("")->required()],
            'name' => ['Name', html()->text("name", old("name"))->class("form-control")->placeholder("")],
            'aktivitas' => ['Aktivitas', html()->textarea("aktivitas", old("aktivitas"))->class("form-control rich-editor")],
            'route' => ['Route', html()->text("route", old("route"))->class("form-control")->placeholder("")],
            'action' => ['Action', html()->text("action", old("action"))->class("form-control")->placeholder("")],
            'context' => ['Context', html()->text("context", old("context"))->class("form-control")->placeholder("")],
            'data_from' => ['Data From', html()->text("data_from", old("data_from"))->class("form-control")->placeholder("")],
            'data_to' => ['Data To', html()->text("data_to", old("data_to"))->class("form-control")->placeholder("")],
            'ip_address' => ['Ip Address', html()->text("ip_address", old("ip_address"))->class("form-control")->placeholder("")],
            'user_agent' => ['User Agent', html()->text("user_agent", old("user_agent"))->class("form-control")->placeholder("")],
            
		);

		$this->log($request, 'membuka form tambah '.$this->title);
		return view('Log::log_create', array_merge($data, ['title' => $this->title]));
	}

	function store(Request $request)
	{
		$request->validate([
			'user_id' => 'required|string',
			'name' => 'nullable|string',
			'aktivitas' => 'required|string',
			'route' => 'nullable|string',
			'action' => 'nullable|string',
			'context' => 'nullable|string',
			'data_from' => 'nullable|string',
			'data_to' => 'nullable|string',
			'ip_address' => 'nullable|string',
			'user_agent' => 'nullable|string',
			
		]);

		$log = new Log();
		$log->user_id = $request->input("user_id");
        $log->name = $request->input("name");
        $log->aktivitas = $request->input("aktivitas");
        $log->route = $request->input("route");
        $log->action = $request->input("action");
        $log->context = $request->input("context");
        $log->data_from = $request->input("data_from");
        $log->data_to = $request->input("data_to");
        $log->ip_address = $request->input("ip_address");
        $log->user_agent = $request->input("user_agent");
        $log->save();

		$text = 'membuat '.$this->title; //' baru '.$log->what;
		$this->log($request, $text, ['log.id' => $log->id]);
		return redirect()->route($request->back ?? 'log.index')->with('message_success', 'Log berhasil ditambahkan!');
	}

	public function show(Request $request, Log $log)
	{
		$data['log'] = $log;

		$text = 'melihat detail '.$this->title;//.' '.$log->what;
		$this->log($request, $text, ['log.id' => $log->id]);
		return view('Log::log_detail', array_merge($data, ['title' => $this->title]));
	}

	public function edit(Request $request, Log $log)
	{
		$data['log'] = $log;

		
		$data['forms'] = array(
			'user_id' => ['User', html()->text("user_id", $log->user_id)->class("form-control")->placeholder("")->required()],
            'name' => ['Name', html()->text("name", $log->name)->class("form-control")->placeholder("")],
            'aktivitas' => ['Aktivitas', html()->textarea("aktivitas", $log->aktivitas)->class("form-control rich-editor")],
            'route' => ['Route', html()->text("route", $log->route)->class("form-control")->placeholder("")],
            'action' => ['Action', html()->text("action", $log->action)->class("form-control")->placeholder("")],
            'context' => ['Context', html()->text("context", $log->context)->class("form-control")->placeholder("")],
            'data_from' => ['Data From', html()->text("data_from", $log->data_from)->class("form-control")->placeholder("")],
            'data_to' => ['Data To', html()->text("data_to", $log->data_to)->class("form-control")->placeholder("")],
            'ip_address' => ['Ip Address', html()->text("ip_address", $log->ip_address)->class("form-control")->placeholder("")],
            'user_agent' => ['User Agent', html()->text("user_agent", $log->user_agent)->class("form-control")->placeholder("")],
            
		);

		$text = 'membuka form edit '.$this->title;//.' '.$log->what;
		$this->log($request, $text, ['log.id' => $log->id]);
		return view('Log::log_update', array_merge($data, ['title' => $this->title]));
	}

	public function update(Request $request, $id)
	{
		$request->validate([
			'user_id' => 'required|string',
			'name' => 'nullable|string',
			'aktivitas' => 'required|string',
			'route' => 'nullable|string',
			'action' => 'nullable|string',
			'context' => 'nullable|string',
			'data_from' => 'nullable|string',
			'data_to' => 'nullable|string',
			'ip_address' => 'nullable|string',
			'user_agent' => 'nullable|string',
			
		]);
		
		$log = Log::find($id);
		$log->user_id = $request->input("user_id");
        $log->name = $request->input("name");
        $log->aktivitas = $request->input("aktivitas");
        $log->route = $request->input("route");
        $log->action = $request->input("action");
        $log->context = $request->input("context");
        $log->data_from = $request->input("data_from");
        $log->data_to = $request->input("data_to");
        $log->ip_address = $request->input("ip_address");
        $log->user_agent = $request->input("user_agent");
        $log->save();


		$text = 'mengedit '.$this->title;//.' '.$log->what;
		$this->log($request, $text, ['log.id' => $log->id]);
		return redirect()->route($request->back ?? 'log.index')->with('message_success', 'Log berhasil diubah!');
	}

	public function destroy(Request $request, $id)
	{
		$log = Log::find($id);
		$log->delete();

		$text = 'menghapus '.$this->title;//.' '.$log->what;
		$this->log($request, $text, ['log.id' => $log->id]);
		return back()->with('message_success', 'Log berhasil dihapus!');
	}

}
