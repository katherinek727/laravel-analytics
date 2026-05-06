#!/bin/bash

# Laravel Test Task Deployment Script
# Usage: ./deploy.sh [environment]

set -e

ENVIRONMENT=${1:-production}
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="backups/$TIMESTAMP"

echo "🚀 Starting deployment to $ENVIRONMENT environment"
echo "=============================================="

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup current database if exists
if [ -f "database/database.sqlite" ]; then
    echo "📦 Backing up database..."
    cp database/database.sqlite "$BACKUP_DIR/database.sqlite"
    echo "✅ Database backed up to $BACKUP_DIR/database.sqlite"
fi

# Backup .env file if exists
if [ -f ".env" ]; then
    echo "📦 Backing up .env file..."
    cp .env "$BACKUP_DIR/.env"
    echo "✅ .env backed up to $BACKUP_DIR/.env"
fi

# Install/update dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "📦 Installing Node.js dependencies..."
npm ci --only=production

# Build assets
echo "🔨 Building assets..."
npm run build

# Set up environment
if [ ! -f ".env" ]; then
    echo "⚙️  Creating .env file..."
    cp .env.example .env
    php artisan key:generate
    echo "✅ .env file created and application key generated"
fi

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Clear and cache configuration
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "🔒 Setting permissions..."
chmod -R 755 storage bootstrap/cache

# Create storage link if needed
if [ ! -L "public/storage" ]; then
    echo "🔗 Creating storage symlink..."
    php artisan storage:link
fi

# Display deployment summary
echo ""
echo "=============================================="
echo "✅ Deployment completed successfully!"
echo "=============================================="
echo ""
echo "📊 Application Information:"
echo "   Environment: $ENVIRONMENT"
echo "   Backup created: $BACKUP_DIR"
echo "   Timestamp: $(date)"
echo ""
echo "🔧 Next steps:"
echo "   1. Configure web server to point to /public"
echo "   2. Set up cron job for scheduled tasks:"
echo "      * * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1"
echo "   3. Test the application:"
echo "      - Visit: / (Home page)"
echo "      - Visit: /dashboard (Requires login)"
echo "      - Visit: /analytics (Analytics dashboard)"
echo "      - Test: /api/jokes (Jokes API)"
echo "      - Test: /test-dynamic-fields.html (Dynamic fields demo)"
echo ""
echo "📝 For detailed deployment instructions, see DEPLOYMENT.md"
echo ""