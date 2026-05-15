-- Billing & Pharmacy Service Database Schema
-- Service: Kasir, Pembayaran & Apotik (Kelompok 4)

CREATE DATABASE IF NOT EXISTS billing_pharmacy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE billing_pharmacy;

-- Billings Table
CREATE TABLE billings (
  id VARCHAR(36) PRIMARY KEY COMMENT 'Billing ID (UUID)',
  invoice_number VARCHAR(50) UNIQUE COMMENT 'INV-YYYY-MM-DD-XXXXX',
  registration_id VARCHAR(36) NOT NULL COMMENT 'Reference ke registration_queue',
  patient_id VARCHAR(36) NOT NULL,
  patient_name VARCHAR(255),
  examination_id VARCHAR(36) COMMENT 'Reference ke examination_prescription',
  poli VARCHAR(50),

  -- Cost breakdown
  subtotal_service DECIMAL(12, 2) COMMENT 'Total jasa layanan (konsultasi, lab, dll)',
  medicine_cost DECIMAL(12, 2) COMMENT 'Total biaya obat',
  discount_amount DECIMAL(12, 2) DEFAULT 0,
  discount_reason VARCHAR(255),
  discount_approved_by VARCHAR(36),

  grand_total DECIMAL(12, 2) COMMENT 'Total yang harus dibayar',

  -- Payment status
  status ENUM('BILLED', 'PARTIALLY_PAID', 'PAID', 'CANCELLED') DEFAULT 'BILLED',
  paid_amount DECIMAL(12, 2) DEFAULT 0,
  remaining_amount DECIMAL(12, 2),

  -- Metadata
  notes TEXT,
  kasir_id VARCHAR(36),
  kasir_name VARCHAR(255),
  due_date TIMESTAMP,

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX idx_invoice_number (invoice_number),
  INDEX idx_registration_id (registration_id),
  INDEX idx_patient_id (patient_id),
  INDEX idx_status (status),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Billing Items
CREATE TABLE billing_items (
  id VARCHAR(36) PRIMARY KEY,
  billing_id VARCHAR(36) NOT NULL,
  description VARCHAR(500),
  category ENUM('consultation', 'laboratory', 'radiology', 'procedure', 'medicine', 'administration') COMMENT 'Kategori biaya',
  amount DECIMAL(12, 2),
  quantity INT DEFAULT 1,
  unit_price DECIMAL(12, 2),
  line_number INT,

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (billing_id) REFERENCES billings(id) ON DELETE CASCADE,
  INDEX idx_billing_id (billing_id),
  INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Payments Table
CREATE TABLE payments (
  id VARCHAR(36) PRIMARY KEY COMMENT 'Payment ID (UUID)',
  payment_number VARCHAR(50) UNIQUE COMMENT 'PAY-YYYY-MM-DD-XXXXX',
  billing_id VARCHAR(36) NOT NULL,
  registration_id VARCHAR(36) NOT NULL,
  patient_id VARCHAR(36) NOT NULL,
  patient_name VARCHAR(255),

  amount_paid DECIMAL(12, 2) NOT NULL,
  payment_method ENUM('cash', 'debit_card', 'credit_card', 'bpjs', 'bank_transfer', 'check', 'other') NOT NULL,

  -- Payment details
  payment_status ENUM('PENDING', 'PAID', 'FAILED', 'REFUNDED') DEFAULT 'PAID',
  reference_number VARCHAR(100) COMMENT 'Nomor referensi pembayaran (misal: no kartu, no transfer, dll)',
  payment_note TEXT,

  -- Kasir info
  kasir_id VARCHAR(36),
  kasir_name VARCHAR(255),

  -- Receipt
  receipt_number VARCHAR(50) UNIQUE,

  payment_date DATE,
  payment_time TIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (billing_id) REFERENCES billings(id) ON DELETE RESTRICT,
  INDEX idx_billing_id (billing_id),
  INDEX idx_registration_id (registration_id),
  INDEX idx_payment_date (payment_date),
  INDEX idx_payment_status (payment_status),
  INDEX idx_payment_method (payment_method)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Refunds Table
CREATE TABLE refunds (
  id VARCHAR(36) PRIMARY KEY,
  payment_id VARCHAR(36) NOT NULL,
  billing_id VARCHAR(36) NOT NULL,
  refund_amount DECIMAL(12, 2),
  refund_reason TEXT,
  refund_method ENUM('cash', 'bank_transfer', 'credit_back') DEFAULT 'cash',
  refund_status ENUM('PENDING', 'PROCESSED', 'FAILED') DEFAULT 'PENDING',
  processed_by_id VARCHAR(36),
  processed_by_name VARCHAR(255),

  refund_date DATE,
  refund_time TIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  processed_at TIMESTAMP NULL,

  FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE RESTRICT,
  FOREIGN KEY (billing_id) REFERENCES billings(id) ON DELETE RESTRICT,
  INDEX idx_payment_id (payment_id),
  INDEX idx_refund_status (refund_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- APOTIK SERVICE

-- Pharmacy Queues
CREATE TABLE pharmacy_queues (
  id VARCHAR(36) PRIMARY KEY COMMENT 'Pharmacy Queue ID (UUID)',
  prescription_id VARCHAR(36) NOT NULL COMMENT 'Reference ke prescription dari examination service',
  registration_id VARCHAR(36) NOT NULL,
  patient_id VARCHAR(36) NOT NULL,
  patient_name VARCHAR(255),

  queue_number VARCHAR(10) COMMENT 'AP-001, AP-002, dll',
  queue_date DATE NOT NULL,
  queue_status ENUM('PHARMACY_QUEUED', 'IN_PREPARATION', 'READY', 'MEDICINE_DISPENSED', 'CANCELLED') DEFAULT 'PHARMACY_QUEUED',

  -- Timing
  arrival_time TIMESTAMP NULL COMMENT 'Waktu pasien tiba',
  called_at TIMESTAMP NULL COMMENT 'Waktu dipanggil',
  preparation_started_at TIMESTAMP NULL,
  dispensed_at TIMESTAMP NULL,

  -- Pharmacy staff
  prepared_by_id VARCHAR(36),
  prepared_by_name VARCHAR(255),
  dispensed_by_id VARCHAR(36),
  dispensed_by_name VARCHAR(255),
  verified_by_id VARCHAR(36) COMMENT 'Apotik supervisor yang verifikasi',
  verified_by_name VARCHAR(255),

  notes TEXT,

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  INDEX idx_prescription_id (prescription_id),
  INDEX idx_registration_id (registration_id),
  INDEX idx_queue_date (queue_date),
  INDEX idx_queue_status (queue_status),
  INDEX idx_queue_number (queue_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pharmacy Dispensing Items (tracking obat yang diberikan)
CREATE TABLE pharmacy_dispensing_items (
  id VARCHAR(36) PRIMARY KEY,
  pharmacy_queue_id VARCHAR(36) NOT NULL,
  prescription_item_id VARCHAR(36) COMMENT 'Reference ke prescription item dari examination service',
  medicine_code VARCHAR(50),
  medicine_name VARCHAR(255),
  qty_prescribed INT COMMENT 'Jumlah yang diresepkan',
  qty_given INT COMMENT 'Jumlah yang diberikan',
  unit VARCHAR(50),
  strength VARCHAR(100),
  form VARCHAR(50),
  batch_number VARCHAR(100) COMMENT 'Nomor batch obat',
  expiration_date DATE,

  dispensed_by_id VARCHAR(36),
  dispensed_by_name VARCHAR(255),
  dispensed_date DATE,
  dispensed_time TIME,

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (pharmacy_queue_id) REFERENCES pharmacy_queues(id) ON DELETE CASCADE,
  INDEX idx_pharmacy_queue_id (pharmacy_queue_id),
  INDEX idx_medicine_code (medicine_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pharmacy Inventory (stok obat di apotik)
CREATE TABLE pharmacy_inventory (
  id INT AUTO_INCREMENT PRIMARY KEY,
  medicine_code VARCHAR(50) NOT NULL,
  medicine_name VARCHAR(255),
  strength VARCHAR(100),
  form VARCHAR(50),
  batch_number VARCHAR(100),

  qty INT DEFAULT 0 COMMENT 'Jumlah stok',
  unit VARCHAR(50),
  purchase_price DECIMAL(10, 2),
  selling_price DECIMAL(10, 2),

  expiration_date DATE,
  received_date DATE,

  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY unique_medicine_batch (medicine_code, batch_number),
  INDEX idx_medicine_code (medicine_code),
  INDEX idx_expiration_date (expiration_date),
  INDEX idx_qty (qty)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pharmacy Inventory History (audit trail stok)
CREATE TABLE pharmacy_inventory_histories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  medicine_code VARCHAR(50),
  transaction_type ENUM('RECEIVED', 'DISPENSED', 'ADJUSTMENT', 'EXPIRED', 'DAMAGED'),
  qty_change INT COMMENT 'Perubahan jumlah (bisa negatif)',
  before_qty INT,
  after_qty INT,
  reference_id VARCHAR(36) COMMENT 'ID pharmacy_dispensing_items atau purchase order',
  notes TEXT,
  created_by_id VARCHAR(36),
  created_by_name VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_medicine_code (medicine_code),
  INDEX idx_transaction_type (transaction_type),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pharmacy Shift/Schedule
CREATE TABLE pharmacy_schedules (
  id INT AUTO_INCREMENT PRIMARY KEY,
  schedule_date DATE NOT NULL,
  shift ENUM('MORNING', 'AFTERNOON', 'NIGHT') DEFAULT 'MORNING',
  staff_id VARCHAR(36),
  staff_name VARCHAR(255),
  start_time TIME,
  end_time TIME,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_shift (schedule_date, shift, staff_id),
  INDEX idx_schedule_date (schedule_date),
  INDEX idx_staff_id (staff_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Service Fee Master (tarif konsultasi, lab, dll)
CREATE TABLE service_fees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  poli VARCHAR(50) NOT NULL,
  service_name VARCHAR(255) NOT NULL,
  description TEXT,
  fee DECIMAL(12, 2) NOT NULL,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_service (poli, service_name),
  INDEX idx_poli (poli)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample tarif
INSERT INTO service_fees (poli, service_name, fee) VALUES
('poli_umum', 'Konsultasi Dokter', 150000),
('poli_umum', 'Pemeriksaan Laboratorium', 200000),
('poli_umum', 'Administrasi', 50000),
('poli_gigi', 'Konsultasi Dokter Gigi', 200000),
('poli_gigi', 'Pembersihan Gigi', 250000);
