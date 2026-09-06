-- ================================================================
-- EquipTrack - Complete database for XAMPP / MySQL / MariaDB
-- Database: equiptrack_db
-- Import this ONE file in phpMyAdmin for a clean installation.
-- WARNING: Existing EquipTrack tables in equiptrack_db will be replaced.
-- ================================================================

CREATE DATABASE IF NOT EXISTS `equiptrack_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `equiptrack_db`;
SET NAMES utf8mb4;
SET time_zone = '+07:00';
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `ci_sessions`;
DROP TABLE IF EXISTS `tbl_devices_service`;
DROP TABLE IF EXISTS `tbl_devices`;
DROP TABLE IF EXISTS `tbl_devices_status`;
DROP TABLE IF EXISTS `tbl_devices_type`;
DROP TABLE IF EXISTS `tbl_member`;
DROP TABLE IF EXISTS `tbl_position`;

-- ----------------------------------------------------------------
-- User roles / borrower types
-- IDs 1, 3 and 4 are reserved by application logic.
-- Custom roles are treated as borrower roles.
-- ----------------------------------------------------------------
CREATE TABLE `tbl_position` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `pname` varchar(100) NOT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `uq_position_name` (`pname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_position` (`pid`,`pname`) VALUES
(1,'ผู้ดูแลระบบ'),
(3,'เจ้าหน้าที่ห้องคอม'),
(4,'นักศึกษา');

-- ----------------------------------------------------------------
-- Users
-- ----------------------------------------------------------------
CREATE TABLE `tbl_member` (
  `m_id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_pid` int(11) NOT NULL,
  `m_username` varchar(50) NOT NULL,
  `m_password` varchar(255) NOT NULL,
  `m_fname` varchar(30) NOT NULL DEFAULT '',
  `m_name` varchar(100) NOT NULL,
  `m_lname` varchar(100) NOT NULL DEFAULT '',
  `m_phone` varchar(20) NOT NULL DEFAULT '',
  `m_email` varchar(120) NOT NULL DEFAULT '',
  `m_img` varchar(255) NOT NULL DEFAULT '',
  `m_datesave` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`m_id`),
  UNIQUE KEY `uq_member_username` (`m_username`),
  KEY `idx_member_position` (`ref_pid`),
  CONSTRAINT `fk_member_position`
    FOREIGN KEY (`ref_pid`) REFERENCES `tbl_position` (`pid`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Device statuses
-- IDs 1, 2 and 3 are reserved by application logic and locked in UI.
-- ----------------------------------------------------------------
CREATE TABLE `tbl_devices_status` (
  `s_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_name` varchar(50) NOT NULL,
  PRIMARY KEY (`s_id`),
  UNIQUE KEY `uq_status_name` (`s_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_devices_status` (`s_id`,`s_name`) VALUES
(1,'พร้อมใช้งาน'),
(2,'กำลังถูกยืม'),
(3,'ชำรุด');

-- ----------------------------------------------------------------
-- Device types
-- ----------------------------------------------------------------
CREATE TABLE `tbl_devices_type` (
  `t_id` int(11) NOT NULL AUTO_INCREMENT,
  `t_name` varchar(80) NOT NULL,
  PRIMARY KEY (`t_id`),
  UNIQUE KEY `uq_type_name` (`t_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tbl_devices_type` (`t_id`,`t_name`) VALUES
(1,'คอมพิวเตอร์'),
(2,'เครื่องพิมพ์'),
(3,'โน้ตบุ๊ก'),
(4,'โปรเจคเตอร์');

-- ----------------------------------------------------------------
-- Devices
-- d_id is the visible asset number and is kept unique.
-- ----------------------------------------------------------------
CREATE TABLE `tbl_devices` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `ref_t_id` int(11) NOT NULL,
  `ref_s_id` int(11) NOT NULL DEFAULT 1,
  `d_id` varchar(50) NOT NULL,
  `d_name` varchar(120) NOT NULL,
  `d_detail` text NOT NULL,
  `d_remark` text NOT NULL,
  `d_img` varchar(255) NOT NULL DEFAULT '',
  `ref_m_id` int(11) DEFAULT NULL,
  `d_datesave` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`no`),
  UNIQUE KEY `uq_device_code` (`d_id`),
  KEY `idx_device_type` (`ref_t_id`),
  KEY `idx_device_status` (`ref_s_id`),
  KEY `idx_device_creator` (`ref_m_id`),
  CONSTRAINT `fk_device_type`
    FOREIGN KEY (`ref_t_id`) REFERENCES `tbl_devices_type` (`t_id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_device_status`
    FOREIGN KEY (`ref_s_id`) REFERENCES `tbl_devices_status` (`s_id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_device_creator`
    FOREIGN KEY (`ref_m_id`) REFERENCES `tbl_member` (`m_id`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Borrow / return history and pending requests
-- ----------------------------------------------------------------
CREATE TABLE `tbl_devices_service` (
  `ser_id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_t_id` int(11) NOT NULL,
  `ref_d_id` varchar(50) NOT NULL,
  `ref_m_id` int(11) NOT NULL,
  `ser_reason` varchar(255) NOT NULL,
  `ser_status` enum('pending','approved','borrowed','rejected','cancelled','returned') NOT NULL DEFAULT 'borrowed',
  `ser_request_date` datetime DEFAULT NULL,
  `ser_approved_at` datetime DEFAULT NULL,
  `ser_rejected_at` datetime DEFAULT NULL,
  `ser_date_lend` date DEFAULT NULL,
  `ser_staff_id_lend` int(11) DEFAULT NULL,
  `ser_staff_name_lend` varchar(100) NOT NULL DEFAULT '',
  `ser_date_return` date DEFAULT NULL,
  `ser_staff_id_return` int(11) DEFAULT NULL,
  `ser_staff_name_return` varchar(100) DEFAULT NULL,
  `ser_datesave` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ser_id`),
  KEY `idx_service_device` (`ref_d_id`),
  KEY `idx_service_member` (`ref_m_id`),
  KEY `idx_service_type` (`ref_t_id`),
  KEY `idx_service_status` (`ser_status`),
  KEY `idx_service_device_status` (`ref_d_id`,`ser_status`),
  KEY `idx_service_member_status` (`ref_m_id`,`ser_status`),
  KEY `idx_service_request_date` (`ser_request_date`),
  KEY `idx_service_lend_date` (`ser_date_lend`),
  KEY `idx_service_staff_lend` (`ser_staff_id_lend`),
  KEY `idx_service_staff_return` (`ser_staff_id_return`),
  CONSTRAINT `fk_service_device`
    FOREIGN KEY (`ref_d_id`) REFERENCES `tbl_devices` (`d_id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_service_member`
    FOREIGN KEY (`ref_m_id`) REFERENCES `tbl_member` (`m_id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_service_type`
    FOREIGN KEY (`ref_t_id`) REFERENCES `tbl_devices_type` (`t_id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_service_staff_lend`
    FOREIGN KEY (`ser_staff_id_lend`) REFERENCES `tbl_member` (`m_id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_service_staff_return`
    FOREIGN KEY (`ser_staff_id_return`) REFERENCES `tbl_member` (`m_id`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- CodeIgniter 3 database sessions
-- ----------------------------------------------------------------
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Initial accounts
-- Passwords: admin / staff / student
-- Change them after the first login.
-- ----------------------------------------------------------------
INSERT INTO `tbl_member`
(`ref_pid`,`m_username`,`m_password`,`m_fname`,`m_name`,`m_lname`,`m_phone`,`m_email`,`m_img`) VALUES
(1,'admin','$2y$12$wB.ST0XaQyfGIOR0r7i.me2bQ3OqeNIu1QMHuh4R1Fai7X1jWJt42','','ผู้ดูแล','ระบบ','0999999999','ผู้ดูแลระบบ',''),
(3,'staff','$2y$12$bRX45xmW70AKUVpZH/ElNet7ch6EtM.9yviSXvIgAl4v/l4zYm/Ae','','เจ้าหน้าที่','ห้องคอม','0999999997','เจ้าหน้าที่ห้องคอม',''),
(4,'student','$2y$12$mDwSIx2MOCZj0UdmDlb74.NFaMUR.s1w6LxOCClchzRIyLRHZhX/u','นาย','นักศึกษา','ตัวอย่าง','0999999996','ปริญญาตรี / 66000000','');

SET FOREIGN_KEY_CHECKS = 1;
