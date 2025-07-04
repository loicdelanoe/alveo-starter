<?php

use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/groups')->group(function () {
    Route::post('/', [GroupController::class, 'store'])
        ->name('admin.group.store');

    Route::patch('/{group}', [GroupController::class, 'update'])
        ->name('admin.group.update');

    Route::delete('/{group}', [GroupController::class, 'destroy'])->name('admin.group.destroy');
});
