<?php

namespace Abianbiya\Laralag\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = "";
    public $password = "";

    protected $rules = [
        'email' => 'required|string|email|max:255',
        'password' => 'required',
    ];

   public function mount()
   {
       if (auth()->user()) {
           return redirect()->intended('/');
       }
   }

    public function submit()
    {
        // validate the data
        $this->validate();


        $user = array(
            'email' => $this->email,
            'password' => $this->password,
        );

        if (Auth::attempt($user)) {
            $user = Auth::user();
            if(count($user->roles) < 1) {
                Auth::logout();
                $this->addError('email', 'User does not have any role assigned. Please contact support.');
                return redirect()->back();
            }
            $user->setActiveRole($user->roles->first()->slug);
            $user->setRoleList();

            return redirect()->intended(filled(config('laralag.home_route', null)) ? route(config('laralag.home_route')) : '/');
        } else {
            $this->addError('email', trans('auth.failed'));
           return redirect()->back();
        }
    }

    public function render()
    {
        if(filled(config('laralag.custom_login_blade'))){
            return view(config('laralag.custom_login_blade'));
        }else{
            return view('Laralag::livewire.auth.login')->extends('Laralag::layouts.master-without-nav');
        }
    }
}
