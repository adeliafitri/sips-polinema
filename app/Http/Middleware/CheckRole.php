<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if ($user && in_array($user->role, $roles)) {
            return $next($request);
        }

        return redirect('/unauthorized'); // Sesuaikan dengan rute atau respons yang sesuai
    }

}
