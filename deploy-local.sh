#!/bin/bash
# deploy-local.sh - Setup local development environment

echo "🚀 Setting up local development environment..."
echo "============================================="

# Set the correct environment file
php set-env.php

# Generate application key if not exists
if [ ! -f .env ] || ! grep -q "APP_KEY=" .env | grep -q "base64:"; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Clear and cache configuration
echo "🧹 Clearing configuration cache..."
php artisan config:clear
php artisan cache:clear

# Test database connection
echo "🗄️  Testing database connection..."
php artisan migrate:status > /dev/null 2>&1

if [ $? -eq 0 ]; then
    echo "✅ Database connection successful!"
    echo ""
    echo "🎯 Ready to run migrations and seeders:"
    echo "   php artisan migrate:fresh --seed"
else
    echo "❌ Database connection failed!"
    echo "   Please ensure MySQL is running and configured correctly."
    echo "   Check your .env file for database settings."
fi

echo ""
echo "🌐 Local development server:"
echo "   php artisan serve"
