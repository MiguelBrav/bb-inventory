# Plan de Docker y despliegue en Ubuntu

## Objetivo

Preparar la aplicación Laravel para producción en un servidor Ubuntu. Docker ejecutará únicamente la aplicación; Caddy instalado en el host terminará TLS y actuará como reverse proxy. La base de datos no se ejecutará en un contenedor.

Este archivo describe los cambios y pasos de despliegue pendientes. No reemplaza una configuración de producción ni contiene secretos.

## Arquitectura propuesta

```text
Internet
   |
   | HTTPS :443
   v
Caddy en Ubuntu
   |
   | HTTP a 127.0.0.1:8080
   v
Contenedor Laravel (Apache + PHP 8.4)
   |
   +---- MySQL/MariaDB externo o instalado directamente en el host
```

- Construir una imagen de producción desde el repositorio. La imagen incluye PHP, Apache, dependencias Composer y los assets generados con Vite.
- Configurar Apache para servir desde `public/` y reenviar las solicitudes a Laravel.
- Publicar el puerto del contenedor solo en `127.0.0.1` del host; Caddy será el único punto de entrada público.
- Mantener los datos de la base de datos fuera del contenedor. Persistir en un volumen del host cualquier archivo que la aplicación genere o reciba.
- No incluir Caddy ni la base de datos en Docker Compose.

## Decisiones previas a la implementación

1. **Base de datos:** `.env.example` usa SQLite por defecto. Para producción, se recomienda usar MySQL/MariaDB fuera del contenedor y configurar `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME` y `DB_PASSWORD`. Si se decide conservar SQLite, el archivo `database/database.sqlite` debe estar en un volumen persistente del host y respaldarse junto con los archivos de la aplicación.
2. **Ubicación de la base de datos:** si corre directamente en el mismo Ubuntu, configurar una dirección accesible desde Docker y limitar el acceso de red al contenedor. `127.0.0.1` dentro del contenedor apunta al propio contenedor, no al host. Si la base de datos está en otro servidor, usar su DNS/IP privado y permitir únicamente el acceso necesario.
3. **Archivos persistentes:** definir si la aplicación almacena archivos en `storage/app`. Si los almacena, montar esa ruta en un volumen persistente y conservar el enlace `public/storage` cuando los archivos deban servirse públicamente.
4. **Tareas de cola:** el ejemplo usa `QUEUE_CONNECTION=database`. Si se utilizan trabajos en segundo plano, definir cómo se ejecutará un worker con la misma imagen. No iniciar un worker automáticamente sin supervisar su ciclo de vida.

Las migraciones existentes incluyen tablas para usuarios, cache y jobs. Confirmar que todas las migraciones necesarias para los drivers de sesión/cache/cola elegidos estén desplegadas antes de habilitarlos.

## Cambios de Docker

1. **`Dockerfile` de producción — implementado**
   - Usar PHP 8.4 con Apache. Aunque `composer.json` declara `^8.3`, el lockfile incluye `symfony/error-handler` que requiere PHP 8.4.1 o superior, por lo que PHP 8.3 no puede instalar las dependencias bloqueadas.
   - Instalar las extensiones PHP que requieren Composer y la aplicación; validarlas con `composer check-platform-reqs`.
   - Instalar dependencias de Composer sin dependencias de desarrollo y optimizar el autoloader.
   - Construir CSS/JS con Node y Vite en una etapa de build; copiar `public/build` a la imagen final.
   - Configurar Apache con `DocumentRoot` en `/var/www/html/public`, `mod_rewrite` habilitado y acceso de escritura solo donde Laravel lo necesita.
   - Ejecutar Apache en primer plano para que Docker gestione correctamente el proceso.
2. **`.dockerignore` — implementado**
   - Excluir `.env`, `vendor`, `node_modules`, datos locales, cachés, logs y otros archivos no necesarios para construir la imagen.
   - No excluir los archivos de lock ni el código fuente requerido por Composer/Vite.
3. **`compose.yaml` — pendiente**
   - Definir solo el servicio web de Laravel.
   - Leer variables de un archivo de entorno de producción fuera del repositorio.
   - Publicar el puerto en `127.0.0.1:8080` del host, no en todas las interfaces.
   - Declarar volúmenes persistentes únicamente para los datos de la aplicación que deban sobrevivir a la sustitución del contenedor.
   - Añadir una política de reinicio adecuada y una comprobación de salud basada en el endpoint `/up`.
4. **Pipeline de publicación Docker — implementado**
   - El workflow `.github/workflows/docker-publish.yml` construye la imagen desde el `Dockerfile` de la raíz y la publica en Docker Hub al hacer push a `main` o al ejecutarse manualmente.
   - Publica las etiquetas `latest` y el SHA del commit. Requiere los secrets `DOCKER_USERNAME` y `DOCKER_PASSWORD` en GitHub.
5. **Documentar la configuración de Caddy**
   - Usar el dominio real del sitio y `reverse_proxy 127.0.0.1:8080`.
   - Validar y recargar Caddy después de cada cambio.
6. **Verificar proxy HTTPS y producción**
   - Confirmar que Laravel detecte el esquema HTTPS detrás de Caddy para generar URLs y cookies seguras.
   - Revisar `APP_DEBUG=false`, `APP_ENV=production`, `APP_URL=https://<dominio>` y permisos de `storage`/`bootstrap/cache`.
   - No ejecutar `composer setup` en producción: ese script instala dependencias, genera una clave y ejecuta migraciones durante la preparación, lo cual no es apropiado para el ciclo de build de la imagen.

## Procedimiento de despliegue

### 1. Preparar Ubuntu

1. Configurar un nombre de dominio que resuelva a la IP del servidor.
2. Instalar Docker Engine y el complemento Docker Compose desde los repositorios oficiales de Docker.
3. Instalar Caddy en el host y abrir en el firewall solo los puertos necesarios para SSH y HTTP/HTTPS (80/443).
4. Preparar la base de datos fuera de Docker:
   - Crear la base de datos y un usuario exclusivo para la aplicación.
   - Conceder a ese usuario los permisos requeridos para las migraciones y operaciones de la aplicación; no usar la cuenta `root`.
   - Restringir la escucha y el firewall a las conexiones necesarias.
5. Crear un directorio de despliegue con permisos limitados para el archivo de entorno y los datos persistentes.

### 2. Configurar el entorno de producción

Crear un archivo de entorno de producción fuera del repositorio y suministrarlo a Compose. Completar al menos:

```dotenv
APP_NAME="BB Inventory"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<dominio>
APP_KEY=<clave-generada-una-sola-vez>

DB_CONNECTION=mysql
DB_HOST=<host-o-dns-de-la-base-de-datos>
DB_PORT=3306
DB_DATABASE=<base-de-datos>
DB_USERNAME=<usuario-de-aplicacion>
DB_PASSWORD=<secreto>

```

- Generar `APP_KEY` de forma segura una sola vez (por ejemplo, con `php artisan key:generate --show` en un entorno controlado) y guardarla fuera del repositorio. No regenerarla en cada despliegue: invalidaría datos cifrados y sesiones existentes.
- Completar los valores de sesión, cache y cola de forma coherente con la base de datos y las migraciones instaladas.
- Mantener fuera del repositorio el archivo real de entorno; no imprimirlo en logs ni incorporarlo a la imagen.

### 3. Construir y arrancar

Desde el directorio de la aplicación en el servidor, construir la imagen y arrancar el contenedor. Sustituir la ruta del archivo de entorno y la etiqueta por los valores de la instalación:

```bash
docker build --pull -t bb-inventory:1.0.0 .
docker run -d \
  --name bb-inventory \
  --restart unless-stopped \
  --env-file /srv/bb-inventory/.env.production \
  -p 127.0.0.1:8080:80 \
  bb-inventory:1.0.0
docker ps
docker logs --tail=100 bb-inventory
```

Antes de migrar, comprobar que el contenedor puede conectarse a la base de datos. Ejecutar las migraciones como operación explícita, no como efecto secundario del arranque:

```bash
docker exec bb-inventory php artisan migrate --force
```

No ejecutar seeders de datos de desarrollo en producción. Si se requieren datos iniciales, definirlos y revisarlos explícitamente antes del despliegue.

Si la aplicación almacena archivos en `storage/app`, montar esa ruta desde un directorio persistente del host y preparar sus permisos para `www-data` antes de iniciar el contenedor. No montar un directorio vacío sobre todo `storage` sin crear primero las subcarpetas que Laravel necesita.

### 4. Configurar Caddy

Añadir un sitio para el dominio en el `Caddyfile` del host:

```caddyfile
<dominio> {
    reverse_proxy 127.0.0.1:8080
}
```

Validar la configuración y recargar Caddy según el método de instalación usado. Comprobar que Caddy obtiene el certificado TLS y que el acceso externo usa HTTPS.

### 5. Validar el servicio

1. Confirmar que `docker compose ps` muestra el servicio en ejecución y saludable.
2. Probar desde el servidor `curl -I http://127.0.0.1:8080/up`.
3. Probar el dominio mediante HTTPS desde fuera del servidor.
4. Verificar inicio de sesión y flujos principales, escritura/lectura de la base de datos, carga de assets Vite, generación de documentos y persistencia de archivos si aplica.
5. Revisar logs de Laravel, Apache, Docker y Caddy sin exponer secretos.
6. Confirmar que cookies y URLs se generan con HTTPS y que no se filtran errores de depuración.

## Actualizaciones, respaldos y recuperación

- Para cada versión, construir una imagen nueva y etiquetarla con el commit o una versión inmutable; evitar depender únicamente de `latest`.
- Respaldar la base de datos y los volúmenes de `storage` antes de una actualización. Probar periódicamente la restauración.
- Aplicar migraciones de forma controlada y compatible con el despliegue. Mantener la imagen anterior disponible para poder volver a ella.
- Si una actualización falla, volver a la imagen previa, revisar si la migración requiere una reversión o corrección y restaurar el respaldo solo cuando corresponda.
- No eliminar volúmenes al retirar o actualizar contenedores.
- Definir una política de retención de respaldos y comprobar espacio en disco, logs y estado del servicio.

## Criterios de aceptación

- La imagen se construye en una máquina limpia sin depender de `.env`, `vendor` o `node_modules` del desarrollador.
- El contenedor publica HTTP únicamente en loopback del host y no contiene una base de datos ni Caddy.
- El sitio es accesible por HTTPS mediante Caddy; las solicitudes a la aplicación usan el host y esquema correctos.
- `/up` responde correctamente, las migraciones se ejecutan contra la base de datos elegida y los flujos principales funcionan.
- Los datos que deban sobrevivir a una recreación del contenedor están respaldados y persisten fuera de su sistema de archivos efímero.
- No se exponen secretos, `APP_DEBUG` está desactivado y el despliegue puede actualizarse y recuperarse con una versión anterior.
