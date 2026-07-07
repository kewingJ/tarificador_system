<?php

declare(strict_types=1);

require_once __DIR__ . '/autoload.php';

use LicenseClient\LicenseGuard;
use LicenseClient\LicenseView;

if (!function_exists('license_client_status')) {
    function license_client_status(array $options = [], bool $preferOnline = true): array
    {
        return LicenseGuard::lastStatus() ?? LicenseGuard::status($options, $preferOnline);
    }
}

if (!function_exists('license_client_render_banner')) {
    function license_client_render_banner(?array $status = null): void
    {
        echo LicenseView::renderStatusBanner($status ?? license_client_status());
    }
}

if (!function_exists('license_client_render_modal')) {
    function license_client_render_modal(?array $status = null, string $modalId = 'modalLicense'): void
    {
        echo LicenseView::renderModal($status ?? license_client_status(), $modalId);
    }
}
