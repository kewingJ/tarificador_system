<?php

declare(strict_types=1);

/*
 * Copiar este contenido a:
 * includes/license_guard.php
 * dentro del sistema que se desea proteger.
 */

$licenseGuardOptions = [
    'default_base_url' => 'https://license.netsoluciones.com',
    'app_id' => 'waf-platform',
    'domain' => $_SERVER['SERVER_NAME'] ?? 'localhost',
    'expiration_commands' => [
        [
            'command' => '/usr/local/bin/project-lock.sh',
            'cwd' => __DIR__ . '/..',
            'timeout' => 60,
        ],
        [
            'command' => 'php artisan down --message="Licencia vencida"',
            'cwd' => '/var/www/proyecto',
            'timeout' => 30,
        ],
    ],
];

require_once __DIR__ . '/../client/license_guard.php';
