# API Documentation - Billing & Pharmacy Service

## Base URL
```
http://billing-pharmacy:8004/api
```

## Authentication Header
```
Authorization: Bearer {access_token}
X-User-Role: kasir | admin_apotik
```

---

## KASIR ENDPOINTS

## 1. POST /billings
**Kasir membuat invoice/billing untuk pasien.**

### Request
```json
{
  "registration_id": "reg_2026051300001",
  "patient_id": "pat_00001",
  "examination_id": "exam_2026051300001",
  "poli": "poli_umum",
  "kasir_id": "emp_kasir_001",
  "kasir_name": "Budi Kasir",
  "items": [
    {
      "description": "Tarif Konsultasi Dokter",
      "category": "consultation",
      "amount": 150000
    },
    {
      "description": "Tarif Pemeriksaan Laboratorium",
      "category": "laboratory",
      "amount": 200000
    },
    {
      "description": "Tarif Administrasi",
      "category": "administration",
      "amount": 50000
    }
  ],
  "medicine_cost": 95000,
  "notes": "Pembayaran untuk pemeriksaan pasien"
}
```

### Response Success (201)
```json
{
  "success": true,
  "message": "Invoice berhasil dibuat",
  "data": {
    "billing_id": "bill_2026051300001",
    "registration_id": "reg_2026051300001",
    "patient_id": "pat_00001",
    "patient_name": "Budi Pasien",
    "examination_id": "exam_2026051300001",
    "poli": "poli_umum",
    "invoice_number": "INV-2026-05-20-001",
    "status": "BILLED",
    "subtotal_service": 400000,
    "medicine_cost": 95000,
    "total_amount": 495000,
    "discount": 0,
    "grand_total": 495000,
    "kasir_id": "emp_kasir_001",
    "created_at": "2026-05-20T10:00:00Z",
    "items": [
      {
        "description": "Tarif Konsultasi Dokter",
        "amount": 150000
      }
    ]
  },
  "meta": {
    "request_id": "req_400001"
  }
}
```

### Error Codes
| Code | HTTP | Deskripsi |
|------|------|-----------|
| REGISTRATION_NOT_FOUND | 404 | Registrasi tidak ditemukan |
| EXAMINATION_NOT_FOUND | 404 | Pemeriksaan tidak ditemukan |
| BILLING_ALREADY_CREATED | 409 | Invoice sudah dibuat untuk registrasi ini |
| INVALID_KASIR_ROLE | 403 | User bukan kasir |

---

## 2. GET /billings/{billing_id}
**Get detail invoice.**

### Response Success (200)
```json
{
  "success": true,
  "data": {
    "billing_id": "bill_2026051300001",
    "registration_id": "reg_2026051300001",
    "patient_id": "pat_00001",
    "patient_name": "Budi Pasien",
    "invoice_number": "INV-2026-05-20-001",
    "examination_id": "exam_2026051300001",
    "status": "BILLED",
    "items": [
      {
        "billing_item_id": "bill_item_001",
        "description": "Tarif Konsultasi Dokter",
        "category": "consultation",
        "amount": 150000
      },
      {
        "billing_item_id": "bill_item_002",
        "description": "Tarif Pemeriksaan Laboratorium",
        "category": "laboratory",
        "amount": 200000
      }
    ],
    "subtotal_service": 400000,
    "medicine_cost": 95000,
    "discount": 0,
    "grand_total": 495000,
    "paid_amount": 0,
    "remaining_amount": 495000,
    "created_at": "2026-05-20T10:00:00Z",
    "due_date": "2026-05-22T23:59:59Z"
  },
  "meta": {
    "request_id": "req_400002"
  }
}
```

---

## 3. POST /payments
**Kasir catat pembayaran pasien.**

### Request
```json
{
  "billing_id": "bill_2026051300001",
  "payment_date": "2026-05-20",
  "payment_time": "10:15",
  "payment_method": "cash",
  "amount_paid": 495000,
  "reference_number": "TRX001",
  "kasir_id": "emp_kasir_001",
  "notes": "Pembayaran tunai lengkap"
}
```

### Response Success (201)
```json
{
  "success": true,
  "message": "Pembayaran berhasil dicatat",
  "data": {
    "payment_id": "pay_2026051300001",
    "billing_id": "bill_2026051300001",
    "registration_id": "reg_2026051300001",
    "patient_id": "pat_00001",
    "payment_date": "2026-05-20",
    "payment_time": "10:15",
    "payment_method": "cash",
    "amount_paid": 495000,
    "reference_number": "TRX001",
    "payment_status": "PAID",
    "billing_status": "PAID",
    "remaining_amount": 0,
    "receipt_number": "REC-2026-05-20-001",
    "kasir_id": "emp_kasir_001",
    "created_at": "2026-05-20T10:15:00Z"
  },
  "meta": {
    "request_id": "req_400003"
  }
}
```

### Error Codes
| Code | HTTP | Deskripsi |
|------|------|-----------|
| BILLING_NOT_FOUND | 404 | Invoice tidak ditemukan |
| INSUFFICIENT_AMOUNT | 400 | Jumlah pembayaran kurang |
| BILLING_ALREADY_PAID | 409 | Invoice sudah dibayar |

---

## 4. GET /payments/{payment_id}
**Get detail pembayaran.**

### Response Success (200)
```json
{
  "success": true,
  "data": {
    "payment_id": "pay_2026051300001",
    "billing_id": "bill_2026051300001",
    "registration_id": "reg_2026051300001",
    "patient_id": "pat_00001",
    "patient_name": "Budi Pasien",
    "amount_paid": 495000,
    "payment_method": "cash",
    "reference_number": "TRX001",
    "receipt_number": "REC-2026-05-20-001",
    "payment_status": "PAID",
    "created_at": "2026-05-20T10:15:00Z"
  },
  "meta": {
    "request_id": "req_400004"
  }
}
```

---

## 5. PUT /billings/{billing_id}/discount
**Kasir berikan diskon untuk billing (admin only).**

### Request
```json
{
  "discount_type": "percentage",
  "discount_value": 10,
  "discount_reason": "Pasien kurang mampu",
  "approved_by": "emp_admin_001"
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Diskon berhasil diterapkan",
  "data": {
    "billing_id": "bill_2026051300001",
    "original_total": 495000,
    "discount": 49500,
    "new_total": 445500,
    "discount_type": "percentage",
    "discount_value": 10,
    "discount_reason": "Pasien kurang mampu"
  },
  "meta": {
    "request_id": "req_400005"
  }
}
```

---

## APOTIK ENDPOINTS

## 6. POST /pharmacy/queues
**Buat antrian pengambilan obat dari resep pasien.**

### Request Header
```
X-User-Role: admin_apotik
```

### Request
```json
{
  "prescription_id": "pres_2026051300001",
  "registration_id": "reg_2026051300001",
  "patient_id": "pat_00001",
  "queue_date": "2026-05-20",
  "pharmacy_staff_id": "emp_apotik_001",
  "notes": "Resep sudah disiapkan"
}
```

### Response Success (201)
```json
{
  "success": true,
  "message": "Antrian apotik berhasil dibuat",
  "data": {
    "pharmacy_queue_id": "pq_2026051300001",
    "prescription_id": "pres_2026051300001",
    "registration_id": "reg_2026051300001",
    "patient_id": "pat_00001",
    "patient_name": "Budi Pasien",
    "queue_number": "AP-001",
    "queue_date": "2026-05-20",
    "status": "PHARMACY_QUEUED",
    "estimated_time": "10:30",
    "created_at": "2026-05-20T10:20:00Z"
  },
  "meta": {
    "request_id": "req_400006"
  }
}
```

---

## 7. GET /pharmacy/queues
**Get daftar antrian pengambilan obat per tanggal.**

### Query Parameters
- `queue_date` (required): Tanggal YYYY-MM-DD
- `status` (optional): PHARMACY_QUEUED, MEDICINE_DISPENSED

### Response Success (200)
```json
{
  "success": true,
  "data": {
    "queue_date": "2026-05-20",
    "total_queue": 3,
    "queues": [
      {
        "queue_number": "AP-001",
        "patient_id": "pat_00001",
        "patient_name": "Budi Pasien",
        "prescription_id": "pres_2026051300001",
        "registration_id": "reg_2026051300001",
        "status": "PHARMACY_QUEUED",
        "arrival_time": null,
        "called_at": null
      },
      {
        "queue_number": "AP-002",
        "patient_id": "pat_00002",
        "patient_name": "Ahmad Sakit",
        "prescription_id": "pres_2026051300002",
        "registration_id": "reg_2026051300002",
        "status": "MEDICINE_DISPENSED",
        "arrival_time": "2026-05-20T10:25:00Z",
        "called_at": "2026-05-20T10:30:00Z"
      }
    ]
  },
  "meta": {
    "request_id": "req_400007"
  }
}
```

---

## 8. PUT /pharmacy/queues/{pharmacy_queue_id}/dispense
**Admin apotik berikan obat kepada pasien.**

### Request
```json
{
  "pharmacy_staff_id": "emp_apotik_001",
  "staff_name": "Siti Apotik",
  "dispensed_date": "2026-05-20",
  "dispensed_time": "10:30",
  "verified_by": "emp_apotik_supervisor_001",
  "notes": "Obat diberikan sesuai resep"
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Obat berhasil diberikan kepada pasien",
  "data": {
    "pharmacy_queue_id": "pq_2026051300001",
    "prescription_id": "pres_2026051300001",
    "patient_id": "pat_00001",
    "patient_name": "Budi Pasien",
    "queue_number": "AP-001",
    "status": "MEDICINE_DISPENSED",
    "dispensed_at": "2026-05-20T10:30:00Z",
    "pharmacy_staff_id": "emp_apotik_001",
    "medicines_given": [
      {
        "medicine_name": "Paracetamol 500mg",
        "qty": 10,
        "qty_given": 10,
        "unit": "tablet"
      },
      {
        "medicine_name": "Amoxicillin 500mg",
        "qty": 15,
        "qty_given": 15,
        "unit": "kapsul"
      }
    ]
  },
  "meta": {
    "request_id": "req_400008"
  }
}
```

### Error Codes
| Code | HTTP | Deskripsi |
|------|------|-----------|
| PHARMACY_QUEUE_NOT_FOUND | 404 | Antrian apotik tidak ditemukan |
| ALREADY_DISPENSED | 409 | Obat sudah diberikan |
| INVALID_APOTIK_ROLE | 403 | User bukan admin apotik |

---

## 9. GET /pharmacy/queues/{pharmacy_queue_id}
**Get detail antrian apotik dan resep.**

### Response Success (200)
```json
{
  "success": true,
  "data": {
    "pharmacy_queue_id": "pq_2026051300001",
    "prescription_id": "pres_2026051300001",
    "patient_id": "pat_00001",
    "patient_name": "Budi Pasien",
    "queue_number": "AP-001",
    "status": "MEDICINE_DISPENSED",
    "doctor_name": "Dr. Ahmad",
    "prescription_date": "2026-05-20",
    "items": [
      {
        "medicine_name": "Paracetamol 500mg",
        "form": "tablet",
        "qty": 10,
        "unit": "tablet",
        "dosage": "1-2 tablet, 3x sehari",
        "duration_days": 3,
        "notes": "Setelah makan",
        "qty_given": 10
      }
    ],
    "dispensed_at": "2026-05-20T10:30:00Z",
    "pharmacy_staff_id": "emp_apotik_001",
    "staff_name": "Siti Apotik"
  },
  "meta": {
    "request_id": "req_400009"
  }
}
```

---

## 10. GET /billings/registration/{registration_id}
**Get billing dan payment history untuk satu registrasi.**

### Response Success (200)
```json
{
  "success": true,
  "data": {
    "registration_id": "reg_2026051300001",
    "patient_name": "Budi Pasien",
    "billings": [
      {
        "billing_id": "bill_2026051300001",
        "invoice_number": "INV-2026-05-20-001",
        "status": "PAID",
        "grand_total": 495000,
        "paid_amount": 495000,
        "payment_method": "cash"
      }
    ]
  },
  "meta": {
    "request_id": "req_400010"
  }
}
```

---

## Payment Methods
- `cash` - Tunai
- `debit_card` - Kartu Debit
- `credit_card` - Kartu Kredit
- `bpjs` - BPJS Kesehatan
- `bank_transfer` - Transfer Bank

## Standard Response Format
```json
{
  "success": true|false,
  "message": "Pesan status",
  "data": {},
  "errors": {
    "code": "ERROR_CODE",
    "detail": "Detail error"
  },
  "meta": {
    "request_id": "req_XXXXXX",
    "timestamp": "2026-05-20T10:15:00Z"
  }
}
```
