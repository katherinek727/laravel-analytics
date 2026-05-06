# Deployment Guide

This Laravel application is ready for deployment to various hosting platforms. Below are instructions for common deployment scenarios.

## Prerequisites

- PHP 8.2+
- Composer
- SQLite (or MySQL/PostgreSQL for production)
- Node.js & NPM (for asset compilation)
- Git

## Quick Deployment Options

### 1. **Laravel Forge / Vapor**
```bash
# Push to your Git repository
git push origin main

# Forge/Vapor will automatically:
# - Install dependencies
# - Run migrations
# - Compile assets
# - Set up environment
```

### 2. **Traditional VPS (Ubuntu/Debian)**
```bash
# Clone repository
git clone <your-repo-url>
cd laravel-test-task

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Set up environment
cp .env.example .env
php artisan key:generate

# Configure database (SQLite by default)
touch database/database.sqlite

# Run migrations
php artisan migrate --force

# Set up storage permissions
chmod -R 775 storage bootstrap/cache

# Configure web server (Nginx/Apache)
# Point document root to: /public
```

### 3. **Shared Hosting (cPanel)**
1. Upload all files to your hosting account
2. Set document root to `public/` folder
3. Create database (MySQL recommended for shared hosting)
4. Update `.env` file with database credentials
5. Run migrations via SSH or control panel

## Environment Configuration

### Required `.env` Settings
```env
APP_NAME="Laravel Test Task"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=sqlite
# OR for MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

### Optional Settings for Production
```env
# For better performance
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# For email notifications
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Database Setup

### SQLite (Default)
```bash
touch database/database.sqlite
php artisan migrate --force
```

### MySQL
```sql
CREATE DATABASE laravel_test_task;
CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON laravel_test_task.* TO 'laravel_user'@'localhost';
FLUSH PRIVILEGES;
```

Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_test_task
DB_USERNAME=laravel_user
DB_PASSWORD=secure_password
```

## Scheduled Tasks

The application requires a cron job to run scheduled tasks:

```bash
# Add to crontab (crontab -e)
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

This will:
- Fetch jokes from API every 5 minutes
- Run other scheduled tasks if added

## Security Considerations

### 1. **HTTPS**
- Always use HTTPS in production
- Update `APP_URL` to use `https://`
- Set `SESSION_SECURE_COOKIE=true` in `.env`

### 2. **File Permissions**
```bash
# Recommended permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache
```

### 3. **Environment Protection**
- Never commit `.env` file
- Use different `APP_KEY` for each environment
- Keep database credentials secure

## Performance Optimization

### 1. **Caching**
```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear cache when needed
php artisan optimize:clear
```

### 2. **Asset Optimization**
```bash
# Production build
npm run build

# Or for development
npm run dev
```

### 3. **Database Optimization**
```sql
-- Regular maintenance for SQLite
VACUUM;
ANALYZE;
```

## Monitoring & Maintenance

### 1. **Logs**
- Check `storage/logs/laravel.log`
- Set up log rotation
- Monitor error rates

### 2. **Health Checks**
```bash
# Check application health
php artisan up
php artisan down

# Check scheduled tasks
php artisan schedule:list
```

### 3. **Backup**
```bash
# Backup database (SQLite)
cp database/database.sqlite database/backup-$(date +%Y%m%d).sqlite

# Backup important files
tar -czf backup-$(date +%Y%m%d).tar.gz .env database/ storage/
```

## Troubleshooting

### Common Issues

1. **"No application encryption key has been specified."**
   ```bash
   php artisan key:generate
   ```

2. **"SQLSTATE[HY000]: General error: 1 no such table"**
   ```bash
   php artisan migrate --force
   ```

3. **"Class 'DOMDocument' not found"**
   ```bash
   # Install PHP DOM extension
   sudo apt-get install php-xml
   ```

4. **Assets not loading**
   ```bash
   npm install && npm run build
   php artisan storage:link
   ```

### Debug Mode
For troubleshooting, temporarily enable debug mode:
```env
APP_DEBUG=true
```

**Remember to disable it in production!**

## Deployment Checklist

- [ ] Set up hosting environment
- [ ] Configure `.env` file
- [ ] Run database migrations
- [ ] Set up cron job for scheduled tasks
- [ ] Compile assets for production
- [ ] Configure web server (Nginx/Apache)
- [ ] Set up SSL certificate (HTTPS)
- [ ] Test all features
- [ ] Monitor logs for errors

## Support

For deployment issues:
1. Check Laravel documentation: https://laravel.com/docs/deployment
2. Review error logs in `storage/logs/`
3. Verify file permissions and ownership
4. Ensure all dependencies are installed

---

**Ready for Production** - All test requirements implemented with professional deployment configuration.