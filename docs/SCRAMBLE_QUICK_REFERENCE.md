# Scramble Quick Reference

## 🚀 Start Development Server

```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

Jika port sudah terpakai, gunakan port alternatif:
```bash
php artisan serve --port=8001
```

---

## 📖 Akses Dokumentasi

### Scramble UI (Recommended)
```
http://localhost:8000/docs/api
```
Interface interaktif, search-friendly, dengan "Try It" feature.

### Raw OpenAPI JSON
```
http://localhost:8000/api.json
```
Dapat di-import ke tools lain seperti Postman, Insomnia, dll.

---

## 🧪 Testing Endpoints

### Dari Scramble UI:

1. Buka http://localhost:8000/docs/api
2. Cari endpoint yang ingin di-test
3. Klik endpoint untuk expand
4. Klik tombol "Try It"
5. Isi request body (jika ada)
6. Klik "Send"
7. Lihat response

### Dari cURL:

```bash
# Login (get token)
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "kasir001",
    "password": "password123",
    "client_service": "billing_pharmacy"
  }'

# Gunakan token untuk request berikutnya:
curl -X GET http://localhost:8000/api/registrations/reg_001 \
  -H "Authorization: Bearer <token_dari_login>" \
  -H "Content-Type: application/json"
```

### Dari Postman:

1. Import URL: `http://localhost:8000/api.json`
2. Postman akan auto-generate collection dengan semua endpoints
3. Setup environment variables untuk token
4. Test setiap endpoint

### Dari Insomnia:

1. New Request → Create from OpenAPI
2. Paste: `http://localhost:8000/api.json`
3. Insomnia akan generate requests
4. Test endpoints

---

## 📝 API Documentation Structure

Setiap endpoint didokumentasikan dengan:

```php
#[OA\Post(
    path: '/endpoint/path',           // URL endpoint
    tags: ['Category'],                // Group untuk UI
    summary: 'Short description',      // Title di UI
    description: '...',                // Detailed description
    security: [['bearerAuth' => []]],  // Auth requirement
    requestBody: new OA\RequestBody(   // Input documentation
        required: true,
        content: new OA\JsonContent(
            required: ['field1'],
            properties: [
                new OA\Property(property: 'field1', type: 'string'),
            ]
        )
    ),
    responses: [                        // Output documentation
        new OA\Response(
            response: 200,
            description: 'Success',
            content: new OA\JsonContent(...)
        ),
    ]
)]
Route::post('/endpoint/path', [Controller::class, 'method']);
```

---

## 🔐 Authentication for Testing

### Get Token:
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "kasir001",
    "password": "password123",
    "client_service": "billing_pharmacy"
  }'
```

Response:
```json
{
  "success": true,
  "data": {
    "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expires_in": 3600,
    "user": {
      "id": "usr_001",
      "role": "kasir"
    }
  }
}
```

### Use Token in Requests:
```bash
curl -X POST http://localhost:8000/api/payments \
  -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..." \
  -H "Content-Type: application/json" \
  -d '{
    "billing_id": "bill_001",
    "amount_paid": 495000
  }'
```

---

## 🎯 Endpoint Categories

### 1. Auth Center
```
POST   /api/auth/login              - Login user
POST   /api/auth/introspect         - Verify token
GET    /api/auth/refresh-token      - Refresh token
POST   /api/auth/logout             - Logout
POST   /api/users                   - Create user (admin)
GET    /api/users                   - List users
PUT    /api/users/{id}              - Update user
```

### 2. Registration & Queue
```
POST   /api/registrations                      - Online registration
POST   /api/registrations/{id}/validate        - Validate & assign queue
GET    /api/registrations/{id}                 - Get registration detail
GET    /api/queues/poli                        - Get poli queues
PUT    /api/queues/poli/{number}/call          - Call patient
GET    /api/registrations/patient/{patient_id} - Get patient history
```

### 3. Examination & Prescription
```
POST   /api/examinations                       - Create examination
GET    /api/examinations/{id}                  - Get examination detail
POST   /api/prescriptions                      - Create prescription
GET    /api/prescriptions/{id}                 - Get prescription detail
PUT    /api/prescriptions/{id}                 - Update prescription
GET    /api/examinations/registration/{id}    - Get exam history
```

### 4. Billing & Pharmacy
```
POST   /api/billings                    - Create billing
GET    /api/billings/{id}               - Get billing detail
POST   /api/payments                    - Record payment
GET    /api/payments/{id}               - Get payment detail
POST   /api/pharmacy/queues             - Create pharmacy queue
GET    /api/pharmacy/queues             - Get pharmacy queues
PUT    /api/pharmacy/queues/{id}/dispense - Dispense medicine
```

---

## 🔄 Sample Workflow

### Complete Patient Journey Flow:

```bash
# 1. Pasien daftar online
curl -X POST http://localhost:8000/api/registrations \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "Budi Pasien",
    "phone": "08123456789",
    "visit_date": "2026-05-20",
    "poli_preference": "poli_umum"
  }'
# Response: registration_id = "reg_001"

# 2. Admin login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "admin_poli",
    "password": "password123",
    "client_service": "registration_queue"
  }'
# Response: token = "eyJ..."

# 3. Admin validasi registrasi
curl -X POST http://localhost:8000/api/registrations/reg_001/validate \
  -H "Authorization: Bearer eyJ..." \
  -H "Content-Type: application/json" \
  -d '{
    "poli_final": "poli_umum",
    "examination_type": "pemeriksaan_umum",
    "validator_id": "emp_001"
  }'

# 4. Dokter login
curl -X POST http://localhost:8000/api/auth/login \
  -d '{"username": "dokter001", ...}'

# 5. Dokter buat examination
curl -X POST http://localhost:8000/api/examinations \
  -H "Authorization: Bearer eyJ..." \
  -d '{"registration_id": "reg_001", ...}'

# 6. Dokter buat resep
curl -X POST http://localhost:8000/api/prescriptions \
  -H "Authorization: Bearer eyJ..." \
  -d '{"examination_id": "exam_001", ...}'

# 7. Kasir login dan buat billing
curl -X POST http://localhost:8000/api/auth/login \
  -d '{"username": "kasir001", ...}'

curl -X POST http://localhost:8000/api/billings \
  -H "Authorization: Bearer eyJ..." \
  -d '{"registration_id": "reg_001", ...}'

# 8. Kasir catat pembayaran
curl -X POST http://localhost:8000/api/payments \
  -H "Authorization: Bearer eyJ..." \
  -d '{"billing_id": "bill_001", "amount_paid": 495000}'

# 9. Admin apotik buat antrian pengambilan obat
curl -X POST http://localhost:8000/api/pharmacy/queues \
  -H "Authorization: Bearer eyJ..." \
  -d '{"prescription_id": "pres_001"}'

# 10. Admin apotik berikan obat
curl -X PUT http://localhost:8000/api/pharmacy/queues/pq_001/dispense \
  -H "Authorization: Bearer eyJ..." \
  -d '{"pharmacy_staff_id": "emp_apotik_001"}'
```

---

## 🛠️ Commands

```bash
# Clear cache (jika dokumentasi tidak update)
php artisan cache:clear

# Validate OpenAPI spec
php artisan scramble:validate

# Export OpenAPI spec to file
php artisan scramble:export api.json

# Clear & rebuild cache
php artisan cache:clear && php artisan config:cache

# View logs
tail -f storage/logs/laravel.log

# Run tests
php artisan test
```

---

## 📊 Response Format Standard

### Success Response:
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    "id": "xxx_001",
    "name": "Value"
  },
  "meta": {
    "request_id": "req_123456"
  }
}
```

### Error Response:
```json
{
  "success": false,
  "message": "Error description",
  "data": null,
  "errors": {
    "code": "ERROR_CODE",
    "detail": "Detailed error message"
  },
  "meta": {
    "request_id": "req_123456"
  }
}
```

### Paginated Response:
```json
{
  "success": true,
  "data": [
    { "id": 1, "name": "Item 1" },
    { "id": 2, "name": "Item 2" }
  ],
  "meta": {
    "page": 1,
    "per_page": 20,
    "total": 100,
    "total_pages": 5,
    "request_id": "req_123456"
  }
}
```

---

## 🐛 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Docs tidak muncul | `php artisan cache:clear` + refresh browser |
| 404 error | Check route path di `routes/api.php` |
| 401 Unauthorized | Token missing atau invalid |
| CORS error | Check `config/cors.php` |
| Try It tidak bekerja | Enable CORS, check auth middleware |

---

## 📚 Documentation Files

```
docs/
├── README.md                              # Main index
├── SCRAMBLE_SETUP.md                      # Scramble guide
├── SCRAMBLE_INSTALLATION_SUMMARY.md       # Installation status
├── SCRAMBLE_QUICK_REFERENCE.md           # This file
├── API_AUTH_CENTER.md                     # Auth API details
├── API_REGISTRATION_QUEUE.md              # Registration API details
├── API_EXAMINATION_PRESCRIPTION.md        # Examination API details
├── API_BILLING_PHARMACY.md                # Billing API details
├── MIDDLEWARE_SETUP_GUIDE.md              # Middleware config
└── SEQUENCE_FLOW_PATIENT_JOURNEY.md       # Patient workflow

database/schema/
├── auth_center_schema.sql
├── registration_queue_schema.sql
├── examination_prescription_schema.sql
└── billing_pharmacy_schema.sql

app/Http/Middleware/
├── VerifyAuthCenterToken.php
├── VerifyUserRole.php
└── LogApiRequest.php
```

---

## 🎓 Learning Path

1. **Read** → [SCRAMBLE_INSTALLATION_SUMMARY.md](./SCRAMBLE_INSTALLATION_SUMMARY.md)
2. **Explore** → Open http://localhost:8000/docs/api in browser
3. **Test** → Use "Try It" feature atau cURL commands
4. **Understand** → Read API documentation files (API_*.md)
5. **Implement** → Create controllers & setup database
6. **Integrate** → Setup middleware & authentication
7. **Deploy** → Follow production checklist

---

**Last Updated:** May 13, 2026
**Framework:** Laravel 13
**Documentation Tool:** Scramble 0.13.22
