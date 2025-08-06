#!/bin/bash
# Fix Database Connection Script for Laravel Marketplace
# This script helps fix the "mysql.railway.internal" connection error

echo "🔧 Fixing Database Connection Issues..."
echo "======================================"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to check if running locally or on Railway
check_environment() {
    if [[ -n "$RAILWAY_ENVIRONMENT" ]]; then
        echo -e "${GREEN}✅ Running on Railway${NC}"
        return 1
    else
        echo -e "${YELLOW}⚠️  Running locally${NC}"
        return 0
    fi
}

# Function to create local database
setup_local_database() {
    echo -e "${YELLOW}Setting up local database...${NC}"
    
    # Check if MySQL is running
    if ! pgrep -x "mysqld" > /dev/null; then
        echo -e "${RED}❌ MySQL is not running. Please start XAMPP/WAMP first.${NC}"
        echo "Start XAMPP Control Panel and start MySQL service"
        exit 1
    fi
    
    # Create database if it doesn't exist
    echo "Creating database 'marketplace3'..."
    mysql -u root -e "CREATE DATABASE IF NOT EXISTS marketplace3;" 2>/dev/null
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ Database 'marketplace3' created successfully${NC}"
    else
        echo -e "${RED}❌ Failed to create database. Check MySQL credentials.${NC}"
    fi
}

# Function to configure environment variables
configure_env() {
    echo -e "${YELLOW}Configuring environment variables...${NC}"
    
    # Create backup of current .env
    if [ -f .env ]; then
        cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
        echo -e "${GREEN}✅ Backup created: .env.backup.$(date +%Y%m%d_%H%M%S)${NC}"
    fi
    
    # Use local configuration
    if [ -f .env.local ]; then
        cp .env.local .env
        echo -e "${GREEN}✅ Applied local database configuration${NC}"
    else
        echo -e "${RED}❌ .env.local not found. Creating default configuration...${NC}"
        cat > .env << EOF
APP_NAME="Marketplace"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=marketplace3
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
EOF
    fi
    
    # Generate application key if not exists
    if ! grep -q "base64:" .env; then
        php artisan key:generate
        echo -e "${GREEN}✅ Application key generated${NC}"
    fi
}

# Function to run migrations
run_migrations() {
    echo -e "${YELLOW}Running database migrations...${NC}"
    
    # Check database connection
    php artisan migrate:status > /dev/null 2>&1
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ Database connection successful${NC}"
        
        # Run migrations
        php artisan migrate --force
        if [ $? -eq 0 ]; then
            echo -e "${GREEN}✅ Migrations completed successfully${NC}"
        else
            echo -e "${RED}❌ Migration failed. Check database permissions.${NC}"
        fi
    else
        echo -e "${RED}❌ Cannot connect to database. Check configuration.${NC}"
    fi
}

# Function to create Railway production config
create_railway_config() {
    echo -e "${YELLOW}Creating Railway production configuration...${NC}"
    
    cat > .env.railway << 'EOF'
# Railway Production Environment
APP_NAME="Marketplace"
APP_ENV=production
APP_DEBUG=false
APP_URL=${RAILWAY_STATIC_URL}

# Railway Database (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=${PGHOST}
DB_PORT=${PGPORT}
DB_DATABASE=${PGDATABASE}
DB_USERNAME=${PGUSER}
DB_PASSWORD=${Here is the complete plan to fix the database connection issue:

Information Gathered:
- The error is caused by Laravel trying to connect to a non-existent host `mysql.railway.internal`.
- The database configuration in config/database.php uses environment variables for connection details.
- The deployment guide specifies Railway environment variables for production.
- The local environment likely needs a separate .env.local file with local database settings.

Plan:
- Create a `.env.local` file for local development with correct MySQL host (127.0.0.1), port, database, username, and password.
- Ensure the `.env` file used in production (Railway) has the correct Railway database host and credentials.
- Configure Laravel to load `.env.local` for local development and `.env` for production.
- Test the connection locally using `.env.local`.
- Test the connection in Railway deployment using `.env`.

Dependent Files to be edited:
- `.env.local` (created)
- Possibly `.env` (user to update with Railway credentials)
- No code changes needed in config/database.php as it uses env variables.

Followup steps:
- Confirm with the user if they want me to help create or update the production `.env` file with Railway credentials.
- Guide the user to switch environment files or set environment variables accordingly.
- Test the Laravel app connection locally and on Railway.

<ask_followup_question>
<question>I have created the `.env.local` file for local development with correct MySQL settings. Would you like me to help you update or create the production `.env` file for Railway deployment as well? Also, do you want guidance on how to switch between these environment configurations?</question>
</ask_followup_question>
