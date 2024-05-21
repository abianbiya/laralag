<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\User\Controllers\UserController;

Route::controller(UserController::class)->middleware(['web','auth'])->group(function(){
	Route::get('/user', 'index')->name('user.index')->middleware('permission:user.index');
	Route::get('/user/create', 'create')->name('user.create')->middleware('permission:user.create');
	Route::post('/user', 'store')->name('user.store')->middleware('permission:user.store');
	Route::get('/user/{user}', 'show')->name('user.show')->middleware('permission:user.show');
	Route::get('/user/{user}/edit', 'edit')->name('user.edit')->middleware('permission:user.edit');
	Route::patch('/user/{user}', 'update')->name('user.update')->middleware('permission:user.update');
	Route::get('/user/{user}/delete', 'destroy')->name('user.destroy')->middleware('permission:user.destroy');
});