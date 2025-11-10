-- Minimal bootstrap schema for Freelance Marketplace
-- For full schema refer to BACKEND_SETUP_GUIDE.md

CREATE DATABASE IF NOT EXISTS `freelance_marketplace` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `freelance_marketplace`;

-- Users
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('client','provider') NOT NULL DEFAULT 'client',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_users_email (email)
) ENGINE=InnoDB;

-- Proposals (applications by providers)
CREATE TABLE IF NOT EXISTS proposals (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  job_id INT UNSIGNED NOT NULL,
  provider_id INT UNSIGNED NOT NULL,
  cover_letter TEXT NULL,
  bid_amount DECIMAL(10,2) NULL,
  timeline_days INT NULL,
  status ENUM('submitted','withdrawn','accepted','rejected') NOT NULL DEFAULT 'submitted',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_job_provider (job_id, provider_id),
  CONSTRAINT fk_proposals_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
  CONSTRAINT fk_proposals_provider FOREIGN KEY (provider_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Jobs
CREATE TABLE IF NOT EXISTS jobs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  client_id INT UNSIGNED NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT NOT NULL,
  category VARCHAR(100) NULL,
  location VARCHAR(150) NULL,
  budget_min DECIMAL(10,2) NULL,
  budget_max DECIMAL(10,2) NULL,
  status ENUM('open','in_progress','completed','cancelled') NOT NULL DEFAULT 'open',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_jobs_category (category),
  INDEX idx_jobs_status (status),
  CONSTRAINT fk_jobs_client FOREIGN KEY (client_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Basic seed (optional)
INSERT INTO users (name, email, password_hash, role)
VALUES ('Test Client', 'client@example.com', '$2a$10$4yGZF4mHiv9EqT9k2Z7Z6u6b0t8Q3a7T3z5e1kq1G0u1rZjX2I0lK', 'client')
ON DUPLICATE KEY UPDATE name = VALUES(name);
