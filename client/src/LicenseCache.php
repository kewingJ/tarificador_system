<?php

declare(strict_types=1);

namespace LicenseClient;

final class LicenseCache
{
    private string $cacheDir;

    public function __construct(string $cacheDir)
    {
        $this->cacheDir = rtrim($cacheDir, '/');

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0775, true);
        }
    }

    public function readLicenseEnvelope(): ?array
    {
        $path = $this->licensePath();
        if (!is_file($path)) {
            return null;
        }

        $content = file_get_contents($path);
        $decoded = json_decode((string) $content, true);

        return is_array($decoded) ? $decoded : null;
    }

    public function writeLicenseEnvelope(array $envelope): void
    {
        file_put_contents($this->licensePath(), json_encode($envelope, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    public function readState(): array
    {
        $path = $this->statePath();
        if (!is_file($path)) {
            return [];
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        return is_array($decoded) ? $decoded : [];
    }

    public function writeState(array $state): void
    {
        file_put_contents($this->statePath(), json_encode($state, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    public function rememberLastSeen(string $fingerprint, string $licenseKey): void
    {
        $state = $this->readState();
        $state['last_seen_at'] = time();
        $state['last_fingerprint'] = $fingerprint;
        $state['last_license_key'] = $licenseKey;
        $this->writeState($state);
    }

    public function hasSystemClockMovedBack(int $toleranceSeconds = 60): bool
    {
        $state = $this->readState();

        if (!isset($state['last_seen_at'])) {
            return false;
        }

        return time() + $toleranceSeconds < (int) $state['last_seen_at'];
    }

    public function saveActivationRequest(array $payload): void
    {
        file_put_contents($this->cacheDir . '/activation_request.json', json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }

    public function importOfflineLicenseFromFile(string $filePath): array
    {
        $content = json_decode((string) file_get_contents($filePath), true);
        if (!is_array($content)) {
            throw new \RuntimeException('El archivo license.lic no contiene JSON válido.');
        }

        $this->writeLicenseEnvelope($content);

        return $content;
    }

    public function licensePath(): string
    {
        return $this->cacheDir . '/license.lic';
    }

    public function statePath(): string
    {
        return $this->cacheDir . '/license_state.json';
    }
}
