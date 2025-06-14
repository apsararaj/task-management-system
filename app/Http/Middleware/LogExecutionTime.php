<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogExecutionTime {
    public function handle($request, Closure $next) {
        $start = microtime(true);
        $response = $next($request);
        $time = number_format(microtime(true) - $start, 2);
        Log::info("[Execution Time] {$request->method()} {$request->path()} - {$time}s");
        return $response;
    }
}
