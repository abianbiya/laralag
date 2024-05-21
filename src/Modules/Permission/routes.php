<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\Permission\Controllers\PermissionController;

Route::controller(PermissionController::class)->middleware(['web','auth'])->group(function(){
	Route::get('/permission', 'index')->name('permission.index')->middleware('permission:permission.index');
	Route::get('/permission/create', 'create')->name('permission.create')->middleware('permission:permission.create');
	Route::post('/permission', 'store')->name('permission.store')->middleware('permission:permission.store');
	Route::get('/permission/{permission}', 'show')->name('permission.show')->middleware('permission:permission.show');
	Route::get('/permission/{permission}/edit', 'edit')->name('permission.edit')->middleware('permission:permission.edit');
	Route::patch('/permission/{permission}', 'update')->name('permission.update')->middleware('permission:permission.update');
	Route::get('/permission/{permission}/delete', 'destroy')->name('permission.destroy')->middleware('permission:permission.destroy');
});