<?php

use App\Http\Controllers\FormController;
use App\Http\Controllers\FormSubmissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin/forms')->group(function () {
    Route::get('/', [FormController::class, 'index'])->name('admin.form.index');

    Route::get('/create', [FormController::class, 'create'])->name('admin.form.create');
    Route::get('/{form:slug}', [FormController::class, 'show'])->name('admin.form.show');

    Route::post('/', [FormController::class, 'store'])->name('admin.form.store');

    Route::delete('/{form:slug}', [FormController::class, 'destroy'])->name('admin.form.destroy');

    Route::get('/{form:slug}/submissions', [FormSubmissionController::class, 'index'])->name('admin.form.submissions.index');
    Route::get('/{form:slug}/submissions/{formSubmission}', [FormSubmissionController::class, 'show'])->name('admin.form.submissions.show');

    Route::delete('/{form:slug}/submissions/{formSubmission}', [FormSubmissionController::class, 'destroy'])
        ->name('admin.form.submissions.destroy');
});

Route::post('/forms/{form:slug}/submit', [FormSubmissionController::class, 'store'])
    ->name('form.submit');
