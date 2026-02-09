# 💉 Sistema de Control de Vacunas

Sistema web para la gestión y control del programa de vacunación, desarrollado con Laravel 11.

## 📋 Características

- **Gestión de Inventario**: Control detallado de vacunas, lotes, fechas de vencimiento y stock.
- **Movimientos y Trazabilidad**: Registro completo de entradas, salidas y ajustes de inventario.
- **Gestión de Ubicaciones**: Administración de múltiples puntos de almacenamiento y bodegas.
- **Control de Acceso**: Sistema de usuarios con roles y permisos granulares.
- **Reportes y Estadísticas**: Generación de informes de stock y movimientos.
- **Auditoría**: Registro detallado de actividades y cambios en el sistema.

## 🚀 Requisitos

- PHP >= 8.2
- Composer
- MySQL 8.0 o MariaDB 10.6+
- Node.js >= 18 (para compilación de assets)

## ⚙️ Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/acon3ras/vax-control.git
cd vax-control
```

### 2. Instalar dependencias

```bash
composer install
npm install
```

### 3. Configurar variables de entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita el archivo `.env` con tu configuración de base de datos y correo.

### 4. Configurar base de datos

```bash
php artisan migrate
php artisan db:seed  # Opcional: datos de prueba
```

### 5. Compilar assets

```bash
npm run build
```

### 6. Iniciar servidor de desarrollo

```bash
php artisan serve
```

Visita `http://localhost:8000` en tu navegador.

## 📁 Estructura del Proyecto

```
vax-control/
├── app/                    # Lógica de la aplicación
│   ├── Http/Controllers/   # Controladores
│   ├── Models/             # Modelos Eloquent
│   └── Mail/               # Clases de correo
├── config/                 # Archivos de configuración
├── database/
│   ├── migrations/         # Migraciones de BD
│   └── seeders/            # Seeders de datos
├── public/                 # Archivos públicos
├── resources/
│   ├── views/              # Vistas Blade
│   ├── css/                # Estilos
│   └── js/                 # JavaScript
├── routes/                 # Definición de rutas
└── storage/                # Archivos generados
```

## 🔧 Configuración Adicional

### Correo Electrónico

Configura las siguientes variables en `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=tu-servidor-smtp.com
MAIL_PORT=587
MAIL_USERNAME=tu-usuario
MAIL_PASSWORD=tu-password
MAIL_FROM_ADDRESS=sistema@tudominio.cl
MAIL_FROM_NAME="Control de Vacunas"
```

### Tareas Programadas

Agrega el siguiente cron para tareas automáticas:

```bash
* * * * * cd /ruta-al-proyecto && php artisan schedule:run >> /dev/null 2>&1
```

## 🛡️ Seguridad

- Todas las contraseñas son hasheadas con bcrypt
- Sesiones seguras con tokens CSRF

- Logs de auditoría para acciones críticas

## 📝 Licencia

Este proyecto es de código abierto y se distribuye bajo la **Licencia MIT**.

Copyright © 2026 **Alexi Contreras**.

Puede ser utilizado, modificado y distribuido libremente por personas o instituciones,
siempre que se mantenga el aviso de derechos de autor y la licencia original.

Este software se entrega como referencia técnica y base de apoyo.
No reemplaza sistemas clínicos oficiales ni garantiza cumplimiento normativo
sin las adecuaciones correspondientes en cada país o institución.

---

Desarrollado con ❤️
