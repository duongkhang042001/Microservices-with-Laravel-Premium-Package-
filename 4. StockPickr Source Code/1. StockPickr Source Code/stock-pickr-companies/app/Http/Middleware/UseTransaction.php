<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UseTransaction
{
    public function handle($request, Closure $next)
    {
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return $next($request);
        }

        DB::beginTransaction();

        $response = $next($request);
        if ($response instanceof Response && $response->getStatusCode() >= 500) {
            DB::rollBack();
            return $response;
        }

        DB::commit();

        return $response;
    }
}
