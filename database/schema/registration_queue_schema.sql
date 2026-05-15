-- Registration & Queue Service Database Schema
-- Service: Pendaftaran Pasien & Manajemen Antrian Poli (Kelompok 2)

CREATE DATABASE IF NOT EXISTS registration_queue CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE registration_queue;

-- Patients Table
CREATE TABLE patients (
  id VARCHAR(36) PRIMARY KEY COMMENT 'Patient ID (UUID)',
  full_name VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  email VARCHAR(150),
  date_of_birth DATE,
  gender ENUM('M', 'F') COMMENT 'Male/Female',
  address TEXT,
  identity_type ENUM('KTP', 'SIM', 'Passport') COMMENT 'Tipe identitas',
  identity_number VARCHAR(50) UNIQUE COMMENT 'Nomor identitas',
  emergency_contact_name VARCHAR(255),
  emergency_contact_phone VARCHAR(20),
  blood_type VARCHAR(5) COMMENT 'Golongan darah',
  allergies TEXT COMMENT 'Alergi obat/makanan',
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_full_name (full_name),
  INDEX idx_phone (phone),
  INDEX idx_email (email),
  INDEX idx_identity_number (identity_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Registrations Table (setiap kunjungan pasien)
CREATE TABLE registrations (
  id VARCHAR(36) PRIMARY KEY COMMENT 'Registration ID (UUID)',
  patient_id VARCHAR(36) NOT NULL,
  registration_number VARCHAR(50) UNIQUE COMMENT 'Nomor registrasi: reg_YYYYMMDDXXXXX',
  visit_date DATE NOT NULL COMMENT 'Tanggal kunjungan yang dijadwalkan',
  poli_preference VARCHAR(50) COMMENT 'Pilihan poli awal pasien',
  poli_final VARCHAR(50) COMMENT 'Poli final yang di-assign admin/perawat',
  examination_type VARCHAR(100) COMMENT 'Jenis pemeriksaan',
  complaint TEXT COMMENT 'Keluhan pasien',
  status ENUM(
    'ONLINE_REGISTERED',
    'VALIDATED',
    'QUEUED_POLI',
    'IN_EXAMINATION',
    'EXAMINED',
    'PRESCRIBED',
    'BILLED',
    'PAID',
    'PHARMACY_QUEUED',
    'MEDICINE_DISPENSED',
    'COMPLETED',
    'CANCELLED'
  ) DEFAULT 'ONLINE_REGISTERED' COMMENT 'Status alur pasien',
  queue_number VARCHAR(10) COMMENT 'Nomor antrian poli (misal: 001, 002)',
  queue_estimated_time TIME COMMENT 'Estimasi waktu dipanggil',
  
  -- Validator info
  validator_id VARCHAR(36),
  validator_name VARCHAR(255),
  validated_at TIMESTAMP NULL,
  validation_notes TEXT,
  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE RESTRICT,
  INDEX idx_patient_id (patient_id),
  INDEX idx_visit_date (visit_date),
  INDEX idx_status (status),
  INDEX idx_poli_final (poli_final),
  INDEX idx_registration_number (registration_number),
  INDEX idx_queue_number (queue_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Poli/Department Master
CREATE TABLE polis (
  id VARCHAR(50) PRIMARY KEY COMMENT 'Poli ID: poli_umum, poli_gigi, dll',
  name VARCHAR(255) NOT NULL UNIQUE COMMENT 'Nama poli',
  description TEXT,
  department_head_id VARCHAR(36) COMMENT 'ID dokter kepala poli',
  max_queue_per_day INT DEFAULT 30 COMMENT 'Maksimal antrian per hari',
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_name (name),
  INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Examination Types Master
CREATE TABLE examination_types (
  id VARCHAR(100) PRIMARY KEY COMMENT 'Jenis pemeriksaan ID',
  name VARCHAR(255) NOT NULL UNIQUE,
  description TEXT,
  default_fee DECIMAL(10, 2),
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Queue Management Table (untuk mengelola antrian per poli per hari)
CREATE TABLE daily_queues (
  id INT AUTO_INCREMENT PRIMARY KEY,
  queue_date DATE NOT NULL,
  poli_id VARCHAR(50) NOT NULL,
  queue_number INT NOT NULL,
  registration_id VARCHAR(36),
  patient_id VARCHAR(36),
  status ENUM('PENDING', 'CALLED', 'IN_PROGRESS', 'COMPLETED', 'SKIPPED', 'CANCELLED') DEFAULT 'PENDING',
  called_at TIMESTAMP NULL,
  started_at TIMESTAMP NULL,
  completed_at TIMESTAMP NULL,
  estimated_wait_time INT COMMENT 'Dalam detik',
  actual_wait_time INT COMMENT 'Dalam detik',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE SET NULL,
  FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE SET NULL,
  UNIQUE KEY unique_daily_queue (queue_date, poli_id, queue_number),
  INDEX idx_queue_date (queue_date),
  INDEX idx_poli_id (poli_id),
  INDEX idx_registration_id (registration_id),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Status Change History (audit trail)
CREATE TABLE registration_status_histories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  registration_id VARCHAR(36) NOT NULL,
  patient_id VARCHAR(36) NOT NULL,
  old_status VARCHAR(50),
  new_status VARCHAR(50) NOT NULL,
  changed_by_id VARCHAR(36),
  changed_by_name VARCHAR(255),
  changed_by_role VARCHAR(50),
  change_reason TEXT,
  metadata JSON COMMENT 'Additional info tentang perubahan status',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (registration_id) REFERENCES registrations(id) ON DELETE CASCADE,
  FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  INDEX idx_registration_id (registration_id),
  INDEX idx_patient_id (patient_id),
  INDEX idx_created_at (created_at),
  INDEX idx_new_status (new_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default polis
INSERT INTO polis (id, name, description, max_queue_per_day) VALUES
('poli_umum', 'Poli Umum', 'Poli untuk pemeriksaan umum', 30),
('poli_gigi', 'Poli Gigi', 'Poli untuk pemeriksaan gigi', 25),
('poli_anak', 'Poli Anak', 'Poli untuk pemeriksaan anak-anak', 25),
('poli_ibu_anak', 'Poli Ibu dan Anak', 'Poli untuk ibu hamil dan anak', 20),
('poli_jantung', 'Poli Jantung', 'Poli untuk pemeriksaan jantung', 15);

-- Insert default examination types
INSERT INTO examination_types (id, name, default_fee) VALUES
('pemeriksaan_umum', 'Pemeriksaan Umum', 150000),
('konsultasi', 'Konsultasi', 100000),
('follow_up', 'Follow Up', 75000),
('pemeriksaan_gigi', 'Pemeriksaan Gigi', 200000),
('pembersihan_gigi', 'Pembersihan Gigi', 250000);
