@foreach ($flights as $flight)
<div class="tt-flight-card" data-aos="fade-up">
    <div class="row align-items-center">
        <div class="col-md-8">

            @foreach ($flight['legs'] ?? [] as $leg)
            <div class="tt-flight-leg">
                <div class="tt-flight-route">
                    {{-- Departure --}}
                    <div>
                        <div class="tt-flight-time">{{ \Carbon\Carbon::parse($leg['departure'])->format('H:i') }}</div>
                        <div class="tt-flight-airport">{{ $leg['originCode'] ?? $leg['origin'] ?? '' }}</div>
                    </div>

                    {{-- Flight line --}}
                    <div class="tt-flight-duration">
                        <div class="tt-flight-line">
                            <hr><i class="fas fa-plane" style="color:var(--tt-primary);"></i><hr>
                        </div>
                        <div>{{ $leg['duration'] ? $leg['duration'] . ' min' : '' }}</div>
                        <span class="flight-tag {{ ($leg['stops'] ?? 0) === 0 ? 'direct' : 'stops' }}">
                            {{ ($leg['stops'] ?? 0) === 0 ? 'Direct' : $leg['stops'] . ' stop' . (($leg['stops'] ?? 0) > 1 ? 's' : '') }}
                        </span>
                    </div>

                    {{-- Arrival --}}
                    <div>
                        <div class="tt-flight-time" style="text-align:right;">
                            {{ $leg['arrival'] ? \Carbon\Carbon::parse($leg['arrival'])->format('H:i') : '--:--' }}
                        </div>
                        <div class="tt-flight-airport" style="text-align:right;">
                            {{ $leg['destinationCode'] ?? $leg['destination'] ?? '' }}
                        </div>
                    </div>
                </div>

                @if(!empty($leg['carrier']))
                <div class="tt-flight-carrier">
                    <i class="fas fa-building me-1"></i> {{ $leg['carrier'] }}
                </div>
                @endif
            </div>
            @endforeach

        </div>{{-- /.col-md-8 --}}

        {{-- Price column --}}
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="tt-flight-price">{{ $flight['formattedPrice'] ?? '—' }}</div>
            <small class="text-muted">per adult</small>

            {{-- Show original price if different from converted --}}
            @if(!empty($flight['originalPrice']) && $flight['originalPrice'] !== $flight['formattedPrice'])
            <div class="mt-1">
                <small class="text-muted" style="text-decoration:line-through;">
                    {{ $flight['originalPrice'] }}
                </small>
                @if(!empty($flight['exchangeRate']))
                <br><small class="text-muted">Rate: 1 USD = {{ number_format($flight['exchangeRate'], 4) }} {{ $currency ?? '' }}</small>
                @endif
            </div>
            @endif

            @php
                $firstLeg = $flight['legs'][0] ?? [];
                $origin = $firstLeg['originCode'] ?? '';
                $dest   = $firstLeg['destinationCode'] ?? '';
                $date   = isset($firstLeg['departure']) ? \Carbon\Carbon::parse($firstLeg['departure'])->format('Y-m-d') : '';
                $isReturn = count($flight['legs'] ?? []) > 1;
                $skyUrl = "https://www.skyscanner.com/transport/flights/{$origin}/{$dest}/{$date}/?adultsv2=1&cabinclass=economy&rtn=" . ($isReturn ? '1' : '0');
            @endphp
            <div class="mt-2 d-flex gap-2 flex-wrap">
                <button type="button"
                        class="btn-tt-accent btn-book-flight"
                        data-flight='{{ json_encode($flight) }}'>
                    <i class="fas fa-lock me-1"></i> Book Now
                </button>

            </div>
        </div>{{-- /.col-md-4 --}}
    </div>{{-- /.row --}}
</div>{{-- /.tt-flight-card --}}
@endforeach
