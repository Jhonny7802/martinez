# Martinez - Configuración Docker

Este proyecto Laravel ha sido configurado para ejecutarse completamente en Docker con todos los servicios necesarios.

## Servicios Incluidos

- **App**: Aplicación Laravel con PHP 8.1 + Nginx
- **MySQL 8.0**: Base de datos principal
- **Redis**: Cache y sesiones
- **phpMyAdmin**: Interfaz web para administrar MySQL

## Requisitos Previos

- Docker Desktop instalado
- Docker Compose

## Configuración Inicial

### 1. Construir y levantar los contenedores

```bash
# Construir las imágenes
docker-compose build

# Levantar todos los servicios
docker-compose up -d
```

### 2. Configurar la aplicación

```bash
# Copiar archivo de entorno
cp .env.docker .env

# Generar clave de aplicación
docker-compose exec app php artisan key:generate

# Ejecutar migraciones
docker-compose exec app php artisan migrate

# Ejecutar seeders (opcional)
docker-compose exec app php artisan db:seed
```

### 3. Compilar assets (si es necesario)

```bash
# Instalar dependencias npm
docker-compose exec app npm install

# Compilar assets para producción
docker-compose exec app npm run production
```

## Acceso a los Servicios

- **Aplicación Web**: http://localhost:8000
- **phpMyAdmin**: http://localhost:8080
- **MySQL**: localhost:3306
- **Redis**: localhost:6379

## Credenciales por Defecto

### MySQL
- **Host**: mysql (interno) / localhost:3306 (externo)
- **Database**: martinez
- **Usuario**: martinez_user
- **Contraseña**: martinez_password
- **Root Password**: root_password

### phpMyAdmin
- **Usuario**: root
- **Contraseña**: root_password

## Comandos Útiles

### Gestión de Contenedores

```bash
# Ver estado de contenedores
docker-compose ps

# Ver logs
docker-compose logs -f app

# Reiniciar servicios
docker-compose restart

# Parar todos los servicios
docker-compose down

# Parar y eliminar volúmenes (¡CUIDADO: elimina datos!)
docker-compose down -v
```

### Comandos Laravel

```bash
# Acceder al contenedor de la aplicación
docker-compose exec app bash

# Ejecutar comandos Artisan
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:cache

# Instalar dependencias Composer
docker-compose exec app composer install
```

### Base de Datos

```bash
# Backup de la base de datos
docker-compose exec mysql mysqldump -u root -proot_password martinez > backup.sql

# Restaurar backup
docker-compose exec -T mysql mysql -u root -proot_password martinez < backup.sql

# Acceder a MySQL CLI
docker-compose exec mysql mysql -u root -proot_password martinez
```

## Estructura de Archivos Docker

```
├── Dockerfile                 # Imagen principal de la aplicación
├── docker-compose.yml         # Configuración de servicios
├── .dockerignore             # Archivos excluidos del build
├── .env.docker               # Variables de entorno para Docker
└── docker/
    ├── nginx.conf            # Configuración de Nginx
    ├── php.ini               # Configuración de PHP
    ├── supervisord.conf      # Configuración de Supervisor
    └── entrypoint.sh         # Script de inicialización
```

## Troubleshooting

### Problemas Comunes

1. **Error de permisos en storage/**
   ```bash
   docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
   docker-compose exec app chmod -R 775 storage bootstrap/cache
   ```

2. **MySQL no se conecta**
   ```bash
   # Verificar que MySQL esté corriendo
   docker-compose ps mysql
   
   # Ver logs de MySQL
   docker-compose logs mysql
   ```

3. **Assets no se cargan**
   ```bash
   # Recompilar assets
   docker-compose exec app npm run production
   
   # Limpiar cache
   docker-compose exec app php artisan view:clear
   ```

4. **Reiniciar desde cero**
   ```bash
   docker-compose down -v
   docker-compose build --no-cache
   docker-compose up -d
   ```

## Desarrollo

Para desarrollo, puedes montar el código como volumen editando el `docker-compose.yml`:

```yaml
services:
  app:
    volumes:
      - .:/var/www/html
      - ./storage:/var/www/html/storage
```

## Producción

Para producción, asegúrate de:

1. Cambiar todas las contraseñas por defecto
2. Configurar variables de entorno apropiadas
3. Usar HTTPS con un proxy reverso (nginx, traefik, etc.)
4. Configurar backups automáticos
5. Monitorear logs y rendimiento
