# Gestor de Tareas - Laravel + Vue

Gestión de tareas con API REST en Laravel y frontend en Vue 3

## Instrucciones de instalación

### Requisitos

- PHP 8.2+ y Composer
- Node.js v22 y npm
- MySQL 8 (local o contenedor)

Si no tienes MySQL local, levantar la base de datos con Docker:

```bash
docker compose up -d db
```

### Pasos

**1. Instalar dependencias de backend**

```bash
composer install
```

**2. Instalar dependencias de frontend**

```bash
npm install
```

**3. Configurar el entorno**

Copiar el archivo de ejemplo y generar la clave de la aplicación:

```bash
cp .env.example .env
php artisan key:generate
```

Configurar la conexión a la base de datos en `.env`:

```env
APP_NAME="Gestor de Tareas"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Base de datos (MySQL local o contenedor en otro puerto)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

**4. Migrar y sembrar la base de datos**

```bash
php artisan migrate --seed
```

**5. Compilar el frontend**

```bash
npm run build
```

**6. Levantar el servidor**

```bash
php artisan serve
```

Acceder a la aplicación en http://127.0.0.1:8000. Para desarrollo del frontend usar `npm run dev` (Vite, puerto 5173).

## Migraciones y seeders

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar migraciones (borrando datos previos)
php artisan migrate:fresh

# Sembrar datos de ejemplo (usuarios y tareas)
php artisan db:seed

# Migrar y sembrar en un solo comando
php artisan migrate --seed

# Usuarios generados (contraseña: password)
#   admin@example.com   (Admin)
#   maria.garcia@example.com
#   carlos.lopez@example.com
#   lucia.fernandez@example.com
```

## Arquitectura

Stack: **Laravel 10 + PHP 8** (backend/API REST), **Vue 3 + Vite** (frontend), **MySQL** (Base datos). El backend sigue una arquitectura en capas: Modelos Eloquent, una Service Class (`TaskManager`) que encapsula la lógica de negocio con transacciones, FormRequests para validación y controladores API que delegan y responden JSON. Autenticación token-based con **Laravel Sanctum**. El frontend usa **Vue Router**, store centralizado con **Pinia** y consume la API interna vía módulos ES6 (`fetch`/`async-await`). Cualquier usuario autenticado puede gestionar las tareas de todos los usuarios.