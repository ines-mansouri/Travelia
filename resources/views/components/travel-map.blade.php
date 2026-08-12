@props([
    'height' => '450px',
    'apiEndpoint' => '/api/v1/destinations/coordinates',
    'centerLat' => 34.0,
    'centerLng' => 9.0,
    'zoom' => 5,
    'showFilter' => true,
])

@php
    $locale = app()->getLocale();
@endphp

<div class="tt-map-card" {{ $attributes->merge(['class' => 'mb-4']) }}>
    @if($showFilter)
    <div class="tt-map-header">
        <div class="row g-2 align-items-center">
            <div class="col-md">
                <div class="tt-map-title">
                    <i class="fas fa-map-marked-alt me-2" style="color:var(--tt-primary);"></i>
                    <span>{{ __('messages.map.explore_destinations') }}</span>
                </div>
            </div>
            <div class="col-md-auto">
                <div class="input-group input-group-sm" style="max-width:300px;">
                    <input type="text" id="tt-map-search" class="form-control"
                           placeholder="{{ __('messages.map.search_destinations') }}" aria-label="{{ __('messages.map.search_destinations') }}">
                    <button class="btn btn-outline-secondary" type="button" id="tt-map-search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div id="tt-live-map"
         style="height: {{ $height }}; border-radius: 0 0 12px 12px; {{ $showFilter ? '' : 'border-radius: 12px;' }}"
         data-center-lat="{{ $centerLat }}"
         data-center-lng="{{ $centerLng }}"
         data-zoom="{{ $zoom }}"
         data-api="{{ $apiEndpoint }}"
         data-locale="{{ $locale }}"
         data-text-view-details="{{ __('messages.map.view_details') }}"
         data-text-from="{{ __('messages.map.from') }}"
         data-text-no-results="{{ __('messages.map.no_destinations_found') }}"
         data-text-origin="{{ __('messages.map.origin') }}"
         data-text-destination="{{ __('messages.map.destination') }}"
         data-text-outbound="{{ __('messages.map.outbound') }}"
         data-text-return="{{ __('messages.map.return') }}"
         data-text-leg="{{ __('messages.map.leg') }}">
        <div class="tt-map-loading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">{{ __('messages.map.loading_map') }}</span>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin=""/>
<style>
.tt-map-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}
.tt-map-header {
    padding: 0.75rem 1.25rem;
    background: #fafafa;
    border-bottom: 1px solid #eee;
}
.tt-map-title {
    font-weight: 600;
    font-size: 0.95rem;
    color: #1a1a2e;
}
#tt-live-map {
    position: relative;
    background: #f0f0f0;
    min-height: 300px;
}
.tt-map-loading {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.6);
    z-index: 999;
    transition: opacity 0.3s;
}
.tt-map-loading.loaded {
    opacity: 0;
    pointer-events: none;
}
/* Custom marker popup styling */
.leaflet-popup-content-wrapper {
    border-radius: 10px !important;
    padding: 0 !important;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,0.15) !important;
}
.leaflet-popup-content {
    margin: 0 !important;
    min-width: 220px;
    font-family: 'Inter', system-ui, sans-serif;
}
.tt-map-popup {
    padding: 0;
}
.tt-map-popup img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    display: block;
}
.tt-map-popup-body {
    padding: 0.75rem 1rem;
}
.tt-map-popup-title {
    font-weight: 700;
    font-size: 0.95rem;
    color: #1a1a2e;
    margin-bottom: 0.25rem;
    line-height: 1.3;
}
.tt-map-popup-meta {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}
.tt-map-popup-price {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--tt-primary, #0d6efd);
    margin-bottom: 0.5rem;
}
.tt-map-popup-btn {
    display: inline-block;
    padding: 0.35rem 1rem;
    background: var(--tt-primary, #0d6efd);
    color: #fff;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.2s;
}
.tt-map-popup-btn:hover {
    background: #0b5ed7;
    color: #fff;
}
/* Leaflet custom marker */
.tt-marker-icon {
    background: var(--tt-primary, #0d6efd);
    color: #fff;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.25);
    cursor: pointer;
    transition: transform 0.15s;
}
.tt-marker-icon:hover {
    transform: scale(1.1);
}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
<script src="{{ asset('js/tt-map.js') }}"></script>
@endpush
