<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/forms')->group(function () {
    Route::get('/', [FormController::class, 'index'])->name('admin.form.index');

    Route::get('/create', [FormController::class, 'create'])->name('admin.form.create');
    Route::get('/{form:slug}', [FormController::class, 'show'])->name('admin.form.show');

    Route::post('/', [FormController::class, 'store'])->name('admin.form.store');

    Route::delete('/{form:slug}', [FormController::class, 'destroy'])->name('admin.form.destroy');
});
