# Tarificador / Smart Voice Server

## Que es este proyecto

Este sistema es un panel web en PHP para revisar llamadas, extensiones, directorio telefonico, ataques bloqueados y algunos datos de licencia. Tambien permite generar archivos XML para telefonos IP y administrar extensiones que terminan en Asterisk.

La entrada principal para el usuario es:

- `index.php` para login
- `home.php` como panel principal

## Para que sirve en palabras simples

Con este proyecto se puede:

- ver llamadas y reportes
- mantener una tabla espejo de llamadas para trabajar mas rapido
- revisar extensiones y su estado
- registrar ataques bloqueados desde logs
- administrar directorio telefonico
- generar XML para equipos Grandstream, Cisco y Snom
- crear, editar y eliminar extensiones desde `endpoints/tel_endpoints.conf`

## Archivo principal de configuracion

La configuracion mas importante esta en `includes/config.php`.

Desde ahi se controla:

- conexion a `base de datos`
- conexion a `asteriskcel`
- datos del acceso ARI
- bandera para mostrar audios
- estado de licencia
- estado general del sistema

Campos que normalmente se revisan primero:

- host, usuario, clave y nombre de base de datos
- usuario y password de ARI
- IP del servidor ARI
- `activar_licencia`
- `activar_sistema`

## Archivos y carpetas que conviene conocer

- `includes/config.php`: configuracion general
- `home.php`: panel principal
- `controller/`: procesos del sistema
- `ajax_table/`: tablas que se cargan por AJAX
- `endpoints/tel_endpoints.conf`: archivo local de extensiones
- `controller/hints.json`: archivo generado con el estado de extensiones
- `gs_phonebook.xml`: directorio XML general
- `gs_phonebookCisco.xml`: directorio XML para Cisco
- `gs_phonebookSnomIp.xml`: directorio XML para Snom
- `licencias.txt`: historial simple de licencias activadas
- `fail2ban.log` y `sipban.log`: logs que usa el modulo de ataques

## Configuracion operativa que no se debe olvidar

### 1. ARI de Asterisk

El listado de extensiones usa ARI. Si ARI no responde, la pantalla de extensiones no mostrara la informacion esperada.

Se revisa en:

- `includes/config.php`
- `controller/ajax_lista_extensiones.php`

### 2. Archivo de extensiones

Las extensiones se guardan en:

- `endpoints/tel_endpoints.conf`

### 3. Permisos de escritura

El sistema necesita poder escribir en estos archivos:

- `controller/hints.json`
- `endpoints/tel_endpoints.conf`
- `gs_phonebook.xml`
- `gs_phonebookCisco.xml`
- `gs_phonebookSnomIp.xml`
- `licencias.txt`

Si no hay permisos, algunas acciones desde el panel van a fallar aunque la pantalla parezca normal.

## AJAX que SI conviene usar con cron

No todos los AJAX del proyecto son para cron. La mayoria son solo para tablas, botones o modales del panel.

Los que si vale la pena programar como tareas automaticas son estos:

| Archivo | Para que sirve | Frecuencia sugerida | Comentario |
|---|---|---:|---|
| `controller/ajax_cdr_espejo.php` | sincroniza la tabla `cdr_espejo` y actualiza estadisticas | cada 5 o 10 min | recomendado para mantener reportes al dia |
| `controller/ajax_lista_estado_extension.php` | genera `controller/hints.json` con el estado de las extensiones | cada 1 o 2 min | recomendado para que el estado de extensiones se vea actualizado |
| `controller/ajax_ataque_fail.php` | lee `fail2ban.log` y guarda bloqueos recientes | cada 20 min | no conviene correrlo mas seguido porque puede repetir registros |
| `controller/ajax_ataque_sip.php` | lee `sipban.log` y guarda bloqueos recientes | cada 40 min | no conviene correrlo mas seguido porque puede repetir registros |

## AJAX que NO hace falta mandar por cron

Estos se usan desde la interfaz y no hace falta programarlos como tarea automatica:

- `controller/ajax_lista_extensiones.php`
- `controller/ajax_get_extension.php`
- `controller/ajax_update_extension.php`
- `controller/ajax_crear_extensiones_nuevo.php`
- `controller/ajax_eliminar_extensiones_nuevo.php`
- los archivos dentro de `ajax_table/`

En especial:

- `controller/ajax_lista_extensiones.php` consume ARI y tambien lee `controller/hints.json`
- por eso lo ideal es mantener actualizado `hints.json` con cron usando `controller/ajax_lista_estado_extension.php`

## Licencia

La licencia se guarda de forma simple en:

- `licencias.txt`

Y se consulta desde:

- `controller/license_status.php`

Cuando se registra una licencia, tambien se intenta dejar activo el sistema desde `includes/config.php`.

## Recomendaciones practicas

- revisar primero `includes/config.php` al mover el proyecto a otro servidor
- confirmar que `db_cdra` y `asteriskcel` existan y respondan
- validar que ARI este accesible
- validar permisos de escritura en archivos generados
- revisar las rutas fijas hacia Asterisk antes de usar el modulo de extensiones
- programar cron solo para los AJAX que realmente son de mantenimiento

## Si algo no actualiza

Puntos rapidos para revisar:

- credenciales de base de datos
- credenciales ARI
- permisos de archivos
- existencia de `fail2ban.log` y `sipban.log`
- existencia de `endpoints/tel_endpoints.conf`
- rutas fijas `/var/www/ucs` y `/etc/asterisk/pjsip.d`
- servicio Asterisk activo

## Resumen corto

Si alguien va a operar este proyecto por primera vez, normalmente debe revisar solo esto:

1. `includes/config.php`
2. importar `db_cdra.sql`
3. confirmar la base `asteriskcel`
4. validar permisos de escritura
5. configurar los cron de espejo CDR, estado de extensiones y ataques

