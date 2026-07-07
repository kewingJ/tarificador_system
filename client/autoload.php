<?php

declare(strict_types=1);

spl_autoload_register(function (string $className): void {
    $prefix = 'LicenseClient\\';

    if (strpos($className, $prefix) !== 0) {
        return;
    }

    $relative = str_replace('\\', '/', substr($className, strlen($prefix)));
    $file = __DIR__ . '/src/' . $relative . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});
