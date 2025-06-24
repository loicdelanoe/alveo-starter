<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/forms')->group(function () {
    Route::get('/', [FormController::class, 'index'])->name('admin.form.index');

    Route::get('/create', [FormController::class, 'create'])->name('admin.form.create');
});
