<?php

namespace Abianbiya\Laralag\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $email = "admin@mail.com";
    public $password = "12345678";

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
            if(count($user->roles) == 0) {
                Auth::logout();
                $this->addError('email', 'User does not have any role assigned. Please contact support.');
                return redirect()->back();
            }
            $user->setActiveRole($user->roles->first()->slug);

            return redirect()->intended('/');
        } else {
            $this->addError('email', trans('auth.failed'));
           return redirect()->back();
        }
    }

    public function render()
    {
        return view('Laralag::livewire.auth.login')->extends('Laralag::layouts.master-without-nav');
    }
}
