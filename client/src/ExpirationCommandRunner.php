<?php

declare(strict_types=1);

namespace LicenseClient;

final class ExpirationCommandRunner
{
    private LicenseCache $cache;
    private array $options;
    private string $logPath;

    public function __construct(LicenseCache $cache, array $options)
    {
        $this->cache = $cache;
        $this->options = $options;
        $this->logPath = (string) ($options['expiration_commands_log'] ?? dirname($cache->statePath()) . '/expiration_commands.log');
    }

    public function runIfNeeded(array $status, array $commands): void
    {
        $commands = $this->normalizeCommands($commands);
        if ($commands === []) {
            return;
        }

        $state = $this->cache->readState();
        $eventKey = $this->eventKey($status, $commands);
        $once = (bool) ($this->options['expiration_commands_once'] ?? true);

        if ($once && (($state['expiration_commands']['last_event_key'] ?? '') === $eventKey)) {
            return;
        }

        $this->appendLog('Starting expiration commands for event ' . $eventKey);
        $results = [];

        foreach ($commands as $index => $command) {
            $results[] = $this->runCommand($command, $index + 1);
        }

        $state = $this->cache->readState();
        $state['expiration_commands'] = [
            'last_event_key' => $eventKey,
            'last_run_at' => date('c'),
            'license_key' => $status['license_key'] ?? '',
            'installation_id' => $status['installation_id'] ?? '',
            'results' => $results,
        ];
        $this->cache->writeState($state);
    }

    /**
     * Ejecuta comandos en CADA visita mientras la licencia esté vencida.
     * Siempre corre, pero guarda el último evento para poder ejecutar recovery.
     */
    public function runAlways(array $status, array $commands): void
    {
        $commands = $this->normalizeCommands($commands);
        if ($commands === []) {
            return;
        }

        $this->appendLog('Starting immediate expiration commands (every visit)');
        $results = [];

        foreach ($commands as $index => $command) {
            $results[] = $this->runCommand($command, $index + 1);
        }

        $state = $this->cache->readState();
        $state['expiration_commands_immediate'] = [
            'last_event_key' => $this->eventKey($status, $commands),
            'last_run_at' => date('c'),
            'license_key' => $status['license_key'] ?? '',
            'installation_id' => $status['installation_id'] ?? '',
            'results' => $results,
        ];
        $this->cache->writeState($state);
    }

    /**
     * Ejecuta comandos UNA SOLA VEZ cuando han pasado $delayDays días desde el vencimiento.
     * Usa clave de caché propia para no interferir con los otros conjuntos.
     */
    public function runIfDelayed(array $status, array $commands, int $delayDays = 15): void
    {
        $commands = $this->normalizeCommands($commands);
        if ($commands === []) {
            return;
        }

        $daysRemaining = (int) ($status['days_remaining'] ?? 0);
        if ($daysRemaining > -$delayDays) {
            return; // Aún no han pasado los días requeridos
        }

        $state = $this->cache->readState();
        $eventKey = $this->eventKeyDelayed($status, $commands, $delayDays);

        if (($state['expiration_commands_delayed']['last_event_key'] ?? '') === $eventKey) {
            return; // Ya se ejecutaron para esta combinación
        }

        $this->appendLog(sprintf(
            'Starting delayed expiration commands (%d days past expiration)',
            $delayDays
        ));
        $results = [];

        foreach ($commands as $index => $command) {
            $results[] = $this->runCommand($command, $index + 1);
        }

        $state = $this->cache->readState();
        $state['expiration_commands_delayed'] = [
            'last_event_key' => $eventKey,
            'last_run_at'    => date('c'),
            'license_key'    => $status['license_key'] ?? '',
            'delay_days'     => $delayDays,
            'results'        => $results,
        ];
        $this->cache->writeState($state);
    }

    /**
     * Ejecuta comandos de recuperación UNA SOLA VEZ cuando la licencia vuelve
     * a estar válida después de haber corrido comandos de expiración, siempre
     * que los comandos diferidos/destructivos no se hayan ejecutado todavía.
     */
    public function runRecoveryIfNeeded(array $status, array $commands, array $delayedCommands = [], int $delayDays = 15): void
    {
        $commands = $this->normalizeCommands($commands);
        if ($commands === []) {
            return;
        }

        $state = $this->cache->readState();
        $expiration = $this->lastExpirationState($state);
        if ($expiration === []) {
            return;
        }

        if ($this->delayedAlreadyRan($state, $expiration, $status, $delayedCommands, $delayDays)) {
            return;
        }

        $eventKey = $this->recoveryEventKey($status, $commands, (string) ($expiration['last_event_key'] ?? ''));
        if (($state['expiration_commands_recovery']['last_event_key'] ?? '') === $eventKey) {
            return;
        }

        $this->appendLog('Starting recovery commands for event ' . $eventKey);
        $results = [];

        foreach ($commands as $index => $command) {
            $results[] = $this->runCommand($command, $index + 1);
        }

        $state = $this->cache->readState();
        $state['expiration_commands_recovery'] = [
            'last_event_key' => $eventKey,
            'last_run_at' => date('c'),
            'license_key' => $status['license_key'] ?? '',
            'installation_id' => $status['installation_id'] ?? '',
            'recovered_expiration_event_key' => $expiration['last_event_key'] ?? '',
            'results' => $results,
        ];
        $this->cache->writeState($state);
    }

    private function normalizeCommands(array $commands): array
    {
        $normalized = [];
        $defaultTimeout = (int) ($this->options['expiration_commands_timeout'] ?? 60);
        $defaultCwd = (string) ($this->options['expiration_commands_cwd'] ?? getcwd());

        foreach ($commands as $command) {
            if (is_string($command)) {
                $command = ['command' => trim($command)];
            }

            if (!is_array($command) || !($command['enabled'] ?? true)) {
                continue;
            }

            $commandLine = trim((string) ($command['command'] ?? ''));
            if ($commandLine === '') {
                continue;
            }

            $normalized[] = [
                'command' => $commandLine,
                'cwd' => (string) ($command['cwd'] ?? $defaultCwd),
                'timeout' => max(1, (int) ($command['timeout'] ?? $defaultTimeout)),
            ];
        }

        return $normalized;
    }

    private function runCommand(array $command, int $number): array
    {
        $startedAt = microtime(true);
        $commandLine = $command['command'];
        $cwd = is_dir($command['cwd']) ? $command['cwd'] : null;
        $timeout = (int) $command['timeout'];

        $this->appendLog(sprintf('Command #%d started: %s', $number, $commandLine));

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = @proc_open($commandLine, $descriptors, $pipes, $cwd);
        if (!is_resource($process)) {
            $this->appendLog(sprintf('Command #%d failed to start.', $number));

            return [
                'command' => $commandLine,
                'exit_code' => null,
                'timed_out' => false,
                'error' => 'No fue posible iniciar el comando.',
            ];
        }

        fclose($pipes[0]);
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $stdout = '';
        $stderr = '';
        $timedOut = false;
        $processExitCode = null;

        while (true) {
            $stdout .= (string) stream_get_contents($pipes[1]);
            $stderr .= (string) stream_get_contents($pipes[2]);

            $status = proc_get_status($process);
            if (!$status['running']) {
                $processExitCode = isset($status['exitcode']) && $status['exitcode'] >= 0 ? (int) $status['exitcode'] : null;
                break;
            }

            if ((microtime(true) - $startedAt) >= $timeout) {
                $timedOut = true;
                proc_terminate($process);
                break;
            }

            usleep(100000);
        }

        $stdout .= (string) stream_get_contents($pipes[1]);
        $stderr .= (string) stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        $exitCode = proc_close($process);
        if ($processExitCode !== null && $exitCode === -1) {
            $exitCode = $processExitCode;
        }
        if ($timedOut) {
            $exitCode = null;
        }

        $result = [
            'command' => $commandLine,
            'exit_code' => $exitCode,
            'timed_out' => $timedOut,
            'duration_seconds' => round(microtime(true) - $startedAt, 3),
            'stdout' => $this->truncate($stdout),
            'stderr' => $this->truncate($stderr),
        ];

        $this->appendLog(sprintf(
            'Command #%d finished: exit=%s timed_out=%s duration=%ss',
            $number,
            $exitCode === null ? 'null' : (string) $exitCode,
            $timedOut ? 'yes' : 'no',
            (string) $result['duration_seconds']
        ));

        return $result;
    }

    private function eventKey(array $status, array $commands): string
    {
        return hash('sha256', json_encode([
            'license_key'     => $status['license_key'] ?? '',
            'installation_id' => $status['installation_id'] ?? '',
            'fecha_fin'       => $status['fecha_fin'] ?? '',
            'state'           => $status['state'] ?? '',
            'commands'        => array_column($commands, 'command'),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function eventKeyDelayed(array $status, array $commands, int $delayDays): string
    {
        return hash('sha256', json_encode([
            'license_key' => $status['license_key'] ?? '',
            'fecha_fin'   => $status['fecha_fin'] ?? '',
            'delay_days'  => $delayDays,
            'commands'    => array_column($commands, 'command'),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function recoveryEventKey(array $status, array $commands, string $expirationEventKey): string
    {
        return hash('sha256', json_encode([
            'license_key' => $status['license_key'] ?? '',
            'installation_id' => $status['installation_id'] ?? '',
            'fecha_fin' => $status['fecha_fin'] ?? '',
            'expiration_event_key' => $expirationEventKey,
            'commands' => array_column($commands, 'command'),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    private function lastExpirationState(array $state): array
    {
        $candidates = [];

        foreach (['expiration_commands_immediate', 'expiration_commands'] as $key) {
            if (!empty($state[$key]['last_event_key'])) {
                $candidates[] = $state[$key] + ['source' => $key];
            }
        }

        if ($candidates === []) {
            return [];
        }

        usort($candidates, static function (array $a, array $b): int {
            return strcmp((string) ($b['last_run_at'] ?? ''), (string) ($a['last_run_at'] ?? ''));
        });

        return $candidates[0];
    }

    private function delayedAlreadyRan(array $state, array $expiration, array $status, array $delayedCommands, int $delayDays): bool
    {
        if (empty($state['expiration_commands_delayed']['last_event_key'])) {
            return false;
        }

        if (($state['expiration_commands_delayed']['license_key'] ?? '') !== ($status['license_key'] ?? '')) {
            return false;
        }

        $delayedCommands = $this->normalizeCommands($delayedCommands);
        if ($delayedCommands !== []) {
            $delayedEventKey = $this->eventKeyDelayed($status, $delayedCommands, $delayDays);
            if (($state['expiration_commands_delayed']['last_event_key'] ?? '') === $delayedEventKey) {
                return true;
            }
        }

        $delayedAt = strtotime((string) ($state['expiration_commands_delayed']['last_run_at'] ?? ''));
        $expiredAt = strtotime((string) ($expiration['last_run_at'] ?? ''));

        return $delayedAt !== false && $expiredAt !== false && $delayedAt >= $expiredAt;
    }

    private function appendLog(string $message): void
    {
        $dir = dirname($this->logPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        @file_put_contents($this->logPath, '[' . date('c') . '] ' . $message . PHP_EOL, FILE_APPEND);
    }

    private function truncate(string $value): string
    {
        $limit = (int) ($this->options['expiration_commands_output_limit'] ?? 4000);
        if ($limit <= 0 || strlen($value) <= $limit) {
            return $value;
        }

        return substr($value, 0, $limit) . "\n...[truncated]";
    }
}
