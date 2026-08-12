<div>
    <div class="card shadow-sm border-0 mb-4" data-aos="fade-up">
        <div class="card-body p-4">
            <form wire:submit="search">
                <div class="row g-3">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-city me-1 text-primary"></i> City
                        </label>
                        <input type="text" class="form-control" wire:model.blur="city"
                               placeholder="e.g. Tunis, Sousse, Hammamet" required>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-calendar-check me-1 text-primary"></i> Check-in
                        </label>
                        <input type="date" class="form-control" wire:model="checkIn" required>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-calendar-times me-1 text-primary"></i> Check-out
                        </label>
                        <input type="date" class="form-control" wire:model="checkOut" required>
                    </div>
                    <div class="col-lg-1 col-md-3">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-user me-1 text-primary"></i> Guests
                        </label>
                        <select class="form-select form-select-sm" wire:model="guests">
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ $i == 2 ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-3">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-star me-1 text-warning"></i> Min Stars
                        </label>
                        <select class="form-select form-select-sm" wire:model="minStars">
                            <option value="">Any</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}+</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-12">
                        <label class="form-label text-muted small mb-1">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary flex-grow-1" wire:click="toggleFilters">
                                <i class="fas fa-filter me-1"></i> Filters
                            </button>
                            <button type="submit" class="btn btn-primary flex-grow-1" wire:loading.attr="disabled">
                                <span wire:loading.remove><i class="fas fa-search me-1"></i> Search</span>
                                <span wire:loading><span class="spinner-border spinner-border-sm me-1"></span> Searching</span>
                            </button>
                        </div>
                    </div>
                </div>

                @if($showFilters)
                <div class="row g-3 mt-3 pt-3 border-top" data-aos="fade-down">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-hotel me-1 text-primary"></i> Hotel Name
                        </label>
                        <input type="text" class="form-control" wire:model="name"
                               placeholder="Search by name...">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-flag me-1 text-primary"></i> Country
                        </label>
                        <input type="text" class="form-control" wire:model="country"
                               placeholder="e.g. TN, FR">
                    </div>
                    <div class="col-lg-1 col-md-3">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-star-half-alt me-1 text-warning"></i> Max Stars
                        </label>
                        <select class="form-select form-select-sm" wire:model="maxStars">
                            <option value="">Any</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }}-</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-dollar-sign me-1 text-success"></i> Min Price
                        </label>
                        <input type="number" class="form-control form-control-sm" wire:model="minPrice"
                               placeholder="Min price" min="0" step="1">
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-dollar-sign me-1 text-success"></i> Max Price
                        </label>
                        <input type="number" class="form-control form-control-sm" wire:model="maxPrice"
                               placeholder="Max price" min="0" step="1">
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <label class="form-label text-muted small mb-1">&nbsp;</label>
                        <button type="button" class="btn btn-outline-danger w-100" wire:click="resetFilters">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small mb-1">
                            <i class="fas fa-concierge-bell me-1 text-primary"></i> Amenities
                        </label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($this->availableAmenities as $amenity)
                                <label class="btn btn-sm btn-outline-secondary amenity-checkbox">
                                    <input type="checkbox" 
                                           value="{{ $amenity }}" 
                                           wire:model="selectedAmenities"
                                           class="d-none">
                                    <span>{{ $amenity }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>

    @if($searched)
    <div class="row g-4 mt-3">
        <div class="col-lg-7">
            @if($errorMessage)
                <div class="card border-0 shadow-sm text-center py-5" data-aos="fade-up">
                    <div class="mb-4">
                        <i class="fas fa-hotel fa-4x text-muted opacity-50"></i>
                    </div>
                    <h4 class="fw-semibold mb-2">No Hotels Found</h4>
                    <p class="text-muted">{{ $errorMessage }}</p>
                </div>
            @else
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="mb-1 fw-semibold">
                            <span class="text-primary">{{ $count }}</span> Hotel{{ $count !== 1 ? 's' : '' }} in <span class="text-dark">{{ $city }}</span>
                        </h5>
                        <p class="text-muted small mb-0">Showing search results</p>
                    </div>
                </div>

                <div wire:loading class="row g-4">
                    @for($i = 0; $i < 3; $i++)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="skeleton-loader" style="height: 200px;"></div>
                        </div>
                    </div>
                    @endfor
                </div>

                <div wire:loading.remove class="row g-4">
                    @foreach($hotels as $hotel)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm hotel-search-card">
                            <div class="row g-0">
                                <div class="col-md-5" style="min-height: 220px;">
                                    @if(count($hotel['images']) > 1)
                                    <div id="hotelCarousel{{ $hotel['id'] }}" class="carousel slide h-100" data-bs-ride="carousel">
                                        <div class="carousel-indicators">
                                            @foreach($hotel['images'] as $idx => $img)
                                            <button type="button" data-bs-target="#hotelCarousel{{ $hotel['id'] }}"
                                                    data-bs-slide-to="{{ $idx }}" {{ $idx === 0 ? 'class=active' : '' }}></button>
                                            @endforeach
                                        </div>
                                        <div class="carousel-inner">
                                            @foreach($hotel['images'] as $idx => $img)
                                            <div class="carousel-item {{ $idx === 0 ? 'active' : }}">
                                                <img src="{{ $img }}" alt="{{ $hotel['name'] }}" loading="lazy"
                                                     style="width: 100%; height: 100%; object-fit: cover;">
                                            </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button"
                                                data-bs-target="#hotelCarousel{{ $hotel['id'] }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                                data-bs-target="#hotelCarousel{{ $hotel['id'] }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    </div>
                                    @else
                                    <img src="{{ $hotel['thumbnail'] ?? $hotel['images'][0] ?? 'https://placehold.co/400x300?text=Hotel' }}"
                                         alt="{{ $hotel['name'] }}" style="width:100%;height:100%;object-fit:cover;">
                                    @endif
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title fw-bold mb-0">{{ $hotel['name'] }}</h5>
                                            <span class="badge bg-white text-dark shadow-sm">
                                                <i class="fas fa-star text-warning me-1"></i>{{ $hotel['stars'] }}
                                            </span>
                                        </div>
                                        <p class="text-muted small mb-3">
                                            <i class="fas fa-map-marker-alt me-1 text-danger"></i> 
                                            {{ $hotel['city'] }}, {{ $hotel['country'] }}
                                        </p>
                                        @if(count($hotel['amenities']) > 0)
                                        <div class="mb-3">
                                            @foreach(array_slice($hotel['amenities'], 0, 4) as $amenity)
                                                <span class="badge bg-light text-dark me-1 small">{{ $amenity }}</span>
                                            @endforeach
                                            @if(count($hotel['amenities']) > 4)
                                                <span class="badge bg-light text-dark small">+{{ count($hotel['amenities']) - 4 }}</span>
                                            @endif
                                        </div>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <div>
                                                <span class="h5 mb-0 text-primary fw-bold">{{ $hotel['formatted_price'] }}</span>
                                                <small class="text-muted">/ night</small>
                                            </div>
                                            <button class="btn btn-primary btn-sm">
                                                Book Now <i class="fas fa-arrow-right ms-1 small"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm overflow-hidden" style="position:sticky;top:24px;">
                <div class="card-header bg-transparent border-bottom-0 d-flex justify-content-between align-items-center px-3 py-2">
                    <small class="fw-semibold text-muted"><i class="fas fa-map-marker-alt me-1 text-primary"></i> Live Map</small>
                    <small class="text-muted">{{ $count }} hotels</small>
                </div>
                <div wire:ignore id="tt-hotel-live-map" style="height:600px;width:100%;z-index:1;"></div>
            </div>
        </div>
    </div>
    @endif

    <script>
    document.addEventListener('livewire:init', () => {
        let map = null;
        let markers = [];

        async function loadLeaflet() {
            if (window.L) return window.L;
            const mod = await import('leaflet');
            await import('leaflet/dist/leaflet.css');
            window.L = mod.default || mod;
            return window.L;
        }

        function initMap(L) {
            if (!map) {
                map = L.map('tt-hotel-live-map').setView([34.0, 9.0], 6);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);
            }
        }

        function updateMarkers(coords) {
            if (!map || !window.L) return;
            markers.forEach(m => map.removeLayer(m));
            markers = [];
            if (!coords?.length) return;

            const L = window.L;
            const bounds = [];
            coords.forEach(c => {
                const m = L.marker([c.lat, c.lng])
                    .addTo(map)
                    .bindPopup(`<b>${c.name}</b>`);
                markers.push(m);
                bounds.push([c.lat, c.lng]);
            });
            if (bounds.length > 1) map.fitBounds(bounds, { padding: [30, 30], maxZoom: 13 });
            else if (bounds.length === 1) map.setView(bounds[0], 13);
        }

        Livewire.hook('commit', ({ component, respond, succeed }) => {
            succeed(() => {
                if (component.name !== 'hotel-search') return;
                const coords = component.$wire.get('coordinates');
                if (coords?.length) {
                    loadLeaflet().then(L => {
                        initMap(L);
                        setTimeout(() => updateMarkers(coords), 50);
                    });
                }
            });
        });
    });
    </script>

    <style>
    .hotel-search-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hotel-search-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
    }

    .amenity-checkbox {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .amenity-checkbox input:checked + span {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }

    .skeleton-loader {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s infinite;
    }

    @keyframes skeleton-loading {
        0% {
            background-position: 200% 0;
        }
        100% {
            background-position: -200% 0;
        }
    }
</style>

	@include('partials.footer')
	</div>
