<?php

use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/links')->group(function () {
    Route::post('/{link}', [LinkController::class, 'update'])->name('admin.link.update');

    Route::post('/', [LinkController::class, 'store'])
        ->name('admin.link.store');
    Route::patch('/{link}', [LinkController::class, 'update'])
        ->name('admin.link.update');

    Route::delete('/{link}', [LinkController::class, 'destroy'])->name('admin.link.destroy');
});
