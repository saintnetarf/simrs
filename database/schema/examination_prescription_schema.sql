-- Examination & Prescription Service Database Schema
-- Service: Pemeriksaan Pasien & Resep Obat (Kelompok 3)

CREATE DATABASE IF NOT EXISTS examination_prescription CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE examination_prescription;

-- Examinations Table
CREATE TABLE examinations (
  id VARCHAR(36) PRIMARY KEY COMMENT 'Examination ID (UUID)',
  examination_number VARCHAR(50) UNIQUE COMMENT 'exam_YYYYMMDDXXXXX',
  registration_id VARCHAR(36) NOT NULL COMMENT 'Reference ke registration_queue service',
  patient_id VARCHAR(36) NOT NULL COMMENT 'Patient ID (dari registration_queue)',
  patient_name VARCHAR(255),
  doctor_id VARCHAR(36) NOT NULL,
  doctor_name VARCHAR(255),
  poli VARCHAR(50) NOT NULL,
  examination_date DATE NOT NULL,
  examination_time TIME NOT NULL,
  
  -- Pemeriksaan
  complaint TEXT COMMENT 'Keluhan pasien',
  anamnesis TEXT COMMENT 'Riwayat penyakit',
  physical_exam TEXT COMMENT 'Hasil pemeriksaan fisik (TD, HR, RR, Temp, dll)',
  additional_exam TEXT COMMENT 'Pemeriksaan tambahan (lab, rontgen, dll)',
  
  -- Diagnosa
  diagnosis VARCHAR(500),
  diagnosis_icd10 VARCHAR(10) COMMENT 'Kode diagnosa ICD-10',
  secondary_diagnosis VARCHAR(500) COMMENT 'Diagnosa sekunder jika ada',
  
  -- Tindakan
  action TEXT COMMENT 'Tindakan yang dilakukan',
  
  -- Status & catatan
  status ENUM('DRAFT', 'EXAMINED', 'FINALIZED', 'CANCELLED') DEFAULT 'DRAFT',
  notes TEXT COMMENT 'Catatan dokter',
  has_prescription BOOLEAN DEFAULT FALSE,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  finalized_at TIMESTAMP NULL,
  
  FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE RESTRICT,
  INDEX idx_registration_id (registration_id),
  INDEX idx_patient_id (patient_id),
  INDEX idx_doctor_id (doctor_id),
  INDEX idx_examination_date (examination_date),
  INDEX idx_status (status),
  INDEX idx_poli (poli)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Doctors Table (cache dari auth_center untuk query lokal)
CREATE TABLE doctors (
  id VARCHAR(36) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  license_number VARCHAR(50) UNIQUE,
  specialization VARCHAR(100),
  poli_assignment VARCHAR(50),
  is_active BOOLEAN DEFAULT TRUE,
  synced_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Prescriptions Table
CREATE TABLE prescriptions (
  id VARCHAR(36) PRIMARY KEY COMMENT 'Prescription ID (UUID)',
  prescription_number VARCHAR(50) UNIQUE COMMENT 'pres_YYYYMMDDXXXXX',
  examination_id VARCHAR(36) NOT NULL,
  registration_id VARCHAR(36) NOT NULL,
  patient_id VARCHAR(36) NOT NULL,
  patient_name VARCHAR(255),
  doctor_id VARCHAR(36) NOT NULL,
  doctor_name VARCHAR(255),
  
  prescription_date DATE NOT NULL,
  status ENUM('DRAFT', 'PRESCRIBED', 'DISPENSED', 'CANCELLED') DEFAULT 'DRAFT',
  
  instruction TEXT COMMENT 'Instruksi penggunaan umum',
  total_items INT DEFAULT 0,
  total_medicine_price DECIMAL(12, 2) DEFAULT 0,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (examination_id) REFERENCES examinations(id) ON DELETE RESTRICT,
  FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE RESTRICT,
  INDEX idx_examination_id (examination_id),
  INDEX idx_registration_id (registration_id),
  INDEX idx_patient_id (patient_id),
  INDEX idx_status (status),
  INDEX idx_prescription_date (prescription_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Prescription Items
CREATE TABLE prescription_items (
  id VARCHAR(36) PRIMARY KEY,
  prescription_id VARCHAR(36) NOT NULL,
  medicine_code VARCHAR(50) NOT NULL,
  medicine_name VARCHAR(255) NOT NULL,
  strength VARCHAR(100) COMMENT 'Kekuatan/dosis: 500mg, 5ml, dll',
  form VARCHAR(50) COMMENT 'Bentuk: tablet, kapsul, sirup, injeksi, dll',
  qty INT NOT NULL COMMENT 'Jumlah satuan obat',
  unit VARCHAR(50) COMMENT 'Satuan: tablet, kapsul, botol, ampul, dll',
  price_per_unit DECIMAL(10, 2),
  total_price DECIMAL(12, 2) COMMENT 'qty * price_per_unit',
  
  -- Aturan penggunaan
  dosage VARCHAR(255) COMMENT 'Aturan minum: 1-2 tablet, 3x sehari',
  duration_days INT COMMENT 'Durasi pemakaian dalam hari',
  notes TEXT COMMENT 'Catatan khusus: sebelum/sesudah makan, dll',
  
  -- Tracking
  line_number INT COMMENT 'Urutan item dalam resep',
  is_active BOOLEAN DEFAULT TRUE,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE,
  INDEX idx_prescription_id (prescription_id),
  INDEX idx_medicine_code (medicine_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Medicines Master
CREATE TABLE medicines (
  code VARCHAR(50) PRIMARY KEY COMMENT 'Kode obat: med_XXXXX',
  name VARCHAR(255) NOT NULL,
  strength VARCHAR(100) COMMENT 'Kekuatan: 500mg, 5ml, dll',
  form ENUM('tablet', 'kapsul', 'sirup', 'injeksi', 'salep', 'tetes', 'inhaler', 'lain-lain'),
  manufacturer VARCHAR(255),
  generic_name VARCHAR(255) COMMENT 'Nama generik/bahan aktif',
  
  -- Stok & harga
  stock_qty INT DEFAULT 0,
  price DECIMAL(10, 2),
  
  -- Info obat
  contraindication TEXT COMMENT 'Kontraindikasi/efek samping',
  storage_instruction TEXT COMMENT 'Cara penyimpanan',
  expiration_date DATE,
  
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX idx_name (name),
  INDEX idx_generic_name (generic_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Diagnosis Master (ICD-10)
CREATE TABLE diagnoses (
  code VARCHAR(10) PRIMARY KEY COMMENT 'Kode diagnosa ICD-10',
  name VARCHAR(500),
  description TEXT,
  category VARCHAR(100) COMMENT 'Kategori diagnosa',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Examination Status History (audit trail)
CREATE TABLE examination_histories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  examination_id VARCHAR(36) NOT NULL,
  old_status VARCHAR(50),
  new_status VARCHAR(50) NOT NULL,
  changed_by_id VARCHAR(36),
  changed_by_name VARCHAR(255),
  change_reason TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (examination_id) REFERENCES examinations(id) ON DELETE CASCADE,
  INDEX idx_examination_id (examination_id),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Prescription Status History (audit trail)
CREATE TABLE prescription_histories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  prescription_id VARCHAR(36) NOT NULL,
  old_status VARCHAR(50),
  new_status VARCHAR(50) NOT NULL,
  changed_by_id VARCHAR(36),
  changed_by_name VARCHAR(255),
  change_reason TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (prescription_id) REFERENCES prescriptions(id) ON DELETE CASCADE,
  INDEX idx_prescription_id (prescription_id),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample medicines
INSERT INTO medicines (code, name, strength, form, price) VALUES
('med_001', 'Paracetamol', '500mg', 'tablet', 5000),
('med_002', 'Amoxicillin', '500mg', 'kapsul', 3000),
('med_003', 'Vitamin C', '500mg', 'tablet', 4000),
('med_004', 'Antacid', '500mg', 'tablet', 6000),
('med_005', 'Antihistamine', '25mg', 'tablet', 8000);

-- Sample diagnoses
INSERT INTO diagnoses (code, name, category) VALUES
('R51.9', 'Headache, unspecified', 'Symptoms and signs'),
('R50.9', 'Fever, unspecified', 'Symptoms and signs'),
('J06.9', 'Acute upper respiratory infection', 'Infections'),
('A15.9', 'Respiratory tuberculosis', 'Infections');
