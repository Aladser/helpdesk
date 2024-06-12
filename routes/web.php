<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TaskController;

// индексная страница
Route::get('/', [TaskController::class, 'index'])->name('index');

Route::get('/dashboard', function () {
    return view('dashboard', ['auth_user' => Auth::user()]);
})->middleware(['auth'])->name('dashboard');
require __DIR__.'/auth.php';
