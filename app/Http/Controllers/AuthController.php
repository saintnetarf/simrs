<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AuthController extends Controller
{
    private const TOKEN_EXPIRY = 3600;

    /**
     * Login endpoint
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validated = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
                'client_service' => 'nullable|string',
            ]);

            // Find user by username
            $user = User::where('username', $validated['username'])
                ->where('is_active', true)
                ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username atau password salah',
                    'error_code' => 'INVALID_CREDENTIALS'
                ], 401);
            }

            // Verify password
            if (!$user->verifyPassword($validated['password'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username atau password salah',
                    'error_code' => 'INVALID_CREDENTIALS'
                ], 401);
            }

            // Generate signed token
            $now = time();
            $expires_at = $now + self::TOKEN_EXPIRY;

            $payload = [
                'iss' => config('app.url', 'http://localhost'),
                'iat' => $now,
                'exp' => $expires_at,
                'sub' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
                'client_service' => $validated['client_service'] ?? 'unknown',
            ];

            $token = $this->createToken($payload);

            // Update last login
            $user->update(['last_login_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => self::TOKEN_EXPIRY,
                    'user' => [
                        'id' => $user->id,
                        'username' => $user->username,
                        'full_name' => $user->full_name,
                        'role' => $user->role,
                        'client_service' => $validated['client_service'] ?? null,
                    ]
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
                'error_code' => 'VALIDATION_ERROR'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error_code' => 'SERVER_ERROR'
            ], 500);
        }
    }

    /**
     * Introspect token (verify token validity)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function introspect(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'token' => 'required|string',
            ]);

            $token = $validated['token'];

            // Remove 'Bearer ' prefix if exists
            if (str_starts_with($token, 'Bearer ')) {
                $token = substr($token, 7);
            }

            try {
                $decoded = $this->decodeToken($token);

                return response()->json([
                    'success' => true,
                    'message' => 'Token valid',
                    'data' => [
                        'active' => true,
                        'user_id' => $decoded['sub'],
                        'username' => $decoded['username'],
                        'role' => $decoded['role'],
                        'exp' => $decoded['exp'],
                    ]
                ], 200);
            } catch (\RuntimeException $e) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'error_code' => 'INVALID_TOKEN'
                ], 401);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak valid',
                    'error_code' => 'INVALID_TOKEN'
                ], 401);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors(),
                'error_code' => 'VALIDATION_ERROR'
            ], 422);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil',
        ]);
    }

    public function refreshToken(Request $request): JsonResponse
    {
        $token = (string) $request->bearerToken();

        if ($token === '') {
            return response()->json([
                'success' => false,
                'message' => 'Token wajib dikirim',
                'error_code' => 'TOKEN_REQUIRED',
            ], 422);
        }

        try {
            $payload = $this->decodeToken($token, ignoreExpiry: true);
        } catch (\RuntimeException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
                'error_code' => 'INVALID_TOKEN',
            ], 401);
        }

        $now = time();
        $payload['iat'] = $now;
        $payload['exp'] = $now + self::TOKEN_EXPIRY;

        return response()->json([
            'success' => true,
            'message' => 'Token berhasil diperbarui',
            'data' => [
                'access_token' => $this->createToken($payload),
                'token_type' => 'Bearer',
                'expires_in' => self::TOKEN_EXPIRY,
            ],
        ]);
    }

    private function createToken(array $payload): string
    {
        $header = ['typ' => 'JWT', 'alg' => 'HS256'];
        $segments = [
            $this->base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES)),
            $this->base64UrlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES)),
        ];

        $signature = hash_hmac('sha256', implode('.', $segments), $this->tokenSecret(), true);
        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    private function decodeToken(string $token, bool $ignoreExpiry = false): array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new \RuntimeException('Format token tidak valid');
        }

        [$headerPart, $payloadPart, $signaturePart] = $parts;
        $header = json_decode($this->base64UrlDecode($headerPart), true);
        $payload = json_decode($this->base64UrlDecode($payloadPart), true);

        if (!is_array($header) || !is_array($payload)) {
            throw new \RuntimeException('Token tidak dapat dibaca');
        }

        $expectedSignature = $this->base64UrlEncode(hash_hmac('sha256', $headerPart . '.' . $payloadPart, $this->tokenSecret(), true));

        if (!hash_equals($expectedSignature, $signaturePart)) {
            throw new \RuntimeException('Signature token tidak valid');
        }

        if (!$ignoreExpiry && isset($payload['exp']) && time() > (int) $payload['exp']) {
            throw new \RuntimeException('Token sudah expired');
        }

        return $payload;
    }

    private function tokenSecret(): string
    {
        return hash('sha256', (string) config('app.key', 'simrs-auth-secret'));
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): string
    {
        $remainder = strlen($value) % 4;
        if ($remainder !== 0) {
            $value .= str_repeat('=', 4 - $remainder);
        }

        return (string) base64_decode(strtr($value, '-_', '+/'));
    }
}
