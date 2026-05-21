<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has a role and if that role is allowed
        if ($user->role && in_array($user->role->name, $roles)) {
            return $next($request);
        }

        // Redirect based on actual user role
        $roleName = $user->role->name ?? 'mahasiswa';
        $redirectUrl = match ($roleName) {
            'admin' => '/admin/dashboard',
            'dosen' => '/dosen/dashboard',
            default => '/mahasiswa/dashboard',
        };

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak! Anda tidak memiliki wewenang untuk mengakses halaman ini.'
            ], 403);
        }

        return redirect($redirectUrl)->with('error_unauthorized', 'Akses Ditolak! Anda tidak memiliki wewenang untuk mengakses halaman tersebut.');
    }
}
