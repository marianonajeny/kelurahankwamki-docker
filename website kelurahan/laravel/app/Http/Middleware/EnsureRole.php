<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        if (! $user->hasRole(...$roles)) {
            return redirect()
                ->route('login')
                ->withErrors(['username' => 'Akun tidak memiliki akses ke halaman ini.']);
        }

        return $next($request);
    }
}
