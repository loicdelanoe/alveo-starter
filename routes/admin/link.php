<?php

use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/links')->group(function () {
    Route::post('/', [LinkController::class, 'store'])
        ->name('admin.link.store');

    Route::delete('/{link}', [LinkController::class, 'destroy'])->name('admin.link.destroy');
});
