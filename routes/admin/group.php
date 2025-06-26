<?php

use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/groups')->group(function () {
    Route::post('/', [GroupController::class, 'store'])
        ->name('admin.group.store');

    Route::delete('/{group}', [GroupController::class, 'destroy'])->name('admin.group.destroy');
});
