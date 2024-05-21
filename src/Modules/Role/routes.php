<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\Role\Controllers\RoleController;

Route::controller(RoleController::class)->middleware(['web','auth'])->group(function(){
	Route::get('/role', 'index')->name('role.index')->middleware('permission:role.index');
	Route::get('/role/create', 'create')->name('role.create')->middleware('permission:role.create');
	Route::post('/role', 'store')->name('role.store')->middleware('permission:role.store');
	Route::get('/role/{role}', 'show')->name('role.show')->middleware('permission:role.show');
	Route::get('/role/{role}/edit', 'edit')->name('role.edit')->middleware('permission:role.edit');
	Route::patch('/role/{role}', 'update')->name('role.update')->middleware('permission:role.update');
	Route::post('/role/permission/{role}', 'updatePermission')->name('role.permission.update')->middleware('permission:role.permission.update');
	Route::get('/role/{role}/delete', 'destroy')->name('role.destroy')->middleware('permission:role.destroy');
});