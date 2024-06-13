<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// список роутов - php artisan route:list

// индексная страница
Route::get('/', [TaskController::class, 'index'])->middleware(['auth'])->name('index');
// задачи
Route::resource('/task', TaskController::class)->middleware(['auth']);

Route::get('/dashboard', function () {
    return view('dashboard', ['auth_user' => Auth::user()]);
})->middleware(['auth'])->name('dashboard');
require __DIR__.'/auth.php';
