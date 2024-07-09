<?php

namespace Abianbiya\Laralag\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function home()
    {
        if(config('laralag.has_landing') == false){
            return redirect()->route('login');
        }
        return view('welcome');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function root()
    {
        return view('Laralag::pages.dashboard');
    }

    public function index(Request $request)
    {
        if (view()->exists($request->path())) {
            return view($request->path());
        }
        return abort(404);
    }

    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function logout() {
        Auth::logout();
        if(config('laralag.has_landing') == true){
            return redirect(route(config('laralag.landing_route', 'home.index')));
        }else{
            return redirect(route('login'));
        }
    }

    public function changeRole($slugRole, $scopeId = null) {
        $user = Auth::user();
        $user->setActiveRole($slugRole, $scopeId);
        return redirect()->back()->with('message_success', "Berhasil mengganti role ke $slugRole");
    }
}
