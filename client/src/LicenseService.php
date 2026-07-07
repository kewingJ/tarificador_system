<?php

declare(strict_types=1);

namespace LicenseClient;

use RuntimeException;

final class LicenseService
{
    private ApiClient $apiClient;
    private FingerprintService $fingerprintService;
    private LicenseVerifier $verifier;
    private LicenseCache $cache;
    private string $publicKey;
    private int $clockToleranceSeconds;

    public function __construct(array $config)
    {
        $this->apiClient = new ApiClient(
            rtrim((string) $config['base_url'], '/') . '/api',
            $config['api_key'] ?? null,
            (int) ($config['timeout'] ?? 10)
        );
        $this->fingerprintService = new FingerprintService();
        $this->verifier = new LicenseVerifier();
        $this->cache = new LicenseCache((string) $config['cache_dir']);
        $state = $this->cache->readState();
        $configuredPublicKey = trim((string) ($config['public_key'] ?? ''));
        $this->publicKey = $configuredPublicKey !== ''
            ? $configuredPublicKey
            : (string) ($state['public_key'] ?? '');
        $this->clockToleranceSeconds = (int) ($config['clock_tolerance_seconds'] ?? 60);
    }

    public function activate(string $token, string $installationId, array $context = []): array
    {
        $context['installation_id'] = $installationId;
        $fingerprint = $this->fingerprintService->generate($context);

        $response = $this->apiClient->post('/v1/activate', [
            'token_activacion' => $token,
            'installation_id' => $installationId,
            'hostname' => $context['hostname'] ?? gethostname(),
            'dominio' => $context['domain'] ?? ($_SERVER['SERVER_NAME'] ?? ''),
            'fingerprint' => $fingerprint,
        ]);

        $this->storeRemoteLicense($response, $fingerprint);

        return $response;
    }

    public function validate(string $licenseKey, string $installationId, array $context = [], bool $preferOnline = true): array
    {
        $context['installation_id'] = $installationId;
        $fingerprint = $this->fingerprintService->generate($context);

        if ($preferOnline) {
            try {
                $response = $this->apiClient->post('/v1/validate', [
                    'license_key' => $licenseKey,
                    'installation_id' => $installationId,
                    'hostname' => $context['hostname'] ?? gethostname(),
                    'dominio' => $context['domain'] ?? ($_SERVER['SERVER_NAME'] ?? ''),
                    'fingerprint' => $fingerprint,
                ]);

                $this->storeRemoteLicense($response, $fingerprint);

                return [
                    'valid' => true,
                    'source' => 'online',
                    'message' => 'Licencia validada contra servidor.',
                    'data' => $response,
                ];
            } catch (\Throwable $exception) {
                $localEnvelope = $this->cache->readLicenseEnvelope();
                if (!$localEnvelope) {
                    throw new RuntimeException('Validación online fallida y no existe licencia local disponible. ' . $exception->getMessage());
                }
            }
        }

        $localEnvelope = $this->cache->readLicenseEnvelope();
        if (!$localEnvelope) {
            throw new RuntimeException('No existe licencia local para validación offline.');
        }

        return $this->verifier->validateLocal(
            $localEnvelope,
            $this->publicKey,
            $fingerprint,
            $this->cache,
            $this->clockToleranceSeconds
        );
    }

    public function heartbeat(string $licenseKey, string $installationId, array $context = []): array
    {
        $context['installation_id'] = $installationId;
        $fingerprint = $this->fingerprintService->generate($context);

        try {
            $response = $this->apiClient->post('/v1/heartbeat', [
                'license_key' => $licenseKey,
                'installation_id' => $installationId,
                'hostname' => $context['hostname'] ?? gethostname(),
                'dominio' => $context['domain'] ?? ($_SERVER['SERVER_NAME'] ?? ''),
                'fingerprint' => $fingerprint,
            ]);

            $this->storeRemoteLicense($response, $fingerprint);

            return $response;
        } catch (\Throwable $exception) {
            return $this->validate($licenseKey, $installationId, $context, false);
        }
    }

    public function generateOfflineRequest(string $licenseKey, string $installationId, array $context = []): array
    {
        $context['installation_id'] = $installationId;
        $fingerprint = $this->fingerprintService->generate($context);
        $response = $this->apiClient->post('/v1/offline-request', [
            'license_key' => $licenseKey,
            'installation_id' => $installationId,
            'hostname' => $context['hostname'] ?? gethostname(),
            'dominio' => $context['domain'] ?? ($_SERVER['SERVER_NAME'] ?? ''),
            'fingerprint' => $fingerprint,
        ]);

        if (isset($response['activation_request'])) {
            $this->cache->saveActivationRequest($response['activation_request']);
        }

        return $response;
    }

    public function importOfflineLicense(string $filePath): array
    {
        $envelope = $this->cache->importOfflineLicenseFromFile($filePath);

        if (!$this->verifier->verifyEnvelope($envelope, $this->publicKey)) {
            throw new RuntimeException('La licencia offline importada no pasó la verificación de firma.');
        }

        return $envelope;
    }

    public function currentLicense(): ?array
    {
        return $this->cache->readLicenseEnvelope();
    }

    private function storeRemoteLicense(array $response, string $fingerprint): void
    {
        if (!isset($response['signed_license'])) {
            throw new RuntimeException('El servidor no devolvió una licencia firmada.');
        }

        if (!empty($response['public_key'])) {
            $this->publicKey = (string) $response['public_key'];
            $state = $this->cache->readState();
            $state['public_key'] = $this->publicKey;
            $this->cache->writeState($state);
        }

        $this->cache->writeLicenseEnvelope($response['signed_license']);
        $licenseKey = (string) ($response['license']['license_key'] ?? $response['signed_license']['payload']['license_key'] ?? '');
        $this->cache->rememberLastSeen($fingerprint, $licenseKey);
    }
}
