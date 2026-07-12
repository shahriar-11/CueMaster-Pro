-- ============================================================
-- CueMaster Pro - Smart Snooker Club, Tournament & Billing System
-- Database: cuemaster_pro
-- Milestone: 1 - Foundation, Auth & Dashboard
-- Compatibility: MariaDB 10.x (XAMPP default)
-- ============================================================
-- HOW TO IMPORT:
-- 1. Open phpMyAdmin (http://localhost/phpmyadmin)
-- 2. Click "Import" -> choose this file -> Go
--    (This script creates the database itself, no need to
--     create "cuemaster_pro" manually first.)
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS cuemaster_pro;
CREATE DATABASE cuemaster_pro
  CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE cuemaster_pro;

-- ------------------------------------------------------------
-- Table: users
-- Login accounts. `role` column exists now so future milestones
-- (Cashier / Staff roles) can be added without a schema change.
-- ------------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'cashier', 'staff') NOT NULL DEFAULT 'admin',
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Table: settings
-- Single-row configuration table (club name, global hourly rate, currency)
-- ------------------------------------------------------------
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    club_name VARCHAR(100) NOT NULL DEFAULT 'CueMaster Pro',
    hourly_rate DECIMAL(10,2) NOT NULL DEFAULT 300.00,
    currency_symbol VARCHAR(10) NOT NULL DEFAULT 'TK',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Table: tables
-- Physical snooker/pool tables in the club
-- ------------------------------------------------------------
CREATE TABLE tables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_name VARCHAR(50) NOT NULL,
    table_type VARCHAR(50) NOT NULL DEFAULT 'Snooker',
    status ENUM('available', 'occupied', 'maintenance') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Table: sessions
-- A play session on a table. When active, end_time is NULL.
-- Billing = duration_minutes * (hourly_rate / 60), snapshotted
-- into `amount` once the session is completed.
-- ------------------------------------------------------------
CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_id INT NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NULL,
    duration_minutes INT NULL,
    amount DECIMAL(10,2) NULL,
    status ENUM('active', 'completed') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sessions_table FOREIGN KEY (table_id) REFERENCES tables(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Default Admin account -> username: admin | password: admin123
INSERT INTO users (full_name, username, password, role) VALUES
('Club Administrator', 'admin', '$2b$12$Bc7CpQ05OaJ7alKGxNbgoulLw940/.ihDroAjzI40wfldnIwxV0eq', 'admin');

-- Global settings (hourly rate TK 300/hr)
INSERT INTO settings (club_name, hourly_rate, currency_symbol) VALUES
('CueMaster Pro', 300.00, 'TK');

-- Tables (mixed live statuses so the dashboard looks alive)
INSERT INTO tables (table_name, table_type, status) VALUES
('Table 01', 'Snooker', 'occupied'),
('Table 02', 'Snooker', 'available'),
('Table 03', 'Snooker', 'occupied'),
('Table 04', 'Snooker', 'available'),
('Table 05', 'Pool', 'maintenance'),
('Table 06', 'Pool', 'available');

-- Active sessions matching the occupied tables above
INSERT INTO sessions (table_id, start_time, end_time, duration_minutes, amount, status) VALUES
(1, DATE_SUB(NOW(), INTERVAL 42 MINUTE), NULL, NULL, NULL, 'active'),
(3, DATE_SUB(NOW(), INTERVAL 15 MINUTE), NULL, NULL, NULL, 'active');

-- Completed sessions across the last 7 days (drives the revenue chart)
INSERT INTO sessions (table_id, start_time, end_time, duration_minutes, amount, status) VALUES
(2, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 6 DAY), INTERVAL 10 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 6 DAY), INTERVAL 690 MINUTE), 90, 450.00, 'completed'),
(4, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 6 DAY), INTERVAL 14 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 6 DAY), INTERVAL 15 HOUR), 60, 300.00, 'completed'),
(6, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 5 DAY), INTERVAL 12 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 5 DAY), INTERVAL 795 MINUTE), 75, 375.00, 'completed'),
(1, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 5 DAY), INTERVAL 18 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 5 DAY), INTERVAL 20 HOUR), 120, 600.00, 'completed'),
(2, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 4 DAY), INTERVAL 9 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 4 DAY), INTERVAL 10 HOUR), 60, 300.00, 'completed'),
(3, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 4 DAY), INTERVAL 16 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 4 DAY), INTERVAL 1065 MINUTE), 105, 525.00, 'completed'),
(4, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 3 DAY), INTERVAL 11 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 3 DAY), INTERVAL 12 HOUR), 60, 300.00, 'completed'),
(6, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 3 DAY), INTERVAL 19 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 3 DAY), INTERVAL 21 HOUR), 120, 600.00, 'completed'),
(1, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 2 DAY), INTERVAL 13 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 2 DAY), INTERVAL 870 MINUTE), 90, 450.00, 'completed'),
(2, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 2 DAY), INTERVAL 17 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 2 DAY), INTERVAL 18 HOUR), 60, 300.00, 'completed'),
(3, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 1 DAY), INTERVAL 10 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 1 DAY), INTERVAL 12 HOUR), 120, 600.00, 'completed'),
(4, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 1 DAY), INTERVAL 15 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 1 DAY), INTERVAL 975 MINUTE), 75, 375.00, 'completed'),
(6, DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 1 DAY), INTERVAL 20 HOUR), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 1 DAY), INTERVAL 21 HOUR), 60, 300.00, 'completed'),
(2, DATE_ADD(CURDATE(), INTERVAL 9 HOUR), DATE_ADD(CURDATE(), INTERVAL 630 MINUTE), 90, 450.00, 'completed'),
(4, DATE_ADD(CURDATE(), INTERVAL 11 HOUR), DATE_ADD(CURDATE(), INTERVAL 12 HOUR), 60, 300.00, 'completed'),
(6, DATE_ADD(CURDATE(), INTERVAL 13 HOUR), DATE_ADD(CURDATE(), INTERVAL 825 MINUTE), 105, 525.00, 'completed');
