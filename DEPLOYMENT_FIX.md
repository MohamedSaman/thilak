# Deployment Fix for Hostinger - Livewire Error & Slow Search

## Problem

-   "MethodNotAllowedHttpException" when searching products (Livewire route issue)
-   Slow search performance compared to other systems on the same server

## Solutions Applied

### 1. Updated .htaccess Configuration

-   Added proper Livewire route handling
-   Ensures POST requests work correctly for Livewire updates

### 2. Optimized Search Performance

-   Added 500ms debounce to search input (reduces server requests)
-   Limited search results to 20 items
-   Removed unnecessary join queries
-   Added proper WHERE clause grouping

### 3. Database Indexes

-   Created migration to add indexes on frequently searched columns

## Deployment Steps for Hostinger

### Step 1: Upload Files

Upload these modified files to your Hostinger server:

-   `public/.htaccess`
-   `app/Livewire/Admin/StoreBilling.php`
-   `resources/views/livewire/admin/store-billing.blade.php`
-   `database/migrations/2026_01_08_051450_add_indexes_to_product_details_table.php`

### Step 2: Run Migration (via SSH or Hostinger Terminal)

```bash
php artisan migrate
```

### Step 3: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

### Step 4: Check .env Configuration

Ensure these settings in your `.env` file on Hostinger:

```env
APP_URL=https://yourdomain.com  # Must match your actual domain
SESSION_DRIVER=database         # or 'file' - database is recommended
SESSION_SECURE_COOKIE=true      # if using HTTPS
SESSION_SAME_SITE=lax

# Database settings
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 5: Verify Livewire Assets

Make sure Livewire assets are published:

```bash
php artisan livewire:publish --assets
```

### Step 6: Fix File Permissions (If Needed)

```bash
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs
```

## Additional Optimization for Hostinger

### Option 1: Use Session Database Driver

If you haven't already, create sessions table:

```bash
php artisan session:table
php artisan migrate
```

Then in `.env`:

```env
SESSION_DRIVER=database
```

### Option 2: Enable OPcache (if available)

In your Hostinger cPanel, check PHP settings and enable OPcache for better performance.

### Option 3: Optimize Composer Autoloader

```bash
composer dump-autoload --optimize
```

## Testing After Deployment

1. Clear your browser cache
2. Test the search functionality
3. Check if the error is resolved
4. Verify search speed improvement

## If Issues Persist

### Check Apache/Nginx Logs

Look for errors in:

-   `/path/to/your/app/storage/logs/laravel.log`
-   Hostinger error logs in cPanel

### Common Issues:

1. **Still getting MethodNotAllowed error:**

    - Verify `.htaccess` was uploaded correctly
    - Check if `mod_rewrite` is enabled on server
    - Ensure `APP_URL` matches your domain exactly (including https://)

2. **Search still slow:**

    - Run the migration to add indexes: `php artisan migrate`
    - Check database connection settings
    - Consider upgrading Hostinger plan if on shared hosting

3. **CSRF Token Mismatch:**
    - Clear cookies in browser
    - Ensure `SESSION_DOMAIN` is set correctly in `.env`
    - Check `config/session.php` settings

## Performance Tips

1. **Use CDN for Assets:** Move CSS/JS to CDN
2. **Enable Browser Caching:** Already in .htaccess
3. **Compress Images:** Optimize product images
4. **Use Queue for Heavy Tasks:** For reports/exports

## Support Commands

### Check Current Environment

```bash
php artisan about
```

### Check Routes

```bash
php artisan route:list | grep livewire
```

### Test Database Connection

```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

## Need More Help?

If the error persists after these steps:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Enable debug mode temporarily: `APP_DEBUG=true` (remember to disable after)
3. Check Hostinger PHP version (should be 8.1 or higher)
4. Contact Hostinger support to verify server configuration
