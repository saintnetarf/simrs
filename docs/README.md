# Microservice SIMRS - Documentation Index



Dokumentasi lengkap sistem microservice SIMRS dengan 4 service terintegrasi via Auth Center.

## 🚀 Quick Access

- **📖 [Scramble API Documentation UI](http://localhost:8000/docs/api)** - Interactive API explorer
- **📄 [OpenAPI JSON Spec](http://localhost:8000/api.json)** - Raw OpenAPI 3.0 specification
- **� [Postman Collection](./postman/SIMRS-Microservice.postman_collection.json)** - Import semua service ke Postman
- **🧩 [Postman Environment](./postman/SIMRS-Microservice.postman_environment.json)** - Variable environment untuk testing
- **�📋 [Scramble Setup Guide](./SCRAMBLE_SETUP.md)** - How to use Scramble documentation

---

## 🎯 Scramble API Documentation

### [Scramble Installation Summary](./SCRAMBLE_INSTALLATION_SUMMARY.md)
**Status:** ✅ Sudah diinstall & dikonfigurasi
- 32 endpoints sudah didokumentasikan
- OpenAPI 3.0 attributes sudah diimplementasikan
- UI documentation ready di `/docs/api`

### [Scramble Setup Guide](./SCRAMBLE_SETUP.md)
Panduan lengkap tentang:
- Akses dokumentasi UI
- Export OpenAPI spec
- Menambah dokumentasi endpoint baru
- Best practices & troubleshooting

### [Scramble Quick Reference](./SCRAMBLE_QUICK_REFERENCE.md)
Referensi cepat:
- Cara test endpoint
- cURL examples
- Sample workflow lengkap

### [Production Deployment Guide](./PRODUCTION_DEPLOYMENT.md)
Checklist untuk deployment ke production:
- Security configuration
- Disable documentation access
- CI/CD pipeline setup
- Monitoring & logging
- Disaster recovery

### [Implementation Checklist](./IMPLEMENTATION_CHECKLIST.md)
Panduan lengkap implementasi setelah dokumentasi:
- Phase-by-phase tasks
- Migration & model creation
- Controller implementation
- Testing strategy
- Team task breakdown

---

## 📋 Dokumentasi API (Manual)

### 1. [Auth Center Service API](./API_AUTH_CENTER.md)
**Service Kelompok 1** - Authentication & Authorization Center
- POST /auth/login - Login user
- POST /auth/introspect - Verifikasi token
- POST /auth/logout - Logout
- POST /users - Create user (admin only)
- GET /users - List users
- PUT /users/{id} - Update user
- PUT /users/{id}/change-password - Change password

**Endpoints**: 8 endpoints
**Authentication**: JWT Token
**Database**: `auth_center`

---

### 2. [Registration & Queue Service API](./API_REGISTRATION_QUEUE.md)
**Service Kelompok 2** - Pendaftaran & Manajemen Antrian Poli
- POST /registrations - Daftar online pasien
- POST /registrations/{id}/validate - Validasi & assign nomor antrian
- GET /registrations/{id} - Get detail registrasi
- GET /queues/poli - Get daftar antrian per poli
- PUT /queues/poli/{number}/call - Panggil nomor antrian
- GET /registrations/patient/{patient_id} - Get riwayat pasien
- PUT /registrations/{id}/status - Update status

**Endpoints**: 7 endpoints
**Authentication**: Bearer Token (dari Auth Center)
**Roles**: admin, perawat (for validation), semua untuk read
**Database**: `registration_queue`

---

### 3. [Examination & Prescription Service API](./API_EXAMINATION_PRESCRIPTION.md)
**Service Kelompok 3** - Pemeriksaan Dokter & Resep Obat
- POST /examinations - Buat catatan pemeriksaan
- GET /examinations/{id} - Get detail pemeriksaan
- POST /prescriptions - Buat resep
- GET /prescriptions/{id} - Get detail resep
- PUT /prescriptions/{id} - Update resep
- GET /examinations/registration/{id} - Get riwayat pemeriksaan
- POST /examinations/{id}/finalize - Finalisasi pemeriksaan

**Endpoints**: 7 endpoints
**Authentication**: Bearer Token (dari Auth Center)
**Roles**: dokter (write), semua (read)
**Database**: `examination_prescription`

---

### 4. [Billing & Pharmacy Service API](./API_BILLING_PHARMACY.md)
**Service Kelompok 4** - Kasir & Apotik
#### Kasir Endpoints:
- POST /billings - Buat invoice
- GET /billings/{id} - Get detail invoice
- POST /payments - Catat pembayaran
- GET /payments/{id} - Get detail pembayaran
- PUT /billings/{id}/discount - Apply diskon

#### Apotik Endpoints:
- POST /pharmacy/queues - Buat antrian apotik
- GET /pharmacy/queues - Get daftar antrian
- PUT /pharmacy/queues/{id}/dispense - Berikan obat
- GET /pharmacy/queues/{id} - Get detail antrian apotik
- GET /billings/registration/{id} - Get billing history

**Endpoints**: 10 endpoints
**Authentication**: Bearer Token (dari Auth Center)
**Roles**: kasir (billing), admin_apotik (pharmacy)
**Database**: `billing_pharmacy`

---

## 🗄️ Database Schemas

### 1. [Auth Center Schema](./database/schema/auth_center_schema.sql)
```
Tables:
- users (user credentials & info)
- user_service_access (role-based service access)
- tokens (JWT token tracking)
- login_logs (audit log untuk login)
- api_access_logs (audit log untuk API access)
- role_permissions (fine-grained access control)
- user_sessions (multi-device login tracking)
```

### 2. [Registration & Queue Schema](./database/schema/registration_queue_schema.sql)
```
Tables:
- patients (data pasien)
- registrations (pendaftaran per kunjungan)
- polis (master poli/departemen)
- examination_types (master jenis pemeriksaan)
- daily_queues (antrian harian per poli)
- registration_status_histories (audit trail status)
```

### 3. [Examination & Prescription Schema](./database/schema/examination_prescription_schema.sql)
```
Tables:
- examinations (catatan pemeriksaan dokter)
- doctors (cache dokter dari auth center)
- prescriptions (resep obat)
- prescription_items (detail item resep)
- medicines (master obat)
- diagnoses (master diagnosa ICD-10)
- examination_histories (audit trail pemeriksaan)
- prescription_histories (audit trail resep)
```

### 4. [Billing & Pharmacy Schema](./database/schema/billing_pharmacy_schema.sql)
```
Tables (Billing):
- billings (invoice)
- billing_items (detail item billing)
- payments (pembayaran)
- refunds (pengembalian dana)
- service_fees (master tarif layanan)

Tables (Pharmacy):
- pharmacy_queues (antrian pengambilan obat)
- pharmacy_dispensing_items (tracking obat diberikan)
- pharmacy_inventory (stok obat)
- pharmacy_inventory_histories (audit log stok)
- pharmacy_schedules (jadwal apotik)
```

---

## 🔐 Middleware & Authentication

### [Middleware Setup Guide](./MIDDLEWARE_SETUP_GUIDE.md)

#### Middleware Files:
1. **VerifyAuthCenterToken.php** - Verifikasi JWT token dari Auth Center
   - Extract Bearer token dari Authorization header
   - Validasi token signature (lokal)
   - Introspect token ke Auth Center
   - Cache hasil introspect (5 menit)
   - Tambahkan user info ke request

2. **VerifyUserRole.php** - Validasi role user
   - Cek apakah user memiliki role yang diizinkan
   - Return 403 jika role tidak sesuai

3. **LogApiRequest.php** - Log semua API request untuk audit
   - Catat user_id, endpoint, method, status
   - Catat response time
   - Add request_id ke response header

#### Configuration:
```php
// bootstrap/app.php atau app/Http/Kernel.php
$middleware->alias([
    'auth.center' => VerifyAuthCenterToken::class,
    'role' => VerifyUserRole::class,
]);

// Environment variables (.env)
AUTH_CENTER_URL=http://auth-center:8001
AUTH_CENTER_JWT_SECRET=your-secret-key
```

#### Usage di Routes:
```php
Route::middleware(['auth.center', 'role:kasir|admin_apotik'])->group(function () {
    Route::post('/payments', [PaymentController::class, 'store']);
});
```

---

## 📈 Patient Journey Flow

### [Complete Sequence Flow Documentation](./SEQUENCE_FLOW_PATIENT_JOURNEY.md)

#### 9 Phases:
1. **Daftar Online** (Service 2) - Pasien create registration
2. **Validasi Admin** (Service 2) - Admin assign poli & nomor antrian
3. **Antrian Poli** (Service 2) - Pasien datang & dipanggil
4. **Pemeriksaan** (Service 3) - Dokter periksa pasien
5. **Resep** (Service 3) - Dokter buat resep (jika ada)
6. **Billing** (Service 4) - Kasir buat invoice
7. **Pembayaran** (Service 4) - Pasien bayar
8. **Antrian Apotik** (Service 4) - Apotik siapkan obat
9. **Ambil Obat** (Service 4) - Pasien ambil obat

#### Status Progression:
```
ONLINE_REGISTERED → VALIDATED → QUEUED_POLI → IN_EXAMINATION → 
EXAMINED → [PRESCRIBED] → BILLED → PAID → 
[PHARMACY_QUEUED → MEDICINE_DISPENSED] → COMPLETED
```

#### Includes:
- Detailed sequence diagrams per phase
- Alternative flows (tanpa resep, pembayaran partial, batal)
- cURL examples untuk setiap API call
- Data flow diagram antar service
- Status summary table

---

## 🚀 Quick Start Implementation

### Untuk Team Kelompok 1 (Auth Center):
1. Baca [API_AUTH_CENTER.md](./API_AUTH_CENTER.md) untuk contract API
2. Setup database dengan [auth_center_schema.sql](./database/schema/auth_center_schema.sql)
3. Implement endpoints: /auth/login, /auth/introspect, /users management
4. Generate JWT token dengan HS256 signature
5. Setup rate limiting untuk login endpoint
6. Implement audit logging

### Untuk Team Kelompok 2 (Registration & Queue):
1. Baca [API_REGISTRATION_QUEUE.md](./API_REGISTRATION_QUEUE.md)
2. Setup database dengan [registration_queue_schema.sql](./database/schema/registration_queue_schema.sql)
3. Copy middleware: VerifyAuthCenterToken.php, VerifyUserRole.php
4. Implement endpoints per [API documentation](./API_REGISTRATION_QUEUE.md)
5. Setup flow: online register → validate → queue management
6. Call Service 4 API untuk update status VALIDATED, EXAMINED, PAID, MEDICINE_DISPENSED

### Untuk Team Kelompok 3 (Examination & Prescription):
1. Baca [API_EXAMINATION_PRESCRIPTION.md](./API_EXAMINATION_PRESCRIPTION.md)
2. Setup database dengan [examination_prescription_schema.sql](./database/schema/examination_prescription_schema.sql)
3. Copy middleware files
4. Implement endpoints: examination, prescription creation
5. Call Service 2 API untuk update status EXAMINED, PRESCRIBED
6. Call Service 4 API untuk get billing info jika perlu

### Untuk Team Kelompok 4 (Billing & Pharmacy):
1. Baca [API_BILLING_PHARMACY.md](./API_BILLING_PHARMACY.md)
2. Setup database dengan [billing_pharmacy_schema.sql](./database/schema/billing_pharmacy_schema.sql)
3. Copy middleware files
4. Implement Kasir endpoints: billing, payment
5. Implement Apotik endpoints: pharmacy queue, dispensing
6. Call Service 3 API untuk get prescription & medicine details
7. Call Service 2 API untuk update registration status

---

## 🔗 Service Integration Points

### Service 2 ↔ Service 3:
```
Service 2 → GET /examinations/registration/{id}
Service 3 → PUT /registrations/{id}/status (EXAMINED, PRESCRIBED)
```

### Service 3 ↔ Service 4:
```
Service 4 → GET /prescriptions/{id}
Service 4 → GET /prescriptions/{id}/items
Service 3 ← PUT /registrations/{id}/status (PRESCRIBED)
```

### Service 2 ↔ Service 4:
```
Service 4 → PUT /registrations/{id}/status (BILLED, PAID, MEDICINE_DISPENSED)
Service 2 ← GET /registrations/{id}
Service 2 ← GET /registrations/patient/{id}
```

---

## 📊 Environment Variables Setup

### All Services:
```env
# Auth Center
AUTH_CENTER_URL=http://auth-center:8001
AUTH_CENTER_JWT_SECRET=your-secret-key-here

# Database - masing-masing service
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=auth_center|registration_queue|examination_prescription|billing_pharmacy
DB_USERNAME=root
DB_PASSWORD=password

# Service Communication
SERVICE_NAME=auth_center|registration_queue|examination_prescription|billing_pharmacy
SERVICE_PORT=8001|8002|8003|8004

# Logging
LOG_LEVEL=debug
LOG_CHANNEL=stack

# Cache (untuk token caching)
CACHE_DRIVER=redis
REDIS_HOST=redis
REDIS_PORT=6379

# Queue (untuk async logging)
QUEUE_CONNECTION=redis
```

---

## ✅ Checklist Implementation

### Auth Center:
- [ ] Database schema created
- [ ] User management API endpoints
- [ ] Login endpoint with JWT generation
- [ ] Token introspect endpoint
- [ ] Audit logging
- [ ] Rate limiting
- [ ] Test dengan Postman

### Service 2-4:
- [ ] Database schema created
- [ ] Middleware files copied & configured
- [ ] Environment variables set
- [ ] API endpoints implemented
- [ ] Input validation
- [ ] Error handling
- [ ] Audit logging
- [ ] Inter-service API calls working
- [ ] Status update notifications
- [ ] Test end-to-end flow

---

## 📞 Support & Notes

- Semua API response menggunakan standard JSON format
- Semua request harus include `X-Request-ID` header untuk tracing
- Token cache di service lain selama 5 menit untuk performa
- Recommend menggunakan Redis untuk cache & session
- Implement database connection pooling untuk inter-service calls
- Setup monitoring & alerting untuk API performance
- Jangan lupa HTTPS untuk production
- Implement rate limiting di semua endpoint
