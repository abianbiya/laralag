<?php

namespace Abianbiya\Laralag\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Abianbiya\Laralag\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class Register extends Component
{
    use WithFileUploads;

    public $first_name;
    public $last_name;
    public $avatar;
    public $password;
    public $email;
    public $password_confirmation;

    protected $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
    ];

    public function submit() {
        // validate the data
        $this->validate();

        if ($this->avatar != null) {
            $avatar = $this->avatar;
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            Storage::putFileAs('public/images/users', $avatar, $avatarName);

        }
        $token = bin2hex(random_bytes(20));

        // create new user
        $user = User::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'avatar' => $avatarName,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'remember_token' => $token,
            'created_at' => now()
        ]);

        return redirect('login')->with('success', 'User registered successfully.!');
    }
    public function render()
    {
        if('laralag.custom_register_blade'){
            return view('laralag.custom_register_blade');
        }
        return view('Laralag::livewire.auth.register')->extends('Laralag::layouts.master-without-nav');
    }
}
