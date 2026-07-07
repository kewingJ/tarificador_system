<?php

declare(strict_types=1);

namespace LicenseClient;

final class LicenseView
{
    public static function renderActivationPage(array $data = [], ?string $message = null, string $level = 'info'): void
    {
        self::renderShell('Activación de licencia', self::activationMarkup($data, $message, $level));
    }

    public static function renderBlockedPage(array $status): void
    {
        $level = $status['level'] ?? 'danger';
        $message = $status['message'] ?? 'No fue posible validar la licencia.';
        $details = '';

        if (!empty($status['license_key'])) {
            $details .= '<div><span>ID de licencia</span><strong>' . self::e((string) $status['license_key']) . '</strong></div>';
        }

        if (!empty($status['installation_id'])) {
            $details .= '<div><span>Instalación</span><strong>' . self::e((string) $status['installation_id']) . '</strong></div>';
        }

        if (!empty($status['fecha_fin'])) {
            $details .= '<div><span>Vencimiento</span><strong>' . self::e((string) $status['fecha_fin']) . '</strong></div>';
        }

        $systemLabel = self::e((string) ($status['system_label'] ?? 'Keynet License Manager'));
        $logoFile = __DIR__ . '/../logo.png';
        $logoHtml = '';
        if (is_file($logoFile)) {
            $logoB64 = base64_encode((string) file_get_contents($logoFile));
            $logoHtml = '<div class="license-logo"><img src="data:image/png;base64,' . $logoB64 . '" alt="Keynet License Manager"></div>';
        } else {
            $logoHtml = '<div class="license-mark license-mark--' . self::e((string) $level) . '">!</div>';
        }

        $html = '
            <section class="license-card">
                <div class="license-header">
                    ' . $logoHtml . '
                    <p class="eyebrow">' . $systemLabel . '</p>
                </div>
                <h1 style="text-align:center;">Acceso detenido</h1>
            </section>';

        self::renderShell('Licencia no válida', $html);
    }

    public static function renderStatusBanner(array $status): string
    {
        $level = (string) ($status['level'] ?? 'success');
        $message = (string) ($status['message'] ?? 'Licencia activa.');
        $days = isset($status['days_remaining']) ? (int) $status['days_remaining'] : null;
        $source = (string) ($status['source'] ?? 'local');
        $licenseKey = (string) ($status['license_key'] ?? '');
        $statusText = self::statusText($status);
        $suffix = $days !== null ? 'Vence en ' . $days . ' ' . ($days === 1 ? 'día' : 'días') : 'Vencimiento no disponible';
        $detail = trim(ucfirst($source) . ' · ' . $suffix);

        return self::bannerStyles() . '
            <div class="keynet-license-banner keynet-license-banner--' . self::e($level) . '" role="status">
                <span class="keynet-license-banner__icon" aria-hidden="true"></span>
                <span class="keynet-license-banner__content">
                    <strong>' . self::e($message) . '</strong>
                    <small>' . self::e($detail) . '</small>
                </span>
                <span class="keynet-license-banner__status">' . self::e($statusText) . '</span>
            </div>';
    }

    public static function renderModal(array $status, string $modalId = 'modalLicense'): string
    {
        if (self::isSmartVoiceStatus($status)) {
            return self::modalStyles() . self::renderSmartVoiceModal($status, $modalId);
        }

        if (self::isNsmStatus($status)) {
            return self::modalStyles() . self::renderNsmModal($status, $modalId);
        }

        if (self::isMailtrackingStatus($status)) {
            return self::modalStyles() . self::renderMailtrackingModal($status, $modalId);
        }

        if (self::isWafStatus($status)) {
            return self::modalStyles() . self::renderWafModal($status, $modalId);
        }

        return self::modalStyles() . self::renderDefaultModal($status, $modalId);
    }

    private static function renderDefaultModal(array $status, string $modalId): string
    {
        $statusText = self::statusText($status);
        $validationText = self::validationText($status);
        $modelLevel = self::modelLevel($status);
        $fields = [
            'Token Key:' => $status['token_activacion'] ?? $status['license_key'] ?? 'No disponible',
            'ID License:' => $status['license_key'] ?? 'No disponible',
            'Model:' => self::modelText($status),
            'Status:' => $statusText . ' - ' . $validationText,
            'Expiration Date:' => self::formatDate($status['fecha_fin'] ?? null),
        ];

        $rows = '';
        foreach ($fields as $label => $value) {
            if ($label === 'Status:') {
                $rows .= '
                    <div class="keynet-license-info__row">
                        <span>' . self::e($label) . '</span>
                        <strong><span class="keynet-license-status-pill keynet-license-status-pill--' . self::e($modelLevel) . '"><i></i>' . self::e((string) $value) . '</span></strong>
                    </div>';
                continue;
            }

            $rows .= '
                <div class="keynet-license-info__row">
                    <span>' . self::e($label) . '</span>
                    <strong>' . self::e((string) $value) . '</strong>
                </div>';
        }

        return '
            <div class="modal fade keynet-license-modal" id="' . self::e($modalId) . '" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog keynet-license-modal__dialog" role="document">
                    <div class="modal-content keynet-license-modal__content">
                        <div class="keynet-license-modal__titlebar">
                            <h4 class="keynet-license-modal__title"><span class="keynet-license-title-check" aria-hidden="true"></span>Licencia</h4>
                            <button type="button" class="keynet-license-modal__close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="keynet-license-modal__body">
                            <section class="keynet-license-info">
                                <header class="keynet-license-info__header">
                                    <span class="keynet-license-shield" aria-hidden="true"></span>
                                    <h3>License Information</h3>
                                </header>
                                <div class="keynet-license-info__rows">' . $rows . '</div>
                            </section>
                        </div>
                        <div class="keynet-license-modal__footer">
                            <button type="button" class="keynet-license-button" data-dismiss="modal" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>';
    }

    private static function renderSmartVoiceModal(array $status, string $modalId): string
    {
        $licenseKey = (string) ($status['license_key'] ?? 'No disponible');
        $quantity = isset($status['quantity']) ? max(0, (int) $status['quantity']) : null;
        $active = isset($status['point']) && $status['point'] !== null ? max(0, (int) $status['point']) : $quantity;
        $licensedDevices = $quantity !== null ? (string) $quantity : 'No disponible';
        $activeDevices = $active !== null ? (string) $active : 'No disponible';
        $expireTime = self::formatSmartVoiceDateTime($status['fecha_fin'] ?? null);
        $activatedAt = self::formatSmartVoiceDateTime($status['fecha_creacion'] ?? ($status['last_sync_at'] ?? null));
        $subscription = self::smartVoiceSubscriptionText($status);

        return '
            <div class="modal fade keynet-license-modal keynet-license-modal--smartvoice" id="' . self::e($modalId) . '" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog keynet-license-smartvoice__dialog" role="document">
                    <div class="modal-content keynet-license-smartvoice">
                        <div class="keynet-license-smartvoice__header">
                            <h3>Status</h3>
                            <button type="button" class="keynet-license-smartvoice__close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Cerrar">&times;</button>
                        </div>
                        <div class="keynet-license-smartvoice__body">
                            <div class="keynet-license-smartvoice__row">
                                <span>Purchased Subscription:</span>
                                <strong>' . self::e($subscription) . '</strong>
                            </div>
                            <div class="keynet-license-smartvoice__row">
                                <span>Activation Key:</span>
                                <strong><code data-keynet-smartvoice-key="' . self::e($licenseKey) . '">' . self::e($licenseKey) . '</code><button type="button" data-keynet-smartvoice-copy>Copy</button></strong>
                            </div>
                            <div class="keynet-license-smartvoice__row">
                                <span>Expire Time:</span>
                                <strong>' . self::e($expireTime) . '</strong>
                            </div>
                            <div class="keynet-license-smartvoice__row">
                                <span>Number of licensed devices:</span>
                                <strong>' . self::e($licensedDevices) . '</strong>
                            </div>
                            <div class="keynet-license-smartvoice__row">
                                <span>Number of active devices:</span>
                                <strong>' . self::e($activeDevices) . '</strong>
                            </div>
                        </div>
                        <div class="keynet-license-smartvoice__footer">
                            <span>Activated: ' . self::e($activatedAt) . '</span>
                            <button type="button" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
                <script>
                    (function(){
                        var modal = document.getElementById("' . self::e($modalId) . '");
                        if (!modal) return;
                        var copyButton = modal.querySelector("[data-keynet-smartvoice-copy]");
                        var keyNode = modal.querySelector("[data-keynet-smartvoice-key]");
                        if (!copyButton || !keyNode) return;
                        copyButton.addEventListener("click", function(){
                            var value = keyNode.getAttribute("data-keynet-smartvoice-key") || keyNode.textContent || "";
                            if (navigator.clipboard && navigator.clipboard.writeText) {
                                navigator.clipboard.writeText(value);
                            }
                            copyButton.textContent = "Copied";
                            window.setTimeout(function(){ copyButton.textContent = "Copy"; }, 1400);
                        });
                    })();
                </script>
            </div>';
    }

    private static function renderNsmModal(array $status, string $modalId): string
    {
        $statusText = self::statusText($status);
        $statusLevel = self::modelLevel($status);
        $licenseKey = (string) ($status['license_key'] ?? 'No disponible');
        $visibleKey = self::maskMiddle($licenseKey);
        $serverId = (string) ($status['installation_id'] ?? 'No disponible');
        $visibleServerId = self::maskMiddle($serverId);
        $quantity = $status['quantity'] ?? null;
        $quantityText = $quantity !== null ? (string) (int) $quantity : 'No disponible';
        $lastChecked = self::formatDateTime($status['last_validation_at'] ?? ($status['last_sync_at'] ?? null));
        $dueDate = self::formatDateIso($status['fecha_fin'] ?? null);
        $type = self::nsmSubscriptionText($status, $quantityText);

        return '
            <div class="modal fade keynet-license-modal keynet-license-modal--nsm" id="' . self::e($modalId) . '" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog keynet-license-nsm__dialog" role="document">
                    <div class="modal-content keynet-license-nsm">
                        <div class="keynet-license-nsm__hero">
                            <button type="button" class="keynet-license-nsm__close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Cerrar">&times;</button>
                            <p>License Information</p>
                            <h3>NSM</h3>
                            <span>' . self::e($type) . '</span>
                        </div>
                        <div class="keynet-license-nsm__table">
                            <div class="keynet-license-nsm__row">
                                <span>Type</span>
                                <strong>' . self::e($type) . '</strong>
                            </div>
                            <div class="keynet-license-nsm__row">
                                <span>Subscription Key</span>
                                <strong><em data-keynet-nsm-license-key="' . self::e($licenseKey) . '">' . self::e($visibleKey) . '</em><button type="button" data-keynet-nsm-copy="license">Copiar</button></strong>
                            </div>
                            <div class="keynet-license-nsm__row">
                                <span>Status</span>
                                <strong><i class="keynet-license-nsm__status keynet-license-nsm__status--' . self::e($statusLevel) . '"><b></b>' . self::e(strtolower($statusText)) . '</i></strong>
                            </div>
                            <div class="keynet-license-nsm__row">
                                <span>Server ID</span>
                                <strong><em data-keynet-nsm-server-id="' . self::e($serverId) . '">' . self::e($visibleServerId) . '</em><button type="button" data-keynet-nsm-copy="server">Copiar</button></strong>
                            </div>
                            <div class="keynet-license-nsm__row">
                                <span>Sockets</span>
                                <strong>' . self::e($quantityText) . '</strong>
                            </div>
                            <div class="keynet-license-nsm__row">
                                <span>Last checked</span>
                                <strong>' . self::e($lastChecked) . '</strong>
                            </div>
                            <div class="keynet-license-nsm__row keynet-license-nsm__row--accent">
                                <span>Next due date</span>
                                <strong>' . self::e($dueDate) . '</strong>
                            </div>
                        </div>
                        <div class="keynet-license-nsm__footer">
                            <button type="button" class="keynet-license-nsm__button keynet-license-nsm__button--ghost" data-dismiss="modal" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
                <script>
                    (function(){
                        var modal = document.getElementById("' . self::e($modalId) . '");
                        if (!modal) return;
                        modal.addEventListener("click", function(event){
                            var button = event.target.closest("[data-keynet-nsm-copy]");
                            if (!button) return;
                            var type = button.getAttribute("data-keynet-nsm-copy");
                            var selector = type === "server" ? "[data-keynet-nsm-server-id]" : "[data-keynet-nsm-license-key]";
                            var node = modal.querySelector(selector);
                            if (!node) return;
                            var value = node.getAttribute(type === "server" ? "data-keynet-nsm-server-id" : "data-keynet-nsm-license-key") || node.textContent || "";
                            if (navigator.clipboard && navigator.clipboard.writeText) {
                                navigator.clipboard.writeText(value);
                            }
                            var original = button.textContent;
                            button.textContent = "Copiado";
                            window.setTimeout(function(){ button.textContent = original; }, 1400);
                        });
                    })();
                </script>
            </div>';
    }

    private static function renderMailtrackingModal(array $status, string $modalId): string
    {
        $systemName = trim((string) ($status['system_name'] ?? '')) ?: 'Mailtracking';
        $companyName = trim((string) ($status['company_name'] ?? '')) ?: 'No disponible';
        $licenseKey = trim((string) ($status['license_key'] ?? '')) ?: 'No disponible';
        $statusText = self::statusText($status);
        $statusLevel = self::mailtrackingStatusLevel($status);
        $plan = self::mailtrackingPlanText($status);
        $seats = isset($status['quantity']) ? (string) max(0, (int) $status['quantity']) : 'No disponible';
        $expires = self::formatDateIso($status['fecha_fin'] ?? null);
        $issued = self::formatDateIso($status['fecha_creacion'] ?? null);
        $fingerprint = trim((string) ($status['fingerprint'] ?? ''));
        $fingerprintText = $fingerprint !== '' ? self::maskMiddle($fingerprint) : 'No disponible';
        $environment = self::validationText($status);
        $risk = self::mailtrackingRiskText($status);
        $policy = self::mailtrackingPolicyText($status);
        $mark = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $systemName) ?: 'M', 0, 1));

        return '
            <div class="modal fade keynet-license-modal keynet-license-modal--mailtracking" id="' . self::e($modalId) . '" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog keynet-license-mailtracking__dialog" role="document">
                    <div class="modal-content keynet-license-mailtracking">
                        <aside class="keynet-license-mailtracking__aside">
                            <div class="keynet-license-mailtracking__mark" aria-hidden="true">' . self::e($mark) . '</div>
                            <div style="text-align:center">
                                <h3>' . self::e($systemName) . '</h3>
                                <p>' . self::e($licenseKey) . '</p>
                            </div>
                        </aside>
                        <section class="keynet-license-mailtracking__main">
                            <button type="button" class="keynet-license-mailtracking__close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Cerrar">&times;</button>
                            <header class="keynet-license-mailtracking__header">
                                <p>License Information</p>
                                <h3>' . self::e($companyName) . '</h3>
                            </header>
                            <div class="keynet-license-mailtracking__stats">
                                <article><span>Estado</span><strong class="keynet-license-mailtracking__status keynet-license-mailtracking__status--' . self::e($statusLevel) . '">' . self::e($statusText) . '</strong></article>
                                <article><span>Plan</span><strong>' . self::e($plan) . '</strong></article>
                                <article><span>Seats</span><strong>' . self::e($seats) . '</strong></article>
                                <article><span>Expira</span><strong>' . self::e($expires) . '</strong></article>
                            </div>
                            <div class="keynet-license-mailtracking__cards">
                                <article class="keynet-license-mailtracking__card">
                                    <h4>Comercial</h4>
                                    ' . self::mailtrackingInfoRow('Software', $systemName) . '
                                    ' . self::mailtrackingInfoRow('Cliente', $companyName) . '
                                    ' . self::mailtrackingInfoRow('Ambiente', $environment) . '
                                </article>
                                <article class="keynet-license-mailtracking__card">
                                    <h4>Técnico</h4>
                                    ' . self::mailtrackingInfoRow('Emitida', $issued) . '
                                    ' . self::mailtrackingInfoRow('Fingerprint', $fingerprintText) . '
                                    ' . self::mailtrackingInfoRow('Riesgo', $risk) . '
                                </article>
                            </div>
                            <article class="keynet-license-mailtracking__key">
                                <h4>License Key</h4>
                                <div>
                                    <code data-keynet-mailtracking-key="' . self::e($licenseKey) . '">' . self::e($licenseKey) . '</code>
                                    <button type="button" data-keynet-mailtracking-copy>Copiar</button>
                                </div>
                            </article>
                            <article class="keynet-license-mailtracking__policy">
                                <h4>Política</h4>
                                <p>' . self::e($policy) . '</p>
                            </article>
                            <article class="keynet-license-mailtracking__modules">
                                <h4>Módulo autorizado</h4>
                                <span>' . self::e($systemName) . '</span>
                            </article>
                        </section>
                    </div>
                </div>
                <script>
                    (function(){
                        var modal = document.getElementById("' . self::e($modalId) . '");
                        if (!modal) return;
                        var copyButton = modal.querySelector("[data-keynet-mailtracking-copy]");
                        var keyNode = modal.querySelector("[data-keynet-mailtracking-key]");
                        if (!copyButton || !keyNode) return;
                        copyButton.addEventListener("click", function(){
                            var value = keyNode.getAttribute("data-keynet-mailtracking-key") || keyNode.textContent || "";
                            if (navigator.clipboard && navigator.clipboard.writeText) {
                                navigator.clipboard.writeText(value);
                            }
                            var original = copyButton.textContent;
                            copyButton.textContent = "Copiado";
                            window.setTimeout(function(){ copyButton.textContent = original; }, 1400);
                        });
                    })();
                </script>
            </div>';
    }

    private static function renderWafModal(array $status, string $modalId): string
    {
        $statusText = self::statusText($status);
        $statusLevel = self::modelLevel($status);
        $licenseKey = (string) ($status['license_key'] ?? 'No disponible');
        $visibleKey = $licenseKey;
        $serverId = (string) ($status['installation_id'] ?? 'No disponible');
        $checkedAt = self::formatDateTime($status['last_validation_at'] ?? ($status['last_sync_at'] ?? null));
        $dueDate = self::formatDateIso($status['fecha_fin'] ?? null);
        $equipment = trim((string) ($status['equipment_type'] ?? ''));
        $subtitle = $equipment !== '' ? $equipment : 'WAF License Subscription';
        $quantity = $status['quantity'] ?? null;
        $quantityText = $quantity !== null ? (string) (int) $quantity : 'No disponible';

        return '
            <div class="modal fade keynet-license-modal keynet-license-modal--waf" id="' . self::e($modalId) . '" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog keynet-license-waf__dialog" role="document">
                    <div class="modal-content keynet-license-waf">
                        <div class="keynet-license-waf__hero">
                            <button type="button" class="keynet-license-waf__close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Cerrar">&times;</button>
                            <p>License Information</p>
                            <h3>WAF</h3>
                            <span>' . self::e($subtitle) . '</span>
                        </div>
                        <div class="keynet-license-waf__body">
                            <section class="keynet-license-waf__tile keynet-license-waf__tile--full">
                                <span>Subscription Key</span>
                                <strong data-keynet-visible-key="' . self::e($licenseKey) . '">' . self::e($visibleKey) . '</strong>
                            </section>
                            <section class="keynet-license-waf__tile">
                                <span>Status</span>
                                <strong><em class="keynet-license-waf__status keynet-license-waf__status--' . self::e($statusLevel) . '"><i></i>' . self::e(strtoupper($statusText)) . '</em></strong>
                            </section>
                            <section class="keynet-license-waf__tile">
                                <span>Licenses</span>
                                <strong>' . self::e($quantityText) . '</strong>
                            </section>
                            <section class="keynet-license-waf__tile keynet-license-waf__tile--full">
                                <span>Server ID</span>
                                <strong>' . self::e($serverId) . '</strong>
                            </section>
                            <section class="keynet-license-waf__tile">
                                <span>Last Checked</span>
                                <strong>' . self::e($checkedAt) . '</strong>
                            </section>
                            <section class="keynet-license-waf__tile keynet-license-waf__tile--accent">
                                <span>Next Due Date</span>
                                <strong>' . self::e($dueDate) . '</strong>
                            </section>
                            <div class="keynet-license-waf__actions">
                                <button type="button" class="keynet-license-waf__button keynet-license-waf__button--ghost" data-dismiss="modal" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
    }

    public static function modalStyles(): string
    {
        return '<style>
            .keynet-license-modal{font-family:Arial,Helvetica,sans-serif;color:#222936}
            .keynet-license-modal .keynet-license-modal__dialog{width:min(660px,calc(100% - 24px));max-width:660px;margin:34px auto}
            .keynet-license-modal .keynet-license-modal__content{border:0;border-radius:0;background:#fff;box-shadow:0 16px 48px rgba(15,31,53,.20);overflow:hidden}
            .keynet-license-modal .keynet-license-modal__titlebar{height:50px;display:flex;align-items:center;justify-content:space-between;padding:0 20px;border-bottom:1px solid #edf1f6;background:#fff}
            .keynet-license-modal .keynet-license-modal__title{display:flex;align-items:center;gap:8px;margin:0;color:#4a525c;font-size:18px;font-weight:400;letter-spacing:-.02em}
            .keynet-license-modal .keynet-license-title-check{width:18px;height:18px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;background:#515b66;position:relative}
            .keynet-license-modal .keynet-license-title-check:before{content:"";width:8px;height:5px;border-left:2px solid #fff;border-bottom:2px solid #fff;transform:rotate(-45deg);margin-top:-1px}
            .keynet-license-modal .keynet-license-modal__close{border:0;background:transparent;color:#b9bdc3;font-size:24px;font-weight:300;line-height:1;opacity:1;text-shadow:none;cursor:pointer;padding:0}
            .keynet-license-modal .keynet-license-modal__close:hover{color:#7b8491}
            .keynet-license-modal .keynet-license-modal__body{padding:16px 20px 16px;background:#fff}
            .keynet-license-modal .keynet-license-info{border:1px solid #dfe6ee;border-radius:5px;overflow:hidden;background:#fff}
            .keynet-license-modal .keynet-license-info__header{height:64px;display:flex;align-items:center;gap:13px;padding:0 20px;border-bottom:1px solid #dfe6ee}
            .keynet-license-modal .keynet-license-info__header h3{margin:0;color:#222936;font-size:18px;font-weight:800;letter-spacing:.01em}
            .keynet-license-modal .keynet-license-shield{width:36px;height:36px;display:inline-flex;align-items:center;justify-content:center;border-radius:8px;background:#eef8f1;position:relative;flex:0 0 36px}
            .keynet-license-modal .keynet-license-shield:before{content:"";width:11px;height:14px;background:#3b8a57;clip-path:polygon(50% 0,86% 14%,86% 52%,50% 86%,14% 52%,14% 14%)}
            .keynet-license-modal .keynet-license-shield:after{content:"";position:absolute;width:4px;height:7px;border-left:2px solid #eef8f1;border-bottom:2px solid #eef8f1;transform:rotate(-45deg);top:14px;left:16px}
            .keynet-license-modal .keynet-license-info__row{min-height:48px;display:grid;grid-template-columns:36% 1fr;align-items:center;border-bottom:1px solid #dfe6ee;background:#f9fbfe}
            .keynet-license-modal .keynet-license-info__row:last-child{border-bottom:0}
            .keynet-license-modal .keynet-license-info__row span:first-child{padding-left:20px;color:#5f6874;font-size:14px;font-weight:800;letter-spacing:.01em}
            .keynet-license-modal .keynet-license-info__row strong{padding:8px 20px;color:#222936;font-size:14px;font-weight:900;letter-spacing:.08em;word-break:break-word}
            .keynet-license-modal .keynet-license-status-pill{display:inline-flex;align-items:center;gap:7px;min-height:30px;padding:0 13px;border-radius:6px;background:#dd9f48;color:#fff!important;font-size:14px;font-weight:900;letter-spacing:.02em;text-shadow:none}
            .keynet-license-modal .keynet-license-status-pill,.keynet-license-modal .keynet-license-status-pill *{color:#fff!important}
            .keynet-license-modal .keynet-license-status-pill i{width:8px;height:8px;border-radius:999px;background:#fff;opacity:.9;display:inline-block}
            .keynet-license-modal .keynet-license-status-pill--success{background:#2f9e68;color:#fff!important}
            .keynet-license-modal .keynet-license-status-pill--danger{background:#b42318;color:#fff!important}
            .keynet-license-modal .keynet-license-status-pill--warning{background:#dd9f48;color:#fff!important}
            .keynet-license-modal .keynet-license-modal__footer{display:flex;justify-content:flex-end;padding:15px 20px 17px;border-top:1px solid #edf1f6;background:#fff}
            .keynet-license-modal .keynet-license-button{min-width:68px;height:34px;border:1px solid #dce3ec;border-radius:4px;background:#fff;color:#4b5563;font-size:12px;font-weight:700;cursor:pointer}
            .keynet-license-modal .keynet-license-button:hover{background:#f8fafc}
            .keynet-license-modal--smartvoice{font-family:"SFMono-Regular",Consolas,"Liberation Mono",Menlo,monospace;color:#f2f2f2}
            .keynet-license-modal--smartvoice .keynet-license-smartvoice__dialog{width:min(720px,calc(100% - 28px));max-width:720px;margin:24px auto}
            .keynet-license-smartvoice{border:1px solid #343434;border-radius:2px;background:#1f1f1f;box-shadow:0 24px 70px rgba(0,0,0,.42);overflow:hidden}
            .keynet-license-smartvoice__header{height:58px;display:flex;align-items:center;justify-content:space-between;padding:0 18px;border-bottom:1px solid #363636;background:#1f1f1f}
            .keynet-license-smartvoice__header h3{margin:0;color:#f4f4f4;font-size:24px;line-height:1;font-weight:400;letter-spacing:1px}
            .keynet-license-smartvoice__close{border:0;background:transparent;color:#6d6d6d;font-size:26px;line-height:1;cursor:pointer;text-shadow:none;padding:0}
            .keynet-license-smartvoice__close:hover{color:#bdbdbd}
            .keynet-license-smartvoice__body{display:grid;gap:0;padding:16px 18px 18px;background:#202020}
            .keynet-license-smartvoice__row{min-height:36px;display:grid;grid-template-columns:minmax(220px,1fr) auto;align-items:center;gap:16px;color:#aeb0b5;font-size:14px;line-height:1.3}
            .keynet-license-smartvoice__row span{font-weight:500}
            .keynet-license-smartvoice__row strong{display:flex;align-items:center;justify-content:flex-end;gap:10px;color:#f4f4f4;font-size:14px;font-weight:800;text-align:right;white-space:nowrap}
            .keynet-license-smartvoice__row code{display:inline-flex;align-items:center;min-height:28px;padding:0 9px;border-radius:5px;background:#15181f;border:1px solid #2b2f36;color:#dceaff;font:inherit;font-weight:700;letter-spacing:0}
            .keynet-license-smartvoice__row button{min-height:30px;padding:0 12px;border:1px solid #444;border-radius:4px;background:#2a2a2a;color:#f2f2f2;font:inherit;font-size:13px;font-weight:700;cursor:pointer}
            .keynet-license-smartvoice__row button:hover{background:#333}
            .keynet-license-smartvoice__footer{min-height:56px;display:flex;align-items:center;justify-content:flex-end;gap:12px;padding:0 18px;border-top:1px solid #363636;background:#202020;color:#8f9298;font-size:13px}
            .keynet-license-smartvoice__footer button{min-height:32px;padding:0 16px;border:1px solid #11a9d6;border-radius:4px;background:#25a9d2;color:#fff;font:inherit;font-size:13px;font-weight:800;cursor:pointer;box-shadow:0 0 0 2px rgba(37,169,210,.22)}
            .keynet-license-smartvoice__footer button:hover{background:#1c98bd}
            .keynet-license-modal--nsm{color:#142033;font-family:Arial,Helvetica,sans-serif}
            .keynet-license-modal--nsm .keynet-license-nsm__dialog{width:min(900px,calc(100% - 28px));max-width:min(900px,calc(100% - 28px));margin:22px auto}
            .keynet-license-nsm{border:0;border-radius:20px;background:#fff;box-shadow:0 24px 64px rgba(17,30,54,.25);overflow:hidden}
            .keynet-license-nsm__hero{position:relative;padding:24px 34px 30px;background:linear-gradient(135deg,#4f70bf 0%,#273f76 100%);color:#fff}
            .keynet-license-nsm__hero p{margin:0 0 12px;color:#dce7ff;font-size:16px;font-weight:800;text-transform:uppercase;letter-spacing:3px}
            .keynet-license-nsm__hero h3{margin:0 0 9px;color:#fff;font-size:30px;line-height:1;font-weight:900;letter-spacing:0}
            .keynet-license-nsm__hero span{display:block;color:#dbe6ff;font-size:18px;line-height:1.35;font-weight:500}
            .keynet-license-nsm__close{position:absolute;top:20px;right:22px;width:48px;height:48px;border:2px solid rgba(255,255,255,.42);border-radius:999px;background:rgba(255,255,255,.10);color:#fff;font-size:32px;font-weight:500;line-height:1;cursor:pointer;text-shadow:none}
            .keynet-license-nsm__table{background:#fff}
            .keynet-license-nsm__row{min-height:60px;display:grid;grid-template-columns:32% 1fr;align-items:center;gap:16px;padding:0 24px;border-bottom:1px solid #e7eaf0}
            .keynet-license-nsm__row--accent{background:#f3f6fb}
            .keynet-license-nsm__row span{color:#697386;font-size:18px;font-weight:500}
            .keynet-license-nsm__row strong{display:flex;align-items:center;justify-content:space-between;gap:14px;min-width:0;color:#111827;font-size:20px;line-height:1.25;font-weight:800}
            .keynet-license-nsm__row strong em{font-style:normal;word-break:break-word}
            .keynet-license-nsm__row button{min-height:36px;padding:0 16px;border:1px solid #d7deea;border-radius:999px;background:#fff;color:#4768b3;font-size:16px;font-weight:800;cursor:pointer;white-space:nowrap}
            .keynet-license-nsm__row button:hover{background:#f5f7fc}
            .keynet-license-nsm__status{display:inline-flex;align-items:center;gap:9px;min-height:38px;padding:0 16px;border-radius:999px;background:#ecf9f0;border:1px solid #d0eedb;color:#11713e;font-style:normal;font-size:17px;font-weight:800}
            .keynet-license-nsm__status b{width:9px;height:9px;border-radius:999px;background:#11713e;display:inline-block}
            .keynet-license-nsm__status--warning{background:#fff7df;border-color:#f4deb0;color:#946100}
            .keynet-license-nsm__status--warning b{background:#946100}
            .keynet-license-nsm__status--danger{background:#fff0ee;border-color:#f4cbc5;color:#a51d17}
            .keynet-license-nsm__status--danger b{background:#a51d17}
            .keynet-license-nsm__footer{display:flex;justify-content:flex-end;align-items:center;gap:14px;padding:18px 24px;background:#fff}
            .keynet-license-nsm__button{min-height:44px;border-radius:999px;padding:0 24px;font-size:17px;font-weight:900;cursor:pointer}
            .keynet-license-nsm__button--ghost{border:1px solid #d7deea;background:#fff;color:#17233a}
            .keynet-license-nsm__button--primary{border:0;background:linear-gradient(135deg,#4f70bf,#1f3565);color:#fff}
            .keynet-license-modal--waf{color:#112433}
            .keynet-license-modal--waf .keynet-license-waf__dialog{width:min(620px,calc(100% - 28px));max-width:620px;margin:20px auto}
            .keynet-license-modal--waf .keynet-license-waf{border:0;border-radius:18px;background:#fff;box-shadow:0 22px 58px rgba(25,45,65,.24);overflow:hidden}
            .keynet-license-waf__hero{position:relative;padding:22px 30px 26px;background:linear-gradient(135deg,#1f9dc3 0%,#46b4d1 58%,#8dd0df 100%);color:#fff}
            .keynet-license-waf__hero p{margin:0 0 7px;font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:2px}
            .keynet-license-waf__hero h3{margin:0 0 8px;font-size:29px;line-height:1;font-weight:900;letter-spacing:0;color:#fff}
            .keynet-license-waf__hero span{display:block;font-size:16px;line-height:1.35;font-weight:500;color:rgba(255,255,255,.96)}
            .keynet-license-waf__close{position:absolute;top:17px;right:18px;width:44px;height:44px;border:2px solid rgba(255,255,255,.48);border-radius:999px;background:rgba(255,255,255,.14);color:#fff;font-size:28px;font-weight:700;line-height:1;cursor:pointer;text-shadow:none}
            .keynet-license-waf__body{display:grid;grid-template-columns:1fr 1fr;gap:12px;padding:22px 28px 24px;background:#fff}
            .keynet-license-waf__tile{min-height:74px;border:2px solid #d4eef5;border-radius:14px;background:#f8fdff;padding:14px 18px;display:flex;flex-direction:column;justify-content:center;overflow:hidden}
            .keynet-license-waf__tile--full{grid-column:1 / -1}
            .keynet-license-waf__tile--accent{background:#e2f6f8}
            .keynet-license-waf__tile span{display:block;margin:0 0 6px;color:#687993;font-size:12px;font-weight:900;text-transform:uppercase;letter-spacing:2px}
            .keynet-license-waf__tile strong{display:block;color:#102331;font-size:19px;line-height:1.2;font-weight:900;letter-spacing:0;word-break:break-word}
            .keynet-license-waf__status{display:inline-flex;align-items:center;gap:8px;min-height:34px;padding:0 14px;border-radius:999px;background:#d9f8e2;color:#106b3d;font-style:normal;font-size:14px;font-weight:900}
            .keynet-license-waf__status i{width:9px;height:9px;border-radius:999px;background:#106b3d;display:inline-block}
            .keynet-license-waf__status--warning{background:#fff0cd;color:#9a5b00}
            .keynet-license-waf__status--warning i{background:#9a5b00}
            .keynet-license-waf__status--danger{background:#ffe1dc;color:#a51d17}
            .keynet-license-waf__status--danger i{background:#a51d17}
            .keynet-license-waf__actions{grid-column:1 / -1;display:flex;justify-content:flex-end;gap:12px;margin-top:4px}
            .keynet-license-waf__button{min-height:38px;border:0;border-radius:999px;padding:0 22px;font-size:15px;font-weight:900;cursor:pointer}
            .keynet-license-waf__button--ghost{border:2px solid #d4eef5;background:#ecfcff;color:#2299bd}
            .keynet-license-waf__button--primary{background:#249bc3;color:#fff}
            .keynet-license-modal--mailtracking{color:#111827;font-family:Arial,Helvetica,sans-serif}
            .keynet-license-modal--mailtracking .keynet-license-mailtracking__dialog{width:min(860px,calc(100% - 28px));max-width:min(860px,calc(100% - 28px));margin:30px auto}
            .keynet-license-mailtracking{display:grid;grid-template-columns:282px 1fr;border:0;border-radius:24px;background:#f8fbff;box-shadow:0 26px 70px rgba(17,30,54,.28);overflow:hidden}
            .keynet-license-mailtracking__aside{min-height:470px;padding:42px 26px 30px;display:flex;flex-direction:column;justify-content:center;gap:28px;background:linear-gradient(145deg,#e7f8ff 0%,#f9fdff 62%,#f3edff 100%);border-right:1px solid #e1e8f0}
            .keynet-license-mailtracking__mark{width:104px;height:104px;margin:0 auto;border-radius:30px;display:grid;place-items:center;background:linear-gradient(145deg,#fff 0%,#e4f8ff 48%,#99ddeb 100%);box-shadow:inset 0 0 0 1px rgba(255,255,255,.78),0 18px 35px rgba(69,154,185,.18);color:#1d3446;font-size:34px;font-weight:900}
            .keynet-license-mailtracking__aside h3{margin:0;color:#101827;font-size:26px;line-height:1.05;font-weight:900;letter-spacing:-.05em;word-break:break-word}
            .keynet-license-mailtracking__aside p{margin:10px 0 0;color:#6e7a8d;font-size:12px;font-weight:800;letter-spacing:.02em;word-break:break-word}
            .keynet-license-mailtracking__main{position:relative;padding:24px 24px 22px;background:linear-gradient(145deg,#fbfcff 0%,#f8f9fc 72%,#f3f0f8 100%)}
            .keynet-license-mailtracking__close{position:absolute;top:20px;right:20px;width:34px;height:34px;border:0;border-radius:12px;background:rgba(255,255,255,.86);color:#364255;font-size:24px;font-weight:700;line-height:1;cursor:pointer;text-shadow:none;box-shadow:0 1px 5px rgba(17,30,54,.08)}
            .keynet-license-mailtracking__close:hover{background:#fff;color:#102033}
            .keynet-license-mailtracking__header{padding-right:46px;margin-bottom:18px}
            .keynet-license-mailtracking__header p{margin:0 0 6px;color:#2b5870;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:2.8px}
            .keynet-license-mailtracking__header h3{margin:0;color:#111827;font-size:24px;line-height:1.1;font-weight:900;letter-spacing:-.05em}
            .keynet-license-mailtracking__stats{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:12px}
            .keynet-license-mailtracking__stats article{min-height:62px;padding:12px 12px;border:1px solid #e4e8ef;border-radius:14px;background:rgba(255,255,255,.72);box-shadow:0 1px 0 rgba(255,255,255,.8)}
            .keynet-license-mailtracking__stats span,.keynet-license-mailtracking__card h4,.keynet-license-mailtracking__key h4,.keynet-license-mailtracking__policy h4,.keynet-license-mailtracking__modules h4{display:block;margin:0 0 6px;color:#718094;font-size:10px;font-weight:900;text-transform:uppercase;letter-spacing:2.4px}
            .keynet-license-mailtracking__stats strong{display:block;color:#111827;font-size:14px;line-height:1.25;font-weight:900;word-break:break-word}
            .keynet-license-mailtracking__status{display:inline-flex!important;width:max-content;align-items:center;min-height:24px;padding:0 9px;border-radius:999px;background:#ecf9f0;color:#11713e}
            .keynet-license-mailtracking__status--warning{background:#fff7df;color:#946100}
            .keynet-license-mailtracking__status--danger{background:#fff0ee;color:#a51d17}
            .keynet-license-mailtracking__cards{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
            .keynet-license-mailtracking__card,.keynet-license-mailtracking__key,.keynet-license-mailtracking__policy,.keynet-license-mailtracking__modules{border:1px solid #e4e8ef;border-radius:16px;background:rgba(255,255,255,.76);box-shadow:0 1px 0 rgba(255,255,255,.8)}
            .keynet-license-mailtracking__card{padding:14px 16px 10px}
            .keynet-license-mailtracking__line{display:grid;grid-template-columns:42% 1fr;gap:10px;align-items:center;padding:8px 0;border-top:1px solid #edf0f5}
            .keynet-license-mailtracking__line:first-of-type{border-top:0}
            .keynet-license-mailtracking__line span{color:#718094;font-size:12px;font-weight:700}
            .keynet-license-mailtracking__line strong{min-width:0;color:#111827;font-size:12px;line-height:1.25;font-weight:900;text-align:right;word-break:break-word}
            .keynet-license-mailtracking__key{padding:14px 16px;margin-bottom:12px}
            .keynet-license-mailtracking__key div{display:flex;align-items:center;gap:10px;padding:10px 10px;border:1px dashed #bfe4f2;border-radius:14px;background:#f8fdff}
            .keynet-license-mailtracking__key code{min-width:0;flex:1;color:#234058;background:transparent;border:0;font:inherit;font-size:12px;font-weight:800;word-break:break-word}
            .keynet-license-mailtracking__key button{min-height:34px;padding:0 14px;border:1px solid #cfe3ee;border-radius:11px;background:#fff;color:#2f6384;font-size:12px;font-weight:900;cursor:pointer}
            .keynet-license-mailtracking__key button:hover{background:#f3fbff}
            .keynet-license-mailtracking__policy{padding:14px 16px;margin-bottom:12px}
            .keynet-license-mailtracking__policy p{margin:0;color:#718094;font-size:12px;line-height:1.35;font-weight:700}
            .keynet-license-mailtracking__modules{padding:14px 16px}
            .keynet-license-mailtracking__modules span{display:inline-flex;align-items:center;min-height:26px;padding:0 12px;border:1px solid #dfe6ee;border-radius:999px;background:#fff;color:#405066;font-size:12px;font-weight:900}
            @media (max-width:700px){
                .keynet-license-modal .keynet-license-modal__dialog{width:calc(100% - 20px);margin:18px auto}
                .keynet-license-modal .keynet-license-info__row{grid-template-columns:1fr;gap:4px;padding:10px 14px}
                .keynet-license-modal .keynet-license-info__row span:first-child{padding:0}
                .keynet-license-modal .keynet-license-info__row strong{padding:0}
                .keynet-license-modal--smartvoice .keynet-license-smartvoice__dialog{width:calc(100% - 18px);margin:12px auto}
                .keynet-license-smartvoice__header{height:64px;padding:0 16px}
                .keynet-license-smartvoice__header h3{font-size:24px}
                .keynet-license-smartvoice__body{padding:16px}
                .keynet-license-smartvoice__row{grid-template-columns:1fr;gap:7px;align-items:start;padding:8px 0;font-size:14px}
                .keynet-license-smartvoice__row strong{justify-content:flex-start;text-align:left;white-space:normal;flex-wrap:wrap}
                .keynet-license-smartvoice__footer{min-height:auto;align-items:flex-start;flex-direction:column;padding:16px}
                .keynet-license-smartvoice__footer button{align-self:flex-end}
                .keynet-license-modal--nsm .keynet-license-nsm__dialog{width:calc(100% - 18px);margin:12px auto}
                .keynet-license-nsm{border-radius:16px}
                .keynet-license-nsm__hero{padding:22px 20px 26px}
                .keynet-license-nsm__hero p{font-size:13px}
                .keynet-license-nsm__hero h3{font-size:26px}
                .keynet-license-nsm__hero span{font-size:16px}
                .keynet-license-nsm__close{top:16px;right:16px;width:42px;height:42px;font-size:28px}
                .keynet-license-nsm__row{grid-template-columns:1fr;gap:7px;align-items:start;min-height:auto;padding:14px 18px}
                .keynet-license-nsm__row span{font-size:15px}
                .keynet-license-nsm__row strong{justify-content:flex-start;align-items:flex-start;flex-wrap:wrap;font-size:18px}
                .keynet-license-nsm__footer{flex-direction:column-reverse;align-items:stretch;padding:16px 18px}
                .keynet-license-nsm__button{width:100%;font-size:16px}
                .keynet-license-modal--waf .keynet-license-waf__dialog{width:calc(100% - 18px);margin:12px auto}
                .keynet-license-modal--waf .keynet-license-waf{border-radius:20px}
                .keynet-license-waf__hero{padding:24px 22px 28px}
                .keynet-license-waf__hero p{font-size:13px}
                .keynet-license-waf__hero h3{font-size:30px}
                .keynet-license-waf__hero span{font-size:16px}
                .keynet-license-waf__close{top:16px;right:16px;width:46px;height:46px;font-size:28px}
                .keynet-license-waf__body{grid-template-columns:1fr;gap:13px;padding:20px 16px}
                .keynet-license-waf__tile{min-height:86px;border-radius:16px;padding:17px 18px}
                .keynet-license-waf__tile span{font-size:13px}
                .keynet-license-waf__tile strong{font-size:20px}
                .keynet-license-waf__actions{grid-column:auto;flex-direction:column}
                .keynet-license-waf__button{width:100%;font-size:16px}
                .keynet-license-modal--mailtracking .keynet-license-mailtracking__dialog{width:calc(100% - 18px);margin:12px auto}
                .keynet-license-mailtracking{grid-template-columns:1fr;border-radius:20px}
                .keynet-license-mailtracking__aside{min-height:auto;padding:22px 20px;display:grid;grid-template-columns:72px 1fr;align-items:center;gap:16px}
                .keynet-license-mailtracking__mark{width:72px;height:72px;margin:0;border-radius:22px;font-size:26px}
                .keynet-license-mailtracking__aside h3{font-size:22px}
                .keynet-license-mailtracking__main{padding:20px 16px 18px}
                .keynet-license-mailtracking__stats,.keynet-license-mailtracking__cards{grid-template-columns:1fr 1fr}
                .keynet-license-mailtracking__line{grid-template-columns:1fr;gap:3px}
                .keynet-license-mailtracking__line strong{text-align:left}
                .keynet-license-mailtracking__key div{align-items:stretch;flex-direction:column}
                .keynet-license-mailtracking__key button{align-self:flex-end}
            }
            @media (max-width:480px){
                .keynet-license-mailtracking__stats,.keynet-license-mailtracking__cards{grid-template-columns:1fr}
            }
        </style>';
    }

    private static function bannerStyles(): string
    {
        return '<style>
            .keynet-license-banner{width:100%;display:flex;align-items:center;gap:12px;margin:0 0 18px;padding:13px 16px;border:1px solid #dce6f2;border-radius:12px;background:#f8fbff;color:#1d2a3a;box-shadow:0 10px 30px rgba(15,31,53,.07);font-family:Arial,Helvetica,sans-serif}
            .keynet-license-banner__icon{width:36px;height:36px;display:inline-flex;align-items:center;justify-content:center;flex:0 0 36px;border-radius:10px;background:#e8f3ff;position:relative}
            .keynet-license-banner__icon:before{content:"";width:16px;height:9px;border-left:3px solid #075baa;border-bottom:3px solid #075baa;transform:rotate(-45deg);margin-top:-3px}
            .keynet-license-banner__content{display:flex;flex-direction:column;gap:2px;min-width:0;line-height:1.25}
            .keynet-license-banner__content strong{font-size:14px;font-weight:800;color:#111827}
            .keynet-license-banner__content small{font-size:12px;font-weight:700;color:#63728a;white-space:normal}
            .keynet-license-banner__status{margin-left:auto;display:inline-flex;align-items:center;min-height:28px;padding:0 12px;border-radius:999px;background:#e9f6ef;color:#12844f;font-size:12px;font-weight:900}
            .keynet-license-banner--warning{border-color:#f4d5b7;background:#fff8f1}
            .keynet-license-banner--warning .keynet-license-banner__icon{background:#ffe8d4}
            .keynet-license-banner--warning .keynet-license-banner__icon:before{border-color:#a74d14}
            .keynet-license-banner--warning .keynet-license-banner__status{background:#ffd9bf;color:#9a3f0f}
            .keynet-license-banner--danger{border-color:#f1c4c1;background:#fff5f4}
            .keynet-license-banner--danger .keynet-license-banner__icon{background:#ffe3e0}
            .keynet-license-banner--danger .keynet-license-banner__icon:before{border-color:#b42318}
            .keynet-license-banner--danger .keynet-license-banner__status{background:#ffd6d1;color:#a51d17}
            @media (max-width:700px){
                .keynet-license-banner{align-items:flex-start}
                .keynet-license-banner__status{display:none}
            }
        </style>';
    }

    private static function activationMarkup(array $data, ?string $message, string $level): string
    {
        // Siempre vacío: el administrador ingresa la URL al momento de activar
        $baseUrl = '';
        $token = (string) ($data['token_activacion'] ?? '');
        $licenseKey = (string) ($data['license_key'] ?? '');
        $installationId = (string) ($data['installation_id'] ?? '');

        // Logo embebido desde client/logo.png — funciona en cualquier proyecto sin URL externa
        // __DIR__ es client/src/, un nivel arriba es client/
        $logoFile = __DIR__ . '/../logo.png';
        $logoHtml = '';
        if (is_file($logoFile)) {
            $logoB64 = base64_encode((string) file_get_contents($logoFile));
            $logoHtml = '<div class="license-logo"><img src="data:image/png;base64,' . $logoB64 . '" alt="Keynet License Manager"></div>';
        }

        $alert = $message
            ? '<div class="alert alert--' . self::e($level) . '">' . self::e($message) . '</div>'
            : '';

        return '
            <section class="license-card">
                <div class="license-header">
                    ' . $logoHtml . '
                    <p class="eyebrow">Keynet License Manager</p>
                </div>
                <h1>Activar licencia</h1>
                <p class="lead">Ingresa los datos entregados por el administrador para habilitar este sistema.</p>
                ' . $alert . '
                <form method="post" class="license-form">
                    <input type="hidden" name="license_client_action" value="activate">
                    <label>
                        <span>Servidor de licencias</span>
                        <input type="url" name="base_url" required value="' . self::e($baseUrl) . '">
                    </label>
                    <label>
                        <span>Token de activación</span>
                        <input type="text" name="token_activacion" required value="' . self::e($token) . '">
                    </label>
                    <label>
                        <span>ID de licencia</span>
                        <input type="text" name="license_key" required value="' . self::e($licenseKey) . '">
                    </label>
                    <label>
                        <span>ID de instalación</span>
                        <input type="text" name="installation_id" required value="' . self::e($installationId) . '">
                    </label>
                    <button type="submit" class="button button--primary">Activar y continuar</button>
                </form>
                <p class="footnote">Esta verificación es obligatoria antes de acceder al sistema.</p>
            </section>';
    }

    private static function renderShell(string $title, string $content): void
    {
        if (!headers_sent()) {
            http_response_code(200);
            header('Content-Type: text/html; charset=utf-8');
        }

        echo '<!doctype html>
        <html lang="es">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>' . self::e($title) . '</title>
            <style>
                :root{--primary:#075baa;--primary-dark:#073f82;--text:#111827;--muted:#64748b;--border:#dce6f2;--bg:#f4f7fb;--danger:#b42318;--warning:#9a4d11}
                *{box-sizing:border-box}
                body{margin:0;min-height:100vh;display:grid;place-items:center;padding:32px;background:linear-gradient(180deg,#f8fbff 0%,#f1f5fb 100%);font-family:Arial,Helvetica,sans-serif;color:var(--text)}
                .license-card{width:min(660px,100%);background:#fff;border:1px solid #e5edf8;border-radius:8px;padding:34px;box-shadow:0 18px 50px rgba(15,31,53,.10)}
                .license-logo,.license-mark{width:80px;height:80px;border-radius:8px;display:grid;place-items:center;margin-bottom:12px;background:var(--primary);color:#fff;font-weight:800;font-size:22px}
                .license-mark--danger{background:#fff2f1;color:var(--danger);border:1px solid #f2c8c4}
                .license-mark--warning{background:#fff6ed;color:var(--warning);border:1px solid #f3d1af}
                .license-header{text-align:center;margin-bottom:24px}
                .license-logo{display:inline-block;width:100px;height:100px;background:transparent;border:none;padding:0;margin:0 auto 12px}.license-logo img{width:100%;height:100%;object-fit:contain;border-radius:4px}
                .eyebrow{margin:0 0 8px;color:var(--primary-dark);font-size:12px;font-weight:800;letter-spacing:.12em;text-transform:uppercase}
                h1{margin:0 0 8px;font-size:32px;line-height:1.1;letter-spacing:0}
                .lead{margin:0 0 24px;color:var(--muted);font-size:16px;line-height:1.55}
                .license-form{display:grid;gap:15px}
                label span{display:block;margin-bottom:7px;color:#334155;font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
                input{width:100%;height:48px;border:1px solid var(--border);border-radius:8px;padding:0 13px;color:var(--text);font-size:15px;outline:none}
                input:focus{border-color:#b8cbe4;box-shadow:0 0 0 3px rgba(7,91,170,.08)}
                .button{height:50px;border:0;border-radius:8px;padding:0 18px;font-weight:800;cursor:pointer}
                .button--primary{background:var(--primary);color:#fff}
                .button--ghost{background:#fff;border:1px solid var(--border);color:var(--primary-dark)}
                .alert{margin:0 0 18px;padding:12px 14px;border-radius:8px;font-weight:700;line-height:1.45}
                .alert--danger{background:#fff2f1;color:var(--danger);border:1px solid #f2c8c4}
                .alert--warning{background:#fff6ed;color:var(--warning);border:1px solid #f3d1af}
                .alert--success,.alert--info{background:#eef6ff;color:#0b4c8c;border:1px solid #cfe2f7}
                .status{display:inline-flex;margin:4px 0 18px;padding:7px 10px;border-radius:8px;font-weight:800}
                .status--danger{background:#fff2f1;color:var(--danger)}
                .status--warning{background:#fff6ed;color:var(--warning)}
                .status--success{background:#e9f6ef;color:#12844f}
                .detail-grid{display:grid;gap:10px;margin:18px 0}
                .detail-grid div{display:flex;justify-content:space-between;gap:14px;border-bottom:1px solid #edf2f7;padding-bottom:10px}
                .detail-grid span{color:var(--muted);font-weight:700}
                .detail-grid strong{text-align:right;word-break:break-word}
                .actions{margin-top:18px}
                .footnote{margin:18px 0 0;color:#94a3b8;font-size:13px;text-align:center}
            </style>
        </head>
        <body>' . $content . '</body>
        </html>';
        exit;
    }

    private static function statusText(array $status): string
    {
        $state = strtolower((string) ($status['state'] ?? ''));
        $labels = [
            'activa' => 'Activa',
            'inactiva' => 'Inactiva',
            'vencida' => 'Vencida',
            'pendiente' => 'Pendiente',
            'trial' => 'Trial',
        ];

        return $labels[$state] ?? 'No disponible';
    }

    private static function modelText(array $status): string
    {
        $system = trim((string) ($status['system_name'] ?? ''));
        $model = strtolower((string) ($status['model'] ?? ''));
        $labels = [
            'activa' => 'Activa',
            'pasiva' => 'Pasiva',
        ];

        $modelText = $labels[$model] ?? ($model !== '' ? ucfirst($model) : 'No disponible');

        return $system !== '' ? $system . ' - ' . $modelText : $modelText;
    }

    private static function modelLevel(array $status): string
    {
        $state = strtolower((string) ($status['state'] ?? ''));
        $model = strtolower((string) ($status['model'] ?? ''));

        if ($state === 'vencida' || $state === 'inactiva') {
            return 'danger';
        }

        return $model === 'activa' ? 'success' : 'warning';
    }

    private static function validationText(array $status): string
    {
        $type = strtolower((string) ($status['validation_type'] ?? ''));
        $labels = [
            'online' => 'Online',
            'offline' => 'Offline',
            'hibrida' => 'Híbrida',
            'hybrid' => 'Híbrida',
        ];

        return $labels[$type] ?? 'Validación';
    }

    private static function formatDate($value): string
    {
        if (empty($value)) {
            return 'No disponible';
        }

        try {
            return (new \DateTimeImmutable((string) $value))->format('Y/m/d');
        } catch (\Throwable $exception) {
            return (string) $value;
        }
    }

    private static function formatDateIso($value): string
    {
        if (empty($value)) {
            return 'No disponible';
        }

        try {
            return (new \DateTimeImmutable((string) $value))->format('Y-m-d');
        } catch (\Throwable $exception) {
            return (string) $value;
        }
    }

    private static function formatDateTime($value): string
    {
        if (empty($value)) {
            return 'No disponible';
        }

        try {
            return (new \DateTimeImmutable((string) $value))->format('Y-m-d H:i:s');
        } catch (\Throwable $exception) {
            return (string) $value;
        }
    }

    private static function formatSmartVoiceDateTime($value): string
    {
        if (empty($value)) {
            return 'No disponible';
        }

        try {
            $date = new \DateTimeImmutable((string) $value);
        } catch (\Throwable $exception) {
            return (string) $value;
        }

        $months = [
            1 => 'ene',
            2 => 'feb',
            3 => 'mar',
            4 => 'abr',
            5 => 'may',
            6 => 'jun',
            7 => 'jul',
            8 => 'ago',
            9 => 'sep',
            10 => 'oct',
            11 => 'nov',
            12 => 'dic',
        ];
        $hour = (int) $date->format('g');
        $minute = $date->format('i');
        $period = $date->format('A') === 'AM' ? 'a.m.' : 'p.m.';

        return (int) $date->format('j') . ' ' . $months[(int) $date->format('n')] . ' ' . $date->format('Y') . ', ' . $hour . ':' . $minute . ' ' . $period;
    }

    private static function mailtrackingInfoRow(string $label, string $value): string
    {
        $value = trim($value) !== '' ? $value : 'No disponible';

        return '<div class="keynet-license-mailtracking__line"><span>' . self::e($label) . '</span><strong>' . self::e($value) . '</strong></div>';
    }

    private static function mailtrackingPlanText(array $status): string
    {
        $model = trim((string) ($status['model'] ?? ''));
        if ($model !== '') {
            return self::modelText($status);
        }

        return self::validationText($status);
    }

    private static function mailtrackingRiskText(array $status): string
    {
        $state = strtolower(trim((string) ($status['state'] ?? '')));
        $days = isset($status['days_remaining']) ? (int) $status['days_remaining'] : null;

        if ($state === 'vencida' || $state === 'inactiva' || ($days !== null && $days < 0)) {
            return 'High';
        }

        if ($days !== null && $days <= 7) {
            return 'Medium';
        }

        return 'Low';
    }

    private static function mailtrackingStatusLevel(array $status): string
    {
        $state = strtolower(trim((string) ($status['state'] ?? '')));
        $days = isset($status['days_remaining']) ? (int) $status['days_remaining'] : null;

        if ($state === 'vencida' || $state === 'inactiva' || ($days !== null && $days < 0)) {
            return 'danger';
        }

        if ($days !== null && $days <= 7) {
            return 'warning';
        }

        return 'success';
    }

    private static function mailtrackingPolicyText(array $status): string
    {
        $type = strtolower(trim((string) ($status['validation_type'] ?? '')));
        $grace = isset($status['offline_grace_days']) ? (int) $status['offline_grace_days'] : null;
        $labels = [
            'online' => 'Online activation',
            'offline' => 'Offline validation',
            'hibrida' => 'Hybrid validation',
            'hybrid' => 'Hybrid validation',
        ];

        $policy = $labels[$type] ?? 'Validation policy unavailable';

        if ($grace !== null && $grace > 0 && in_array($type, ['offline', 'hibrida', 'hybrid'], true)) {
            $policy .= ' + ' . $grace . 'd grace';
        }

        return $policy;
    }

    private static function smartVoiceSubscriptionText(array $status): string
    {
        $subscription = trim((string) ($status['subscription'] ?? ''));
        if ($subscription !== '') {
            return $subscription;
        }

        return 'Business';
    }

    private static function nsmSubscriptionText(array $status, string $quantityText): string
    {
        $subscription = trim((string) ($status['subscription'] ?? ''));
        if ($subscription !== '') {
            return $subscription;
        }

        $duration = self::licenseDurationLabel($status);

        return $quantityText !== 'No disponible'
            ? ' ' . $quantityText . ' socket' . ((int) $quantityText === 1 ? '' : 's') . ($duration !== '' ? ' / ' . $duration : '')
            : 'NSM Community Subscription';
    }

    private static function licenseDurationLabel(array $status): string
    {
        $days = 0;

        if (!empty($status['fecha_fin'])) {
            $referenceDate = $status['last_validation_at'] ?? ($status['last_sync_at'] ?? null);

            if (!empty($referenceDate)) {
                $days = self::daysBetweenDates((string) $referenceDate, (string) $status['fecha_fin']);
            }

            if ($days <= 0 && isset($status['days_remaining'])) {
                $days = (int) $status['days_remaining'];
            }

            if ($days <= 0) {
                $days = self::daysBetweenDates(date('Y-m-d'), (string) $status['fecha_fin']);
            }
        }

        if ($days <= 0 && !empty($status['fecha_creacion']) && !empty($status['fecha_fin'])) {
            $days = self::daysBetweenDates((string) $status['fecha_creacion'], (string) $status['fecha_fin']);
        }

        if ($days <= 0 && isset($status['license_days'])) {
            $days = (int) $status['license_days'];
        }

        if ($days <= 0) {
            return '';
        }

        if ($days < 30) {
            return $days . ' ' . ($days === 1 ? 'day' : 'days');
        }

        if ($days < 365) {
            $months = max(1, (int) round($days / 30));
            return $months . ' ' . ($months === 1 ? 'month' : 'months');
        }

        $years = max(1, (int) round($days / 365));
        return $years . ' ' . ($years === 1 ? 'year' : 'years');
    }

    private static function daysBetweenDates(string $startValue, string $endValue): int
    {
        try {
            $start = new \DateTimeImmutable((new \DateTimeImmutable($startValue))->format('Y-m-d'));
            $end = new \DateTimeImmutable((new \DateTimeImmutable($endValue))->format('Y-m-d'));

            return max(0, (int) $start->diff($end)->format('%r%a'));
        } catch (\Throwable $exception) {
            return 0;
        }
    }

    private static function maskMiddle(string $value): string
    {
        $value = trim($value);
        $length = strlen($value);

        if ($value === '' || $value === 'No disponible' || $length <= 12) {
            return $value;
        }

        return substr($value, 0, 6) . str_repeat('•', max(6, min(18, $length - 12))) . substr($value, -6);
    }

    private static function isWafStatus(array $status): bool
    {
        $slug = self::normalizeIdentifier((string) ($status['system_slug'] ?? ''));
        $name = self::normalizeIdentifier((string) ($status['system_name'] ?? ''));
        $licenseKey = self::normalizeIdentifier((string) ($status['license_key'] ?? ''));
        $installationId = self::normalizeIdentifier((string) ($status['installation_id'] ?? ''));

        return $slug === 'waf'
            || $name === 'waf'
            || strpos($licenseKey, 'waf') !== false
            || strpos($licenseKey, 'licwa') !== false
            || strpos($installationId, 'waf') !== false;
    }

    private static function isSmartVoiceStatus(array $status): bool
    {
        $slug = self::normalizeIdentifier((string) ($status['system_slug'] ?? ''));
        $name = self::normalizeIdentifier((string) ($status['system_name'] ?? ''));
        $licenseKey = self::normalizeIdentifier((string) ($status['license_key'] ?? ''));
        $installationId = self::normalizeIdentifier((string) ($status['installation_id'] ?? ''));

        return $slug === 'smartvoice'
            || $slug === 'tarificador'
            || $name === 'smartvoice'
            || $name === 'tarificador'
            || strpos($licenseKey, 'smartvoice') !== false
            || strpos($licenseKey, 'licsma') !== false
            || strpos($installationId, 'smartvoice') !== false;
    }

    private static function isNsmStatus(array $status): bool
    {
        $slug = self::normalizeIdentifier((string) ($status['system_slug'] ?? ''));
        $name = self::normalizeIdentifier((string) ($status['system_name'] ?? ''));
        $licenseKey = self::normalizeIdentifier((string) ($status['license_key'] ?? ''));
        $installationId = self::normalizeIdentifier((string) ($status['installation_id'] ?? ''));

        return $slug === 'nsm'
            || $name === 'nsm'
            || strpos($licenseKey, 'nsm') !== false
            || strpos($installationId, 'nsm') !== false;
    }

    private static function isMailtrackingStatus(array $status): bool
    {
        $slug = self::normalizeIdentifier((string) ($status['system_slug'] ?? ''));
        $name = self::normalizeIdentifier((string) ($status['system_name'] ?? ''));
        $licenseKey = self::normalizeIdentifier((string) ($status['license_key'] ?? ''));
        $installationId = self::normalizeIdentifier((string) ($status['installation_id'] ?? ''));

        return $slug === 'mailtracking'
            || $name === 'mailtracking'
            || strpos($licenseKey, 'mailtracking') !== false
            || strpos($installationId, 'mailtracking') !== false;
    }

    private static function normalizeIdentifier(string $value): string
    {
        return preg_replace('/[^a-z0-9]+/', '', strtolower(trim($value))) ?? '';
    }

    private static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
