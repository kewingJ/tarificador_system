<?php

declare(strict_types=1);

namespace LicenseClient;

use RuntimeException;

final class ApiClient
{
    private string $baseUrl;
    private ?string $apiKey;
    private int $timeout;

    public function __construct(string $baseUrl, ?string $apiKey = null, int $timeout = 10)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->timeout = $timeout;
    }

    public function post(string $path, array $payload = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        $ch = curl_init($url);
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        if ($this->apiKey) {
            $headers[] = 'X-API-KEY: ' . $this->apiKey;
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ]);

        $response = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false || $error !== '') {
            throw new RuntimeException('No fue posible conectar con el servidor de licencias: ' . $error);
        }

        $decoded = json_decode($response, true);
        if (!is_array($decoded)) {
            throw new RuntimeException('Respuesta inválida del servidor de licencias.');
        }

        if ($httpCode >= 400 || !($decoded['success'] ?? false)) {
            $message = $decoded['message'] ?? 'El servidor devolvió un error.';
            if ($message === 'Resource not found.') {
                $message .= ' Endpoint consultado: ' . $url;
            }

            throw new RuntimeException($message);
        }

        return $decoded['data'] ?? [];
    }
}
