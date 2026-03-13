<?php

use Illuminate\Support\Facades\Route;
use Abianbiya\Laralag\Modules\Config\Controllers\ConfigController;

Route::controller(ConfigController::class)->middleware(['web','auth'])->group(function(){
    Route::get('/config', 'index')->name('config.index')->middleware('permission:config.index');
    Route::post('/config/{configGroup}', 'update')->name('config.update');

    // Config item CRUD (gated under config-group permissions)
    Route::get('/config-item/create', 'createItem')->name('configitem.create')->middleware('permission:config-group.create');
    Route::post('/config-item', 'storeItem')->name('configitem.store')->middleware('permission:config-group.store');
    Route::get('/config-item/{config}/edit', 'editItem')->name('configitem.edit')->middleware('permission:config-group.edit');
    Route::patch('/config-item/{config}', 'updateItem')->name('configitem.update')->middleware('permission:config-group.update');
    Route::delete('/config-item/{config}', 'destroyItem')->name('configitem.destroy')->middleware('permission:config-group.destroy');
});
