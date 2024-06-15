<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Cписок роутов: php artisan route:list

// индексная страница
Route::get('/', [TaskController::class, 'index'])->middleware(['auth'])->name('index');

// задачи
Route::resource('/task', TaskController::class)->middleware(['auth']);

// профиль пользователя
Route::get('/profile', function () {
    return view('dashboard', ['auth_user' => Auth::user()]);
})->middleware(['auth'])->name('dashboard');

// AUTH
require __DIR__.'/auth.php';
