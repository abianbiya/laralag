<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Controllers\HomeController;

Route::group(['middleware' => 'web'], function () {
	Route::get('/login', \Abianbiya\Laralag\Livewire\Auth\Login::class)->name('login');
	Route::get('/register', \Abianbiya\Laralag\Livewire\Auth\Register::class)->name('register');
	Route::get('/forget-password', \Abianbiya\Laralag\Livewire\Auth\ForgetPassword::class)->name('password.reset');
	Route::get('/new-password/{email?}/{token?}', \Abianbiya\Laralag\Livewire\Auth\NewPassword::class);

	Route::get('/logout', [HomeController::class, 'logout'])->name('logout');
	Route::get('index/{locale}', [HomeController::class, 'lang']);
	Route::get('/', [HomeController::class, 'home'])->name(config('laralag.landing_route', 'home.index'));
});

Route::controller(HomeController::class)->middleware(['web', 'auth'])->group(function () {
	Route::get('/test', function () {
		return view('widgets');
	});
	// Route::get('{any}', 'index')->name('pages.read');
	Route::get('/dashboard', 'root')->name('dashboard.index');
	Route::get('/change/role/{slugRole}/{scopeId?}', 'changeRole')->name('dashboard.change.role.index');
});
