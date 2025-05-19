<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan user sudah login
        if (!$request->user()) {
            abort(401, 'Unauthorized');
        }

        // 2. Dapatkan role user dalam bentuk enum
        $userRole = $request->user()->getRole(); // Mengembalikan RoleEnum|null

        // 3. Jika user tidak memiliki role atau role tidak valid
        if (!$userRole instanceof RoleEnum) {
            abort(403, 'Forbidden: Invalid user role');
        }

        // 4. Cek setiap role yang diizinkan
        foreach ($roles as $role) {
            try {
                $allowedRole = RoleEnum::from($role);
                if ($userRole === $allowedRole) {
                    return $next($request);
                }
            } catch (\ValueError $e) {
                // Role tidak valid dalam enum
                continue;
            }
        }

        // 5. Jika tidak ada role yang cocok
        abort(403, 'Forbidden: You do not have access to this resource');
    }
}