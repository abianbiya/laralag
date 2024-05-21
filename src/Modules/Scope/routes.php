<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\Scope\Controllers\ScopeController;

Route::controller(ScopeController::class)->middleware(['web','auth'])->group(function(){
	Route::get('/scope', 'index')->name('scope.index')->middleware('permission:scope.index');
	Route::get('/scope/create', 'create')->name('scope.create')->middleware('permission:scope.create');
	Route::post('/scope', 'store')->name('scope.store')->middleware('permission:scope.store');
	Route::get('/scope/{scope}', 'show')->name('scope.show')->middleware('permission:scope.show');
	Route::get('/scope/{scope}/edit', 'edit')->name('scope.edit')->middleware('permission:scope.edit');
	Route::patch('/scope/{scope}', 'update')->name('scope.update')->middleware('permission:scope.update');
	Route::get('/scope/{scope}/delete', 'destroy')->name('scope.destroy')->middleware('permission:scope.destroy');
});