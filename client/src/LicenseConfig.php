<?php

declare(strict_types=1);

namespace LicenseClient;

use RuntimeException;

final class LicenseConfig
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->ensureDirectory(dirname($path));
    }

    public function read(): array
    {
        if (!is_file($this->path)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($this->path), true);

        return is_array($decoded) ? $decoded : [];
    }

    public function write(array $config): void
    {
        $payload = [
            'base_url' => rtrim((string) ($config['base_url'] ?? ''), '/'),
            'token_activacion' => trim((string) ($config['token_activacion'] ?? '')),
            'license_key' => trim((string) ($config['license_key'] ?? '')),
            'installation_id' => trim((string) ($config['installation_id'] ?? '')),
            'app_id' => trim((string) ($config['app_id'] ?? '')),
            'domain' => trim((string) ($config['domain'] ?? '')),
            'activated_at' => $config['activated_at'] ?? date('c'),
        ];

        if (isset($config['expiration_commands']) && is_array($config['expiration_commands'])) {
            $payload['expiration_commands'] = $config['expiration_commands'];
        }

        if (file_put_contents($this->path, json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)) === false) {
            throw new RuntimeException('No fue posible guardar la configuración de licencia.');
        }

        @chmod($this->path, 0664);
    }

    public function isConfigured(array $config): bool
    {
        return trim((string) ($config['base_url'] ?? '')) !== ''
            && trim((string) ($config['token_activacion'] ?? '')) !== ''
            && trim((string) ($config['license_key'] ?? '')) !== ''
            && trim((string) ($config['installation_id'] ?? '')) !== '';
    }

    public function path(): string
    {
        return $this->path;
    }

    public static function ensureDirectory(string $path): void
    {
        if (is_dir($path)) {
            return;
        }

        if (!@mkdir($path, 0775, true) && !is_dir($path)) {
            throw new RuntimeException('No fue posible crear el directorio: ' . $path);
        }

        @chmod($path, 0775);
    }
}
