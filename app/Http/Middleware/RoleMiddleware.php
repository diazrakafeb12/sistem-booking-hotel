<?php
// Middleware: validasi role user (admin, ceo, customer)

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
            return redirect()->back()->with('error', 'Halaman ini hanya dapat diakses oleh ' . implode(' atau ', $roles) . '.');
        }

        return $next($request);
    }
}
