# ⚠️ CRITICAL FIX: Livewire MethodNotAllowedHttpException on Hostinger

## The Error You're Seeing

```
MethodNotAllowedHttpException
The GET method is not supported for route livewire/update. Supported methods: POST.
```

## Root Cause

Hostinger's Apache configuration is converting Livewire POST requests to GET requests, or the .htaccess isn't preserving the HTTP method properly.

## 🔥 CRITICAL FILES TO UPLOAD

### 1. `public/.htaccess` (MOST IMPORTANT!)

The updated version now includes `[QSA,L]` flags which preserve POST data.

### 2. `config/livewire.php` (NEW FILE!)

This is a new file that was just created. You MUST upload it.

## 📋 Step-by-Step Fix (Do IN ORDER)

### Step 1: Update .env File on Server

**This is usually the #1 cause of the error!**

SSH or use Hostinger File Manager to edit `.env`:

```env
# CRITICAL: Must match your exact domain
APP_URL=https://yourdomain.com
# Or if no HTTPS:
# APP_URL=http://yourdomain.com

# Session Settings
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true  # false if no HTTPS
SESSION_SAME_SITE=lax
SESSION_DOMAIN=.yourdomain.com

# Make sure APP_KEY exists
APP_KEY=base64:your-key-here
```

### Step 2: Upload Files via FTP/File Manager

Upload these files:

-   ✅ `public/.htaccess` → `/public_html/public/.htaccess`
-   ✅ `config/livewire.php` → `/public_html/config/livewire.php`
-   ✅ `app/Livewire/Admin/StoreBilling.php`
-   ✅ `resources/views/livewire/admin/store-billing.blade.php`
-   ✅ `database/migrations/2026_01_08_051450_add_indexes_to_product_details_table.php`

### Step 3: Run Commands via SSH

```bash
cd public_html  # or your app folder

# Run migration
php artisan migrate

# Clear ALL caches (CRITICAL!)
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize

# Republish Livewire assets
php artisan livewire:publish --assets --force
```

### Step 4: Test in Clean Browser

1. **Open Incognito/Private window**
2. Go to your site
3. Clear browser cache (Ctrl+Shift+Delete)
4. Try the search again

## 🚨 If Error STILL Appears

### Option A: Check Document Root

Your domain MUST point to the `/public` folder:

-   Hostinger Control Panel
-   Domains → Manage → Advanced → Document Root
-   Should be: `/domains/yourdomain.com/public_html/public`

### Option B: Try Alternative .htaccess

If the error persists, replace `public/.htaccess` with this simpler version:

```apache
<IfModule mod_rewrite.c>
    Options -MultiViews -Indexes
    RewriteEngine On

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

Then:

```bash
php artisan config:clear
php artisan cache:clear
```

### Option C: Change Session Driver

In `.env` on server:

```env
SESSION_DRIVER=file  # Instead of database
SESSION_SECURE_COOKIE=false  # If not using HTTPS
```

Then:

```bash
php artisan config:clear
```

### Option D: Verify PHP Version

-   Go to Hostinger Control Panel
-   Advanced → PHP Configuration
-   Ensure PHP 8.1 or 8.2 is selected

### Option E: Contact Hostinger Support

Ask them to verify:

1. Is `mod_rewrite` enabled?
2. Are `.htaccess` files being processed?
3. Is the document root set to `/public`?

## 🧪 Debug Commands

### Check if Livewire route exists:

```bash
php artisan route:list | grep livewire
```

Should show:

```
POST   livewire/update
```

### Check configuration:

```bash
php artisan tinker
>>> config('app.url')
>>> config('session.driver')
```

### Check logs:

```bash
tail -f storage/logs/laravel.log
```

## ✅ What Was Fixed

1. **`.htaccess`**: Added `[QSA,L]` flags to preserve POST method
2. **`livewire.php`**: Created config with explicit POST method handling
3. **Search optimization**: Added debounce (500ms) and query limits
4. **Database indexes**: Smart migration that checks for existing indexes
5. **StoreBilling.php**: Optimized search query (removed joins, added limits)

## 📊 Expected Results After Fix

-   ✅ Search works without errors
-   ✅ Much faster search performance (< 1 second)
-   ✅ No more MethodNotAllowedHttpException
-   ✅ Smoother user experience with debouncing

## 💡 Pro Tips

1. **Always test in Incognito** after changes
2. **Clear cache** after every config change
3. **Check `.env` first** - 90% of deployment issues are here
4. **Don't cache config** until everything works perfectly
5. **Monitor `storage/logs/laravel.log`** for errors

## Need Help?

Run these and send me the output:

```bash
php artisan about
php artisan route:list | grep livewire
php artisan tinker --execute="dump(config('app.url'))"
cat .env | grep -E "APP_URL|SESSION"
```
