# Despliegue en Railway - Proyecto Martinez

## Archivos de Configuración Creados

### 1. `railway.json`
Configuración principal para Railway con:
- Build automático usando Nixpacks
- Comandos de instalación de dependencias (Composer + NPM)
- Comandos de despliegue con migraciones y optimizaciones

### 2. `.env.railway`
Variables de entorno específicas para Railway con:
- Configuración de MySQL usando variables de Railway
- URL dinámica de la aplicación
- Configuración de producción

### 3. `Procfile`
Comando para iniciar el servidor web de Laravel

## Pasos para Desplegar en Railway

### 1. Preparar el Repositorio
```bash
git add .
git commit -m "Configuración para Railway"
git push origin main
```

### 2. Crear Proyecto en Railway
1. Ve a [railway.app](https://railway.app)
2. Conecta tu repositorio de GitHub
3. Selecciona el proyecto Martinez

### 3. Agregar Base de Datos MySQL
1. En el dashboard de Railway, haz clic en "New"
2. Selecciona "Database" → "MySQL"
3. Railway creará automáticamente las variables de entorno:
   - `MYSQLHOST`
   - `MYSQLPORT`
   - `MYSQLDATABASE`
   - `MYSQLUSER`
   - `MYSQLPASSWORD`

### 4. Configurar Variables de Entorno
En el dashboard de Railway, ve a Variables y agrega:

```
APP_NAME=Martinez
APP_ENV=production
APP_KEY=base64:pE3C+QdZFX0FXC45uNzbIZ8FPtmVrtfd4kOIDnvF/ow=
APP_DEBUG=false
LOG_CHANNEL=stack
LOG_LEVEL=info
BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
MEDIA_DISK=public
FILESYSTEM_DISK=local
DEBUGBAR_ENABLED=false
QUERY_DETECTOR_ENABLED=false
SHOW_VERSION=true
UPGRADE_MODE=false
```

### 5. Variables Opcionales (si las necesitas)
```
MAIL_MAILER=smtp
MAIL_HOST=tu-servidor-smtp
MAIL_PORT=587
MAIL_USERNAME=tu-email
MAIL_PASSWORD=tu-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tudominio.com

STRIPE_KEY=tu-stripe-key
STRIPE_SECRET_KEY=tu-stripe-secret
```

## Comandos que se Ejecutan Automáticamente

Railway ejecutará estos comandos durante el despliegue:

1. **Build:**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install
   npm run production
   ```

2. **Deploy:**
   ```bash
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan serve --host=0.0.0.0 --port=$PORT
   ```

## Verificación del Despliegue

1. Railway te proporcionará una URL pública
2. Visita la URL para verificar que la aplicación funciona
3. Verifica que las migraciones se ejecutaron correctamente
4. Prueba las funcionalidades principales

## Notas Importantes

- **Base de Datos:** Railway usa MySQL 8.0
- **PHP:** El proyecto requiere PHP 8.0+
- **Storage:** Los archivos subidos se almacenan en el filesystem local
- **Logs:** Accesibles desde el dashboard de Railway
- **SSL:** Railway proporciona HTTPS automáticamente

## Solución de Problemas

### Error de Migraciones
Si las migraciones fallan, puedes ejecutarlas manualmente:
```bash
php artisan migrate:fresh --force
```

### Error de Permisos
Asegúrate de que las carpetas storage y bootstrap/cache tengan permisos de escritura.

### Error de APP_KEY
Si necesitas generar una nueva clave:
```bash
php artisan key:generate --show
```

## Monitoreo

- **Logs:** Dashboard de Railway → Deployments → View Logs
- **Métricas:** Dashboard de Railway → Metrics
- **Base de Datos:** Puedes conectarte usando las credenciales del dashboard
