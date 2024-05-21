<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\Module\Controllers\ModuleController;

Route::controller(ModuleController::class)->middleware(['web','auth'])->group(function(){
	Route::get('/module', 'index')->name('module.index')->middleware('permission:module.index');
	Route::get('/module/create', 'create')->name('module.create')->middleware('permission:module.create');
	Route::post('/module', 'store')->name('module.store')->middleware('permission:module.store');
	Route::get('/module/{module}', 'show')->name('module.show')->middleware('permission:module.show');
	Route::get('/module/{module}/edit', 'edit')->name('module.edit')->middleware('permission:module.edit');
	Route::patch('/module/{module}', 'update')->name('module.update')->middleware('permission:module.update');
	Route::get('/module/{module}/delete', 'destroy')->name('module.destroy')->middleware('permission:module.destroy');
});