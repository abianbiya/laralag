<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\PermissionRole\Controllers\PermissionRoleController;

Route::controller(PermissionRoleController::class)->middleware(['web','auth'])->group(function(){
	Route::get('/permissionrole', 'index')->name('permissionrole.index')->middleware('permission:permissionrole.index');
	Route::get('/permissionrole/create', 'create')->name('permissionrole.create')->middleware('permission:permissionrole.create');
	Route::post('/permissionrole', 'store')->name('permissionrole.store')->middleware('permission:permissionrole.store');
	Route::get('/permissionrole/{permissionrole}', 'show')->name('permissionrole.show')->middleware('permission:permissionrole.show');
	Route::get('/permissionrole/{permissionrole}/edit', 'edit')->name('permissionrole.edit')->middleware('permission:permissionrole.edit');
	Route::patch('/permissionrole/{permissionrole}', 'update')->name('permissionrole.update')->middleware('permission:permissionrole.update');
	Route::get('/permissionrole/{permissionrole}/delete', 'destroy')->name('permissionrole.destroy')->middleware('permission:permissionrole.destroy');
});