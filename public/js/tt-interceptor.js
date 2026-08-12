(function () {
    'use strict';

    /* ── Toast System ────────────────────────────────────────────── */
    var Toast = {
        container: null,
        init: function () {
            if (this.container) return;
            this.container = document.createElement('div');
            this.container.id = 'tt-toast-container';
            this.container.style.cssText =
                'position:fixed;top:24px;right:24px;z-index:9999;' +
                'display:flex;flex-direction:column;gap:10px;' +
                'pointer-events:none;max-width:420px;width:100%;';
            document.body.appendChild(this.container);
        },
        show: function (message, type, duration) {
            if (!message) return;
            type = type || 'error';
            duration = duration || 5000;
            this.init();
            var colors = {
                success: { bg: '#198754', icon: 'fa-check-circle' },
                error:   { bg: '#dc3545', icon: 'fa-exclamation-circle' },
                warning: { bg: '#F7B041', icon: 'fa-exclamation-triangle' },
                info:    { bg: '#2C514C', icon: 'fa-info-circle' },
            };
            var cfg = colors[type] || colors.info;
            var el = document.createElement('div');
            el.className = 'tt-toast';
            el.style.cssText =
                'background:' + cfg.bg + ';color:#fff;padding:14px 18px;' +
                'border-radius:12px;font-size:14px;display:flex;' +
                'align-items:flex-start;gap:12px;' +
                'box-shadow:0 8px 24px rgba(0,0,0,0.18);' +
                'pointer-events:auto;animation:ttToastIn 0.35s ease forwards;' +
                'max-width:100%;word-break:break-word;';
            el.innerHTML = '<i class="fas ' + cfg.icon +
                '" style="font-size:16px;margin-top:2px;flex-shrink:0;"></i>' +
                '<span>' + escapeHtml(message) + '</span>';
            this.container.appendChild(el);
            setTimeout(function () {
                el.style.animation = 'ttToastOut 0.3s ease forwards';
                setTimeout(function () { if (el.parentNode) el.remove(); }, 300);
            }, duration);
        },
    };

    function escapeHtml(str) {
        var d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    /* ── Offline Banner ──────────────────────────────────────────── */
    var offlineBanner = null;

    function createOfflineBanner() {
        if (offlineBanner) return;
        offlineBanner = document.createElement('div');
        offlineBanner.id = 'tt-offline-banner';
        offlineBanner.style.cssText =
            'position:fixed;top:0;left:0;right:0;z-index:10000;' +
            'background:#dc3545;color:#fff;text-align:center;' +
            'padding:10px 16px;font-size:14px;font-weight:600;' +
            'font-family:Inter,system-ui,sans-serif;' +
            'transform:translateY(-100%);transition:transform 0.35s ease;' +
            'box-shadow:0 4px 12px rgba(0,0,0,0.15);' +
            'display:flex;align-items:center;justify-content:center;gap:8px;';
        offlineBanner.innerHTML =
            '<i class="fas fa-wifi-slash"></i> ' +
            'You are currently offline. Some features may be unavailable.';
        document.body.insertBefore(offlineBanner, document.body.firstChild);
        requestAnimationFrame(function () {
            offlineBanner.style.transform = 'translateY(0)';
        });
    }

    function removeOfflineBanner() {
        if (!offlineBanner) return;
        offlineBanner.style.transform = 'translateY(-100%)';
        setTimeout(function () {
            if (offlineBanner && offlineBanner.parentNode) {
                offlineBanner.remove();
            }
            offlineBanner = null;
        }, 350);
    }

    window.addEventListener('offline', createOfflineBanner);
    window.addEventListener('online', function () {
        removeOfflineBanner();
        Toast.show('Your internet connection has been restored.', 'success', 4000);
    });

    if (!navigator.onLine) {
        document.addEventListener('DOMContentLoaded', createOfflineBanner);
    }

    /* ── Fetch Interceptor ───────────────────────────────────────── */
    function getCsrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    /**
     * ttFetch(url, options, controls)
     *
     * @param {string} url
     * @param {object} options  Standard fetch options (method, headers, body, etc.)
     * @param {object} controls (optional)
     *   button     - HTMLElement or CSS selector for button to disable/restore
     *   buttonHtml - HTML to restore on button after request completes
     *   spinner    - HTMLElement or CSS selector for spinner to show/hide
     *   onError    - function(message, status) custom handler (runs after toast)
     *   showToast  - boolean (default true) whether to show toast on error
     *   noThrow    - boolean (default true) returns result object instead of throwing
     * @returns {{ success: boolean, data: any|null, status: number }}
     */
    window.ttFetch = async function (url, options, controls) {
        options = options || {};
        controls = controls || {};
        var btn = typeof controls.button === 'string'
            ? document.querySelector(controls.button)
            : controls.button;
        var spinner = typeof controls.spinner === 'string'
            ? document.querySelector(controls.spinner)
            : controls.spinner;
        var showToast = controls.showToast !== false;
        var restored = false;

        function restoreUI() {
            if (restored) return;
            restored = true;
            if (btn) {
                btn.disabled = false;
                if (controls.buttonHtml) btn.innerHTML = controls.buttonHtml;
            }
            if (spinner) spinner.style.display = 'none';
        }

        try {
            /* Auto-serialize JSON body and attach Content-Type */
            if (options.body && typeof options.body === 'object' &&
                !(options.body instanceof FormData) &&
                !(options.body instanceof URLSearchParams)) {
                var serialized = JSON.stringify(options.body);
                options.body = serialized;
                if (!options.headers) options.headers = {};
                if (!options.headers['Content-Type']) {
                    options.headers['Content-Type'] = 'application/json';
                }
            }

            /* Attach CSRF token for non-GET requests */
            var method = (options.method || 'GET').toUpperCase();
            if (method !== 'GET') {
                if (!options.headers) options.headers = {};
                if (!options.headers['X-CSRF-TOKEN']) {
                    options.headers['X-CSRF-TOKEN'] = getCsrfToken();
                }
            }

            /* Always request JSON */
            if (!options.headers) options.headers = {};
            if (!options.headers['Accept']) {
                options.headers['Accept'] = 'application/json';
            }

            var res = await fetch(url, options);
            var data;
            var contentType = (res.headers.get('content-type') || '').toLowerCase();

            if (contentType.includes('application/json')) {
                data = await res.json();
            } else if (contentType.includes('text/')) {
                data = await res.text();
            } else {
                data = null;
            }

            if (!res.ok) {
                restoreUI();
                var status = res.status;
                var message = '';

                if (status === 422 && data && data.errors) {
                    var keys = Object.keys(data.errors);
                    message = data.errors[keys[0]][0] ||
                        'Validation error. Please check your input.';
                } else if (status === 419) {
                    message = 'Your session has expired. Please refresh the page and try again.';
                } else if (status === 429) {
                    message = 'Too many requests. Please wait a moment and try again.';
                } else if (status >= 500) {
                    message = 'We are experiencing a temporary delay from ' +
                        'our travel partners. Please try again in a few moments.';
                } else {
                    message = (data && (data.message || data.error)) ||
                        'An unexpected error occurred.';
                }

                if (showToast) Toast.show(message, 'error');
                if (controls.onError) controls.onError(message, status);
                return { success: false, data: data, status: status };
            }

            restoreUI();
            return { success: true, data: data, status: res.status };

        } catch (err) {
            restoreUI();
            var netMsg = 'A network error occurred. Please check your connection and try again.';
            if (!navigator.onLine) {
                netMsg = 'You appear to be offline. Please check your internet connection.';
            }
            if (showToast) Toast.show(netMsg, 'error');
            if (controls.onError) controls.onError(netMsg, 0);
            return { success: false, data: null, status: 0 };
        }
    };

    /* Expose helpers globally */
    window.ttToast = Toast;
    window.ttCsrfToken = getCsrfToken;

    /* ── Inject keyframe animations ───────────────────────────────── */
    var styleEl = document.createElement('style');
    styleEl.textContent =
        '@keyframes ttToastIn {' +
        '  from { transform: translateX(100%); opacity: 0; }' +
        '  to   { transform: translateX(0); opacity: 1; }' +
        '}' +
        '@keyframes ttToastOut {' +
        '  from { transform: translateX(0); opacity: 1; }' +
        '  to   { transform: translateX(100%); opacity: 0; }' +
        '}';
    document.head.appendChild(styleEl);

})();
