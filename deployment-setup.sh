#!/bin/bash
# Laravel Auto-Cancel Order Deployment Setup Script
# This script sets up automatic order cancellation for deployment

echo "🚀 Setting up Laravel Auto-Cancel Order for deployment..."

# Create deployment directory
mkdir -p deployment

# Create deployment instructions
cat > deployment/README.md << 'EOF'
# Auto-Cancel Order Deployment Guide

## 🎯 Overview
This setup ensures automatic order cancellation runs every 5 minutes after deployment.

## 📋 Deployment Options

### Option 1: Linux Server with Cron (Recommended)
```bash
# Add this to your crontab
* * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
```

### Option 2: Shared Hosting (cPanel)
1. Go to cPanel → Cron Jobs
2. Add new cron job:
   - Command: `cd /home/username/public_html && php artisan schedule:run`
   - Interval: Every 5 minutes

### Option 3: Laravel Forge/Envoyer
- The scheduler is automatically configured

### Option 4: Docker Deployment
- Use the provided docker-compose.yml with scheduler service

### Option 5: Windows Server
- Use Windows Task Scheduler with the provided .bat file
EOF

# Create cPanel cron setup script
cat > deployment/setup-cpanel-cron.sh << 'EOF'
#!/bin/bash
# Setup for cPanel shared hosting

echo "Setting up cPanel cron job..."
echo "Add this command to cPanel Cron Jobs:"
echo "cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1"
echo "Set interval to: Every 5 minutes"
EOF

# Create Windows batch file
cat > deployment/setup-windows.bat << 'EOF'
@echo off
echo Setting up Windows Task Scheduler...
echo.
echo 1. Open Task Scheduler
echo 2. Create Basic Task
echo 3. Set trigger: Every 5 minutes
echo 4. Set action: php artisan schedule:run
echo 5. Set working directory: %cd%
pause
EOF

# Create Docker setup
cat > deployment/docker-compose.yml << 'EOF'
version: '3.8'
services:
  app:
    build: .
    ports:
      - "8000:8000"
    volumes:
      - .:/var/www/html
    command: php artisan serve --host=0.0.0.0 --port=8000
  
  scheduler:
    build: .
    volumes:
      - .:/var/www/html
    command: sh -c "while true; do php artisan schedule:run; sleep 300; done"
    depends_on:
      - app
EOF

# Create deployment checklist
cat > deployment/DEPLOYMENT_CHECKLIST.md << 'EOF'
# ✅ Deployment Checklist for Auto-Cancel Order

## Pre-deployment
- [ ] Upload all files to server
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Run `php artisan migrate`
- [ ] Run `php artisan config:cache`

## Scheduler Setup (Choose one)
- [ ] **Linux/Cron**: Add cron job
- [ ] **cPanel**: Setup cron job via cPanel
- [ ] **Windows**: Setup Task Scheduler
- [ ] **Docker**: Use docker-compose.yml
- [ ] **Laravel Forge**: Already configured

## Post-deployment verification
- [ ] Run `php artisan order:auto-cancel` manually to test
- [ ] Check logs in `storage/logs/laravel.log`
- [ ] Create test order and wait 5 minutes
- [ ] Verify order status changes to 'cancelled'

## Troubleshooting
- [ ] Check file permissions
- [ ] Verify PHP version compatibility
- [ ] Check database connection
- [ ] Review scheduler logs
EOF

chmod +x deployment/setup-cpanel-cron.sh
chmod +x deployment-setup.sh

echo "✅ Deployment files created!"
echo "📁 Check the 'deployment' folder for setup instructions"
echo "🎯 Run: ./deployment-setup.sh to see all options"
