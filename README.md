# JCSES - Parent and Project Management System

A  web-based management system for JCSES Elementary School's parent and project (PTA) management needs.

![Laravel](https://img.shields.io/badge/Laravel-12.0-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![Node.js](https://img.shields.io/badge/Node.js-20.x-green.svg)

## Quick Start

**First time setup? Follow these steps:**

1. **Install Prerequisites:**
   - [PHP 8.2+](https://www.php.net/downloads)
   - [Composer](https://getcomposer.org/download/)
   - [Node.js LTS](https://nodejs.org/)
   - [XAMPP](https://www.apachefriends.org/)

2. **Install Dependencies:**
   ```powershell
   composer install
   npm install
   ```

3. **Configure Environment:**
   ```powershell
   copy .env.example .env
   php artisan key:generate
   php artisan migrate
   ```

4. **Run Development Servers (2 terminals):**
   ```powershell
   # Terminal 1 - Vite (CSS/JS hot reload)
   npm run dev
   
   # Terminal 2 - Laravel Server
   php artisan serve
   ```

5. **Visit:** http://127.0.0.1:8000

6. **Register your first account!**

---

## Table of Contents

- [Quick Start](#-quick-start)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Database Setup](#-database-setup)
- [Configuration](#configuration)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [Login System](#-login-system)
- [Troubleshooting](#troubleshooting)

## 📚 Additional Guides

- **[Database Setup Guide](SETUP_DATABASE.md)** - Step-by-step MySQL setup
- **[Login Integration Guide](LOGIN_INTEGRATION.md)** - Authentication system details

## Features

### Authentication & Authorization
- Multi-role authentication system (Parent, Teacher, Principal, Administrator)
- Custom login/registration with JCSES branding
- Password recovery and reset functionality
- Session management with "Remember Me" option
- Account security features (lockout after 5 failed attempts)
- Role-based access control (RBAC)

### Profile Management System
**Parent Account Management:**
- Administrator-created parent accounts with secure credential generation
- Parent profile viewing and updating capabilities
- Family relationship management (parent-child linking)
- Contact information management

**Student Management:**
- Student enrollment tracking
- Grade level and section assignment
- Student transfer functionality with historical records
- Multi-guardian relationship support

### Project Management System
**PTA Project Lifecycle:**
- Principal-initiated project creation with goals, budgets, and timelines
- Project status management (Active, In Progress, Completed)
- Project activation for parent contributions
- Progress tracking and milestone management
- Project archival with complete audit trails

**Parent Participation:**
- Browse active PTA projects
- View project descriptions, goals, and progress
- Make contributions through integrated payment system
- Track personal participation history

### Payment Processing & Financial Management
**Transaction Processing:**
- Multiple payment method support (cash, check, bank transfer)
- Manual payment recording by administrators
- Automatic receipt generation
- Real-time contribution tracking

**Financial Operations:**
- Complete transaction audit trails
- Financial transparency reporting

### User Management & Security
**Account Administration:**
- CRUD operations for all user types
- Role-based permission assignment
- Account activation/deactivation
- Password reset functionality

**Security & Compliance:**
- Comprehensive activity logging
- Security audit trails (login attempts, authentication events)
- Session timeout enforcement (30 minutes)
- User activity monitoring
- FERPA and Philippine Data Privacy Act compliance

### Reporting & Analytics Dashboard
**Report Generation:**
- Parent participation reports
- Financial summaries by project and period
- Enrollment statistics
- User activity reports
- Custom report creation

**Analytics & Insights:**
- Real-time performance metrics
- Project completion rates and ROI analysis
- Contribution trends and patterns
- Exportable reports (PDF, Excel, CSV)

## Requirements

Before you begin, ensure you have the following installed:

- **PHP** >= 8.2
- **Composer** (for PHP dependencies)
- **XAMPP** (or similar local server environment)
  - Apache
  - MySQL/MariaDB
- **Git** (optional, for version control)


## Installation

### Step 1: Clone or Download the Repository

```bash
# Using Git
git clone https://github.com/ItzRenzo/JCSES-Parent-and-Project-Management-System.git

# Or download the ZIP file and extract it to:
# C:\xampp\htdocs\Web Developement\JCSES-Parent and Project Management System
```

### Step 2: Install PHP Dependencies

Open PowerShell or Command Prompt and navigate to the project directory:

```powershell
cd "C:\xampp\htdocs\Web Developement\JCSES-Parent and Project Management System"
composer install
```

### Step 3: Set Up Environment Configuration

1. Copy the `.env.example` file to `.env`:

```powershell
copy .env.example .env
```

2. Generate application key:

```powershell
php artisan key:generate
```

### Step 4: Configure Database

1. Open **XAMPP Control Panel** and start **Apache** and **MySQL**

2. Edit the `.env` file and configure your database settings:

```env
DB_CONNECTION=sqlite
DB_DATABASE=C:\xampp\htdocs\Web Developement\JCSES-Parent and Project Management System\database\database.sqlite

# Or if using MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=jcses_pta
# DB_USERNAME=root
# DB_PASSWORD=
```

3. If using SQLite (already configured), the database file already exists at `database/database.sqlite`

4. If using MySQL, create the database:
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `jcses_pta`

### Step 5: Install Node.js and npm (Required for Vite)

**Vite** is used to compile CSS and JavaScript assets for the dashboard and admin panel.

1. **Download Node.js**:
   - Visit: https://nodejs.org/
   - Download the **LTS (Long Term Support)** version
   - Recommended: Node.js 20.x or higher

2. **Install Node.js**:
   - Run the installer
   - Accept all default settings
   - Make sure "Add to PATH" is checked
   - npm (Node Package Manager) will be installed automatically

3. **Verify Installation**:
   
   Close and reopen PowerShell, then run:
   ```powershell
   node --version
   npm --version
   ```
   
   You should see version numbers like:
   ```
   v20.x.x
   10.x.x
   ```

4. **Install Project Dependencies**:
   ```powershell
   cd "C:\xampp\htdocs\Web Developement\JCSES-Parent and Project Management System"
   npm install
   ```
   
   This installs:
   - Vite
   - Laravel Vite Plugin
   - Tailwind CSS
   - Alpine.js
   - Other frontend dependencies

### Step 6: Run Database Migrations

```powershell
php artisan migrate
```

This will create all the necessary database tables.

## Configuration

### Adding Custom Assets

The application requires custom images for full branding. Add these files to complete the setup:

1. **School Logo** (JCSES Elementary School logo)
   - Path: `public/images/logos/jcses-logo.png`
   - Recommended size: 200x200px or similar
   - Format: PNG with transparent background

2. **Password Toggle Icons**
   - View icon: `public/images/icons/view.png` (open eye)
   - Hide icon: `public/images/icons/hide.png` (closed eye)
   - Recommended size: 20x20px or 24x24px
   - Format: PNG with transparent background

**Folder structure:**
```
public/
  images/
    icons/
      view.png       ← Add your "show password" eye icon
      hide.png       ← Add your "hide password" eye icon
    logos/
      jcses-logo.png  ← Add your school logo
    backgrounds/     ← Optional: custom background images
```

## 🚀 Running the Application

### Development Mode (Recommended)

For development with **hot module replacement (HMR)** and auto-refresh, you need to run **TWO terminal windows simultaneously**:

#### Terminal 1: Start Vite Development Server

```powershell
cd "C:\xampp\htdocs\Web Developement\JCSES-Parent and Project Management System"
npm run dev
```

**What this does:**
- Compiles CSS and JavaScript in real-time
- Enables hot module replacement (changes appear instantly)
- Watches for file changes
- **Keep this terminal running while developing**

You should see output like:
```
VITE v7.x.x  ready in xxx ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose
➜  press h + enter to show help
```

#### Terminal 2: Start Laravel Server

In a **new terminal window**:

```powershell
cd "C:\xampp\htdocs\Web Developement\JCSES-PTA and Project Management System"
php artisan serve
```

The application will be available at: **http://127.0.0.1:8000**

---

### Production Mode

For production deployment, build the assets once:

```powershell
npm run build
```

This creates optimized files in `public/build/`. Then start Laravel:

```powershell
php artisan serve
```

---

### Alternative: Using XAMPP (Without Vite)

If you prefer not to use Vite:

1. Build assets once: `npm run build`
2. Ensure project is in XAMPP `htdocs` directory
3. Start Apache and MySQL from XAMPP Control Panel
4. Access at: **http://localhost/Web%20Developement/JCSES-PTA%20and%20Project%20Management%20System/public**

**Note**: Without `npm run dev`, you won't have hot module replacement. You'll need to run `npm run build` after every CSS/JS change.

### Available Routes

- **Home**: `/`
- **Login**: `/login`
- **Register**: `/register`
- **Dashboard**: `/dashboard` (after login)
- **Forgot Password**: `/forgot-password`
- **Reset Password**: `/reset-password`

## 📁 Project Structure

```
JCSES-PTA and Project Management System/
├── app/
│   ├── Http/
│   │   └── Controllers/        # Application controllers
│   ├── Models/                 # Eloquent models
│   └── Providers/              # Service providers
├── bootstrap/                  # Bootstrap files
├── config/                     # Configuration files
├── database/
│   ├── migrations/             # Database migrations
│   ├── seeders/                # Database seeders
│   └── database.sqlite         # SQLite database file
├── public/
│   ├── images/                 # Image assets
│   │   ├── icons/              # UI icons
│   │   ├── logos/              # School logos
│   │   └── backgrounds/        # Background images
│   └── index.php               # Entry point
├── resources/
│   ├── views/
│   │   ├── auth/               # Authentication views
│   │   │   ├── login.blade.php
│   │   │   └── register.blade.php
│   │   └── layouts/
│   │       └── guest.blade.php # Guest layout with JCSES branding
│   ├── css/                    # CSS files
│   └── js/                     # JavaScript files
├── routes/
│   ├── web.php                 # Web routes
│   └── auth.php                # Authentication routes
├── storage/                    # Storage for logs, cache, etc.
├── tests/                      # Application tests
├── vendor/                     # Composer dependencies
├── .env                        # Environment configuration
├── artisan                     # Laravel CLI tool
├── composer.json               # PHP dependencies
└── README.md                   # This file
```

## 🛠️ Troubleshooting

### Common Issues and Solutions

#### 1. **Vite Manifest Not Found Error**
```
ViteManifestNotFoundException: Vite manifest not found at: public/build/manifest.json
```

**Solution:**
- Make sure you've run `npm install`
- Start the Vite dev server: `npm run dev`
- Or build for production: `npm run build`

#### 2. **"npm: command not found" or "node: command not found"**

**Solution:**
- Node.js is not installed or not in PATH
- Download from https://nodejs.org/ and install
- **Close and reopen all terminal windows** after installation
- Verify with: `node --version` and `npm --version`

#### 3. **npm install fails or takes forever**

**Solution:**
```powershell
# Clear npm cache
npm cache clean --force

# Delete node_modules and package-lock.json
Remove-Item -Recurse -Force node_modules
Remove-Item package-lock.json

# Reinstall
npm install
```

#### 4. **Port 5173 already in use (Vite error)**

**Solution:**
```powershell
# Kill the process using port 5173
netstat -ano | findstr :5173
taskkill /PID <process_id> /F

# Or change Vite port in vite.config.js:
# server: { port: 5174 }
```

#### 5. **Changes not showing in browser**

**Solution:**
- Make sure `npm run dev` is running
- Hard refresh browser: `Ctrl + Shift + R` or `Ctrl + F5`
- Clear browser cache
- Check browser console for errors

#### 6. **"Class not found" or Composer errors**
```powershell
composer dump-autoload
```

#### 7. **Database connection errors**
- Verify MySQL is running in XAMPP
- Check `.env` database credentials
- Ensure database exists

#### 8. **Permission errors**
```powershell
# Windows: Right-click folder > Properties > Security > Edit permissions
# Give full control to the following folders:
# - storage/
# - bootstrap/cache/
```

#### 9. **Images not showing**
- Verify images are in the correct folders
- Check file names match exactly (case-sensitive)
- Clear browser cache (Ctrl + F5)

#### 10. **"419 Page Expired" error**
```powershell
php artisan cache:clear
php artisan config:clear
```

#### 11. **Routes not working**
```powershell
php artisan route:clear
php artisan route:cache
```

#### 12. **Can't login - "No users in database"**
- You need to register first!
- Go to: http://127.0.0.1:8000/register
- Create your first account
- Then you can login

### Clearing All Cache

If you encounter unexpected issues, clear all caches:

```powershell
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 🎨 Customization

### Changing Colors

The main color scheme uses JCSES green (#28a745). To customize:

1. Open `resources/views/layouts/guest.blade.php`
2. Find the `<style>` section
3. Modify color values:
   - Primary green: `#28a745`
   - Hover green: `#218838`
   - Link green: `#28a745`

### Modifying Forms

Authentication forms are located in:
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`

## � Helpful Commands Reference

### Daily Development Workflow

```powershell
# Start development (run in 2 terminals)
npm run dev          # Terminal 1: Vite dev server
php artisan serve    # Terminal 2: Laravel server
```

### Asset Management

```powershell
npm run dev          # Development mode with hot reload
npm run build        # Production build (optimized assets)
```

### Database Commands

```powershell
php artisan migrate              # Run migrations
php artisan migrate:fresh        # Drop all tables and re-run migrations
php artisan migrate:rollback     # Rollback last migration
php artisan migrate:status       # Check migration status
php artisan db:seed              # Run seeders
```

### Cache Management

```powershell
php artisan cache:clear          # Clear application cache
php artisan config:clear         # Clear config cache
php artisan route:clear          # Clear route cache
php artisan view:clear           # Clear compiled views
php artisan optimize:clear       # Clear all cached files
```

### User Management (via Tinker)

```powershell
php artisan tinker

# Create a new user
User::create([
    'name' => 'Admin User',
    'email' => 'admin@jcses.edu',
    'password' => Hash::make('password123')
]);
```

### Debugging

```powershell
php artisan route:list           # List all routes
php artisan config:show          # Show configuration
tail storage/logs/laravel.log    # View logs (PowerShell: Get-Content -Tail 50)
```

**Developed for JCSES Elementary School**
