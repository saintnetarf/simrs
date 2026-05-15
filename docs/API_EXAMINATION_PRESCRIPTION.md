# API Documentation - Examination & Prescription Service

## Base URL
```
http://examination-prescription:8003/api
```

## Authentication Header
```
Authorization: Bearer {access_token}
X-User-Role: dokter
```

---

## 1. POST /examinations
**Dokter membuat catatan pemeriksaan pasien.**

### Request
```json
{
  "registration_id": "reg_2026051300001",
  "patient_id": "pat_00001",
  "examination_date": "2026-05-20",
  "examination_time": "09:15",
  "doctor_id": "emp_doc_001",
  "doctor_name": "Dr. Ahmad",
  "poli": "poli_umum",
  "complaint": "Sakit kepala dan demam",
  "anamnesis": "Pasien mengeluh sakit kepala sejak 3 hari, demam tinggi 38.5°C",
  "physical_exam": "TD: 140/90, HR: 95, RR: 20, Temp: 38.5°C",
  "diagnosis": "Demam dan sakit kepala",
  "diagnosis_icd10": "R51.9",
  "action": "Pemeriksaan darah, rontgen thorax",
  "notes": "Monitor vital signs setiap jam"
}
```

### Response Success (201)
```json
{
  "success": true,
  "message": "Catatan pemeriksaan berhasil dibuat",
  "data": {
    "examination_id": "exam_2026051300001",
    "registration_id": "reg_2026051300001",
    "patient_id": "pat_00001",
    "patient_name": "Budi Pasien",
    "status": "EXAMINED",
    "doctor_id": "emp_doc_001",
    "doctor_name": "Dr. Ahmad",
    "poli": "poli_umum",
    "diagnosis": "Demam dan sakit kepala",
    "diagnosis_icd10": "R51.9",
    "examination_date": "2026-05-20",
    "examination_time": "09:15",
    "created_at": "2026-05-20T09:15:00Z"
  },
  "meta": {
    "request_id": "req_300001"
  }
}
```

### Error Codes
| Code | HTTP | Deskripsi |
|------|------|-----------|
| REGISTRATION_NOT_FOUND | 404 | Registrasi tidak ditemukan |
| INVALID_DOCTOR_ROLE | 403 | User bukan dokter |
| EXAMINATION_ALREADY_CREATED | 409 | Pemeriksaan sudah dibuat |

---

## 2. GET /examinations/{examination_id}
**Get detail pemeriksaan pasien.**

### Response Success (200)
```json
{
  "success": true,
  "data": {
    "examination_id": "exam_2026051300001",
    "registration_id": "reg_2026051300001",
    "patient_id": "pat_00001",
    "patient_name": "Budi Pasien",
    "doctor_id": "emp_doc_001",
    "doctor_name": "Dr. Ahmad",
    "poli": "poli_umum",
    "complaint": "Sakit kepala dan demam",
    "anamnesis": "Pasien mengeluh sakit kepala sejak 3 hari, demam tinggi 38.5°C",
    "physical_exam": "TD: 140/90, HR: 95, RR: 20, Temp: 38.5°C",
    "diagnosis": "Demam dan sakit kepala",
    "diagnosis_icd10": "R51.9",
    "action": "Pemeriksaan darah, rontgen thorax",
    "notes": "Monitor vital signs setiap jam",
    "examination_date": "2026-05-20",
    "examination_time": "09:15",
    "created_at": "2026-05-20T09:15:00Z",
    "has_prescription": true,
    "prescription_id": "pres_2026051300001"
  },
  "meta": {
    "request_id": "req_300002"
  }
}
```

---

## 3. POST /prescriptions
**Dokter membuat resep obat untuk pasien.**

### Request
```json
{
  "examination_id": "exam_2026051300001",
  "registration_id": "reg_2026051300001",
  "patient_id": "pat_00001",
  "doctor_id": "emp_doc_001",
  "prescription_date": "2026-05-20",
  "items": [
    {
      "medicine_code": "med_001",
      "medicine_name": "Paracetamol",
      "strength": "500mg",
      "form": "tablet",
      "qty": 10,
      "unit": "tablet",
      "dosage": "1-2 tablet, 3x sehari",
      "duration_days": 3,
      "notes": "Setelah makan"
    },
    {
      "medicine_code": "med_002",
      "medicine_name": "Amoxicillin",
      "strength": "500mg",
      "form": "kapsul",
      "qty": 15,
      "unit": "kapsul",
      "dosage": "1 kapsul, 3x sehari",
      "duration_days": 5,
      "notes": "Sebelum makan"
    }
  ],
  "instruction": "Minum obat sesuai aturan, jika tidak membaik dalam 3 hari kontrol kembali"
}
```

### Response Success (201)
```json
{
  "success": true,
  "message": "Resep berhasil dibuat",
  "data": {
    "prescription_id": "pres_2026051300001",
    "examination_id": "exam_2026051300001",
    "registration_id": "reg_2026051300001",
    "patient_id": "pat_00001",
    "patient_name": "Budi Pasien",
    "doctor_id": "emp_doc_001",
    "doctor_name": "Dr. Ahmad",
    "prescription_date": "2026-05-20",
    "status": "PRESCRIBED",
    "total_items": 2,
    "items": [
      {
        "item_id": "pres_item_001",
        "medicine_name": "Paracetamol 500mg",
        "qty": 10,
        "dosage": "1-2 tablet, 3x sehari",
        "duration_days": 3
      }
    ],
    "created_at": "2026-05-20T09:20:00Z"
  },
  "meta": {
    "request_id": "req_300003"
  }
}
```

### Error Codes
| Code | HTTP | Deskripsi |
|------|------|-----------|
| EXAMINATION_NOT_FOUND | 404 | Pemeriksaan tidak ditemukan |
| PRESCRIPTION_ALREADY_EXISTS | 409 | Resep sudah ada untuk pemeriksaan ini |
| INVALID_MEDICINE | 400 | Obat tidak dikenali |

---

## 4. GET /prescriptions/{prescription_id}
**Get detail resep obat pasien.**

### Response Success (200)
```json
{
  "success": true,
  "data": {
    "prescription_id": "pres_2026051300001",
    "examination_id": "exam_2026051300001",
    "patient_id": "pat_00001",
    "patient_name": "Budi Pasien",
    "doctor_name": "Dr. Ahmad",
    "prescription_date": "2026-05-20",
    "status": "PRESCRIBED",
    "items": [
      {
        "item_id": "pres_item_001",
        "medicine_code": "med_001",
        "medicine_name": "Paracetamol",
        "strength": "500mg",
        "form": "tablet",
        "qty": 10,
        "unit": "tablet",
        "dosage": "1-2 tablet, 3x sehari",
        "duration_days": 3,
        "notes": "Setelah makan",
        "price_per_unit": 5000,
        "total_price": 50000
      },
      {
        "item_id": "pres_item_002",
        "medicine_code": "med_002",
        "medicine_name": "Amoxicillin",
        "strength": "500mg",
        "form": "kapsul",
        "qty": 15,
        "unit": "kapsul",
        "dosage": "1 kapsul, 3x sehari",
        "duration_days": 5,
        "notes": "Sebelum makan",
        "price_per_unit": 3000,
        "total_price": 45000
      }
    ],
    "total_medicine_price": 95000,
    "instruction": "Minum obat sesuai aturan, jika tidak membaik dalam 3 hari kontrol kembali",
    "created_at": "2026-05-20T09:20:00Z"
  },
  "meta": {
    "request_id": "req_300004"
  }
}
```

---

## 5. PUT /prescriptions/{prescription_id}
**Update resep (jika belum diambil di apotik).**

### Request
```json
{
  "items": [
    {
      "medicine_code": "med_001",
      "medicine_name": "Paracetamol",
      "strength": "500mg",
      "form": "tablet",
      "qty": 10,
      "unit": "tablet",
      "dosage": "1-2 tablet, 3x sehari",
      "duration_days": 3,
      "notes": "Setelah makan"
    }
  ],
  "instruction": "Minum obat sesuai aturan"
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Resep berhasil diupdate",
  "data": {
    "prescription_id": "pres_2026051300001",
    "total_items": 1,
    "updated_at": "2026-05-20T09:25:00Z"
  },
  "meta": {
    "request_id": "req_300005"
  }
}
```

---

## 6. GET /examinations/registration/{registration_id}
**Get riwayat pemeriksaan untuk satu registrasi.**

### Response Success (200)
```json
{
  "success": true,
  "data": [
    {
      "examination_id": "exam_2026051300001",
      "registration_id": "reg_2026051300001",
      "patient_name": "Budi Pasien",
      "doctor_name": "Dr. Ahmad",
      "poli": "poli_umum",
      "diagnosis": "Demam dan sakit kepala",
      "examination_date": "2026-05-20",
      "has_prescription": true,
      "created_at": "2026-05-20T09:15:00Z"
    }
  ],
  "meta": {
    "request_id": "req_300006"
  }
}
```

---

## 7. POST /examinations/{examination_id}/finalize
**Finalisasi pemeriksaan (ubah status ke EXAMINED dan siap untuk billing).**

### Request
```json
{
  "has_prescription": true,
  "next_step": "billing"
}
```

### Response Success (200)
```json
{
  "success": true,
  "message": "Pemeriksaan selesai, siap untuk billing",
  "data": {
    "examination_id": "exam_2026051300001",
    "status": "EXAMINED",
    "finalized_at": "2026-05-20T09:30:00Z"
  },
  "meta": {
    "request_id": "req_300007"
  }
}
```

---

## Medicine Codes (Contoh)
- `med_001` - Paracetamol 500mg tablet
- `med_002` - Amoxicillin 500mg kapsul
- `med_003` - Vitamin C 500mg tablet
- `med_004` - Antacid tablet
- `med_005` - Antihistamine tablet

## Standard Error Responses
```json
{
  "success": false,
  "message": "Error deskripsi",
  "data": null,
  "errors": {
    "code": "ERROR_CODE",
    "detail": "Detail error"
  },
  "meta": {
    "request_id": "req_XXXXXX"
  }
}
```
