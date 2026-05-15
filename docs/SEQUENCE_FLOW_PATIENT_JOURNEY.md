# Sequence Flow: Alur Lengkap Pasien dari Daftar Sampai Tebus Obat

## 1. FLOW OVERVIEW (Status Progression)

```
┌─────────────────────────────────────────────────────────────────────┐
│                      PATIENT JOURNEY FLOW                            │
└─────────────────────────────────────────────────────────────────────┘

  [1]                  [2]                    [3]                [4]
DAFTAR ONLINE ──→ VALIDASI ADMIN ──→ PEMERIKSAAN ──→ PEMBAYARAN ──→ TEBUS OBAT
     ↓                    ↓                  ↓              ↓            ↓
  SERVICE 2           SERVICE 2         SERVICE 3      SERVICE 4    SERVICE 4
(Registration_      (Registration_     (Examination)  (Billing_    (Pharmacy)
  Queue)             Queue)              +             Pharmacy)
                                       Prescription
```

---

## 2. DETAILED SEQUENCE DIAGRAM

### Phase 1: Pendaftaran Online (SERVICE 2 - Registration & Queue)

```
PASIEN                          SERVICE 2                    DATABASE
  │                              │                             │
  │──────POST /registrations────→│                             │
  │ (nama, telepon, email,       │                             │
  │  tanggal kunjungan,          │                             │
  │  poli pilihan)               │                             │
  │                              │─── INSERT patient ─────────→│
  │                              │                             │
  │                              │─── INSERT registration ───→│
  │                              │    (status: ONLINE_       │
  │                              │     REGISTERED)            │
  │                              │←──────────────────────────│
  │←────201 Created──────────────│                             │
  │ {registration_id,            │                             │
  │  queue_number: null,         │                             │
  │  status: ONLINE_REGISTERED}  │                             │
  │                              │                             │
```

**Status: ONLINE_REGISTERED**
- Pasien berhasil mendaftar
- Belum ada nomor antrian
- Menunggu validasi admin/perawat

---

### Phase 2: Validasi oleh Admin/Perawat (SERVICE 2)

```
ADMIN/PERAWAT                  SERVICE 2                    DATABASE
   │                            │                            │
   │─POST /registrations/{id}   │                            │
   │ /validate                  │                            │
   │ (poli_final,               │                            │
   │  examination_type,         │                            │
   │  validator_id)            │                            │
   │                            │                            │
   │                            │─ GET daily_queues ────────→│
   │                            │   (cari queue number)      │
   │                            │←──────────────────────────│
   │                            │                            │
   │                            │─ UPDATE registration ─────→│
   │                            │   (status: VALIDATED,     │
   │                            │    queue_number: 001,     │
   │                            │    poli_final: poli_umum) │
   │                            │                            │
   │                            │─ INSERT daily_queue ──────→│
   │                            │   (queue_date, queue#)    │
   │                            │←──────────────────────────│
   │                            │                            │
   │←────200 OK──────────────────│                            │
   │ {status: VALIDATED,        │                            │
   │  queue_number: 001,        │                            │
   │  poli_final: poli_umum,    │                            │
   │  queue_estimated_time:     │                            │
   │  '09:30'}                  │                            │
   │                            │                            │
```

**Status: VALIDATED**
- Data pasien sudah valid
- Nomor antrian sudah ditentukan
- Estimasi waktu untuk dipanggil sudah ada
- Pasien bisa datang ke poli

---

### Phase 3: Pasien Datang ke Poli & Antrian (SERVICE 2)

```
ADMIN POLI                      SERVICE 2                    DATABASE
   │                             │                            │
   │── GET /queues/poli ─────────→│                            │
   │    (poli_id, date)           │                            │
   │                              │─ GET daily_queues ──────→│
   │                              │   (status: PENDING)      │
   │                              │←──────────────────────────│
   │←───────────────────────────────│                            │
   │  {total_queue: 5,            │                            │
   │   queues: [{                 │                            │
   │     queue_number: 001,       │                            │
   │     patient_name: "Budi",    │                            │
   │     status: "PENDING",       │                            │
   │     arrival_time: null       │                            │
   │   }]}                        │                            │
   │                              │                            │
   │ (Pasien datang dan harus    │                            │
   │  check in di admin poli)    │                            │
   │                              │                            │
   │── PUT /queues/poli/001/call ─→│                            │
   │    (panggil pasien nomor 001) │                            │
   │                              │─ UPDATE daily_queue ─────→│
   │                              │   (called_at: now,       │
   │                              │    status: CALLED)       │
   │                              │                            │
   │                              │─ UPDATE registration ────→│
   │                              │   (status:               │
   │                              │    IN_EXAMINATION)       │
   │                              │←──────────────────────────│
   │←────200 OK──────────────────────│                            │
   │ {queue_number: 001,          │                            │
   │  patient_name: "Budi",       │                            │
   │  status: IN_EXAMINATION,     │                            │
   │  called_at: "2026-05-20      │                            │
   │  T09:30:00Z"}               │                            │
   │                              │                            │
   │ "Nomor 001 silakan masuk     │                            │
   │  ke ruang pemeriksaan"       │                            │
   │                              │                            │
```

**Status: IN_EXAMINATION**
- Pasien sudah dipanggil
- Pasien sedang diperiksa dokter

---

### Phase 4: Dokter Memeriksa Pasien (SERVICE 3 - Examination & Prescription)

```
DOKTER                          SERVICE 3                    SERVICE 2  AUTH_CTR
   │                             │                             │        │
   │── POST /examinations ───────→│                             │        │
   │    (registration_id,         │                             │        │
   │     complaint,               │── Verify token ────────────────────→│
   │     diagnosis,               │   (middleware)                      │
   │     physical_exam,           │←──────────────────────────────────│
   │     anamnesis)              │ {user_id, role: dokter}            │
   │                              │                             │        │
   │                              │─ INSERT examination ──────→│        │
   │                              │   (status: DRAFT)          │        │
   │                              │                             │        │
   │                              │─ API UPDATE registration status ────→
   │                              │   (status: EXAMINED)       │
   │                              │←────────────────────────────│
   │                              │                            │
   │←────201 Created───────────────│                            │
   │ {examination_id: exam_001,   │                            │
   │  status: EXAMINED}           │                            │
   │                              │                            │
   │ (Pasien diperiksa)          │                            │
   │                              │                            │
   │── POST /prescriptions ──────→│                            │
   │    (examination_id,          │                            │
   │     items: [                 │                            │
   │       {medicine: "Paracet",  │                            │
   │        qty: 10,              │                            │
   │        dosage: "1-2 x 3x"}   │                            │
   │     ],                       │                            │
   │     instruction: "...")      │                            │
   │                              │                            │
   │                              │─ INSERT prescription ─────→│
   │                              │   (status: PRESCRIBED)    │
   │                              │                            │
   │                              │─ INSERT prescription ─────→│
   │                              │   items                    │
   │                              │                            │
   │←────201 Created───────────────│                            │
   │ {prescription_id: pres_001,  │                            │
   │  total_items: 2,             │                            │
   │  total_medicine_price:       │                            │
   │  95000}                      │                            │
   │                              │                            │
```

**Status: EXAMINED + PRESCRIBED**
- Pemeriksaan selesai
- Resep sudah dibuat (jika ada)
- Ready untuk pembayaran

---

### Phase 5: Kasir Membuat Billing (SERVICE 4 - Billing & Pharmacy)

```
KASIR                          SERVICE 4                 SERVICE 3    DATABASE
   │                            │                          │            │
   │── POST /billings ─────────→│                          │            │
   │    (registration_id,       │                          │            │
   │     examination_id)        │                          │            │
   │                            │                          │            │
   │                            │─ GET prescription ────→ GET /         │
   │                            │   (via API)            prescriptions  │
   │                            │←──────────────────────────│
   │                            │ {items, total_medicine   │
   │                            │  _price: 95000}          │
   │                            │                          │
   │                            │─ INSERT billing ───────→│
   │                            │   (subtotal_service:     │
   │                            │    400000,               │
   │                            │    medicine_cost: 95000) │
   │                            │                          │
   │                            │─ INSERT billing_items ──→│
   │                            │   (konsultasi, lab, dll) │
   │                            │←───────────────────────│
   │                            │                          │
   │←───201 Created─────────────│                          │
   │ {billing_id: bill_001,     │                          │
   │  invoice_number: INV-...,  │                          │
   │  grand_total: 495000,      │                          │
   │  status: BILLED}           │                          │
   │                            │                          │
```

**Status: BILLED**
- Invoice sudah dibuat
- Total harga sudah dihitung (service + obat)
- Menunggu pembayaran

---

### Phase 6: Pasien Membayar (SERVICE 4)

```
KASIR                          SERVICE 4                 SERVICE 2    DATABASE
   │                            │                          │            │
   │── POST /payments ─────────→│                          │            │
   │    (billing_id: bill_001,  │                          │            │
   │     amount_paid: 495000,   │                          │            │
   │     payment_method: cash)  │                          │            │
   │                            │                          │            │
   │                            │─ INSERT payment ──────→│
   │                            │   (status: PAID)       │
   │                            │                          │            │
   │                            │─ UPDATE billing ──────→│
   │                            │   (status: PAID,       │
   │                            │    paid_amount)        │
   │                            │                          │
   │                            │─ API UPDATE registration status ────→
   │                            │   (status: PAID)       │
   │                            │←──────────────────────────│
   │←────201 Created─────────────│                          │
   │ {payment_id: pay_001,      │                          │
   │  receipt_number: REC-...,  │                          │
   │  payment_status: PAID}     │                          │
   │                            │                          │
   │ "Pembayaran diterima,      │                          │
   │  silakan ambil obat"       │                          │
   │                            │                          │
```

**Status: PAID**
- Pembayaran diterima
- Receipt sudah dicetak
- Pasien bisa ambil obat

---

### Phase 7: Buat Antrian Apotik (SERVICE 4)

```
ADMIN APOTIK                   SERVICE 4                SERVICE 3    DATABASE
   │                            │                          │            │
   │── POST /pharmacy/queues ──→│                          │            │
   │    (prescription_id,       │                          │            │
   │     registration_id)       │                          │            │
   │                            │                          │            │
   │                            │─ GET prescription ────→ GET /         │
   │                            │   items + medicine    prescriptions   │
   │                            │   details              /{id}          │
   │                            │←──────────────────────────│
   │                            │                          │            │
   │                            │─ INSERT pharmacy ──────→│
   │                            │   _queue               │
   │                            │   (queue_number: AP001)│            │
   │                            │                          │            │
   │                            │─ API UPDATE registration status ────→
   │                            │   (status:             │
   │                            │    PHARMACY_QUEUED)    │
   │                            │←──────────────────────────│
   │←────201 Created─────────────│                          │
   │ {pharmacy_queue_id: pq_001,│                          │
   │  queue_number: AP-001,     │                          │
   │  status: PHARMACY_QUEUED}  │                          │
   │                            │                          │
   │ Resep siap disiapkan       │                          │
   │                            │                          │
```

**Status: PHARMACY_QUEUED**
- Antrian apotik dibuat
- Nomor antrian: AP-001
- Perawat apotik siapkan obat sesuai resep

---

### Phase 8: Pasien Ambil Obat (SERVICE 4)

```
PASIEN                         ADMIN APOTIK               SERVICE 4     DATABASE
   │                              │                          │             │
   │ (Datang dengan nomor AP-001) │                          │             │
   │                              │                          │             │
   │                              │── PUT /pharmacy/queues   │             │
   │                              │    /{id}/dispense       │             │
   │                              │    (dispensed_by_id)    │             │
   │                              │                          │             │
   │                              │                         │─ INSERT   │
   │                              │                         │  pharmacy_│
   │                              │                         │  dispensing│
   │                              │                         │  _items   │
   │                              │                         │           │
   │                              │                         │─ UPDATE   │
   │                              │                         │  pharmacy_│
   │                              │                         │  _inventory│
   │                              │                         │  (qty -1) │
   │                              │                         │           │
   │                              │                         │─ API      │
   │                              │                         │  UPDATE   │
   │                              │                         │  registrat│
   │                              │                         │  ion      │
   │                              │                         │  status:  │
   │                              │                         │  MEDICINE │
   │                              │                         │  _DISPENSE│
   │                              │                         │  D        │
   │                              │                         │←──────────│
   │                              │←────200 OK──────────────│           │
   │                              │ {pharmacy_queue_id,     │           │
   │                              │  status: MEDICINE_      │           │
   │                              │  DISPENSED,             │           │
   │                              │  medicines_given: [...]}│           │
   │                              │                          │           │
   │  ←────── AMBIL OBAT ──────────│                          │           │
   │  Obat diberikan sesuai resep  │                          │           │
   │                              │                          │           │
```

**Status: MEDICINE_DISPENSED**
- Obat diberikan kepada pasien
- Inventory apotik sudah ter-update
- Riwayat dispensing sudah tercatat

---

### Phase 9: Selesai (COMPLETED)

```
SERVICE 2 / SERVICE 4                      DATABASE
    │                                         │
    │─ API UPDATE registration status ──────→│
    │    (status: COMPLETED)                 │
    │←──────────────────────────────────────│
    │                                         │
```

**Status: COMPLETED**
- Alur pasien selesai
- Semua data tercatat lengkap
- Pasien bisa pulang

---

## 3. ALTERNATIVE FLOWS

### Scenario A: Pasien Tanpa Resep

```
Status Flow: 
ONLINE_REGISTERED → VALIDATED → IN_EXAMINATION → 
EXAMINED → BILLED → PAID → COMPLETED
(Skip: PRESCRIBED, PHARMACY_QUEUED, MEDICINE_DISPENSED)
```

### Scenario B: Pembayaran Partial

```
Pembayaran 1: Rp 250.000 → Status: PARTIALLY_PAID
Pembayaran 2: Rp 245.000 → Status: PAID → Ready untuk apotik
```

### Scenario C: Pasien Membatalkan

```
Status dapat berubah ke: CANCELLED
- Registrasi dibatalkan sebelum validasi
- Pembayaran di-refund
- Obat tidak diberikan
```

---

## 4. API CALL SEQUENCE (cURL Examples)

### 1. Daftar Online
```bash
curl -X POST http://registration:8002/api/registrations \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "Budi Pasien",
    "phone": "08123456789",
    "visit_date": "2026-05-20",
    "poli_preference": "poli_umum"
  }'
```

### 2. Validasi Pendaftaran
```bash
curl -X POST http://registration:8002/api/registrations/reg_001/validate \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "poli_final": "poli_umum",
    "examination_type": "pemeriksaan_umum",
    "validator_id": "emp_001"
  }'
```

### 3. Buat Pemeriksaan
```bash
curl -X POST http://examination:8003/api/examinations \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "registration_id": "reg_001",
    "complaint": "Sakit kepala",
    "diagnosis": "Demam dan sakit kepala",
    "diagnosis_icd10": "R51.9"
  }'
```

### 4. Buat Resep
```bash
curl -X POST http://examination:8003/api/prescriptions \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "examination_id": "exam_001",
    "items": [
      {
        "medicine_code": "med_001",
        "qty": 10,
        "dosage": "1-2 tablet, 3x sehari"
      }
    ]
  }'
```

### 5. Buat Invoice
```bash
curl -X POST http://billing:8004/api/billings \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "registration_id": "reg_001",
    "examination_id": "exam_001",
    "items": [{"description": "Konsultasi", "amount": 150000}],
    "medicine_cost": 95000
  }'
```

### 6. Buat Pembayaran
```bash
curl -X POST http://billing:8004/api/payments \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "billing_id": "bill_001",
    "amount_paid": 495000,
    "payment_method": "cash"
  }'
```

### 7. Buat Antrian Apotik
```bash
curl -X POST http://billing:8004/api/pharmacy/queues \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "prescription_id": "pres_001",
    "registration_id": "reg_001"
  }'
```

### 8. Ambil Obat
```bash
curl -X PUT http://billing:8004/api/pharmacy/queues/pq_001/dispense \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "pharmacy_staff_id": "emp_apotik_001",
    "dispensed_date": "2026-05-20"
  }'
```

---

## 5. Data Flow Diagram Antar Service

```
┌─────────────────────────────────────────────────────────────┐
│                    Auth Center (Kelompok 1)                 │
│                                                              │
│  POST /auth/login → verify token → emit JWT Token + Role   │
│  POST /auth/introspect → validate token → return user_info │
└──────────────────────────┬──────────────────────────────────┘
                           │
        ┌──────────────────┼──────────────────┐
        │                  │                  │
        ▼                  ▼                  ▼
    ┌─────────────┐  ┌─────────────┐  ┌─────────────┐
    │ Service 2   │  │ Service 3   │  │ Service 4   │
    │ Registration│  │ Examination │  │ Billing &   │
    │ & Queue     │  │ & Resep     │  │ Pharmacy    │
    └─────────────┘  └─────────────┘  └─────────────┘
        │                  │                  │
        └──────────────────┼──────────────────┘
                           │
                    Request API (JSON)
                    + Bearer Token
                    + X-Request-ID

    Status Update Notification:
    Service 2 → (API) → Service 4
    Service 3 → (API) → Service 2, Service 4
    Service 4 → (API) → Service 2
```

---

## 6. Status Summary Table

| Status | Service | Actor | Action |
|--------|---------|-------|--------|
| ONLINE_REGISTERED | Service 2 | Pasien | Daftar online |
| VALIDATED | Service 2 | Admin/Perawat | Validasi & assign nomor antrian |
| QUEUED_POLI | Service 2 | Admin | Pasien sudah datang & dalam antrian |
| IN_EXAMINATION | Service 2 | Dokter | Dokter memanggil & periksa pasien |
| EXAMINED | Service 3 | Dokter | Pemeriksaan selesai |
| PRESCRIBED | Service 3 | Dokter | Resep dibuat (optional) |
| BILLED | Service 4 | Kasir | Invoice dibuat |
| PAID | Service 4 | Kasir | Pembayaran diterima |
| PHARMACY_QUEUED | Service 4 | Admin Apotik | Antrian apotik dibuat (if prescribed) |
| MEDICINE_DISPENSED | Service 4 | Admin Apotik | Obat diberikan kepada pasien |
| COMPLETED | Service 2/4 | System | Alur pasien selesai |

