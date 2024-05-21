<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\MenuGroup\Controllers\MenuGroupController;

Route::controller(MenuGroupController::class)->middleware(['web','auth'])->group(function(){
	Route::get('/menugroup', 'index')->name('menugroup.index')->middleware('permission:menugroup.index');
	Route::get('/menugroup/create', 'create')->name('menugroup.create')->middleware('permission:menugroup.create');
	Route::post('/menugroup', 'store')->name('menugroup.store')->middleware('permission:menugroup.store');
	Route::get('/menugroup/{menugroup}', 'show')->name('menugroup.show')->middleware('permission:menugroup.show');
	Route::get('/menugroup/{menugroup}/edit', 'edit')->name('menugroup.edit')->middleware('permission:menugroup.edit');
	Route::patch('/menugroup/{menugroup}', 'update')->name('menugroup.update')->middleware('permission:menugroup.update');
	Route::get('/menugroup/{menugroup}/delete', 'destroy')->name('menugroup.destroy')->middleware('permission:menugroup.destroy');
});