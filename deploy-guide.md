# Panduan Deploy Marketplace Laravel ke Hosting Gratis

## Rekomendasi Hosting Gratis Terbaik

### 1. Railway.app (⭐⭐⭐⭐⭐ - Rekomendasi Utama)

-   **Free tier**: $5/month credit
-   **Database**: PostgreSQL/MySQL gratis
-   **SSL**: Otomatis
-   **Deploy**: Via GitHub
-   **Custom domain**: Support

### 2. Render.com (⭐⭐⭐⭐)

-   **Free tier**: Web service + database
-   **Database**: PostgreSQL gratis
-   **SSL**: Otomatis
-   **Sleep**: 15 menit idle

### 3. 000webhost (⭐⭐⭐)

-   **Pure PHP hosting**
-   **Database**: MySQL
-   **Limit**: 1GB storage
-   **No SSH access**

## Setup Railway.app (5 Menit Deploy)

### Langkah 1: Persiapan GitHub

```bash
# Di terminal project
git init
git add .
git commit -m "Initial commit for Railway deployment"
git branch -M main
git remote add origin https://github.com/YOUR_USERNAME/marketplace3.git
git push -u origin main
```

### Langkah 2: Railway Setup

1. Buka https://railway.app
2. Login dengan GitHub
3. Klik "New Project"
4. Pilih "Deploy from GitHub repo"
5. Pilih repository `marketplace3`

### Langkah 3: Environment Variables

Tambahkan di Railway dashboard:

```
APP_NAME="Marketplace"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-app.railway.app

DB_CONNECTION=pgsql
DB_HOST=containers-us-west-XXX.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=YOUR_PASSWORD

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Langkah 4: Post-Deploy Commands

Jalankan di Railway console:

```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
```

## Setup Render.com (Alternatif)

### Build Settings:

-   **Build Command**: `composer install --no-dev && php artisan config:cache`
-   **Start Command**: `php artisan serve --host=0.0.0.0 --port=$PORT`

### Environment:

-   **Runtime**: PHP
-   **Plan**: Free

## File yang Sudah Disiapkan

✅ `Procfile` - Heroku/Railway deployment  
✅ `railway.json` - Railway configuration  
✅ `nginx.conf` - Web server config  
✅ `database/database.sqlite` - Production database

## Quick Deploy Checklist

-   [ ] Push ke GitHub
-   [ ] Connect ke Railway/Render
-   [ ] Set environment variables
-   [ ] Run migration
-   [ ] Test URL

## URL Hasil Deploy

-   Railway: `https://marketplace3-production.up.railway.app`
-   Render: `https://marketplace3.onrender.com`

## Troubleshooting

-   Jika error database: cek environment variables
-   Jika 404: cek .htaccess atau nginx config
-   Jika assets tidak load: jalankan `php artisan storage:link`
