# API Documentation - Registration & Queue Service

## Base URL
```
http://registration-queue:8002/api
```

## Authentication Header
```
Authorization: Bearer {access_token}
```

---

## 1. POST /registrations
**Pasien mendaftar online.**

### Request
```json
{
  "full_name": "Budi Pasien",
  "phone": "08123456789",
  "email": "budi@email.com",
  "date_of_birth": "1990-05-15",
  "gender": "M",
  "address": "Jl. Merdeka No. 123",
  "visit_date": "2026-05-20",
  "poli_preference": "poli_umum",
  "complaint": "Sakit kepala dan demam"
}
```

### Response Success (201)
```json
{
  "success": true,
  "message": "Pendaftaran online berhasil",
  "data": {
    "registration_id": "reg_2026051300001",
    "patient_id": "pat_00001",
    "full_name": "Budi Pasien",
    "phone": "08123456789",
    "status": "ONLINE_REGISTERED",
    "visit_date": "2026-05-20",
    "poli_preference": "poli_umum",
    "created_at": "2026-05-13T08:00:00Z",
    "queue_number": null
  },
  "meta": {
    "request_id": "req_200001"
  }
}
```

### Error Codes
| Code | HTTP | Deskripsi |
|------|------|-----------|
| INVALID_VISIT_DATE | 400 | Tanggal kunjungan tidak valid |
| INVALID_POLI | 400 | Poli tidak dikenali |
| DUPLICATE_REGISTRATION | 409 | Pasien sudah mendaftar untuk tanggal itu |

---

## 2. POST /registrations/{registration_id}/validate
**Admin/Perawat validasi data pasien dan assign poli final + nomor antrian.**

### Request Header
```
Authorization: Bearer {access_token}
X-User-Role: admin | perawat
```

### Request
```json
{
  "poli_final": "poli_umum",
  "examination_type": "pemeriksaan_umum",
  "validator_id": "emp_0001",
  "validator_name": "Siti Admin",
  "notes": "Pasien konfirmasi via telepon"
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Validasi berhasil, nomor antrian diberikan",
  "data": {
    "registration_id": "reg_2026051300001",
    "patient_id": "pat_00001",
    "status": "VALIDATED",
    "poli_final": "poli_umum",
    "queue_number": "001",
    "queue_date": "2026-05-20",
    "queue_estimated_time": "09:30",
    "examination_type": "pemeriksaan_umum",
    "validator_id": "emp_0001",
    "validated_at": "2026-05-13T08:15:00Z"
  },
  "meta": {
    "request_id": "req_200002"
  }
}
```

### Error Codes
| Code | HTTP | Deskripsi |
|------|------|-----------|
| REGISTRATION_NOT_FOUND | 404 | Pendaftaran tidak ditemukan |
| ALREADY_VALIDATED | 409 | Sudah di-validasi sebelumnya |
| INVALID_ROLE | 403 | User tidak punya akses |

---

## 3. GET /registrations/{registration_id}
**Get detail pendaftaran pasien.**

### Response Success (200)
```json
{
  "success": true,
  "data": {
    "registration_id": "reg_2026051300001",
    "patient_id": "pat_00001",
    "full_name": "Budi Pasien",
    "phone": "08123456789",
    "email": "budi@email.com",
    "date_of_birth": "1990-05-15",
    "gender": "M",
    "address": "Jl. Merdeka No. 123",
    "status": "VALIDATED",
    "poli_preference": "poli_umum",
    "poli_final": "poli_umum",
    "queue_number": "001",
    "queue_date": "2026-05-20",
    "queue_estimated_time": "09:30",
    "examination_type": "pemeriksaan_umum",
    "complaint": "Sakit kepala dan demam",
    "created_at": "2026-05-13T08:00:00Z",
    "validated_at": "2026-05-13T08:15:00Z"
  },
  "meta": {
    "request_id": "req_200003"
  }
}
```

---

## 4. GET /queues/poli
**Get daftar antrian per poli per tanggal (untuk tampilan antrian di poli).**

### Query Parameters
- `poli_id` (required): ID poli
- `date` (required): Tanggal YYYY-MM-DD
- `status` (optional): QUEUED_POLI, IN_EXAMINATION, EXAMINED

### Response Success (200)
```json
{
  "success": true,
  "data": {
    "poli_id": "poli_umum",
    "poli_name": "Poli Umum",
    "queue_date": "2026-05-20",
    "total_queue": 5,
    "queues": [
      {
        "queue_number": "001",
        "patient_id": "pat_00001",
        "patient_name": "Budi Pasien",
        "status": "QUEUED_POLI",
        "registration_id": "reg_2026051300001",
        "arrival_time": "2026-05-20T09:00:00Z",
        "called_at": null
      },
      {
        "queue_number": "002",
        "patient_id": "pat_00002",
        "patient_name": "Ahmad Sakit",
        "status": "IN_EXAMINATION",
        "registration_id": "reg_2026051300002",
        "arrival_time": "2026-05-20T09:05:00Z",
        "called_at": "2026-05-20T09:15:00Z"
      }
    ]
  },
  "meta": {
    "request_id": "req_200004"
  }
}
```

---

## 5. PUT /queues/poli/{queue_number}/call
**Admin/Perawat memanggil nomor antrian (ubah status ke IN_EXAMINATION).**

### Request Header
```
X-User-Role: admin | perawat
```

### Request
```json
{
  "poli_id": "poli_umum",
  "queue_date": "2026-05-20",
  "staff_id": "emp_0001"
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Pasien nomor 002 silakan masuk ruang pemeriksaan",
  "data": {
    "queue_number": "002",
    "patient_name": "Ahmad Sakit",
    "status": "IN_EXAMINATION",
    "called_at": "2026-05-20T09:15:00Z"
  },
  "meta": {
    "request_id": "req_200005"
  }
}
```

---

## 6. GET /registrations/patient/{patient_id}
**Get riwayat registrasi pasien.**

### Query Parameters
- `limit` (int): jumlah record (default 20)
- `offset` (int): pagination offset (default 0)

### Response Success (200)
```json
{
  "success": true,
  "data": [
    {
      "registration_id": "reg_2026051300001",
      "patient_id": "pat_00001",
      "visit_date": "2026-05-20",
      "poli_final": "poli_umum",
      "queue_number": "001",
      "status": "EXAMINED",
      "created_at": "2026-05-13T08:00:00Z"
    }
  ],
  "meta": {
    "total": 5,
    "limit": 20,
    "offset": 0,
    "request_id": "req_200006"
  }
}
```

---

## 7. PUT /registrations/{registration_id}/status
**Update status registrasi (internal use atau dari service lain via API).**

### Request Header
```
Authorization: Bearer {access_token}
X-Service-Name: examination_prescription | billing_pharmacy
```

### Request
```json
{
  "status": "EXAMINED",
  "metadata": {
    "examination_id": "exam_001",
    "updated_by_service": "examination_prescription"
  }
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Status berhasil diupdate",
  "data": {
    "registration_id": "reg_2026051300001",
    "status": "EXAMINED",
    "updated_at": "2026-05-20T10:30:00Z"
  },
  "meta": {
    "request_id": "req_200007"
  }
}
```

---

## Standard Poli List
- `poli_umum` - Poli Umum
- `poli_gigi` - Poli Gigi
- `poli_anak` - Poli Anak
- `poli_ibu_anak` - Poli Ibu dan Anak
- `poli_jantung` - Poli Jantung

## Examination Types
- `pemeriksaan_umum` - Pemeriksaan Umum
- `konsultasi` - Konsultasi
- `follow_up` - Follow Up

## Status Flow
```
ONLINE_REGISTERED → VALIDATED → QUEUED_POLI → IN_EXAMINATION → 
EXAMINED → [PRESCRIBED] → BILLED → PAID → 
[PHARMACY_QUEUED → MEDICINE_DISPENSED] → COMPLETED
```
