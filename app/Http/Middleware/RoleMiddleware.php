<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Penggunaan di routes:
     *   Route::middleware('role:super_admin,auditor')->group(...)
     *
     * Sekarang menggunakan Spatie hasAnyRole() menggantikan role->name lama.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasAnyRole($roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}