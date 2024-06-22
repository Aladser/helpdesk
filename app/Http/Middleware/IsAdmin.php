<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    // проверка прав для страниц администраторов
    public function handle(Request $request, \Closure $next)
    {
        if (Auth::user()->role->name == 'author') {
            return redirect(route('403'));
        }

        return $next($request);
    }
}
