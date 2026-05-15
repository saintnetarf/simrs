-- Auth Center Database Schema
-- Service: Authentication & Authorization Center (Kelompok 1)

CREATE DATABASE IF NOT EXISTS auth_center CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE auth_center;

-- Users Table
CREATE TABLE users (
  id VARCHAR(36) PRIMARY KEY COMMENT 'User ID (UUID)',
  username VARCHAR(100) NOT NULL UNIQUE COMMENT 'Username untuk login',
  email VARCHAR(150) NOT NULL UNIQUE COMMENT 'Email user',
  password_hash VARCHAR(255) NOT NULL COMMENT 'Hash password Argon2',
  full_name VARCHAR(255) NOT NULL COMMENT 'Nama lengkap user',
  role ENUM('admin', 'perawat', 'dokter', 'kasir', 'admin_apotik', 'pasien') NOT NULL COMMENT 'Role/jabatan user',
  is_active BOOLEAN DEFAULT TRUE COMMENT 'Apakah user aktif',
  is_locked BOOLEAN DEFAULT FALSE COMMENT 'Apakah user terkunci',
  last_login_at TIMESTAMP NULL COMMENT 'Waktu login terakhir',
  login_attempts INT DEFAULT 0 COMMENT 'Jumlah percobaan login gagal',
  locked_until TIMESTAMP NULL COMMENT 'User terkunci sampai jam ini',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at TIMESTAMP NULL COMMENT 'Soft delete',
  INDEX idx_username (username),
  INDEX idx_email (email),
  INDEX idx_role (role),
  INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Service Access Table (User dapat akses ke mana saja)
CREATE TABLE user_service_access (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id VARCHAR(36) NOT NULL,
  service_name VARCHAR(100) NOT NULL COMMENT 'Nama service: auth_center, registration_queue, examination_prescription, billing_pharmacy',
  can_access BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY unique_user_service (user_id, service_name),
  INDEX idx_user_id (user_id),
  INDEX idx_service_name (service_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tokens Table (untuk logout/invalidate)
CREATE TABLE tokens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id VARCHAR(36) NOT NULL,
  token_hash VARCHAR(255) NOT NULL UNIQUE COMMENT 'Hash dari JWT token',
  token_type ENUM('access', 'refresh') DEFAULT 'access',
  expires_at TIMESTAMP NOT NULL,
  revoked_at TIMESTAMP NULL COMMENT 'Token di-revoke saat logout',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_expires_at (expires_at),
  INDEX idx_revoked_at (revoked_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Login Audit Log
CREATE TABLE login_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id VARCHAR(36),
  username VARCHAR(100),
  login_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  success BOOLEAN DEFAULT FALSE,
  ip_address VARCHAR(50),
  user_agent TEXT,
  failure_reason VARCHAR(255) COMMENT 'Alasan jika login gagal',
  INDEX idx_user_id (user_id),
  INDEX idx_login_at (login_at),
  INDEX idx_success (success)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- API Access Log (audit untuk trace request)
CREATE TABLE api_access_logs (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  request_id VARCHAR(50) UNIQUE COMMENT 'Unique request ID untuk trace',
  user_id VARCHAR(36),
  username VARCHAR(100),
  endpoint VARCHAR(255),
  method ENUM('GET', 'POST', 'PUT', 'DELETE', 'PATCH'),
  status_code INT,
  response_time INT COMMENT 'Dalam millisecond',
  ip_address VARCHAR(50),
  accessed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  request_body LONGTEXT COMMENT 'Store request body untuk audit',
  response_body LONGTEXT COMMENT 'Store response body untuk troubleshooting',
  INDEX idx_user_id (user_id),
  INDEX idx_accessed_at (accessed_at),
  INDEX idx_request_id (request_id),
  INDEX idx_endpoint (endpoint),
  INDEX idx_method (method)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Permissions/Privileges (untuk fine-grained access control)
CREATE TABLE role_permissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role VARCHAR(50) NOT NULL,
  permission VARCHAR(255) NOT NULL COMMENT 'endpoint:method atau fitur yang di-access',
  is_allowed BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_role_permission (role, permission),
  INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Session Table (opsional, untuk multi-device login)
CREATE TABLE user_sessions (
  id VARCHAR(36) PRIMARY KEY,
  user_id VARCHAR(36) NOT NULL,
  device_name VARCHAR(255),
  device_type ENUM('web', 'mobile_ios', 'mobile_android', 'tablet'),
  ip_address VARCHAR(50),
  user_agent TEXT,
  access_token VARCHAR(500),
  refresh_token VARCHAR(500),
  expires_at TIMESTAMP,
  logged_out_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_expires_at (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password hash contoh)
INSERT INTO users (id, username, email, password_hash, full_name, role, is_active)
VALUES ('admin_001', 'admin', 'admin@hospital.local', '$2y$12$examplehash', 'Administrator', 'admin', TRUE);

-- Insert default roles permissions
INSERT INTO role_permissions (role, permission, is_allowed) VALUES
('admin', 'users:*', TRUE),
('admin', 'auth:*', TRUE),
('dokter', 'auth:login', TRUE),
('dokter', 'auth:logout', TRUE),
('kasir', 'auth:login', TRUE),
('kasir', 'auth:logout', TRUE),
('perawat', 'auth:login', TRUE),
('perawat', 'auth:logout', TRUE),
('admin_apotik', 'auth:login', TRUE),
('admin_apotik', 'auth:logout', TRUE);
