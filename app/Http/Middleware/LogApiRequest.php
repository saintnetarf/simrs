<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk Log API request dan response (untuk tracing & audit)
 * Mencatat semua request ke endpoint API dengan detail:
 * - Request ID
 * - User ID & Role
 * - Endpoint & Method
 * - Response status
 * - Response time
 * 
 * Usage di routes atau middleware group:
 * $middleware->push(LogApiRequest::class);
 */
class LogApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $requestId = $request->header('X-Request-ID') ?? 'req_' . substr(uniqid(), -12);
        
        // Store request info
        $authUser = $request->get('auth_user');
        $requestData = [
            'request_id' => $requestId,
            'timestamp' => now()->toIso8601String(),
            'method' => $request->getMethod(),
            'endpoint' => $request->getPathInfo(),
            'user_id' => $authUser['user_id'] ?? null,
            'username' => $authUser['username'] ?? null,
            'role' => $authUser['role'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
        ];

        // Process request
        $response = $next($request);

        // Calculate response time
        $responseTime = (microtime(true) - $startTime) * 1000; // dalam ms

        // Log API request
        \Log::info('API Request', array_merge($requestData, [
            'status_code' => $response->status(),
            'response_time_ms' => round($responseTime, 2),
        ]));

        // Add request_id ke response header untuk tracking
        $response->headers->set('X-Request-ID', $requestId);
        $response->headers->set('X-Response-Time', round($responseTime, 2) . 'ms');

        return $response;
    }
}
