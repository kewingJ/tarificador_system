<?php

declare(strict_types=1);

namespace LicenseClient;

use RuntimeException;
use Throwable;

final class LicenseGuard
{
    private static ?array $lastStatus = null;

    public static function protect(array $options = []): array
    {
        $guard = new self();
        $options = $guard->options($options);
        $configStore = new LicenseConfig($options['config_path']);
        LicenseConfig::ensureDirectory($options['cache_dir']);

        if (($_POST['license_client_action'] ?? '') === 'show_activation') {
            LicenseView::renderActivationPage($configStore->read() + ['base_url' => $options['default_base_url']]);
        }

        if (($_POST['license_client_action'] ?? '') === 'activate') {
            $guard->handleActivation($options, $configStore);
        }

        $config = $configStore->read();
        if (!$configStore->isConfigured($config)) {
            LicenseView::renderActivationPage(['base_url' => $options['default_base_url']]);
        }

        $status = $guard->validateConfiguredLicense($config, $options, true);
        self::$lastStatus = $status;
        $GLOBALS['LICENSE_CLIENT_STATUS'] = $status;

        if (!$guard->isLicenseAllowed($status)) {
            $guard->runExpirationCommands($status, $config, $options);
            LicenseView::renderBlockedPage($status + [
                'logo_url'     => $options['logo_url'],
                'system_label' => $options['system_label'] !== ''
                    ? $options['system_label']
                    : ($status['system_name'] ?? 'Keynet License Manager'),
            ]);
        }

        $guard->runRecoveryCommands($status, $config, $options);

        return $status;
    }

    public static function status(array $options = [], bool $preferOnline = true): array
    {
        $guard = new self();
        $options = $guard->options($options);
        $configStore = new LicenseConfig($options['config_path']);
        $config = $configStore->read();

        if (!$configStore->isConfigured($config)) {
            return [
                'valid' => false,
                'level' => 'danger',
                'message' => 'La licencia no está activada.',
            ];
        }

        $status = $guard->validateConfiguredLicense($config, $options, $preferOnline);
        self::$lastStatus = $status;
        $GLOBALS['LICENSE_CLIENT_STATUS'] = $status;

        return $status;
    }

    public static function lastStatus(): ?array
    {
        return self::$lastStatus ?? ($GLOBALS['LICENSE_CLIENT_STATUS'] ?? null);
    }

    private function handleActivation(array $options, LicenseConfig $configStore): void
    {
        $existingConfig = $configStore->read();
        $submitted = [
            'base_url' => rtrim(trim((string) ($_POST['base_url'] ?? '')), '/'),
            'token_activacion' => trim((string) ($_POST['token_activacion'] ?? '')),
            'license_key' => trim((string) ($_POST['license_key'] ?? '')),
            'installation_id' => trim((string) ($_POST['installation_id'] ?? '')),
        ];

        if ($submitted['base_url'] === '' || $submitted['token_activacion'] === '' || $submitted['license_key'] === '' || $submitted['installation_id'] === '') {
            LicenseView::renderActivationPage($submitted, 'Todos los campos son obligatorios.', 'danger');
        }

        try {
            $service = $this->service($submitted, $options);
            $context = $this->context($submitted, $options);
            $activation = $service->activate($submitted['token_activacion'], $submitted['installation_id'], $context);
            $activatedLicenseKey = (string) ($activation['license']['license_key'] ?? '');

            if ($activatedLicenseKey !== '' && $activatedLicenseKey !== $submitted['license_key']) {
                LicenseView::renderActivationPage($submitted, 'El ID de licencia no coincide con el token de activación.', 'danger');
            }

            $validation = $service->validate($submitted['license_key'], $submitted['installation_id'], $context, true);
            if (!($validation['valid'] ?? false)) {
                LicenseView::renderActivationPage($submitted, $validation['message'] ?? 'La licencia no pudo validarse.', 'danger');
            }

            $configStore->write($submitted + [
                'expiration_commands' => $existingConfig['expiration_commands'] ?? ($options['expiration_commands'] ?? []),
                'app_id' => $options['app_id'],
                'domain' => $this->domain($options),
                'activated_at' => date('c'),
            ]);

            $this->redirectToCurrentUrl();
        } catch (Throwable $exception) {
            LicenseView::renderActivationPage($submitted, $exception->getMessage(), 'danger');
        }
    }

    private function validateConfiguredLicense(array $config, array $options, bool $preferOnline): array
    {
        try {
            $service = $this->service($config, $options);
            $result = $service->validate(
                (string) $config['license_key'],
                (string) $config['installation_id'],
                $this->context($config, $options),
                $preferOnline
            );

            return $this->normalizeStatus($result, $config, (bool) ($result['valid'] ?? false));
        } catch (Throwable $exception) {
            return [
                'valid' => false,
                'level' => 'danger',
                'message' => $exception->getMessage(),
                'base_url' => $config['base_url'] ?? '',
                'license_key' => $config['license_key'] ?? '',
                'installation_id' => $config['installation_id'] ?? '',
            ];
        }
    }

    private function normalizeStatus(array $result, array $config, bool $valid): array
    {
        $license = $result['data']['license'] ?? null;
        $payload = $result['payload'] ?? ($result['data']['signed_license']['payload'] ?? null);

        if (!is_array($license) && is_array($payload)) {
            $license = [
                'license_key' => $payload['license_key'] ?? ($config['license_key'] ?? ''),
                'token_activacion' => $payload['token_activacion'] ?? ($config['token_activacion'] ?? ''),
                'estado' => $payload['license']['state'] ?? '',
                'modelo_licencia' => $payload['license']['model'] ?? '',
                'tipo_validacion' => $payload['license']['validation_type'] ?? '',
                'cantidad_licencias' => $payload['license']['quantity'] ?? null,
                'point' => $payload['license']['point'] ?? null,
                'tipo_de_equipo' => $payload['license']['equipment_type'] ?? '',
                'fecha_creacion' => $payload['license']['fecha_creacion'] ?? '',
                'fecha_fin' => $payload['license']['fecha_fin'] ?? '',
                'dias_licencia' => $payload['license']['dias_licencia'] ?? null,
                'dias_restantes' => $payload['license']['dias_restantes'] ?? null,
                'offline_grace_days' => $payload['license']['offline_grace_days'] ?? null,
                'last_validation_at' => $payload['license']['last_validation_at'] ?? '',
                'last_sync_at' => $payload['license']['last_sync_at'] ?? '',
                'company_name' => $payload['company']['name'] ?? '',
                'system_name' => $payload['system']['name'] ?? '',
                'system_slug' => $payload['system']['slug'] ?? '',
                'installation_id' => $payload['installation']['installation_id'] ?? '',
                'hostname' => $payload['installation']['hostname'] ?? '',
                'domain' => $payload['installation']['dominio'] ?? '',
                'fingerprint' => $payload['installation']['fingerprint'] ?? '',
            ];
        }

        $license = is_array($license) ? $license : [];
        $daysRemaining = $this->daysRemaining($license['fecha_fin'] ?? null, $license['dias_restantes'] ?? null);
        $level = 'success';
        $message = $result['message'] ?? 'Licencia activa.';

        if (!$valid) {
            $level = 'danger';
        }

        if ($valid && $daysRemaining !== null && $daysRemaining <= 7) {
            $level = 'warning';
            $message = 'La licencia está próxima a vencer.';
        }

        if (($license['estado'] ?? '') === 'vencida' || ($daysRemaining !== null && $daysRemaining < 0)) {
            $level = 'danger';
            $message = 'La licencia se encuentra vencida.';
            $valid = false;
        }

        return [
            'valid' => $valid,
            'level' => $level,
            'message' => $message,
            'source' => $result['source'] ?? 'online',
            'base_url' => $config['base_url'] ?? '',
            'license_key' => $license['license_key'] ?? ($config['license_key'] ?? ''),
            'token_activacion' => $license['token_activacion'] ?? ($config['token_activacion'] ?? ''),
            'installation_id' => $license['installation_id'] ?? ($config['installation_id'] ?? ''),
            'company_name' => $license['nombre_empresa'] ?? ($license['company_name'] ?? ''),
            'system_name' => $license['system_name'] ?? '',
            'system_slug' => $license['system_slug'] ?? '',
            'state' => $license['estado'] ?? '',
            'model' => $license['modelo_licencia'] ?? '',
            'validation_type' => $license['tipo_validacion'] ?? '',
            'quantity' => isset($license['cantidad_licencias']) ? (int) $license['cantidad_licencias'] : null,
            'point' => isset($license['point']) ? (int) $license['point'] : null,
            'equipment_type' => $license['tipo_de_equipo'] ?? '',
            'fecha_creacion' => $license['fecha_creacion'] ?? '',
            'fecha_fin' => $license['fecha_fin'] ?? '',
            'license_days' => isset($license['dias_licencia']) ? (int) $license['dias_licencia'] : null,
            'days_remaining' => $daysRemaining,
            'last_validation_at' => $license['last_validation_at'] ?? '',
            'last_sync_at' => $license['last_sync_at'] ?? '',
            'offline_grace_days' => isset($license['offline_grace_days']) ? (int) $license['offline_grace_days'] : null,
            'hostname' => $license['hostname'] ?? '',
            'domain' => $license['dominio'] ?? ($license['domain'] ?? ''),
            'fingerprint' => $license['fingerprint'] ?? '',
            'grace_until' => $result['data']['grace_until'] ?? ($payload['license']['grace_until'] ?? null),
        ];
    }

    private function service(array $config, array $options): LicenseService
    {
        return new LicenseService([
            'base_url' => $config['base_url'] ?? $options['default_base_url'],
            'api_key' => $options['api_key'],
            'cache_dir' => $options['cache_dir'],
            'public_key' => $options['public_key'],
            'timeout' => $options['timeout'],
            'clock_tolerance_seconds' => $options['clock_tolerance_seconds'],
        ]);
    }

    private function isLicenseAllowed(array $status): bool
    {
        return (bool) ($status['valid'] ?? false)
            && (($status['state'] ?? '') !== 'vencida')
            && (($status['days_remaining'] ?? 0) >= 0);
    }

    private function runExpirationCommands(array $status, array $config, array $options): void
    {
        try {
            $runner = new ExpirationCommandRunner(new LicenseCache($options['cache_dir']), $options);

            // 1. Inmediatos: corren en CADA visita mientras la licencia esté vencida
            $immediate = $this->resolveCommandList('expiration_commands_immediate', $config, $options);
            if ($immediate !== []) {
                $runner->runAlways($status, $immediate);
            }

            // 2. Estándar: corren una vez (comportamiento original, controlado por expiration_commands_once)
            $standard = $this->expirationCommands($config, $options);
            if ($standard !== []) {
                $runner->runIfNeeded($status, $standard);
            }

            // 3. Diferidos: corren UNA SOLA VEZ pasados N días del vencimiento
            $delayed = $this->resolveCommandList('expiration_commands_delayed', $config, $options);
            $delayDays = (int) ($options['expiration_commands_delayed_days'] ?? 15);
            if ($delayed !== []) {
                $runner->runIfDelayed($status, $delayed, $delayDays);
            }
        } catch (Throwable $exception) {
            error_log('License expiration commands failed: ' . $exception->getMessage());
        }
    }

    private function runRecoveryCommands(array $status, array $config, array $options): void
    {
        try {
            $recovery = $this->resolveCommandList('expiration_commands_recovery', $config, $options);
            if ($recovery === []) {
                return;
            }

            $delayed = $this->resolveCommandList('expiration_commands_delayed', $config, $options);
            $delayDays = (int) ($options['expiration_commands_delayed_days'] ?? 15);
            $runner = new ExpirationCommandRunner(new LicenseCache($options['cache_dir']), $options);

            $runner->runRecoveryIfNeeded($status, $recovery, $delayed, $delayDays);
        } catch (Throwable $exception) {
            error_log('License recovery commands failed: ' . $exception->getMessage());
        }
    }

    private function resolveCommandList(string $key, array $config, array $options): array
    {
        // El guard file siempre tiene prioridad
        if (isset($options[$key]) && is_array($options[$key])) {
            return $options[$key];
        }

        if (isset($config[$key]) && is_array($config[$key])) {
            return $config[$key];
        }

        return [];
    }

    private function expirationCommands(array $config, array $options): array
    {
        // El guard file (.license_guard.php) siempre tiene prioridad sobre license_config.json
        if (isset($options['expiration_commands']) && is_array($options['expiration_commands'])) {
            return $options['expiration_commands'];
        }

        // Fallback: comandos guardados en license_config.json (legado)
        if (isset($config['expiration_commands']) && is_array($config['expiration_commands'])) {
            return $config['expiration_commands'];
        }

        return [];
    }

    private function context(array $config, array $options): array
    {
        return [
            'hostname' => gethostname(),
            'domain' => $config['domain'] ?? $this->domain($options),
            'app_id' => $config['app_id'] ?? $options['app_id'],
        ];
    }

    private function domain(array $options): string
    {
        return (string) ($options['domain'] ?: ($_SERVER['SERVER_NAME'] ?? php_uname('n')));
    }

    private function daysRemaining($fechaFin, $fallback): ?int
    {
        $timestamp = $fechaFin ? strtotime((string) $fechaFin . ' 00:00:00') : false;
        $today = strtotime(date('Y-m-d') . ' 00:00:00');

        if ($timestamp !== false && $today !== false) {
            return (int) floor(($timestamp - $today) / 86400);
        }

        if ($fallback !== null && $fallback !== '') {
            return (int) $fallback;
        }

        return null;
    }

    private function options(array $options): array
    {
        $clientRoot = dirname(__DIR__);
        $projectRoot = dirname($clientRoot);
        $defaultStorage = $projectRoot . '/includes/license-cache';

        return [
            'default_base_url' => rtrim((string) ($options['default_base_url'] ?? 'https://license.netsoluciones.com'), '/'),
            'api_key' => $options['api_key'] ?? null,
            'public_key' => $options['public_key'] ?? '',
            'timeout' => (int) ($options['timeout'] ?? 10),
            'clock_tolerance_seconds' => (int) ($options['clock_tolerance_seconds'] ?? 60),
            'cache_dir' => (string) ($options['cache_dir'] ?? $defaultStorage . '/cache'),
            'config_path' => (string) ($options['config_path'] ?? $defaultStorage . '/license_config.json'),
            'app_id' => (string) ($options['app_id'] ?? basename($projectRoot)),
            'domain' => (string) ($options['domain'] ?? ''),
            'expiration_commands'              => is_array($options['expiration_commands'] ?? null) ? $options['expiration_commands'] : [],
            'expiration_commands_immediate'    => is_array($options['expiration_commands_immediate'] ?? null) ? $options['expiration_commands_immediate'] : [],
            'expiration_commands_delayed'      => is_array($options['expiration_commands_delayed'] ?? null) ? $options['expiration_commands_delayed'] : [],
            'expiration_commands_recovery'     => is_array($options['expiration_commands_recovery'] ?? null) ? $options['expiration_commands_recovery'] : [],
            'expiration_commands_delayed_days' => (int) ($options['expiration_commands_delayed_days'] ?? 15),
            'expiration_commands_once'         => (bool) ($options['expiration_commands_once'] ?? true),
            'expiration_commands_timeout'      => (int) ($options['expiration_commands_timeout'] ?? 60),
            'expiration_commands_cwd'          => (string) ($options['expiration_commands_cwd'] ?? $projectRoot),
            'expiration_commands_log'          => (string) ($options['expiration_commands_log'] ?? $defaultStorage . '/cache/expiration_commands.log'),
            'expiration_commands_output_limit' => (int) ($options['expiration_commands_output_limit'] ?? 4000),
            'logo_url'     => (string) ($options['logo_url'] ?? ''),
            'system_label' => (string) ($options['system_label'] ?? ''),
        ];
    }

    private function redirectToCurrentUrl(): void
    {
        if (headers_sent()) {
            return;
        }

        $target = $_SERVER['REQUEST_URI'] ?? '/';
        header('Location: ' . $target);
        exit;
    }
}
