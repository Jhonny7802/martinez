@echo off
echo Iniciando Martinez con MySQL completo en Docker...
echo.

echo Deteniendo contenedores existentes...
docker-compose -f docker-compose.xampp.yml down 2>nul
docker-compose -f docker-compose.complete.yml down 2>nul

echo.
echo Construyendo e iniciando servicios...
docker-compose -f docker-compose.complete.yml up -d --build

echo.
echo Esperando que MySQL esté listo...
timeout /t 30 /nobreak

echo.
echo Configurando Laravel...
docker-compose -f docker-compose.complete.yml exec app cp .env.complete .env
docker-compose -f docker-compose.complete.yml exec app php artisan key:generate --force
docker-compose -f docker-compose.complete.yml exec app php artisan config:clear
docker-compose -f docker-compose.complete.yml exec app php artisan cache:clear
docker-compose -f docker-compose.complete.yml exec app php artisan view:clear
docker-compose -f docker-compose.complete.yml exec app php artisan route:clear
docker-compose -f docker-compose.complete.yml exec app php artisan storage:link

echo.
echo Ejecutando migraciones...
docker-compose -f docker-compose.complete.yml exec app php artisan migrate --force

echo.
echo ¡Listo! Servicios disponibles:
echo - Aplicación: http://localhost:8000
echo - phpMyAdmin: http://localhost:8080
echo - MySQL: localhost:3307
echo - Redis: localhost:6380
echo.
pause
