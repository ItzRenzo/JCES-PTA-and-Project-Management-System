# Update Announcement Audiences

## Changes Made

Updated the announcement system to properly align with user roles:

**Before:**
- Audience options: everyone, parents, teachers, **staff** ❌
- "staff" was unclear - meant admin + principal?

**After:**
- Audience options: everyone, parents, teachers, **administrator**, **principal** ✅
- Each user role now has a corresponding audience option

## Database Migration Required

Run these commands to update your database:

```bash
# Rollback the announcements table
php artisan migrate:rollback --step=1

# Re-run the migration with updated audience enum
php artisan migrate

# Re-seed the announcements
php artisan db:seed --class=AnnouncementSeeder
```

## What's Updated

1. **Migration** - Audience enum now includes 'administrator' and 'principal' instead of 'staff'
2. **Controller** - Validation rules updated for both store() and update() methods
3. **Controller** - Role-based filtering now properly filters for each role type
4. **Form** - Audience dropdown now shows "Administrators" and "Principals" options
5. **Model** - forAudience() scope works with new audience values

## Audience Visibility

- **everyone** → All users see it
- **parents** → Only parents see it (+ everyone announcements)
- **teachers** → Only teachers see it (+ everyone announcements)  
- **administrator** → Only administrators see it (+ everyone announcements)
- **principal** → Only principals see it (+ everyone announcements)
