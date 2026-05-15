<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk verifikasi role user
 * Middleware ini akan mengecek apakah user memiliki role yang diizinkan
 * 
 * Usage di routes:
 * Route::middleware(['auth.center', 'role:kasir|admin_apotik'])->group(function () {
 *     Route::post('/payments', [PaymentController::class, 'store']);
 * });
 */
class VerifyUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Ambil user dari request (sebelumnya di-set oleh VerifyAuthCenterToken middleware)
        $authUser = $request->get('auth_user');

        if (!$authUser) {
            return $this->forbiddenResponse('User tidak ditemukan di request');
        }

        // Cek apakah role user ada di list allowed roles
        $userRole = $authUser['role'] ?? null;
        
        if (!$userRole || !in_array($userRole, $roles)) {
            return $this->forbiddenResponse(
                "Role '{$userRole}' tidak diizinkan. Hanya role: " . implode(', ', $roles)
            );
        }

        return $next($request);
    }

    /**
     * Return forbidden response
     */
    private function forbiddenResponse(string $message): Response
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => [
                'code' => 'FORBIDDEN',
                'detail' => $message,
            ],
        ], 403);
    }
}
