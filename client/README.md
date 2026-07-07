# Keynet PHP Client

Carpeta reusable para proteger sistemas PHP existentes con licencias Keynet.

## 1. Copiar carpeta

Copiar `client/` dentro del sistema que se desea proteger:

```text
mi-sistema/
├── client/
├── includes/
└── index.php
```

## 2. Crear guard en el sistema (Archivo Oculto)

Para mayor seguridad y evitar que el archivo sea visible fácilmente, crea un archivo oculto llamado `.license_guard.php` dentro de tu carpeta `includes/` (`includes/.license_guard.php`):

```php
<?php

declare(strict_types=1);

$clientPath = __DIR__ . '/../client';

// Verificación: si la carpeta client no existe, el proyecto asume que no requiere licencia.
if (!is_dir($clientPath)) {
    return;
}

$licenseGuardOptions = [
    'app_id' => 'waf-platform', // Identificador de tu sistema (waf-platform, nsm-sentinel, etc.)
    'expiration_commands' => [
        'mkdir -p mi_carpeta',
        'touch mi_carpeta/index.php',
        '/usr/bin/systemctl reload nginx'
    ]
];

require_once $clientPath . '/license_guard.php';
```

## 3. Integrarlo en tu Login (index.php)

Luego, cárgalo antes del login (por ejemplo, en tu `index.php`). Esta es la forma recomendada y **segura** de llamarlo. La validación comprobará que exista el archivo guardado y que la carpeta `client/` esté disponible. Así, si alguna vez eliminas o no subes la carpeta `client/`, tu sistema seguirá funcionando sin detenerse con un error fatal:

```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

// 1. Cargar la protección (el archivo oculto comprueba la existencia de /client internamente)
if (file_exists(__DIR__ . '/includes/.license_guard.php')) {
    require_once __DIR__ . '/includes/.license_guard.php';
}

// 2. Cargar los helpers visuales y mostrar el banner si la carpeta client existe
if (is_dir(__DIR__ . '/client') && file_exists(__DIR__ . '/client/license_helpers.php')) {
    require_once __DIR__ . '/client/license_helpers.php';
    if (function_exists('license_client_render_banner')) {
        license_client_render_banner();
    }
}
?>
```

Si no existe licencia activada, el cliente muestra una pantalla de activación y detiene el acceso.

## 4. Comandos al vencer la licencia

Como se muestra en el archivo `.license_guard.php`, puedes declarar los comandos que desees que el servidor local ejecute en el array `expiration_commands`. Estos comandos se corren de manera secuencial.
En tu caso de uso:
```php
    'expiration_commands' => [
        'mkdir -p mi_carpeta',
        'touch mi_carpeta/index.php',
        '/usr/bin/systemctl reload nginx'
    ],
];
```

También se pueden guardar en `license_config.json` usando la clave `expiration_commands`.

Por defecto los comandos se ejecutan una sola vez por combinación de licencia, fecha de vencimiento y lista de comandos. Si cambia la lista de comandos, se vuelven a ejecutar. El resultado queda registrado en:

```text
includes/license-cache/cache/expiration_commands.log
includes/license-cache/cache/license_state.json
```

Opciones disponibles:

- `expiration_commands`: lista de comandos. Cada item puede ser texto o un arreglo con `command`, `cwd`, `timeout`, `enabled`.
- `expiration_commands_once`: `true` por defecto para evitar ejecución repetida en cada request.
- `expiration_commands_timeout`: timeout global en segundos, `60` por defecto.
- `expiration_commands_cwd`: directorio de trabajo global, por defecto la raíz del proyecto protegido.
- `expiration_commands_log`: ruta del archivo log.

## 4. Datos solicitados en instalación

- Servidor de licencias: `https://license.netsoluciones.com`
- Token de activación
- ID de licencia
- ID de instalación

Al activar correctamente, se crean automáticamente:

```text
client/storage/license_config.json
client/storage/cache/license.lic
client/storage/cache/license_state.json
```

## 5. Modal de licencia

En el layout del sistema, incluir:

```php
require_once __DIR__ . '/client/license_helpers.php';
```

En el menú:

```html
<a href="#modalLicense" data-toggle="modal">Licencia</a>
```

Antes de cerrar `body`:

```php
license_client_render_modal();
```

Opcionalmente, para mostrar mensajes en el login:

```php
license_client_render_banner();
```

## 6. Permisos

El cliente intenta crear `client/storage` y `client/storage/cache` automáticamente con permisos `0775`.

Si el servidor no permite crear carpetas desde PHP, crear manualmente:

```bash
mkdir -p client/storage/cache
chmod -R 775 client/storage
```
