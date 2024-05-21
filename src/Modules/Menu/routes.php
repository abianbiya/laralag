<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\Menu\Controllers\MenuController;

Route::controller(MenuController::class)->middleware(['web','auth'])->group(function(){
	Route::get('/menu', 'index')->name('menu.index')->middleware('permission:menu.index');
	Route::get('/menu/create', 'create')->name('menu.create')->middleware('permission:menu.create');
	Route::post('/menu', 'store')->name('menu.store')->middleware('permission:menu.store');
	Route::get('/menu/{menu}', 'show')->name('menu.show')->middleware('permission:menu.show');
	Route::get('/menu/{menu}/edit', 'edit')->name('menu.edit')->middleware('permission:menu.edit');
	Route::patch('/menu/{menu}', 'update')->name('menu.update')->middleware('permission:menu.update');
	Route::get('/menu/{menu}/delete', 'destroy')->name('menu.destroy')->middleware('permission:menu.destroy');
});