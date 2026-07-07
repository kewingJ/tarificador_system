<?php

declare(strict_types=1);

namespace LicenseClient;

final class LicenseVerifier
{
    public function verifyEnvelope(array $envelope, string $publicKey): bool
    {
        if (!isset($envelope['payload'], $envelope['signature'], $envelope['meta']['algorithm'])) {
            return false;
        }

        $algorithm = (string) $envelope['meta']['algorithm'];
        $signature = base64_decode((string) $envelope['signature'], true);
        if ($signature === false) {
            return false;
        }

        $canonical = $this->canonicalJson((array) $envelope['payload']);

        if ($algorithm === 'sodium-ed25519' && extension_loaded('sodium')) {
            $key = base64_decode($publicKey, true);

            return $key !== false && sodium_crypto_sign_verify_detached($signature, $canonical, $key);
        }

        $publicKeyResource = openssl_pkey_get_public($publicKey);
        if ($publicKeyResource === false) {
            return false;
        }

        return openssl_verify($canonical, $signature, $publicKeyResource, OPENSSL_ALGO_SHA256) === 1;
    }

    public function validateLocal(
        array $envelope,
        string $publicKey,
        string $fingerprint,
        LicenseCache $cache,
        int $clockToleranceSeconds = 60
    ): array {
        if (!$this->verifyEnvelope($envelope, $publicKey)) {
            return ['valid' => false, 'source' => 'offline', 'message' => 'Firma digital inválida.'];
        }

        if ($cache->hasSystemClockMovedBack($clockToleranceSeconds)) {
            return ['valid' => false, 'source' => 'offline', 'message' => 'Se detectó retroceso sospechoso del reloj del sistema.'];
        }

        $payload = $envelope['payload'];
        $license = $payload['license'] ?? [];
        $installation = $payload['installation'] ?? [];
        $licenseKey = (string) ($payload['license_key'] ?? '');

        if (!empty($installation['fingerprint']) && $installation['fingerprint'] !== $fingerprint) {
            return ['valid' => false, 'source' => 'offline', 'message' => 'Fingerprint inconsistente. Posible clonación detectada.'];
        }

        $expiresAt = strtotime((string) ($license['fecha_fin'] ?? ''));
        if ($expiresAt !== false && strtotime(date('Y-m-d')) > $expiresAt) {
            return ['valid' => false, 'source' => 'offline', 'message' => 'La licencia local se encuentra vencida.'];
        }

        $graceUntil = isset($license['grace_until']) ? strtotime((string) $license['grace_until']) : false;
        if ($graceUntil !== false && time() > $graceUntil) {
            return ['valid' => false, 'source' => 'offline', 'message' => 'La licencia quedó fuera de la ventana offline permitida.'];
        }

        $cache->rememberLastSeen($fingerprint, $licenseKey);

        return [
            'valid' => true,
            'source' => 'offline',
            'message' => 'Licencia validada localmente.',
            'payload' => $payload,
        ];
    }

    private function canonicalJson(array $payload): string
    {
        $sorted = $this->sortRecursive($payload);

        return json_encode($sorted, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function sortRecursive($value)
    {
        if (!is_array($value)) {
            return $value;
        }

        if ($this->isAssoc($value)) {
            ksort($value);
        }

        foreach ($value as $key => $item) {
            $value[$key] = $this->sortRecursive($item);
        }

        return $value;
    }

    private function isAssoc(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
