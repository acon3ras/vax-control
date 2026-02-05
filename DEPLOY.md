# Guía de Despliegue a Producción - Vax Control

Siga estos pasos para llevar el sistema desde su entorno local (Desarrollo) al servidor de Producción (`10.3.190.10`).

## 1. Preparación en Entorno Local (Su PC)

Antes de copiar los archivos, optimice la aplicación:

1.  **Compilar Assets (CSS/JS):**
    Abra su terminal en la carpeta del proyecto y ejecute:
    ```bash
    npm run build
    ```
    *Esto generará los archivos finales en `public/build`.*

2.  **Limpiar Cachés:**
    ```bash
    php artisan optimize:clear
    ```

3.  **Exportar Base de Datos:**
    - Abra **HeidiSQL** (o su gestor de BD).
    - Haga clic derecho en la base de datos `vax_control_db` (o el nombre que use).
    - Seleccione "Exportar base de datos como SQL".
    - Guarde el archivo `vax_control_prod.sql`.

## 2. Transferencia de Archivos

1.  **Copiar Archivos:**
    Copie toda la carpeta `vax-control` al servidor de destino (ej: `C:\laragon\www\vax-control` en el servidor).

2.  **Excluir (Opcional):**
    No es necesario copiar:
    - `node_modules` (muy pesado, no se usa en prod si ya hizo `npm run build`).
    - `.git` (si existe).

## 3. Configuración en el Servidor

1.  **Configurar Entorno (.env):**
    En el servidor, edite el archivo `.env`:
    ```ini
    APP_ENV=production
    APP_DEBUG=false
    APP_URL=http://10.3.190.10/vax-control/public

    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=vax_control_db  <-- Nombre en el servidor
    DB_USERNAME=root            <-- Usuario del servidor
    DB_PASSWORD=                <-- Contraseña del servidor
    ```

2.  **Base de Datos (Instalación Limpia):**
    En lugar de importar una BD llena de basura, vamos a instalar una limpia con TU usuario admin:
    
    *Opción A (Si tienes terminal):*
    ```bash
    php artisan migrate:fresh --seed --class=ProductionSeeder
    ```

    *Opción B (Sin terminal - HeidiSQL):*
    - Crea la base de datos vacía.
    - Importa el archivo `estructura_limpia.sql` (si lo generaste) o simplemente usa las migraciones si puedes. 
    - **Recomendado:** Si no puedes ejecutar comandos en el servidor, exporta tu BD local (Solo Estructura) con HeidiSQL, impórtala allá, y luego inserta manualmente tu usuario.

    **Nota:** He dejado un archivo `database/seeders/ProductionSeeder.php` listo para crear tu usuario Admin automáticamente si puedes correr seeds.

3.  **Optimizar (Opcional pero recomendado):**
    Si tiene acceso a terminal en el servidor, dentro de la carpeta del proyecto ejecute:
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan storage:link
    ```
    *Si no tiene terminal, asegúrese de que la carpeta `storage` y `bootstrap/cache` tengan permisos de escritura.*

4.  **Enlace Simbólico (Storage):**
    Si las imágenes no se ven, verifique que exista la carpeta `public/storage`. Si no existe y no puede ejecutar comandos, copie el contenido de `storage/app/public` en `public/storage` manualmente.

## 4. Verificar Portal (Landing Page)

Asegúrese de copiar el archivo actualizado del portal:
- Origen: `vax-control/public/portal/index.php`
- Destino: `C:\laragon\www\index.php` (Raíz del servidor web)

---
**Nota:** Si el servidor usa IIS o Apache sin Laragon, asegúrese de que la configuración de reescritura de URL (`web.config` o `.htaccess`) esté activa.
