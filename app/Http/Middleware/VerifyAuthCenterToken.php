<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Middleware untuk verifikasi token dari Auth Center Service
 * Middleware ini akan:
 * 1. Mengecek keberadaan Authorization Bearer token di header
 * 2. Melakukan introspect token ke Auth Center
 * 3. Menyimpan user info di request untuk digunakan di controller
 * 
 * Usage di routes:
 * Route::middleware(['auth.center'])->group(function () {
 *     Route::post('/billings', [BillingController::class, 'store']);
 * });
 */
class VerifyAuthCenterToken
{
    /**
     * Auth Center service URL
     */
    private string $authCenterUrl = '';

    /**
     * JWT Secret key untuk validasi local (cache public key dari auth center)
     */
    private string $jwtSecret = '';

    public function __construct()
    {
        $this->authCenterUrl = config('services.auth_center.url', 'http://auth-center:8001');
        $this->jwtSecret = config('services.auth_center.jwt_secret', '');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // 1. Ambil token dari Authorization header
            $token = $this->extractBearerToken($request);
            
            if (!$token) {
                return $this->unauthorizedResponse('Token tidak ditemukan di Authorization header');
            }

            // 2. Validasi dan decode token
            $decodedToken = $this->validateToken($token);
            
            if (!$decodedToken) {
                return $this->unauthorizedResponse('Token tidak valid atau kadaluarsa');
            }

            // 3. Verifikasi token ke Auth Center (introspect)
            $tokenInfo = $this->introspectToken($token);
            
            if (!$tokenInfo || !$tokenInfo->get('active', false)) {
                return $this->unauthorizedResponse('Token tidak aktif di Auth Center');
            }

            // 4. Extract user info dari response introspect
            $user = $tokenInfo->get('data', []);
            
            // 5. Store user info ke request untuk digunakan di controller
            $request->merge([
                'auth_user' => [
                    'user_id' => $user['user_id'] ?? null,
                    'username' => $user['username'] ?? null,
                    'role' => $user['role'] ?? null,
                    'email' => $user['email'] ?? null,
                    'service_access' => $user['service_access'] ?? [],
                ],
                'request_id' => $request->header('X-Request-ID') ?? $this->generateRequestId(),
            ]);

            // 6. Tambahkan request_id ke header jika belum ada
            $request->headers->set('X-Request-ID', $request->get('request_id'));

            return $next($request);

        } catch (\Exception $e) {
            \Log::error('Auth Center Token Verification Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return $this->unauthorizedResponse('Token verification gagal: ' . $e->getMessage());
        }
    }

    /**
     * Extract Bearer token dari Authorization header
     */
    private function extractBearerToken(Request $request): ?string
    {
        $authHeader = $request->header('Authorization');
        
        if (!$authHeader || strpos($authHeader, 'Bearer ') === false) {
            return null;
        }

        return str_replace('Bearer ', '', $authHeader);
    }

    /**
     * Validasi JWT token secara lokal
     * (Opsional: jika ingin cek signature sebelum ke Auth Center)
     */
    private function validateToken(string $token): ?object
    {
        try {
            if (!$this->jwtSecret) {
                // Jika tidak ada secret, skip validasi lokal
                return (object)['valid' => true];
            }

            // Validasi signature JWT dengan secret key
            $decoded = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
            
            // Cek expiration
            if (isset($decoded->exp) && $decoded->exp < time()) {
                return null;
            }

            return $decoded;

        } catch (\Exception $e) {
            \Log::warning('JWT Token validation failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Introspect token ke Auth Center untuk mendapatkan info user
     * Menggunakan cache untuk mengurangi request ke Auth Center
     */
    private function introspectToken(string $token): ?\Illuminate\Http\Client\Response
    {
        try {
            // Cek cache terlebih dahulu (cache selama 5 menit)
            $cacheKey = 'auth_token_' . hash('sha256', $token);
            
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            // Jika tidak ada di cache, panggil API introspect Auth Center
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->timeout(5)
            ->post($this->authCenterUrl . '/auth/introspect', [
                'token' => $token,
            ]);

            // Cek response status
            if ($response->status() !== 200) {
                \Log::warning('Auth Center introspect failed', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);
                return null;
            }

            $tokenInfo = $response->json();

            // Cache token info
            if ($tokenInfo['success'] ?? false) {
                Cache::put($cacheKey, $response, now()->addMinutes(5));
            }

            return $response;

        } catch (\Exception $e) {
            \Log::error('Introspect token error', [
                'error' => $e->getMessage(),
                'auth_center_url' => $this->authCenterUrl,
            ]);
            return null;
        }
    }

    /**
     * Generate request ID untuk tracing
     */
    private function generateRequestId(): string
    {
        return 'req_' . substr(uniqid(), -12);
    }

    /**
     * Return unauthorized response
     */
    private function unauthorizedResponse(string $message): Response
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => [
                'code' => 'UNAUTHORIZED',
                'detail' => $message,
            ],
        ], 401);
    }
}
