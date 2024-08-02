<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use App\Services\WebsocketService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Cписок роутов: php artisan route:list
// Сброс роутов: php artisan route:cache

// индексная страница
Route::get('/', [TaskController::class, 'index'])->middleware(['auth'])->name('index');
Route::get('/dashboard', function () {return redirect('/'); })->middleware(['auth']);
// задачи
Route::resource('/task', TaskController::class)->except(['edit', 'destroy'])->middleware(['auth']);
// профиль пользователя
Route::get('/profile', function () {
    return view('profile', ['auth_user' => Auth::user(), 'websocket_addr' => WebsocketService::getWebsockerAddr()]);
})->middleware(['auth'])->name('profile');
// AUTH папка
require __DIR__.'/auth.php';
// сохранение комментариев
Route::post('/comment', [CommentController::class, 'store'])->name('comment.store');
// статистика
Route::get('/statistic', [TaskController::class, 'stat'])->middleware(['auth', 'admin'])->name('statistic');
// настройки
Route::get('/settings', function () {
    return view('settings', ['websocket_addr' => WebsocketService::getWebsockerAddr()]);
})->middleware(['auth', 'admin'])->name('settings');

// Error 403
Route::get('/403', function () {
    return view('403');
})->name('403');

// Error 500
Route::get('/500', function () {
    return view('500');
})->name('500');
