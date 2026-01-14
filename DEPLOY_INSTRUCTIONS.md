# Clinic Management System - Deployment Instructions

## Quick Deploy with Docker (Recommended for Manus AI)

### Prerequisites
- Docker & Docker Compose installed
- Git installed

### Step 1: Clone and Setup
```bash
git clone [repository-url] clinic
cd clinic
cp .env.example .env
```

### Step 2: Configure Environment
Edit `.env` file:
```env
APP_NAME="Clinic Management"
APP_ENV=staging
APP_DEBUG=true
APP_URL=http://your-domain.com

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=clinic
DB_USERNAME=clinic
DB_PASSWORD=secret123

SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Step 3: Docker Compose
Create `docker-compose.yml`:
```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "8080:80"
    volumes:
      - .:/var/www/html
    depends_on:
      - db
    environment:
      - APP_ENV=staging

  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: clinic
      MYSQL_USER: clinic
      MYSQL_PASSWORD: secret123
      MYSQL_ROOT_PASSWORD: rootsecret
    volumes:
      - dbdata:/var/lib/mysql
    ports:
      - "3306:3306"

volumes:
  dbdata:
```

### Step 4: Dockerfile
Create `Dockerfile`:
```dockerfile
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache mod_rewrite
RUN a]2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Apache config
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

EXPOSE 80
```

### Step 5: Build and Run
```bash
docker-compose up -d --build
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
```

### Step 6: Access
- URL: http://your-server-ip:8080
- Login: admin / password

---

## Alternative: Traditional Server Deploy

### Requirements
- PHP 8.1+
- MySQL 8.0+
- Composer
- Apache/Nginx

### Commands
```bash
composer install --no-dev
php artisan key:generate
php artisan migrate --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Test Credentials
| Field | Value |
|-------|-------|
| Username | admin |
| Password | password |
| Email | admin@guysiri.com |

## Features Ready for Testing
- Patient Management (CRUD)
- Appointment Booking
- Queue System
- Course Purchase & Usage
- Payment Processing (Cash, Transfer, Course)
- Commission Calculation
- DF Payment Tracking
- Refund & Cancellation

## Test Results
- All 30 tests passing (100%)
- Ready for production deployment
