<?php

use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('/admin/settings')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('admin.setting.index');

    Route::patch('/', [SettingController::class, 'update'])
        ->name('admin.setting.update');
});
