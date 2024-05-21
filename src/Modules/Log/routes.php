<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\Log\Controllers\LogController;

Route::controller(LogController::class)->middleware(['web','auth'])->group(function(){
	Route::get('/log', 'index')->name('log.index')->middleware('permission:log.index');
	Route::get('/log/create', 'create')->name('log.create')->middleware('permission:log.create');
	Route::post('/log', 'store')->name('log.store')->middleware('permission:log.store');
	Route::get('/log/{log}', 'show')->name('log.show')->middleware('permission:log.show');
	Route::get('/log/{log}/edit', 'edit')->name('log.edit')->middleware('permission:log.edit');
	Route::patch('/log/{log}', 'update')->name('log.update')->middleware('permission:log.update');
	Route::get('/log/{log}/delete', 'destroy')->name('log.destroy')->middleware('permission:log.destroy');
});