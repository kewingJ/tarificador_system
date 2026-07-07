<?php

declare(strict_types=1);

namespace LicenseClient;

final class FingerprintService
{
    public function generate(array $context = []): string
    {
        $fingerprintData = [
            'hostname' => $context['hostname'] ?? gethostname(),
            'domain' => $context['domain'] ?? ($_SERVER['SERVER_NAME'] ?? php_uname('n')),
            'os' => php_uname(),
            'php' => PHP_VERSION,
            'app_id' => $context['app_id'] ?? ($_SERVER['DOCUMENT_ROOT'] ?? __DIR__),
            'installation_id' => $context['installation_id'] ?? '',
        ];

        return hash('sha256', json_encode($fingerprintData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
