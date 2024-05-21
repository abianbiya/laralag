<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\RoleUser\Controllers\RoleUserController;

Route::controller(RoleUserController::class)->middleware(['web','auth'])->group(function(){
	Route::get('/roleuser', 'index')->name('roleuser.index')->middleware('permission:roleuser.index');
	Route::get('/roleuser/create', 'create')->name('roleuser.create')->middleware('permission:roleuser.create');
	Route::post('/roleuser', 'store')->name('roleuser.store')->middleware('permission:roleuser.store');
	Route::get('/roleuser/{roleuser}', 'show')->name('roleuser.show')->middleware('permission:roleuser.show');
	Route::get('/roleuser/{roleuser}/edit', 'edit')->name('roleuser.edit')->middleware('permission:roleuser.edit');
	Route::patch('/roleuser/{roleuser}', 'update')->name('roleuser.update')->middleware('permission:roleuser.update');
	Route::get('/roleuser/{roleuser}/delete', 'destroy')->name('roleuser.destroy')->middleware('permission:roleuser.destroy');
});