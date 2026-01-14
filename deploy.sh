#!/bin/bash

echo "========================================="
echo "  Clinic Management System Deployment"
echo "========================================="

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}[1/5] Building Docker containers...${NC}"
docker-compose build --no-cache

echo -e "${YELLOW}[2/5] Starting containers...${NC}"
docker-compose up -d

echo -e "${YELLOW}[3/5] Waiting for database to be ready...${NC}"
sleep 15

echo -e "${YELLOW}[4/5] Running migrations and seeders...${NC}"
docker-compose exec -T app php artisan key:generate --force
docker-compose exec -T app php artisan migrate --seed --force
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache

echo -e "${YELLOW}[5/5] Setting permissions...${NC}"
docker-compose exec -T app chmod -R 775 storage bootstrap/cache
docker-compose exec -T app chown -R www-data:www-data storage bootstrap/cache

echo ""
echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}  Deployment Complete!${NC}"
echo -e "${GREEN}=========================================${NC}"
echo ""
echo "Access URL: http://$(hostname -I | awk '{print $1}'):8080"
echo ""
echo "Login Credentials:"
echo "  Username: admin"
echo "  Password: password"
echo ""
echo "To view logs: docker-compose logs -f"
echo "To stop: docker-compose down"
