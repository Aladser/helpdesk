<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Cписок роутов: php artisan route:list

// индексная страница
Route::get('/', [TaskController::class, 'index'])->middleware(['auth'])->name('index');
Route::get('/dashboard', function () {return redirect('/'); })->middleware(['auth']);
// задачи
Route::resource('/task', TaskController::class)->except(['edit', 'destroy'])->middleware(['auth']);
// профиль пользователя
Route::get('/profile', function () {
    return view('profile', ['auth_user' => Auth::user()]);
})->middleware(['auth'])->name('profile');
// AUTH папка
require __DIR__.'/auth.php';
// сохранение комментариев
Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
