<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\ConfigGroup\Controllers\ConfigGroupController;

Route::controller(ConfigGroupController::class)->middleware(['web','auth'])->group(function(){
    Route::get('/config-group', 'index')->name('config-group.index')->middleware('permission:config-group.index');
    Route::get('/config-group/create', 'create')->name('config-group.create')->middleware('permission:config-group.create');
    Route::post('/config-group', 'store')->name('config-group.store')->middleware('permission:config-group.store');
    Route::get('/config-group/{configGroup}', 'show')->name('config-group.show')->middleware('permission:config-group.show');
    Route::get('/config-group/{configGroup}/edit', 'edit')->name('config-group.edit')->middleware('permission:config-group.edit');
    Route::patch('/config-group/{configGroup}', 'update')->name('config-group.update')->middleware('permission:config-group.update');
    Route::delete('/config-group/{configGroup}', 'destroy')->name('config-group.destroy')->middleware('permission:config-group.destroy');
});
