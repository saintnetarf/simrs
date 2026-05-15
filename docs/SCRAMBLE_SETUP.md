# Scramble API Documentation Setup Guide

## Instalasi Selesai! ✅

Scramble sudah berhasil diinstall dan dikonfigurasi untuk SIMRS Microservice.

---

## 📖 Akses Dokumentasi API

### Scramble UI Documentation
```
http://localhost:8001/docs/api
```
Halaman interaktif untuk browse dan test API dengan interface yang user-friendly.

### OpenAPI Specification (JSON)
```
http://localhost:8001/api.json
```
File spesifikasi OpenAPI 3.0 dalam format JSON. Bisa digunakan untuk:
- Generate SDK/client library
- Dokumentasi tambahan
- Tools integrasi pihak ketiga

---

## 🔧 Konfigurasi Scramble

File konfigurasi: `config/scramble.php`

### Info API
```php
'info' => [
    'version' => env('API_VERSION', '1.0.0'),
    'description' => 'SIMRS Microservice - Sistem Informasi Rumah Sakit terintegrasi...',
],
```

### UI Theme & Title
```php
'ui' => [
    'title' => 'SIMRS Microservice API Documentation',
    'theme' => 'system',  // 'light', 'dark', 'system'
    'layout' => 'responsive',
    'hide_try_it' => false,  // Aktifkan "Try It" feature
],
```

---

## 📝 Format Dokumentasi API dengan Attributes

Setiap endpoint sudah didokumentasikan menggunakan OpenAPI Attributes.

### Contoh POST endpoint:
```php
#[OA\Post(
    path: '/auth/login',
    tags: ['Auth Center'],
    summary: 'Login user dan dapatkan access token',
    description: 'Endpoint publik untuk login...',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['username', 'password'],
            properties: [
                new OA\Property(property: 'username', type: 'string'),
                // ... properties lainnya
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Login berhasil',
            content: new OA\JsonContent(
                properties: [
                    // ... response structure
                ]
            )
        ),
    ]
)]
Route::post('/auth/login', [AuthController::class, 'login']);
```

### Contoh GET endpoint dengan parameters:
```php
#[OA\Get(
    path: '/registrations/{id}',
    tags: ['Registration & Queue'],
    summary: 'Get detail pendaftaran',
    parameters: [
        new OA\Parameter(
            name: 'id',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'string')
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Detail pendaftaran',
            // ...
        ),
    ]
)]
Route::get('/registrations/{id}', [RegistrationController::class, 'show']);
```

---

## 🔐 Securing Documentation

### Batasi akses dokumentasi hanya untuk development/staging:

Edit `config/scramble.php`:
```php
'security' => [
    'expose_documentation' => env('SCRAMBLE_EXPOSE_DOCS', true),
],
```

Atau setup middleware di route:
```php
Route::get('/docs/api', function () {
    // Hanya untuk development
    if (app()->environment('production')) {
        abort(404);
    }
    // Redirect ke Scramble docs
});
```

---

## 🚀 Generate & Export Dokumentasi

### Export dokumentasi ke file JSON:
```bash
php artisan scramble:export api.json
```

Hasilnya bisa digunakan dengan tools seperti:
- **Redocly** - Static documentation
- **Swagger UI** - Interactive API explorer
- **Postman** - Import collection
- **API clients generator** - Generate SDK

---

## 📚 Struktur Dokumentasi yang Sudah Ada

Semua endpoint sudah didokumentasikan dengan:

### 1. **Auth Center (8 endpoints)**
   - POST /auth/login
   - POST /auth/introspect
   - POST /auth/logout
   - GET /auth/refresh-token
   - POST /users
   - GET /users
   - PUT /users/{id}
   - PUT /users/{id}/change-password

### 2. **Registration & Queue (7 endpoints)**
   - POST /registrations
   - POST /registrations/{id}/validate
   - GET /registrations/{id}
   - GET /queues/poli
   - PUT /queues/poli/{number}/call
   - GET /registrations/patient/{id}
   - PUT /registrations/{id}/status

### 3. **Examination & Prescription (7 endpoints)**
   - POST /examinations
   - GET /examinations/{id}
   - POST /prescriptions
   - GET /prescriptions/{id}
   - PUT /prescriptions/{id}
   - GET /examinations/registration/{id}
   - POST /examinations/{id}/finalize

### 4. **Billing & Pharmacy (10 endpoints)**
   - POST /billings
   - GET /billings/{id}
   - POST /payments
   - GET /payments/{id}
   - PUT /billings/{id}/discount
   - POST /pharmacy/queues
   - GET /pharmacy/queues
   - PUT /pharmacy/queues/{id}/dispense
   - GET /pharmacy/queues/{id}
   - GET /billings/registration/{id}

---

## 🔧 Menambah Dokumentasi Endpoint Baru

### 1. Tambahkan Attribute di Route:

```php
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/path/to/endpoint',
    tags: ['Tag Name'],
    summary: 'Short description',
    description: 'Longer description',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['field1', 'field2'],
            properties: [
                new OA\Property(property: 'field1', type: 'string'),
                new OA\Property(property: 'field2', type: 'integer'),
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: 'Success response',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean'),
                    new OA\Property(property: 'data', type: 'object'),
                ]
            )
        ),
    ]
)]
Route::post('/path/to/endpoint', [Controller::class, 'method']);
```

### 2. Validasi dokumentasi:
```bash
php artisan scramble:validate
```

### 3. Lihat di browser:
```
http://localhost:8001/docs/api
```

---

## 📊 Best Practices

1. **Selalu gunakan tags** untuk organize endpoint per service/feature
2. **Tulis description yang jelas** untuk setiap endpoint
3. **Dokumentasi request & response** dengan complete
4. **Dokumentasi error responses** dengan status codes yang sesuai
5. **Gunakan examples** untuk request/response body
6. **Update dokumentasi** ketika ada perubahan API
7. **Test dengan "Try It" feature** untuk verifikasi

---

## 🧪 Testing API dari Dokumentasi

Di halaman `/docs/api`:

1. Klik endpoint yang ingin di-test
2. Klik tombol **"Try it"**
3. Isi request body (jika ada)
4. Klik **"Send"**
5. Lihat response di bagian bawah

---

## 📂 File Struktur Scramble

```
project/
├── config/
│   └── scramble.php              # Config Scramble
├── routes/
│   └── api.php                   # API routes dengan OpenAPI attributes
├── resources/
│   └── views/vendor/scramble/    # Scramble UI views
└── app/
    └── Http/Middleware/          # Middleware untuk auth, etc
```

---

## 🔗 URLs & Endpoints

| Endpoint | Deskripsi |
|----------|-----------|
| `/docs/api` | Scramble UI Documentation |
| `/api.json` | OpenAPI JSON Specification |
| `/health` | Health check (jika ada) |

---

## 📝 Environment Variables

Tambahkan ke `.env`:

```env
# API Documentation
API_VERSION=1.0.0
SCRAMBLE_EXPOSE_DOCS=true

# Auth (untuk test dengan credentials)
AUTH_CENTER_URL=http://localhost:8001
AUTH_CENTER_JWT_SECRET=your-secret-key
```

---

## 🎯 Next Steps

1. ✅ Scramble sudah terinstall
2. ✅ API routes sudah didokumentasikan
3. ✅ Config sudah di-setup
4. **TODO**: Implementasi actual endpoint controllers
5. **TODO**: Setup database & models
6. **TODO**: Test end-to-end flow
7. **TODO**: Deploy ke staging untuk review dokumentasi

---

## 📞 Troubleshooting

### Dokumentasi tidak muncul di `/docs/api`?
- Pastikan routes/api.php sudah ada OpenAPI attributes
- Jalankan: `php artisan cache:clear`
- Refresh browser

### "Try it" feature tidak berfungsi?
- Pastikan CORS sudah dikonfigurasi
- Cek `config/cors.php`
- Pastikan middleware CORS aktif di route

### OpenAPI JSON tidak valid?
- Jalankan: `php artisan scramble:validate`
- Cek error messages dan fix attributes

---

## 🚀 Production Deployment Tips

1. **Disable documentation di production** (set `SCRAMBLE_EXPOSE_DOCS=false`)
2. **Export OpenAPI spec** untuk berbagi dengan clients
3. **Host documentation terpisah** di sub-domain atau external doc site
4. **Implement API rate limiting** sebelum production
5. **Setup API monitoring & logging** untuk track penggunaan

---

Generated dengan Scramble ✨
https://scramble.dedoc.co
