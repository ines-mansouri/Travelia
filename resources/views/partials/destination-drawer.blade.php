{{-- Destination Details Slide-Over Drawer - Travelia --}}
<style>
    /* ── Backdrop ─────────────────────────────────────────────── */
    .tt-drawer-backdrop {
        position: fixed;
        inset: 0;
        z-index: 1085;
        background: rgba(13, 21, 19, .48);
        -webkit-backdrop-filter: blur(3px);
        backdrop-filter: blur(3px);
        opacity: 0;
        visibility: hidden;
        transition: opacity .35s ease, visibility .35s ease;
    }
    .tt-drawer-backdrop.is-open { opacity: 1; visibility: visible; }

    /* ── Drawer shell ─────────────────────────────────────────── */
    .tt-drawer {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 1090;
        width: 100%;
        max-width: 600px;
        pointer-events: none;
    }
    .tt-drawer-panel {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        background: #f4f5f2;
        box-shadow: -20px 0 60px rgba(0, 0, 0, .22);
        transform: translateX(105%);
        transition: transform .5s cubic-bezier(.22, 1, .36, 1);
        pointer-events: auto;
        outline: none;
    }
    .tt-drawer.is-open .tt-drawer-panel { transform: translateX(0); }

    [dir="rtl"] .tt-drawer { right: auto; left: 0; }
    [dir="rtl"] .tt-drawer-panel { transform: translateX(-105%); box-shadow: 20px 0 60px rgba(0, 0, 0, .22); }
    [dir="rtl"] .tt-drawer.is-open .tt-drawer-panel { transform: translateX(0); }

    .tt-drawer-close {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 6;
        width: 42px;
        height: 42px;
        border: none;
        border-radius: 50%;
        background: #ffffff;
        color: var(--tt-dark, #1a1a2e);
        font-size: 1.05rem;
        cursor: pointer;
        display: grid;
        place-items: center;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .2);
        transition: background .25s ease, color .25s ease, transform .25s ease;
    }
    .tt-drawer-close:hover { background: var(--tt-primary, #2C514C); color: #fff; transform: rotate(90deg); }
    [dir="rtl"] .tt-drawer-close { right: auto; left: 16px; }

    .tt-drawer-content {
        flex: 1;
        min-height: 0;
        overflow-y: auto;
        overscroll-behavior: contain;
        -webkit-overflow-scrolling: touch;
    }

    body.tt-drawer-open { overflow: hidden; }

    /* ── Loader / error states ────────────────────────────────── */
    .tt-drawer-loader {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        min-height: 60vh;
        color: var(--tt-text-muted, #6b7280);
        font-family: var(--tt-font, sans-serif);
        font-size: .9rem;
    }
    .tt-drawer-spinner {
        width: 46px;
        height: 46px;
        border: 4px solid var(--tt-primary-light, #e6efe9);
        border-top-color: var(--tt-primary, #2C514C);
        border-radius: 50%;
        animation: ttDrawerSpin .8s linear infinite;
    }
    .tt-drawer-err {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--tt-accent-light, #fef4e0);
        color: var(--tt-accent-dark, #d48d20);
        font-size: 1.5rem;
        display: grid;
        place-items: center;
    }
    @keyframes ttDrawerSpin { to { transform: rotate(360deg); } }

    /* ── Hero image ───────────────────────────────────────────── */
    .tt-drawer-hero {
        position: relative;
        height: 265px;
        flex-shrink: 0;
        overflow: hidden;
        border-radius: 0 0 24px 24px;
    }
    .tt-drawer-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .tt-drawer-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(10, 20, 18, .58) 0%, rgba(10, 20, 18, 0) 62%);
    }
    .tt-drawer-badge {
        position: absolute;
        left: 18px;
        bottom: 16px;
        z-index: 3;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .5rem 1rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, .94);
        color: var(--tt-primary-dark, #1a3a36);
        font-family: var(--tt-font, sans-serif);
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .18);
    }
    [dir="rtl"] .tt-drawer-badge { left: auto; right: 18px; }

    /* ── Body ─────────────────────────────────────────────────── */
    .tt-drawer-body {
        padding: 1.5rem 1.5rem 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
    }
    .tt-drawer-title {
        font-family: var(--tt-font-display, 'Playfair Display', Georgia, serif);
        font-weight: 700;
        font-size: 1.9rem;
        line-height: 1.12;
        color: var(--tt-dark, #1a1a2e);
        margin: 0 0 .6rem;
    }
    .tt-drawer-meta { display: flex; flex-wrap: wrap; gap: .55rem; }
    .tt-drawer-pill {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .4rem .85rem;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid var(--tt-border, #e5e7eb);
        color: var(--tt-text, #3a3a4a);
        font-family: var(--tt-font, sans-serif);
        font-size: .82rem;
        font-weight: 500;
    }
    .tt-drawer-pill i { color: var(--tt-primary, #2C514C); }

    /* ── Price + rating bar ───────────────────────────────────── */
    .tt-drawer-pricerow {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.05rem 1.25rem;
        border-radius: 16px;
        background: linear-gradient(135deg, #1F3D39, #2C4A43);
        color: #fff;
    }
    .tt-drawer-price .label {
        display: block;
        font-family: var(--tt-font, sans-serif);
        font-size: .68rem;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: #c7d3cd;
        margin-bottom: .15rem;
    }
    .tt-drawer-price .value {
        font-family: var(--tt-font-display, 'Playfair Display', Georgia, serif);
        font-size: 1.9rem;
        font-weight: 700;
        line-height: 1;
    }
    .tt-drawer-price .per { font-size: .78rem; color: #c7d3cd; }
    .tt-drawer-stars {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: .25rem;
        flex-shrink: 0;
    }
    .tt-drawer-star-icons { display: flex; gap: .15rem; }
    .tt-drawer-star-icons i { color: var(--tt-accent, #F7B041); font-size: .85rem; }
    .tt-drawer-star-icons .tt-star-off { color: rgba(255, 255, 255, .32); }
    .tt-drawer-stars .count { font-family: var(--tt-font, sans-serif); font-size: .82rem; color: #c7d3cd; }

    /* ── Quick info 2x2 grid ──────────────────────────────────── */
    .tt-drawer-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: .8rem;
    }
    .tt-drawer-cell {
        display: flex;
        align-items: center;
        gap: .85rem;
        padding: .9rem 1rem;
        background: #ffffff;
        border: 1px solid var(--tt-border, #e5e7eb);
        border-radius: 14px;
    }
    .tt-drawer-cell-icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: var(--tt-primary-light, #e6efe9);
        color: var(--tt-primary, #2C514C);
        font-size: 1.05rem;
    }
    .tt-drawer-cell .lbl {
        font-family: var(--tt-font, sans-serif);
        font-size: .66rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--tt-text-muted, #6b7280);
        line-height: 1.2;
    }
    .tt-drawer-cell .val {
        font-family: var(--tt-font, sans-serif);
        font-size: .9rem;
        font-weight: 700;
        color: var(--tt-dark, #1a1a2e);
        line-height: 1.25;
    }

    /* ── Description ──────────────────────────────────────────── */
    .tt-drawer-divider { height: 1px; background: var(--tt-border, #e5e7eb); }
    .tt-drawer-section h3 {
        font-family: var(--tt-font-display, 'Playfair Display', Georgia, serif);
        font-weight: 700;
        font-size: 1.15rem;
        color: var(--tt-dark, #1a1a2e);
        margin: 0 0 .45rem;
    }
    .tt-drawer-section p {
        font-family: var(--tt-font, sans-serif);
        font-size: .92rem;
        line-height: 1.7;
        color: var(--tt-text, #3a3a4a);
        margin: 0 0 .6rem;
        white-space: pre-line;
    }
    .tt-drawer-section p:last-child { margin-bottom: 0; }

    /* ── Booking widget ───────────────────────────────────────── */
    .tt-booking-card {
        position: sticky;
        bottom: 0;
        margin-top: auto;
        padding: 1.15rem 1.25rem 1.05rem;
        background: #ffffff;
        border: 1px solid var(--tt-border, #e5e7eb);
        border-radius: 18px 18px 0 0;
        box-shadow: 0 -12px 32px rgba(0, 0, 0, .1);
    }
    .tt-booking-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        margin-bottom: .9rem;
    }
    .tt-booking-head h3 {
        font-family: var(--tt-font-display, 'Playfair Display', Georgia, serif);
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--tt-dark, #1a1a2e);
        margin: 0;
    }
    .tt-booking-head .price { font-family: var(--tt-font, sans-serif); font-weight: 800; color: var(--tt-primary, #2C514C); font-size: 1.05rem; }
    .tt-booking-head .price small { font-weight: 500; color: var(--tt-text-muted, #6b7280); font-size: .72rem; }
    .tt-booking-fields { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; margin-bottom: .8rem; }
    .tt-booking-field label {
        display: block;
        font-family: var(--tt-font, sans-serif);
        font-size: .66rem;
        font-weight: 600;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--tt-text-muted, #6b7280);
        margin-bottom: .35rem;
    }
    .tt-booking-field input[type="date"] {
        width: 100%;
        padding: .6rem .7rem;
        border: 1px solid var(--tt-border, #e5e7eb);
        border-radius: 10px;
        background: #fbfcfb;
        color: var(--tt-dark, #1a1a2e);
        font-family: var(--tt-font, sans-serif);
        font-size: .88rem;
        outline: none;
    }
    .tt-booking-field input[type="date"]:focus { border-color: var(--tt-primary, #2C514C); box-shadow: 0 0 0 3px rgba(44, 81, 76, .15); }
    .tt-stepper { display: flex; align-items: center; gap: .4rem; }
    .tt-step-btn {
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        border: 1px solid var(--tt-border, #e5e7eb);
        border-radius: 10px;
        background: #ffffff;
        color: var(--tt-primary, #2C514C);
        font-size: .8rem;
        cursor: pointer;
        display: grid;
        place-items: center;
        transition: background .2s ease, color .2s ease, border-color .2s ease;
    }
    .tt-step-btn:hover { background: var(--tt-primary, #2C514C); color: #fff; border-color: var(--tt-primary, #2C514C); }
    .tt-guest-count {
        width: 46px;
        text-align: center;
        border: none;
        background: transparent;
        color: var(--tt-dark, #1a1a2e);
        font-family: var(--tt-font, sans-serif);
        font-weight: 800;
        font-size: 1rem;
        -moz-appearance: textfield;
        appearance: textfield;
    }
    .tt-guest-count::-webkit-outer-spin-button,
    .tt-guest-count::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .tt-booking-total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .75rem 0;
        margin-bottom: .85rem;
        border-top: 1px dashed var(--tt-border, #e5e7eb);
        border-bottom: 1px dashed var(--tt-border, #e5e7eb);
        font-family: var(--tt-font, sans-serif);
        font-size: .92rem;
        color: var(--tt-text, #3a3a4a);
    }
    .tt-booking-total strong {
        font-family: var(--tt-font-display, 'Playfair Display', Georgia, serif);
        font-size: 1.35rem;
        color: var(--tt-dark, #1a1a2e);
    }
    .tt-book-btn {
        width: 100%;
        padding: .95rem 1.25rem;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #1F3D39, #2C4A43);
        color: #fff;
        font-family: var(--tt-font, sans-serif);
        font-size: .95rem;
        font-weight: 700;
        letter-spacing: .02em;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .6rem;
        box-shadow: 0 10px 24px rgba(31, 61, 57, .28);
        transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
    }
    .tt-book-btn:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(31, 61, 57, .38); filter: brightness(1.08); }
    .tt-book-btn:active { transform: translateY(0); }
    .tt-booking-note {
        text-align: center;
        margin: .7rem 0 0;
        font-family: var(--tt-font, sans-serif);
        font-size: .72rem;
        color: var(--tt-text-muted, #6b7280);
    }

    @media (max-width: 575.98px) {
        .tt-drawer-hero { height: 220px; }
        .tt-drawer-title { font-size: 1.6rem; }
        .tt-booking-fields { grid-template-columns: 1fr; }
        .tt-drawer-body { padding: 1.25rem 1.1rem 1rem; }
    }
</style>

<div class="tt-drawer-backdrop" id="ttDrawerBackdrop" aria-hidden="true"></div>

<div class="tt-drawer" id="ttDrawer" role="dialog" aria-modal="true" aria-hidden="true" aria-label="Destination details">
    <div class="tt-drawer-panel" id="ttDrawerPanel" tabindex="-1">
        <button type="button" class="tt-drawer-close" id="ttDrawerClose" aria-label="Close destination details">
            <i class="fas fa-times"></i>
        </button>
        <div class="tt-drawer-content" id="ttDrawerContent"></div>
    </div>
</div>

<script>
(function () {
    'use strict';

    var drawer = document.getElementById('ttDrawer');
    var backdrop = document.getElementById('ttDrawerBackdrop');
    var content = document.getElementById('ttDrawerContent');
    var closeBtn = document.getElementById('ttDrawerClose');
    if (!drawer || !backdrop || !content || !closeBtn) return;

    var lastFocused = null;
    var currentId = null;

    function esc(s) {
        if (s == null) return '';
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function fmt(n) {
        return Number(n || 0).toLocaleString('en-US').replace(/,/g, '.');
    }

    function csrfToken() {
        var m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.getAttribute('content') : '';
    }

    function todayIso() {
        var d = new Date();
        var m = ('0' + (d.getMonth() + 1)).slice(-2);
        var day = ('0' + d.getDate()).slice(-2);
        return d.getFullYear() + '-' + m + '-' + day;
    }

    function stars(rating) {
        var r = Math.round(parseFloat(rating) || 0);
        var html = '';
        for (var i = 1; i <= 5; i++) {
            html += '<i class="fas fa-star' + (i <= r ? '' : ' tt-star-off') + '"></i>';
        }
        return html;
    }

    function loaderHtml() {
        return '<div class="tt-drawer-loader"><div class="tt-drawer-spinner"></div><span>Loading destination details&hellip;</span></div>';
    }

    function errorHtml() {
        return '<div class="tt-drawer-loader"><div class="tt-drawer-err"><i class="fas fa-exclamation-triangle"></i></div><span>Sorry, we couldn\'t load this destination right now.</span></div>';
    }

    function unit(symbol, price) {
        return esc(symbol) + fmt(price);
    }

    function bookingHtml(d) {
        var symbol = d.currency_symbol || '';
        var price = Number(d.price_numeric) || 0;
        var unitLabel = unit(symbol, price);
        return '' +
            '<div class="tt-booking-card">' +
                '<div class="tt-booking-head">' +
                    '<h3>Book this trip</h3>' +
                    '<span class="price">' + unitLabel + ' <small>/ person</small></span>' +
                '</div>' +
                '<div class="tt-booking-fields">' +
                    '<div class="tt-booking-field">' +
                        '<label for="ttDrawerDate">Travel date</label>' +
                        '<input type="date" id="ttDrawerDate" class="tt-booking-date" min="' + todayIso() + '">' +
                    '</div>' +
                    '<div class="tt-booking-field">' +
                        '<label>Guests</label>' +
                        '<div class="tt-stepper">' +
                            '<button type="button" class="tt-step-btn" data-dir="-1" aria-label="Fewer guests"><i class="fas fa-minus"></i></button>' +
                            '<input type="number" class="tt-guest-count" value="1" min="1" max="20" readonly aria-label="Guests">' +
                            '<button type="button" class="tt-step-btn" data-dir="1" aria-label="More guests"><i class="fas fa-plus"></i></button>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="tt-booking-total"><span>Total</span><strong class="tt-booking-total-value">' + unitLabel + '</strong></div>' +
                '<form method="POST" action="' + esc('/cart/add/' + d.id) + '" class="tt-booking-form">' +
                    '<input type="hidden" name="_token" value="' + esc(csrfToken()) + '">' +
                    '<input type="hidden" name="guests" class="tt-booking-guests" value="1">' +
                    '<input type="hidden" name="travel_date" class="tt-booking-date-field" value="">' +
                    '<button type="submit" class="tt-book-btn"><i class="fas fa-plane-departure"></i> Reserve Spot</button>' +
                '</form>' +
                '<p class="tt-booking-note"><i class="fas fa-shield-alt"></i> Free cancellation &middot; No booking fees</p>' +
            '</div>';
    }

    function renderDestination(d) {
        var symbol = d.currency_symbol || '';
        var price = Number(d.price_numeric) || 0;
        var rating = parseFloat(d.average_rating) || 0;
        var reviewWord = d.reviews_count === 1 ? 'review' : 'reviews';
        var ratingLine = rating
            ? Number(rating.toFixed(1)) + ' / 5 (' + d.reviews_count + ' ' + reviewWord + ')'
            : 'No reviews yet';

        content.innerHTML = '' +
            '<div class="tt-drawer-hero">' +
                '<img src="' + esc(d.image_url || '/images/destination-1.jpg') + '" alt="' + esc(d.title) + '">' +
                '<span class="tt-drawer-badge"><i class="fas fa-tag"></i>' + esc(d.category_name || 'Tour') + '</span>' +
            '</div>' +
            '<div class="tt-drawer-body">' +
                '<div>' +
                    '<h2 class="tt-drawer-title">' + esc(d.title) + '</h2>' +
                    '<div class="tt-drawer-meta">' +
                        '<span class="tt-drawer-pill"><i class="fas fa-map-marker-alt"></i>' + esc(d.title) + '</span>' +
                        '<span class="tt-drawer-pill"><i class="fas fa-clock"></i>' + esc(d.duration || 'Contact us') + '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="tt-drawer-pricerow">' +
                    '<div class="tt-drawer-price">' +
                        '<span class="label">Starting from</span>' +
                        '<span class="value">' + unit(symbol, price) + '</span> <span class="per">/ person</span>' +
                    '</div>' +
                    '<div class="tt-drawer-stars">' +
                        '<div class="tt-drawer-star-icons">' + stars(rating) + '</div>' +
                        '<span class="count">' + esc(ratingLine) + '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="tt-drawer-grid">' +
                    '<div class="tt-drawer-cell"><div class="tt-drawer-cell-icon"><i class="fas fa-clock"></i></div><div><div class="lbl">Duration</div><div class="val">' + esc(d.duration || 'Contact us') + '</div></div></div>' +
                    '<div class="tt-drawer-cell"><div class="tt-drawer-cell-icon"><i class="fas fa-users"></i></div><div><div class="lbl">Group Size</div><div class="val">' + esc(d.group_size || 'Flexible') + '</div></div></div>' +
                    '<div class="tt-drawer-cell"><div class="tt-drawer-cell-icon"><i class="fas fa-compass"></i></div><div><div class="lbl">Tour Type</div><div class="val">' + esc(d.tour_type || 'General') + '</div></div></div>' +
                    '<div class="tt-drawer-cell"><div class="tt-drawer-cell-icon"><i class="fas fa-star"></i></div><div><div class="lbl">Rating</div><div class="val">' + esc(rating ? Number(rating.toFixed(1)) + ' / 5' : 'No reviews yet') + '</div></div></div>' +
                '</div>' +
                '<div class="tt-drawer-divider"></div>' +
                '<div class="tt-drawer-section">' +
                    '<h3>About this destination</h3>' +
                    '<p>' + esc(d.description || '') + '</p>' +
                    (d.content ? '<p>' + esc(d.content) + '</p>' : '') +
                '</div>' +
            '</div>' +
            bookingHtml(d);

        wireBooking(d);
    }

    function wireBooking(d) {
        var count = content.querySelector('.tt-guest-count');
        var minus = content.querySelector('[data-dir="-1"]');
        var plus = content.querySelector('[data-dir="1"]');
        var total = content.querySelector('.tt-booking-total-value');
        var guestsInput = content.querySelector('.tt-booking-guests');
        var dateField = content.querySelector('.tt-booking-date-field');
        var dateInput = content.querySelector('.tt-booking-date');
        var price = Number(d.price_numeric) || 0;
        var symbol = d.currency_symbol || '';

        function update() {
            var guests = parseInt(count.value, 10) || 1;
            guests = Math.min(20, Math.max(1, guests));
            count.value = guests;
            if (guestsInput) guestsInput.value = guests;
            if (total) total.textContent = unit(symbol, price * guests);
        }

        if (minus) minus.addEventListener('click', function () { count.value = (parseInt(count.value, 10) || 1) - 1; update(); });
        if (plus) plus.addEventListener('click', function () { count.value = (parseInt(count.value, 10) || 1) + 1; update(); });
        if (dateInput) dateInput.addEventListener('change', function () { if (dateField) dateField.value = dateInput.value; });
        update();
    }

    function show() {
        lastFocused = document.activeElement;
        document.body.classList.add('tt-drawer-open');
        drawer.classList.add('is-open');
        backdrop.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
        backdrop.setAttribute('aria-hidden', 'false');
        setTimeout(function () { closeBtn.focus(); }, 60);
    }

    function close() {
        drawer.classList.remove('is-open');
        backdrop.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
        backdrop.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('tt-drawer-open');
        setTimeout(function () {
            if (!drawer.classList.contains('is-open') && content) {
                content.innerHTML = loaderHtml();
                currentId = null;
            }
        }, 500);
        if (lastFocused && lastFocused.focus) lastFocused.focus();
    }

    function openDestination(id) {
        if (currentId === String(id)) return;
        currentId = String(id);
        content.innerHTML = loaderHtml();
        show();

        var url = '/destinations/' + id + '/details';
        var req;
        if (window.ttFetch) {
            req = window.ttFetch(url, {}, { showToast: false });
        } else {
            req = fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            }).then(function (res) {
                if (!res.ok) throw new Error('load failed');
                return res.json();
            });
        }

        Promise.resolve(req).then(function (res) {
            if (res && res.success === false) throw new Error('load failed');
            var data = res && res.data ? res.data : res;
            if (data && data.destination) {
                renderDestination(data.destination);
            } else {
                content.innerHTML = errorHtml();
            }
        }).catch(function () {
            if (currentId === String(id)) content.innerHTML = errorHtml();
        });
    }

    closeBtn.addEventListener('click', close);
    backdrop.addEventListener('click', close);

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        if (drawer.classList.contains('is-open')) close();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Tab' || !drawer.classList.contains('is-open')) return;
        var focusables = drawer.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (!focusables.length) return;
        var first = focusables[0];
        var last = focusables[focusables.length - 1];
        if (e.shiftKey && document.activeElement === first) {
            e.preventDefault();
            last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
            e.preventDefault();
            first.focus();
        }
    });

    document.addEventListener('click', function (e) {
        if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        var target = e.target;
        if (!target || !target.closest) return;

        var m = null;
        var link = target.closest('a[href]');
        if (link && link.getAttribute('data-tt-drawer') !== 'false' && link.target !== '_blank') {
            var href = link.getAttribute('href') || '';
            if (href.charAt(0) !== '#') {
                m = href.match(/\/(?:destinations\/)+(\d+)/);
            }
        }

        if (!m && !target.closest('button, a, input, select, textarea, label')) {
            var card = target.closest('.tt-dest-card');
            if (card) {
                var cardLink = card.querySelector('a[href*="destinations/"]');
                if (cardLink) {
                    var ch = cardLink.getAttribute('href') || '';
                    m = ch.match(/\/(?:destinations\/)+(\d+)/);
                }
            }
        }

        if (m) {
            e.preventDefault();
            openDestination(m[1]);
        }
    });

    window.TraveliaDrawer = { open: openDestination, close: close };
})();
</script>
