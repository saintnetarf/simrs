# Middleware Registration & Configuration Guide

## 1. Register Middleware di `bootstrap/app.php` (Laravel 11) atau `app/Http/Kernel.php` (Laravel 10)

### Untuk Laravel 11 (`bootstrap/app.php`)
```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\VerifyAuthCenterToken;
use App\Http\Middleware\VerifyUserRole;
use App\Http\Middleware\LogApiRequest;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'auth.center' => VerifyAuthCenterToken::class,
            'role' => VerifyUserRole::class,
        ]);

        // Global middleware (dijalankan untuk setiap request)
        $middleware->append(LogApiRequest::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
```

### Untuk Laravel 10 (`app/Http/Kernel.php`)
```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // ... existing middleware
        \App\Http\Middleware\LogApiRequest::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'api' => [
            \App\Http\Middleware\VerifyAuthCenterToken::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth.center' => \App\Http\Middleware\VerifyAuthCenterToken::class,
        'role' => \App\Http\Middleware\VerifyUserRole::class,
    ];
}
```

---

## 2. Setup Environment Variables (`.env`)

Tambahkan konfigurasi untuk Auth Center:

```env
# Auth Center Configuration
AUTH_CENTER_URL=http://auth-center:8001
AUTH_CENTER_JWT_SECRET=your-secret-key-here
```

---

## 3. Create Config File (`config/services.php`)

Tambahkan ke file `config/services.php`:

```php
'auth_center' => [
    'url' => env('AUTH_CENTER_URL', 'http://auth-center:8001'),
    'jwt_secret' => env('AUTH_CENTER_JWT_SECRET', ''),
],
```

---

## 4. Setup Routes dengan Middleware

### Route Pendaftaran Service (service 2)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

Route::prefix('api')->middleware(['auth.center', 'log.api'])->group(function () {
    
    // Public endpoint (tidak perlu auth)
    Route::post('/registrations', [RegistrationController::class, 'store']);
    
    // Protected endpoints (harus auth + role admin/perawat)
    Route::middleware('role:admin,perawat')->group(function () {
        Route::post('/registrations/{id}/validate', [RegistrationController::class, 'validate']);
        Route::put('/queues/poli/{number}/call', [RegistrationController::class, 'callQueue']);
    });
    
    // Get endpoints (read-only)
    Route::middleware('role:admin,perawat,dokter,kasir,admin_apotik')->group(function () {
        Route::get('/registrations/{id}', [RegistrationController::class, 'show']);
        Route::get('/registrations/patient/{patient_id}', [RegistrationController::class, 'getPatientHistory']);
        Route::get('/queues/poli', [RegistrationController::class, 'getPoliQueues']);
    });
});
```

### Route Pemeriksaan Service (service 3)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\PrescriptionController;

Route::prefix('api')->middleware(['auth.center', 'log.api'])->group(function () {
    
    // Dokter endpoints
    Route::middleware('role:dokter')->group(function () {
        Route::post('/examinations', [ExaminationController::class, 'store']);
        Route::put('/examinations/{id}', [ExaminationController::class, 'update']);
        Route::post('/prescriptions', [PrescriptionController::class, 'store']);
        Route::put('/prescriptions/{id}', [PrescriptionController::class, 'update']);
        Route::post('/examinations/{id}/finalize', [ExaminationController::class, 'finalize']);
    });
    
    // Read endpoints (akses dari service lain)
    Route::middleware('role:dokter,kasir,admin_apotik,perawat')->group(function () {
        Route::get('/examinations/{id}', [ExaminationController::class, 'show']);
        Route::get('/prescriptions/{id}', [PrescriptionController::class, 'show']);
        Route::get('/examinations/registration/{registration_id}', [ExaminationController::class, 'getByRegistration']);
    });
});
```

### Route Billing & Apotik Service (service 4)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PharmacyController;

Route::prefix('api')->middleware(['auth.center', 'log.api'])->group(function () {
    
    // Kasir endpoints
    Route::middleware('role:kasir')->group(function () {
        Route::post('/billings', [BillingController::class, 'store']);
        Route::post('/payments', [PaymentController::class, 'store']);
        Route::put('/billings/{id}/discount', [BillingController::class, 'applyDiscount']);
        Route::get('/billings/{id}', [BillingController::class, 'show']);
        Route::get('/payments/{id}', [PaymentController::class, 'show']);
    });
    
    // Admin Apotik endpoints
    Route::middleware('role:admin_apotik')->group(function () {
        Route::post('/pharmacy/queues', [PharmacyController::class, 'createQueue']);
        Route::put('/pharmacy/queues/{id}/dispense', [PharmacyController::class, 'dispense']);
        Route::get('/pharmacy/queues', [PharmacyController::class, 'getQueues']);
        Route::get('/pharmacy/queues/{id}', [PharmacyController::class, 'show']);
    });
    
    // Read endpoints
    Route::middleware('role:kasir,admin_apotik,perawat,dokter')->group(function () {
        Route::get('/billings/registration/{registration_id}', [BillingController::class, 'getByRegistration']);
    });
});
```

---

## 5. Install Dependencies

Middleware ini memerlukan beberapa package:

```bash
composer require firebase/php-jwt
composer require guzzlehttp/guzzle
```

---

## 6. Cara Menggunakan di Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function store(Request $request)
    {
        // Ambil user info dari middleware
        $authUser = $request->get('auth_user');
        $requestId = $request->get('request_id');
        
        // Log dengan tracing
        \Log::info('Billing created', [
            'request_id' => $requestId,
            'user_id' => $authUser['user_id'],
            'username' => $authUser['username'],
            'role' => $authUser['role'],
        ]);

        // Proses billing...
        $billing = [
            'id' => 'bill_001',
            'created_by' => $authUser['username'],
        ];

        return response()->json([
            'success' => true,
            'message' => 'Billing berhasil dibuat',
            'data' => $billing,
            'meta' => [
                'request_id' => $requestId,
            ],
        ], 201);
    }
}
```

---

## 7. Testing Middleware

### Test dengan Postman/cURL

```bash
# Generate token dari Auth Center terlebih dahulu
curl -X POST http://auth-center:8001/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "kasir001",
    "password": "password123",
    "client_service": "billing_pharmacy"
  }'

# Response akan berisi access_token
# Gunakan token tersebut untuk request ke service lain:

curl -X POST http://billing-pharmacy:8004/api/payments \
  -H "Authorization: Bearer <access_token>" \
  -H "Content-Type: application/json" \
  -H "X-Request-ID: req_123456" \
  -d '{
    "billing_id": "bill_001",
    "amount_paid": 495000,
    "payment_method": "cash"
  }'
```

---

## 8. Error Handling

Middleware akan return error response jika:
- Token tidak ada di header Authorization
- Token tidak valid atau kadaluarsa
- Token tidak aktif di Auth Center
- Role user tidak sesuai dengan endpoint

Response error:
```json
{
  "success": false,
  "message": "Token tidak valid atau kadaluarsa",
  "data": null,
  "errors": {
    "code": "UNAUTHORIZED",
    "detail": "Token tidak valid atau kadaluarsa"
  },
  "meta": {
    "request_id": "req_123456"
  }
}
```

---

## 9. Performance Tips

1. **Cache token introspect**: Middleware sudah cache hasil introspect selama 5 menit
2. **Use async logging**: Untuk LogApiRequest middleware, pertimbangkan queue untuk logging
3. **Connection pooling**: Setup HTTP client connection pooling untuk Auth Center calls
4. **Rate limiting**: Implementasi rate limiting di Auth Center introspect endpoint

---

## 10. Security Best Practices

1. **HTTPS only**: Semua service komunikasi harus via HTTPS
2. **Secret key management**: JWT secret key disimpan di .env, jangan hardcode
3. **Token expiration**: Token standard 1 hour, refresh token untuk long session
4. **Audit logging**: Semua request ter-log dengan user_id dan role
5. **IP whitelist**: (Opsional) Implementasi IP whitelist untuk service communication
