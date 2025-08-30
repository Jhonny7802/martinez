# Martinez - Docker con XAMPP MySQL

Esta configuración permite ejecutar la aplicación Laravel en Docker mientras usa la base de datos MySQL existente de XAMPP.

## Configuración Previa en XAMPP

### 1. Verificar que XAMPP MySQL esté corriendo
- Abrir XAMPP Control Panel
- Asegurar que MySQL esté iniciado (botón "Start")
- El puerto por defecto debe ser 3306

### 2. Crear la base de datos (si no existe)
```sql
-- Acceder a phpMyAdmin (http://localhost/phpmyadmin)
-- O usar MySQL CLI:
CREATE DATABASE IF NOT EXISTS martinez CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Verificar configuración MySQL
En XAMPP, el usuario por defecto es:
- **Usuario**: root
- **Contraseña**: (vacía por defecto)
- **Puerto**: 3306

## Configuración Docker

### 1. Usar la configuración específica para XAMPP
```bash
# Usar el archivo docker-compose específico para XAMPP
docker-compose -f docker-compose.xampp.yml build
docker-compose -f docker-compose.xampp.yml up -d
```

### 2. Configurar variables de entorno
```bash
# Copiar el archivo de entorno configurado para XAMPP
cp .env.docker .env

# Editar .env si es necesario para ajustar credenciales de base de datos
```

### 3. Configurar la aplicación
```bash
# Generar clave de aplicación
docker-compose -f docker-compose.xampp.yml exec app php artisan key:generate

# Ejecutar migraciones
docker-compose -f docker-compose.xampp.yml exec app php artisan migrate

# Ejecutar seeders (opcional)
docker-compose -f docker-compose.xampp.yml exec app php artisan db:seed
```

## Servicios Disponibles

- **Aplicación Laravel**: http://localhost:8000
- **Redis**: localhost:6380 (puerto modificado para evitar conflictos)
- **MySQL XAMPP**: localhost:3306 (tu instalación existente)
- **phpMyAdmin XAMPP**: http://localhost/phpmyadmin

## Configuración de Red

La aplicación Docker se conecta a XAMPP usando:
- `host.docker.internal`: Permite acceso desde el contenedor al host
- Puerto 3306: Puerto estándar de MySQL en XAMPP

## Comandos Útiles

### Gestión con XAMPP
```bash
# Iniciar con configuración XAMPP
docker-compose -f docker-compose.xampp.yml up -d

# Ver logs
docker-compose -f docker-compose.xampp.yml logs -f app

# Parar servicios
docker-compose -f docker-compose.xampp.yml down

# Ejecutar comandos Artisan
docker-compose -f docker-compose.xampp.yml exec app php artisan migrate
docker-compose -f docker-compose.xampp.yml exec app php artisan cache:clear
```

### Verificar conexión a base de datos
```bash
# Probar conexión desde el contenedor
docker-compose -f docker-compose.xampp.yml exec app php artisan tinker
# En tinker:
DB::connection()->getPdo();
```

## Troubleshooting

### 1. Error de conexión a MySQL
**Problema**: No se puede conectar a la base de datos
**Soluciones**:
```bash
# Verificar que XAMPP MySQL esté corriendo
# En XAMPP Control Panel, MySQL debe mostrar "Running"

# Verificar puerto MySQL en XAMPP
# Por defecto es 3306, pero puede cambiar

# Probar conexión desde host
mysql -u root -p -h 127.0.0.1 -P 3306
```

### 2. Puerto 3306 ocupado
Si XAMPP usa un puerto diferente:
```bash
# Editar .env.docker
DB_PORT=3307  # O el puerto que use XAMPP
```

### 3. Permisos de MySQL
Si hay problemas de acceso:
```sql
-- En phpMyAdmin o MySQL CLI:
GRANT ALL PRIVILEGES ON martinez.* TO 'root'@'%';
FLUSH PRIVILEGES;
```

### 4. Firewall de Windows
Si la conexión falla, verificar que Windows Firewall permita conexiones en puerto 3306.

## Ventajas de esta Configuración

✅ **Mantiene tu base de datos existente**: No necesitas migrar datos  
✅ **Usa phpMyAdmin familiar**: El mismo phpMyAdmin de XAMPP  
✅ **Configuración mínima**: Solo Docker para la aplicación  
✅ **Desarrollo híbrido**: Puedes usar XAMPP para otros proyectos  

## Estructura de Archivos

```
├── docker-compose.yml          # Configuración completa (con MySQL)
├── docker-compose.xampp.yml    # Configuración para XAMPP
├── .env.docker                 # Variables para XAMPP
└── docker/
    ├── nginx.conf
    ├── php.ini
    └── supervisord.conf
```

## Migración de Datos (si es necesario)

Si necesitas importar datos existentes:
```bash
# Exportar desde otra base de datos
mysqldump -u usuario -p base_datos_origen > backup.sql

# Importar a XAMPP
mysql -u root -p martinez < backup.sql
```
