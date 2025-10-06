# JCSES-PTA Management System Database Structure

## Overview
This document describes the comprehensive database schema for the JCSES Parent-Teacher Association (PTA) and Project Management System. The database is designed to handle all aspects of PTA operations including profile management, project tracking, payment processing, user management, and reporting.

## Database Structure

### Profile Management System Tables

#### 1. **parents**
- **Purpose**: Store parent/guardian information
- **Primary Key**: `parentID` (AUTO_INCREMENT)
- **Key Fields**: 
  - Personal info: `first_name`, `last_name`, `email`, `phone`
  - Address: `street_address`, `city`, `barangay`, `zipcode`
  - Security: `password_hash`, `account_status`
  - Relationship: `userID` (links to users table)

#### 2. **students**
- **Purpose**: Store student enrollment information
- **Primary Key**: `studentID` (AUTO_INCREMENT)
- **Key Fields**: 
  - Student info: `student_name`, `grade_level`, `section`
  - Academic: `academic_year`, `enrollment_date`, `enrollment_status`
  - Personal: `birth_date`, `gender`

#### 3. **parent_student_relationships**
- **Purpose**: Link parents to their children/students
- **Primary Key**: `relationshipID` (AUTO_INCREMENT)
- **Foreign Keys**: `parentID` → parents, `studentID` → students
- **Key Fields**: `relationship_type`, `is_primary_contact`

### Project Management System Tables

#### 4. **projects**
- **Purpose**: Track PTA projects and fundraising initiatives
- **Primary Key**: `projectID` (AUTO_INCREMENT)
- **Key Fields**: 
  - Project info: `project_name`, `description`, `goals`
  - Financial: `target_budget`, `current_amount`
  - Timeline: `start_date`, `target_completion_date`, `actual_completion_date`
  - Status: `project_status` (created, active, in_progress, completed, archived, cancelled)

#### 5. **project_contributions**
- **Purpose**: Track individual parent contributions to projects
- **Primary Key**: `contributionID` (AUTO_INCREMENT)
- **Foreign Keys**: `projectID` → projects, `parentID` → parents
- **Key Fields**: `contribution_amount`, `payment_method`, `payment_status`

#### 6. **project_updates**
- **Purpose**: Track project progress and milestones
- **Primary Key**: `updateID` (AUTO_INCREMENT)
- **Foreign Key**: `projectID` → projects
- **Key Fields**: `update_title`, `update_description`, `progress_percentage`

### Payment Processing & Financial Management Tables

#### 7. **payment_transactions**
- **Purpose**: Record all payment transactions
- **Primary Key**: `paymentID` (AUTO_INCREMENT)
- **Foreign Keys**: Multiple (parentID, projectID, contributionID)
- **Key Fields**: `amount`, `payment_method`, `transaction_status`, `receipt_number`

#### 8. **payment_receipts**
- **Purpose**: Store receipt information
- **Primary Key**: `receiptID` (AUTO_INCREMENT)
- **Foreign Key**: `paymentID` → payment_transactions

#### 9. **refunds**
- **Purpose**: Handle refund requests and processing
- **Primary Key**: `refundID` (AUTO_INCREMENT)
- **Foreign Key**: `paymentID` → payment_transactions

#### 10. **financial_reconciliations**
- **Purpose**: Track financial reconciliation periods
- **Primary Key**: `reconciliationID` (AUTO_INCREMENT)

### User Management & Security System Tables

#### 11. **users**
- **Purpose**: Central user authentication table
- **Primary Key**: `userID` (AUTO_INCREMENT)
- **Key Fields**: 
  - Authentication: `username`, `password_hash`, `user_type`
  - Security: `failed_login_attempts`, `account_locked_until`
  - User types: parent, administrator, teacher, principal

#### 12. **user_roles**
- **Purpose**: Define system roles
- **Primary Key**: `roleID` (AUTO_INCREMENT)
- **Default Roles**: Administrator, Principal, Teacher, Parent

#### 13. **user_permissions**
- **Purpose**: Define granular permissions
- **Primary Key**: `permissionID` (AUTO_INCREMENT)
- **Modules**: profile_management, project_management, payment_processing, user_management, reporting

#### 14. **role_permissions**
- **Purpose**: Link roles to permissions (Many-to-Many)
- **Primary Key**: `rolePermissionID` (AUTO_INCREMENT)

#### 15. **user_role_assignments**
- **Purpose**: Assign roles to users
- **Primary Key**: `assignmentID` (AUTO_INCREMENT)

#### 16. **security_audit_log**
- **Purpose**: Track all system actions for security
- **Primary Key**: `logID` (AUTO_INCREMENT)
- **Tracks**: user actions, table changes, login attempts

#### 17. **user_sessions**
- **Purpose**: Manage user sessions
- **Primary Key**: `sessionID` (VARCHAR)

### Reporting & Analytics Dashboard Tables

#### 18. **reports**
- **Purpose**: Store report definitions and schedules
- **Primary Key**: `reportID` (AUTO_INCREMENT)
- **Report Types**: participation, financial, project_analytics, custom, automated

#### 19. **report_recipients**
- **Purpose**: Define who receives reports
- **Primary Key**: `recipientID` (AUTO_INCREMENT)

#### 20. **dashboard_metrics**
- **Purpose**: Store dashboard KPIs and metrics
- **Primary Key**: `metricID` (AUTO_INCREMENT)
- **Categories**: enrollment, projects, financial, participation, system

#### 21. **report_execution_log**
- **Purpose**: Track report generation history
- **Primary Key**: `executionID` (AUTO_INCREMENT)

## Views and Triggers

### Views
1. **active_parent_students**: Shows relationships between active parents and students
2. **project_financial_summary**: Provides financial overview of projects

### Triggers
1. **update_project_amount_after_contribution**: Automatically updates project current_amount when contributions are added/modified

## Migration Files Created

1. `0001_01_01_000000_create_users_table.php` - Core authentication table
2. `0001_01_01_000003_create_profile_management_tables.php` - Parents, students, relationships
3. `0001_01_01_000004_create_project_management_tables.php` - Projects and contributions
4. `0001_01_01_000005_create_payment_tables.php` - Payment processing tables
5. `0001_01_01_000006_create_user_management_tables.php` - Roles, permissions, security
6. `0001_01_01_000007_create_reporting_tables.php` - Reports and analytics
7. `0001_01_01_000008_add_foreign_key_relationships.php` - Cross-system relationships
8. `0001_01_01_000009_create_views_and_triggers.php` - Views and triggers

## Seeded Data

The system includes default seed data:
- 4 User Roles (Administrator, Principal, Teacher, Parent)
- 14 Permissions across 5 modules
- 6 Dashboard Metrics with targets

## Key Features

### Security
- Password hashing
- Account locking after failed attempts
- Comprehensive audit logging
- Session management
- Role-based access control

### Financial Management
- Multi-project contribution tracking
- Automatic amount calculations
- Receipt generation
- Refund processing
- Financial reconciliation

### Reporting
- Scheduled report generation
- Multiple output formats (PDF, Excel, CSV, HTML)
- Dashboard metrics with targets
- Execution logging

### Scalability
- Indexed tables for performance
- Normalized structure
- Foreign key constraints
- Trigger-based automation

## Usage Notes

1. **User Types**: The system supports four user types with different access levels
2. **Project Flow**: Projects progress through defined statuses with automatic financial tracking
3. **Security**: All actions are logged in the security audit log
4. **Reporting**: Reports can be scheduled and automatically distributed
5. **Performance**: Indexes are created on commonly queried fields

This database structure provides a complete foundation for the JCSES-PTA Management System with room for future enhancements and scalability.