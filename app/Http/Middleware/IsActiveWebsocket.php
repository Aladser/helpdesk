<?php

namespace App\Http\Middleware;

use Aladser\ScriptLinuxProcess;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** класс запуска вебсокета */
class IsActiveWebsocket
{
    // проверяет активность вебсокета
    public function handle(Request $request, \Closure $next)
    {
        $os = explode(' ', php_uname())[0];
        if ($os !== 'Windows') {
            $websocket = new ScriptLinuxProcess(
                'helpdesk',
                dirname(__DIR__, 2).'/launch_websocket.php',
                dirname(__DIR__, 3).'/logs/websocket.log',
                dirname(__DIR__, 3).'/logs/pids.log'
            );
            if (!$websocket->isActive()) {
                Connection::truncate();
                DB::table('users')->update(['status_id' => 3]);
                $websocket->run();
            }
        }

        return $next($request);
    }
}
