<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f7f6;font-family:'Inter',Helvetica,Arial,sans-serif;">

    {{-- ─── Outer wrapper ─────────────────────────────────────────────── --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f5f7f6;">
        <tr>
            <td align="center" style="padding:32px 16px;">

                {{-- ─── Card ──────────────────────────────────────────── --}}
                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                       style="max-width:600px;width:100%;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.06);">

                    {{-- ─── Header / Brand bar ────────────────────────── --}}
                    <tr>
                        <td style="background:#2C514C;padding:24px 32px;text-align:center;">
                            <h1 style="margin:0;color:#ffffff;font-family:'Playfair Display',Georgia,serif;font-size:24px;font-weight:700;letter-spacing:0.5px;">
                                ✈ Travelia
                            </h1>
                        </td>
                    </tr>

                    {{-- ─── Greeting ───────────────────────────────────── --}}
                    <tr>
                        <td style="padding:32px 32px 16px;text-align:center;">
                            <div style="width:64px;height:64px;border-radius:50%;background:#e8f5e9;display:inline-flex;align-items:center;justify-content:center;margin-bottom:16px;">
                                <span style="font-size:28px;">&#10003;</span>
                            </div>
                            <h2 style="margin:0 0 4px;color:#1a2e2b;font-family:'Playfair Display',Georgia,serif;font-size:22px;font-weight:700;">
                                Your Flight is Confirmed!
                            </h2>
                            <p style="margin:0;color:#5b6f6a;font-size:15px;line-height:1.5;">
                                Thank you for booking with Travelia, <strong>{{ $booking->customer_name ?? 'Valued Traveler' }}</strong>.
                                Your itinerary is ready below.
                            </p>
                        </td>
                    </tr>

                    {{-- ─── Divider ────────────────────────────────────── --}}
                    <tr><td style="padding:0 32px;"><hr style="border:none;border-top:1px solid #e5eae8;margin:0;"></td></tr>

                    {{-- ─── Flight Itinerary ───────────────────────────── --}}
                    <tr>
                        <td style="padding:24px 32px;">
                            <h3 style="margin:0 0 16px;color:#2C514C;font-family:'Playfair Display',Georgia,serif;font-size:17px;font-weight:700;">
                                &#9992; Flight Itinerary
                            </h3>

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
                            @endphp

                            {{-- Route line --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                   style="background:#f8faf9;border-radius:10px;padding:16px;">
                                <tr>
                                    <td align="center" style="padding:0 8px;">
                                        <div style="font-size:18px;font-weight:700;color:#2C514C;">
                                            {{ $departure ? date('H:i', strtotime($departure)) : '--:--' }}
                                        </div>
                                        <div style="font-size:13px;color:#5b6f6a;margin-top:2px;">
                                            {{ $originName }} ({{ $originCode }})
                                        </div>
                                    </td>
                                    <td align="center" style="padding:0 12px;vertical-align:middle;">
                                        <div style="font-size:11px;color:#8a9e98;margin-bottom:2px;">
                                            {{ $duration ? ceil($duration/60) . 'h ' . ($duration % 60) . 'm' : '' }}
                                            &middot; {{ $stops === 0 ? 'Direct' : $stops . ' stop' . ($stops > 1 ? 's' : '') }}
                                        </div>
                                        <div style="border-top:2px dashed #cbd6d1;position:relative;height:2px;width:40px;margin:0 auto;">
                                            <span style="position:absolute;top:-7px;left:50%;margin-left:-6px;font-size:12px;">&#9992;</span>
                                        </div>
                                    </td>
                                    <td align="center" style="padding:0 8px;">
                                        <div style="font-size:18px;font-weight:700;color:#2C514C;">
                                            {{ $arrival ? date('H:i', strtotime($arrival)) : '--:--' }}
                                        </div>
                                        <div style="font-size:13px;color:#5b6f6a;margin-top:2px;">
                                            {{ $destinationName }} ({{ $destinationCode }})
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            {{-- Detail rows --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:12px;">
                                <tr>
                                    <td style="padding:4px 0;color:#5b6f6a;font-size:14px;width:50%;">Airline</td>
                                    <td style="padding:4px 0;color:#1a2e2b;font-size:14px;font-weight:600;width:50%;text-align:right;">
                                        {{ $carrier }}@if($flightNumber) <span style="color:#8a9e98;font-weight:400;">({{ $flightNumber }})</span>@endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0;color:#5b6f6a;font-size:14px;">Flight Date</td>
                                    <td style="padding:4px 0;color:#1a2e2b;font-size:14px;font-weight:600;text-align:right;">
                                        {{ $departure ? date('D, M d, Y', strtotime($departure)) : '—' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0;color:#5b6f6a;font-size:14px;">Booking Ref.</td>
                                    <td style="padding:4px 0;color:#1a2e2b;font-size:14px;font-weight:600;text-align:right;">
                                        #{{ $booking->id }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- ─── Divider ────────────────────────────────────── --}}
                    <tr><td style="padding:0 32px;"><hr style="border:none;border-top:1px solid #e5eae8;margin:0;"></td></tr>

                    {{-- ─── Financial Receipt ──────────────────────────── --}}
                    <tr>
                        <td style="padding:24px 32px;">
                            <h3 style="margin:0 0 16px;color:#2C514C;font-family:'Playfair Display',Georgia,serif;font-size:17px;font-weight:700;">
                                &#128196; Receipt Summary
                            </h3>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding:4px 0;color:#5b6f6a;font-size:14px;">Route</td>
                                    <td style="padding:4px 0;color:#1a2e2b;font-size:14px;font-weight:600;text-align:right;">
                                        {{ $originCode }} &rarr; {{ $destinationCode }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:4px 0;color:#5b6f6a;font-size:14px;">Transaction ID</td>
                                    <td style="padding:4px 0;color:#1a2e2b;font-size:14px;text-align:right;word-break:break-all;">
                                        <code style="background:#f5f7f6;padding:1px 6px;border-radius:4px;font-size:12px;">
                                            {{ $booking->stripe_payment_intent_id ?? '—' }}
                                        </code>
                                    </td>
                                </tr>
                                <tr><td colspan="2" style="padding:8px 0;"><hr style="border:none;border-top:1px solid #e5eae8;margin:0;"></td></tr>

                                {{-- Charged amount (user's currency) --}}
                                <tr>
                                    <td style="padding:4px 0;color:#1a2e2b;font-size:15px;font-weight:600;">
                                        Amount Charged
                                        <span style="color:#8a9e98;font-weight:400;font-size:13px;">
                                            ({{ $booking->currency_code }})
                                        </span>
                                    </td>
                                    <td style="padding:4px 0;color:#2C514C;font-size:22px;font-weight:700;text-align:right;">
                                        {{ config("currencies.symbols.{$booking->currency_code}", $booking->currency_code) }}
                                        {{ number_format($booking->converted_price, 2) }}
                                    </td>
                                </tr>

                                {{-- Original USD cross-reference --}}
                                <tr>
                                    <td style="padding:2px 0;color:#8a9e98;font-size:13px;">Original amount (USD)</td>
                                    <td style="padding:2px 0;color:#8a9e98;font-size:13px;text-align:right;text-decoration:line-through;">
                                        ${{ number_format($booking->original_price_usd, 2) }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- ─── Divider ────────────────────────────────────── --}}
                    <tr><td style="padding:0 32px;"><hr style="border:none;border-top:1px solid #e5eae8;margin:0;"></td></tr>

                    {{-- ─── Footer ─────────────────────────────────────── --}}
                    <tr>
                        <td style="padding:20px 32px;text-align:center;">
                            <p style="margin:0 0 8px;color:#8a9e98;font-size:13px;line-height:1.4;">
                                Need help? Reply to this email or contact our support team.
                            </p>
                            <p style="margin:0;color:#8a9e98;font-size:12px;">
                                &copy; {{ date('Y') }} Travelia. All rights reserved.
                            </p>
                            <div style="margin-top:12px;">
                                <a href="{{ route('flights') }}"
                                   style="display:inline-block;padding:10px 24px;background:#2C514C;color:#ffffff;text-decoration:none;border-radius:8px;font-size:14px;font-weight:600;">
                                    View My Bookings
                                </a>
                            </div>
                        </td>
                    </tr>

                </table>

                {{-- ─── Footnote ───────────────────────────────────────--}}
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;margin-top:12px;">
                    <tr>
                        <td style="padding:8px 16px;text-align:center;color:#a6b8b1;font-size:11px;">
                            This is an automated confirmation message from Travelia.
                            Please do not reply directly to this email.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>
