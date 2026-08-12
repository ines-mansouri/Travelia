<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $ref }}</title>
    <style>
        @page { margin: 32px; }
        body { font-family: 'DejaVu Sans', sans-serif; color: #1a2e2b; font-size: 12px; line-height: 1.5; }
        h1, h2, h3, h4 { margin: 0 0 4px; font-family: 'DejaVu Sans', sans-serif; font-weight: 700; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 6px 8px; text-align: left; }
        .brand-header { text-align: center; padding: 20px 0 12px; border-bottom: 2px solid #2C514C; margin-bottom: 20px; }
        .brand-header h1 { font-size: 22px; color: #2C514C; margin: 0; }
        .brand-header p { font-size: 10px; color: #7f9c86; margin: 2px 0 0; }
        .invoice-title { text-align: center; margin-bottom: 20px; }
        .invoice-title h2 { font-size: 16px; color: #2C514C; }
        .invoice-title p { font-size: 10px; color: #5b6f6a; margin: 0; }
        .section { margin-bottom: 20px; }
        .section h3 { font-size: 13px; color: #2C514C; border-bottom: 1px solid #dce5e1; padding-bottom: 4px; margin-bottom: 8px; }
        .meta-table td { padding: 3px 8px; font-size: 11px; }
        .meta-table td:first-child { color: #5b6f6a; width: 140px; }
        .meta-table td:last-child { font-weight: 600; }
        .flight-route { background: #f4f7f5; border-radius: 6px; padding: 10px 14px; margin: 8px 0; }
        .flight-route td { text-align: center; vertical-align: middle; font-size: 11px; }
        .flight-route .time { font-size: 16px; font-weight: 700; color: #2C514C; }
        .flight-route .airport { font-size: 10px; color: #5b6f6a; }
        .flight-route .dur { font-size: 9px; color: #8a9e98; }
        .price-table td { padding: 5px 8px; font-size: 11px; }
        .price-table .label { color: #5b6f6a; }
        .price-table .final { font-size: 16px; font-weight: 700; color: #2C514C; }
        .price-table .strike { text-decoration: line-through; color: #8a9e98; font-size: 10px; }
        .footer { text-align: center; border-top: 1px solid #dce5e1; padding-top: 12px; margin-top: 20px; font-size: 9px; color: #8a9e98; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 3px; font-size: 9px; font-weight: 700; }
        .badge-paid { background: #198754; color: #fff; }
        .badge-refunding { background: #ffc107; color: #1a2e2b; }
        .badge-cancelled { background: #dc3545; color: #fff; }
    </style>
</head>
<body>

    {{-- ─── Brand header ─────────────────────────────────────────────── --}}
    <div class="brand-header">
        <h1>Travelia</h1>
        <p>Premium Travel Experiences</p>
    </div>

    {{-- ─── Invoice title ────────────────────────────────────────────── --}}
    <div class="invoice-title">
        <h2>Invoice</h2>
        <p>{{ $ref }}</p>
    </div>

    {{-- ─── Flight Itinerary ─────────────────────────────────────────── --}}
    <div class="section">
        <h3>Flight Itinerary</h3>

        @php
            $details = $booking->flight_details ?? [];
            $leg     = $details['legs'][0] ?? $details;
            $originCode      = $leg['originCode']      ?? $leg['origin']      ?? '—';
            $originName      = $leg['originName']      ?? $originCode;
            $destinationCode = $leg['destinationCode'] ?? $leg['destination'] ?? '—';
            $destinationName = $leg['destinationName'] ?? $destinationCode;
            $carrier         = $leg['carrier']         ?? $details['carrier'] ?? '—';
            $flightNumber    = $leg['flightNumber']    ?? $leg['flight_number'] ?? '';
            $departure       = $leg['departure']       ?? null;
            $arrival         = $leg['arrival']         ?? null;
            $duration        = $leg['duration']        ?? 0;
            $stops           = $leg['stops']           ?? 0;
            $depDt = $departure ? \Carbon\Carbon::parse($departure) : null;
        @endphp

        <table class="flight-route">
            <tr>
                <td style="width:33%;">
                    <div class="time">{{ $depDt ? $depDt->format('H:i') : '--:--' }}</div>
                    <div class="airport">{{ $originName }} ({{ $originCode }})</div>
                </td>
                <td style="width:33%;">
                    <div class="dur">
                        {{ $duration ? ceil($duration/60) . 'h ' . ($duration % 60) . 'm' : '' }}
                        &middot; {{ $stops === 0 ? 'Direct' : $stops . ' stop' . ($stops > 1 ? 's' : '') }}
                    </div>
                    <div style="border-top:1px dashed #cbd6d1;margin:4px 20px;"></div>
                </td>
                <td style="width:33%;">
                    <div class="time">{{ $arrival ? \Carbon\Carbon::parse($arrival)->format('H:i') : '--:--' }}</div>
                    <div class="airport">{{ $destinationName }} ({{ $destinationCode }})</div>
                </td>
            </tr>
        </table>

        <table class="meta-table">
            <tr><td>Airline</td><td>{{ $carrier }}@if($flightNumber) ({{ $flightNumber }})@endif</td></tr>
            <tr><td>Flight Date</td><td>{{ $depDt ? $depDt->format('D, M d, Y') : '—' }}</td></tr>
            <tr><td>Booking Reference</td><td>#{{ $booking->id }}</td></tr>
            <tr><td>Status</td>
                <td>
                    <span class="badge badge-{{ $booking->status }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    {{-- ─── Transaction Details ──────────────────────────────────────── --}}
    <div class="section">
        <h3>Transaction Details</h3>
        <table class="meta-table">
            <tr><td>Invoice Number</td><td>{{ $ref }}</td></tr>
            <tr><td>Transaction ID</td><td>{{ $booking->stripe_payment_intent_id ?? '—' }}</td></tr>
            <tr><td>Customer</td><td>{{ $booking->customer_name ?? $booking->customer_email ?? '—' }}</td></tr>
            <tr><td>Email</td><td>{{ $booking->customer_email ?? '—' }}</td></tr>
            <tr><td>Issued</td><td>{{ now()->format('M d, Y H:i') }}</td></tr>
        </table>
    </div>

    {{-- ─── Price Breakdown ──────────────────────────────────────────── --}}
    <div class="section">
        <h3>Price Breakdown</h3>
        <table class="price-table">
            <tr>
                <td class="label">Flight — {{ $originCode }} &rarr; {{ $destinationCode }}</td>
                <td style="text-align:right;">
                    {{ config("currencies.symbols.{$booking->currency_code}", $booking->currency_code) }}{{ number_format($booking->converted_price, 2) }}
                </td>
            </tr>
            <tr><td colspan="2" style="border-top:1px solid #dce5e1;padding:0;"></td></tr>
            <tr>
                <td class="label">Total Charged ({{ $booking->currency_code }})</td>
                <td style="text-align:right;" class="final">
                    {{ config("currencies.symbols.{$booking->currency_code}", $booking->currency_code) }}{{ number_format($booking->converted_price, 2) }}
                </td>
            </tr>
            <tr>
                <td class="label">Original Amount (USD)</td>
                <td style="text-align:right;" class="strike">${{ number_format($booking->original_price_usd, 2) }}</td>
            </tr>
        </table>
    </div>

    {{-- ─── Footer ───────────────────────────────────────────────────── --}}
    <div class="footer">
        <p>Travelia &mdash; Thank you for travelling with us.</p>
        <p>This is a computer-generated invoice. No signature is required.</p>
    </div>

</body>
</html>
