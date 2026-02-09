-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.6.0.6765
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for jcses_pta_system
CREATE DATABASE IF NOT EXISTS `jcses_pta_system` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `jcses_pta_system`;

-- Dumping structure for view jcses_pta_system.active_parent_students
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `active_parent_students` (
	`parentID` BIGINT(20) UNSIGNED NOT NULL,
	`parent_first_name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`parent_last_name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`email` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`phone` VARCHAR(20) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`studentID` BIGINT(20) UNSIGNED NOT NULL,
	`student_name` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`grade_level` VARCHAR(20) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`section` VARCHAR(50) NULL COLLATE 'utf8mb4_unicode_ci',
	`relationship_type` ENUM('mother','father','guardian','grandparent','sibling','other') NOT NULL COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.cache
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `cache` (
	`key` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`value` MEDIUMTEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`expiration` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.cache_locks
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `cache_locks` (
	`key` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`owner` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`expiration` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.dashboard_metrics
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `dashboard_metrics` (
	`metricID` BIGINT(20) UNSIGNED NOT NULL,
	`metric_name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`metric_category` ENUM('enrollment','projects','financial','participation','system') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`current_value` DECIMAL(15,2) NOT NULL,
	`target_value` DECIMAL(15,2) NULL,
	`unit_of_measure` VARCHAR(20) NULL COLLATE 'utf8mb4_unicode_ci',
	`calculation_method` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`last_updated` TIMESTAMP NOT NULL,
	`is_active` TINYINT(1) NOT NULL,
	`display_order` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.failed_jobs
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `failed_jobs` (
	`id` BIGINT(20) UNSIGNED NOT NULL,
	`uuid` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`connection` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`queue` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payload` LONGTEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`exception` LONGTEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`failed_at` TIMESTAMP NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.financial_reconciliations
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `financial_reconciliations` (
	`reconciliationID` BIGINT(20) UNSIGNED NOT NULL,
	`reconciliation_period` VARCHAR(20) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`start_date` DATE NOT NULL,
	`end_date` DATE NOT NULL,
	`total_system_amount` DECIMAL(12,2) NOT NULL,
	`total_bank_amount` DECIMAL(12,2) NOT NULL,
	`discrepancy_amount` DECIMAL(12,2) NOT NULL,
	`reconciliation_status` ENUM('pending','completed','discrepancy_found') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`reconciled_date` TIMESTAMP NOT NULL,
	`reconciled_by` BIGINT(20) UNSIGNED NOT NULL,
	`notes` TEXT NULL COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.jobs
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `jobs` (
	`id` BIGINT(20) UNSIGNED NOT NULL,
	`queue` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payload` LONGTEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`attempts` TINYINT(3) UNSIGNED NOT NULL,
	`reserved_at` INT(10) UNSIGNED NULL,
	`available_at` INT(10) UNSIGNED NOT NULL,
	`created_at` INT(10) UNSIGNED NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.job_batches
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `job_batches` (
	`id` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`name` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`total_jobs` INT(11) NOT NULL,
	`pending_jobs` INT(11) NOT NULL,
	`failed_jobs` INT(11) NOT NULL,
	`failed_job_ids` LONGTEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`options` MEDIUMTEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`cancelled_at` INT(11) NULL,
	`created_at` INT(11) NOT NULL,
	`finished_at` INT(11) NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.migrations
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `migrations` (
	`id` INT(10) UNSIGNED NOT NULL,
	`migration` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`batch` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.parents
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `parents` (
	`parentID` BIGINT(20) UNSIGNED NOT NULL,
	`first_name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`last_name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`email` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`phone` VARCHAR(20) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`street_address` VARCHAR(255) NULL COLLATE 'utf8mb4_unicode_ci',
	`city` VARCHAR(100) NULL COLLATE 'utf8mb4_unicode_ci',
	`barangay` VARCHAR(100) NULL COLLATE 'utf8mb4_unicode_ci',
	`zipcode` VARCHAR(10) NULL COLLATE 'utf8mb4_unicode_ci',
	`password_hash` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_date` TIMESTAMP NOT NULL,
	`last_login` TIMESTAMP NULL,
	`account_status` ENUM('active','inactive','suspended') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`userID` BIGINT(20) UNSIGNED NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.parent_student_relationships
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `parent_student_relationships` (
	`relationshipID` BIGINT(20) UNSIGNED NOT NULL,
	`parentID` BIGINT(20) UNSIGNED NOT NULL,
	`studentID` BIGINT(20) UNSIGNED NOT NULL,
	`relationship_type` ENUM('mother','father','guardian','grandparent','sibling','other') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`is_primary_contact` TINYINT(1) NOT NULL,
	`created_date` TIMESTAMP NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.password_reset_tokens
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `password_reset_tokens` (
	`email` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`token` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.payment_receipts
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `payment_receipts` (
	`receiptID` BIGINT(20) UNSIGNED NOT NULL,
	`paymentID` BIGINT(20) UNSIGNED NOT NULL,
	`receipt_number` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`receipt_content` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`generated_date` TIMESTAMP NOT NULL,
	`generated_by` BIGINT(20) UNSIGNED NOT NULL,
	`email_sent` TINYINT(1) NOT NULL,
	`print_count` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.payment_transactions
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `payment_transactions` (
	`paymentID` BIGINT(20) UNSIGNED NOT NULL,
	`parentID` BIGINT(20) UNSIGNED NOT NULL,
	`projectID` BIGINT(20) UNSIGNED NOT NULL,
	`contributionID` BIGINT(20) UNSIGNED NOT NULL,
	`amount` DECIMAL(10,2) NOT NULL,
	`payment_method` ENUM('cash','check','bank_transfer') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`transaction_status` ENUM('pending','completed','failed','refunded') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`transaction_date` TIMESTAMP NOT NULL,
	`receipt_number` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`reference_number` VARCHAR(100) NULL COLLATE 'utf8mb4_unicode_ci',
	`processed_by` BIGINT(20) UNSIGNED NOT NULL,
	`notes` TEXT NULL COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.projects
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `projects` (
	`projectID` BIGINT(20) UNSIGNED NOT NULL,
	`project_name` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`description` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`goals` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`target_budget` DECIMAL(12,2) NOT NULL,
	`current_amount` DECIMAL(12,2) NOT NULL,
	`start_date` DATE NOT NULL,
	`target_completion_date` DATE NOT NULL,
	`actual_completion_date` DATE NULL,
	`project_status` ENUM('created','active','in_progress','completed','archived','cancelled') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_by` BIGINT(20) UNSIGNED NOT NULL,
	`created_date` TIMESTAMP NOT NULL,
	`updated_date` TIMESTAMP NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.project_contributions
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `project_contributions` (
	`contributionID` BIGINT(20) UNSIGNED NOT NULL,
	`projectID` BIGINT(20) UNSIGNED NOT NULL,
	`parentID` BIGINT(20) UNSIGNED NOT NULL,
	`contribution_amount` DECIMAL(10,2) NOT NULL,
	`payment_method` ENUM('cash','check','bank_transfer') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`payment_status` ENUM('pending','completed','refunded') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`contribution_date` TIMESTAMP NOT NULL,
	`receipt_number` VARCHAR(50) NULL COLLATE 'utf8mb4_unicode_ci',
	`notes` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`processed_by` BIGINT(20) UNSIGNED NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.project_financial_summary
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `project_financial_summary` (
	`projectID` BIGINT(20) UNSIGNED NOT NULL,
	`project_name` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`target_budget` DECIMAL(12,2) NOT NULL,
	`current_amount` DECIMAL(12,2) NOT NULL,
	`completion_percentage` DECIMAL(21,6) NULL,
	`total_contributions` BIGINT(21) NOT NULL,
	`project_status` ENUM('created','active','in_progress','completed','archived','cancelled') NOT NULL COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.project_updates
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `project_updates` (
	`updateID` BIGINT(20) UNSIGNED NOT NULL,
	`projectID` BIGINT(20) UNSIGNED NOT NULL,
	`update_title` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`update_description` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`milestone_achieved` VARCHAR(200) NULL COLLATE 'utf8mb4_unicode_ci',
	`progress_percentage` DECIMAL(5,2) NOT NULL,
	`update_date` TIMESTAMP NOT NULL,
	`updated_by` BIGINT(20) UNSIGNED NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.refunds
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `refunds` (
	`refundID` BIGINT(20) UNSIGNED NOT NULL,
	`paymentID` BIGINT(20) UNSIGNED NOT NULL,
	`refund_amount` DECIMAL(10,2) NOT NULL,
	`refund_reason` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`refund_status` ENUM('pending','approved','completed','rejected') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`requested_date` TIMESTAMP NOT NULL,
	`processed_date` TIMESTAMP NULL,
	`requested_by` BIGINT(20) UNSIGNED NOT NULL,
	`processed_by` BIGINT(20) UNSIGNED NULL,
	`notes` TEXT NULL COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.reports
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `reports` (
	`reportID` BIGINT(20) UNSIGNED NOT NULL,
	`report_name` VARCHAR(200) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`report_type` ENUM('participation','financial','project_analytics','custom','automated') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`report_description` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`parameters` LONGTEXT NULL COLLATE 'utf8mb4_bin',
	`generated_date` TIMESTAMP NOT NULL,
	`generated_by` BIGINT(20) UNSIGNED NOT NULL,
	`file_path` VARCHAR(500) NULL COLLATE 'utf8mb4_unicode_ci',
	`file_format` ENUM('pdf','excel','csv','html') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`is_scheduled` TINYINT(1) NOT NULL,
	`schedule_frequency` ENUM('daily','weekly','monthly','quarterly','yearly') NULL COLLATE 'utf8mb4_unicode_ci',
	`next_run_date` TIMESTAMP NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.report_execution_log
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `report_execution_log` (
	`executionID` BIGINT(20) UNSIGNED NOT NULL,
	`reportID` BIGINT(20) UNSIGNED NOT NULL,
	`execution_start` TIMESTAMP NOT NULL,
	`execution_end` TIMESTAMP NULL,
	`execution_status` ENUM('running','completed','failed','cancelled') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`record_count` INT(11) NOT NULL,
	`file_size_bytes` INT(11) NOT NULL,
	`error_message` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`executed_by` BIGINT(20) UNSIGNED NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.report_recipients
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `report_recipients` (
	`recipientID` BIGINT(20) UNSIGNED NOT NULL,
	`reportID` BIGINT(20) UNSIGNED NOT NULL,
	`userID` BIGINT(20) UNSIGNED NOT NULL,
	`recipient_email` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`delivery_method` ENUM('email','download','both') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`is_active` TINYINT(1) NOT NULL,
	`added_date` TIMESTAMP NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.role_permissions
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `role_permissions` (
	`rolePermissionID` BIGINT(20) UNSIGNED NOT NULL,
	`roleID` BIGINT(20) UNSIGNED NOT NULL,
	`permissionID` BIGINT(20) UNSIGNED NOT NULL,
	`granted_date` TIMESTAMP NOT NULL,
	`granted_by` BIGINT(20) UNSIGNED NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.security_audit_log
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `security_audit_log` (
	`logID` BIGINT(20) UNSIGNED NOT NULL,
	`userID` BIGINT(20) UNSIGNED NULL,
	`action` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`table_affected` VARCHAR(50) NULL COLLATE 'utf8mb4_unicode_ci',
	`record_id` INT(11) NULL,
	`old_values` LONGTEXT NULL COLLATE 'utf8mb4_bin',
	`new_values` LONGTEXT NULL COLLATE 'utf8mb4_bin',
	`ip_address` VARCHAR(45) NULL COLLATE 'utf8mb4_unicode_ci',
	`user_agent` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`session_id` VARCHAR(128) NULL COLLATE 'utf8mb4_unicode_ci',
	`timestamp` TIMESTAMP NOT NULL,
	`success` TINYINT(1) NOT NULL,
	`error_message` TEXT NULL COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.sessions
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `sessions` (
	`id` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`user_id` BIGINT(20) UNSIGNED NULL,
	`ip_address` VARCHAR(45) NULL COLLATE 'utf8mb4_unicode_ci',
	`user_agent` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`payload` LONGTEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`last_activity` INT(11) NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.students
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `students` (
	`studentID` BIGINT(20) UNSIGNED NOT NULL,
	`student_name` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`grade_level` VARCHAR(20) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`section` VARCHAR(50) NULL COLLATE 'utf8mb4_unicode_ci',
	`enrollment_status` ENUM('active','transferred','graduated','dropped') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`academic_year` VARCHAR(20) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`enrollment_date` DATE NOT NULL,
	`birth_date` DATE NULL,
	`gender` ENUM('male','female') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_date` TIMESTAMP NOT NULL,
	`updated_date` TIMESTAMP NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.users
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `users` (
	`userID` BIGINT(20) UNSIGNED NOT NULL,
	`username` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`password_hash` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`user_type` ENUM('parent','administrator','teacher','principal') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`email` VARCHAR(150) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`phone` VARCHAR(20) NULL COLLATE 'utf8mb4_unicode_ci',
	`address` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`first_name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`last_name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`is_active` TINYINT(1) NOT NULL,
	`created_date` TIMESTAMP NOT NULL,
	`last_login` TIMESTAMP NULL,
	`password_changed_date` TIMESTAMP NULL,
	`failed_login_attempts` INT(11) NOT NULL,
	`account_locked_until` TIMESTAMP NULL,
	`remember_token` VARCHAR(100) NULL COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.user_permissions
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `user_permissions` (
	`permissionID` BIGINT(20) UNSIGNED NOT NULL,
	`permission_name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`permission_description` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`module_name` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`is_active` TINYINT(1) NOT NULL,
	`created_date` TIMESTAMP NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.user_roles
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `user_roles` (
	`roleID` BIGINT(20) UNSIGNED NOT NULL,
	`role_name` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`role_description` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`is_active` TINYINT(1) NOT NULL,
	`created_date` TIMESTAMP NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.user_role_assignments
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `user_role_assignments` (
	`assignmentID` BIGINT(20) UNSIGNED NOT NULL,
	`userID` BIGINT(20) UNSIGNED NOT NULL,
	`roleID` BIGINT(20) UNSIGNED NOT NULL,
	`assigned_date` TIMESTAMP NOT NULL,
	`assigned_by` BIGINT(20) UNSIGNED NOT NULL,
	`is_active` TINYINT(1) NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for view jcses_pta_system.user_sessions
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `user_sessions` (
	`sessionID` VARCHAR(128) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`userID` BIGINT(20) UNSIGNED NOT NULL,
	`ip_address` VARCHAR(45) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`user_agent` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NOT NULL,
	`last_activity` TIMESTAMP NOT NULL,
	`expires_at` TIMESTAMP NULL,
	`is_active` TINYINT(1) NOT NULL
) ENGINE=MyISAM;

-- Dumping structure for trigger jcses_pta_system.update_project_amount_after_contribution
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER update_project_amount_after_contribution
            AFTER INSERT ON project_contributions
            FOR EACH ROW
            BEGIN
                UPDATE projects
                SET current_amount = (
                    SELECT COALESCE(SUM(contribution_amount), 0)
                    FROM project_contributions
                    WHERE projectID = NEW.projectID AND payment_status = 'completed'
                )
                WHERE projectID = NEW.projectID;
            END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger jcses_pta_system.update_project_amount_after_contribution_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER update_project_amount_after_contribution_update
            AFTER UPDATE ON project_contributions
            FOR EACH ROW
            BEGIN
                UPDATE projects
                SET current_amount = (
                    SELECT COALESCE(SUM(contribution_amount), 0)
                    FROM project_contributions
                    WHERE projectID = NEW.projectID AND payment_status = 'completed'
                )
                WHERE projectID = NEW.projectID;
            END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `active_parent_students`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `active_parent_students` AS SELECT
                p.parentID,
                p.first_name as parent_first_name,
                p.last_name as parent_last_name,
                p.email,
                p.phone,
                s.studentID,
                s.student_name,
                s.grade_level,
                s.section,
                psr.relationship_type
            FROM parents p
            JOIN parent_student_relationships psr ON p.parentID = psr.parentID
            JOIN students s ON psr.studentID = s.studentID
            WHERE p.account_status = 'active' AND s.enrollment_status = 'active' ;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `cache`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `cache_locks`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `dashboard_metrics`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `failed_jobs`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `financial_reconciliations`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `jobs`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `job_batches`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `migrations`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `parents`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `parent_student_relationships`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `password_reset_tokens`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `payment_receipts`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `payment_transactions`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `projects`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `project_contributions`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `project_financial_summary`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `project_financial_summary` AS SELECT
                p.projectID,
                p.project_name,
                p.target_budget,
                p.current_amount,
                (p.current_amount / p.target_budget * 100) as completion_percentage,
                COUNT(pc.contributionID) as total_contributions,
                p.project_status
            FROM projects p
            LEFT JOIN project_contributions pc ON p.projectID = pc.projectID
            WHERE pc.payment_status = 'completed' OR pc.payment_status IS NULL
            GROUP BY p.projectID, p.project_name, p.target_budget, p.current_amount, p.project_status ;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `project_updates`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `refunds`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `reports`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `report_execution_log`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `report_recipients`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `role_permissions`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `security_audit_log`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `sessions`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `students`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `users`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `user_permissions`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `user_roles`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `user_role_assignments`;
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `user_sessions`;
;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
