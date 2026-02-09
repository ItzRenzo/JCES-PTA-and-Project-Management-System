# JCSES-PTA Management System - Setup Guide

This guide will help you set up the JCSES-PTA Management System on your local development environment.

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **XAMPP** (with Apache, MySQL, PHP 8.2+)
- **Composer** (PHP dependency manager)
- **Node.js & npm** (for frontend assets)
- **Git** (for version control)

## 🚀 Quick Setup (Recommended)

For Windows users, we've created an automated setup script:

### Option 1: Automated Setup (Windows)

1. **Clone the repository**
   ```bash
   cd C:\xampp\htdocs
   git clone <repository-url> "Web Developement\JCES-PTA and Project Management System"
   cd "Web Developement\JCES-PTA and Project Management System"
   ```

2. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start **Apache** and **MySQL**

3. **Create the database**
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Create a new database: `jcses_pta_system`
   - Character set: `utf8mb4_unicode_ci`

4. **Run the setup script**
   ```bash
   setup.bat
   ```

That's it! The script will:
- ✅ Check if MySQL is running
- ✅ Install PHP dependencies
- ✅ Generate application key
- ✅ Run database migrations
- ✅ Seed test data
- ✅ Build frontend assets
- ✅ Clear all caches

### Option 2: Manual Setup

If you prefer manual setup or the automated script doesn't work:

#### Step 1: Clone and Install Dependencies

```bash
cd C:\xampp\htdocs
git clone <repository-url> "Web Developement\JCES-PTA and Project Management System"
cd "Web Developement\JCES-PTA and Project Management System"

# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

#### Step 2: Environment Configuration

```bash
# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate
```

Edit `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jcses_pta_system
DB_USERNAME=root
DB_PASSWORD=
```

#### Step 3: Database Setup

```bash
# Create database in MySQL first, then run:
php artisan db:setup --fresh --seed
```

Or manually:
```bash
# Run migrations
php artisan migrate

# Seed test data
php artisan db:seed
```

#### Step 4: Build Frontend Assets

```bash
# Development build
npm run dev

# Or production build
npm run build
```

#### Step 5: Clear Caches

```bash
php artisan optimize:clear
```

## 🎮 Running the Application

### Start Development Server

```bash
php artisan serve
```

Visit: **http://127.0.0.1:8000**

### Test Accounts

| Role          | Username  | Password     |
|---------------|-----------|--------------|
| Administrator | admin     | password     |
| Principal     | principal | principal123 |
| Teacher       | teacher   | teacher123   |
| Parent        | parent    | parent123    |

For more details, see [TEST_ACCOUNTS.md](TEST_ACCOUNTS.md)

## 🔄 Resetting the Database

If you need to reset the database to its initial state:

### Using Custom Command
```bash
php artisan db:setup --fresh --seed
```

### Using Laravel Commands
```bash
# Drop all tables, run migrations, and seed
php artisan migrate:fresh --seed

# Or step by step
php artisan migrate:fresh
php artisan db:seed
```

## 🛠️ Common Issues & Solutions

### Issue: MySQL Won't Start (Error 1932)

**Solution:**
```bash
# Run the MySQL recovery script
cd C:\xampp
powershell -ExecutionPolicy Bypass -File mysql_recovery.ps1
```

Or manually:
1. Stop MySQL in XAMPP
2. Delete these files from `C:\xampp\mysql\data`:
   - `ibdata1`
   - `ib_logfile0`
   - `ib_logfile1`
3. Copy the same files from `C:\xampp\mysql\backup` to `C:\xampp\mysql\data`
4. Start MySQL

### Issue: 419 Page Expired Error

**Solution:**
```bash
# Clear all caches
php artisan optimize:clear

# Restart the server
php artisan serve
```

Make sure sessions table exists:
```bash
php artisan db:setup --fresh --seed
```

### Issue: Column Not Found Errors

This usually means the database is out of sync.

**Solution:**
```bash
# Reset the database completely
php artisan db:setup --fresh --seed
```

### Issue: Composer Dependencies Error

**Solution:**
```bash
# Update composer
composer self-update

# Clear composer cache
composer clear-cache

# Reinstall dependencies
composer install
```

### Issue: NPM Build Fails

**Solution:**
```bash
# Clear npm cache
npm cache clean --force

# Delete node_modules and reinstall
rmdir /s /q node_modules
npm install

# Build again
npm run build
```

## 📁 Project Structure

```
JCES-PTA and Project Management System/
├── app/
│   ├── Console/Commands/     # Custom artisan commands
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   └── Services/            # Business logic services
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── views/              # Blade templates
│   ├── js/                 # JavaScript files
│   └── css/               # CSS files
├── routes/
│   ├── web.php            # Web routes
│   └── auth.php           # Authentication routes
├── public/                # Public assets
├── .env                   # Environment configuration
├── setup.bat             # Automated setup script
└── README.md             # Project documentation
```

## 🗂️ Database Schema

The database includes the following main tables:

- **users** - User accounts (all types)
- **user_roles** - User role definitions
- **user_permissions** - Permission definitions
- **role_permissions** - Role-permission mappings
- **parents** - Parent profile information
- **students** - Student records
- **projects** - PTA project management
- **project_contributions** - Parent contributions
- **payment_transactions** - Payment tracking
- **announcements** - System announcements
- **schedules** - Event scheduling
- **security_audit_logs** - Security logging
- **sessions** - User session management

For detailed schema, see [DATABASE_STRUCTURE.md](DATABASE_STRUCTURE.md)

## 🔐 Security Notes

- **Never commit `.env` file** to version control
- Change default passwords before deploying to production
- Keep your XAMPP installation updated
- Use strong passwords for production databases
- Enable HTTPS in production environments

## 👥 Team Collaboration

### For Team Members Joining the Project:

1. **Pull latest changes**
   ```bash
   git pull origin main
   ```

2. **Update dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Reset database** (if migrations have changed)
   ```bash
   php artisan db:setup --fresh --seed
   ```

4. **Rebuild assets** (if frontend changed)
   ```bash
   npm run build
   ```

5. **Clear caches**
   ```bash
   php artisan optimize:clear
   ```

### When Making Database Changes:

1. **Create migration**
   ```bash
   php artisan make:migration describe_your_change
   ```

2. **Update seeders** if necessary
   ```bash
   php artisan make:seeder YourSeeder
   ```

3. **Test your changes**
   ```bash
   php artisan db:setup --fresh --seed
   ```

4. **Commit migration files**
   ```bash
   git add database/migrations/
   git commit -m "Add/Update database schema"
   git push
   ```

5. **Notify team** to run:
   ```bash
   php artisan db:setup --fresh --seed
   ```

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [TEST_ACCOUNTS.md](TEST_ACCOUNTS.md) - Test account credentials
- [DATABASE_STRUCTURE.md](DATABASE_STRUCTURE.md) - Database schema
- [UPDATE_AUDIENCES.md](UPDATE_AUDIENCES.md) - Audience updates guide

## 🆘 Getting Help

If you encounter issues:

1. Check this SETUP.md file
2. Review [Common Issues](#-common-issues--solutions) section
3. Check Laravel logs: `storage/logs/laravel.log`
4. Ask team lead or project maintainer

## 📝 Development Workflow

1. **Create a new branch** for your feature
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes** and test thoroughly

3. **Reset database** to test clean setup
   ```bash
   php artisan db:setup --fresh --seed
   ```

4. **Commit and push**
   ```bash
   git add .
   git commit -m "Description of changes"
   git push origin feature/your-feature-name
   ```

5. **Create pull request** for review

## ✅ Checklist for New Developers

- [ ] XAMPP installed and MySQL running
- [ ] Composer installed
- [ ] Node.js and npm installed
- [ ] Repository cloned
- [ ] Dependencies installed (`composer install` & `npm install`)
- [ ] `.env` file configured
- [ ] Database created (`jcses_pta_system`)
- [ ] Database setup completed (`php artisan db:setup --fresh --seed`)
- [ ] Assets built (`npm run build`)
- [ ] Can login with test accounts
- [ ] Development server running (`php artisan serve`)

---

**Last Updated:** February 9, 2026  
**Laravel Version:** 12.32.5  
**PHP Version:** 8.2.12
