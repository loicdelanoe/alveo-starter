<?php

use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

// require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin/block.php';
require __DIR__.'/admin/block-type.php';
require __DIR__.'/admin/page.php';
require __DIR__.'/admin/setting.php';
require __DIR__.'/admin/collection-type.php';
require __DIR__.'/admin/collection.php';
require __DIR__.'/admin.php';
require __DIR__.'/admin/api.php';
require __DIR__.'/admin/menu.php';
require __DIR__.'/admin/user.php';
require __DIR__.'/admin/media.php';
require __DIR__.'/admin/action.php';

Route::get('/', [ViewController::class, 'index'])->name('page.index');
Route::get('/{slug}', [ViewController::class, 'page'])->name('page.show');
Route::get('/{collection}/{slug}', [ViewController::class, 'collection'])->name('page.collection');
