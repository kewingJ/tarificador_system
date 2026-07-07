<?php

declare(strict_types=1);

require dirname(__DIR__) . '/autoload.php';

use LicenseClient\LicenseService;

$licenseService = new LicenseService([
    'base_url' => 'http://localhost/license',
    'api_key' => 'local-dev-license-key-change-me',
    'cache_dir' => __DIR__ . '/cache',
    'public_key' => '',
    'clock_tolerance_seconds' => 60,
]);

$installationId = 'LEGACY-SYSTEM-01';

try {
    $activation = $licenseService->activate('TOK-WAF-TECH-772', $installationId, [
        'hostname' => gethostname(),
        'domain' => 'legacy-app.local',
        'app_id' => 'legacy-waf-platform',
    ]);

    echo "Activación completada\n";
    print_r($activation);

    $validation = $licenseService->validate('LIC-WAF-772-TECH', $installationId, [
        'hostname' => gethostname(),
        'domain' => 'legacy-app.local',
        'app_id' => 'legacy-waf-platform',
    ]);

    echo "Resultado de validación\n";
    print_r($validation);
} catch (Throwable $exception) {
    echo 'Error: ' . $exception->getMessage() . PHP_EOL;
}
