@extends('layouts.front')

@section('title', 'Hotels - Travelia')
@section('og_title', 'Hotels - Travelia')
@section('og_description', 'Browse our curated list of hotels worldwide.')

@section('page')
@include('partials.navbar')

<section class="tt-page-hero">
	<div class="tt-page-hero-bg" style="background-image: url('{{ asset('images/place-4.jpg') }}');"></div>
	<div class="container" data-aos="fade-up">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb justify-content-center">
				<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-1 small"></i>Home</a></li>
				<li class="breadcrumb-item active">Hotels</li>
			</ol>
		</nav>
		<h1 class="tt-page-title">Find Your Perfect <span class="accent">Stay</span></h1>
		<p class="tt-page-subtitle">
			From luxury resorts to boutique hotels, discover accommodations that make your trip unforgettable.
		</p>
	</div>
</section>

<section class="tt-section py-5">
	<div class="container">
		<!-- Search Form -->
		<div class="tt-search-bar mb-4" data-aos="fade-up">
			<form action="{{ route('hotels.index') }}" method="GET" id="hotel-search-form">
				<div class="row g-3">
					<div class="col-lg-3 col-md-6">
						<div class="tt-form-group">
							<label><i class="fas fa-hotel"></i> Hotel Name</label>
							<input type="text" name="name" class="tt-input" 
								   value="{{ request('name') }}"
								   placeholder="Search by name...">
						</div>
					</div>
					<div class="col-lg-2 col-md-6">
						<div class="tt-form-group">
							<label><i class="fas fa-city"></i> City</label>
							<input type="text" name="city" id="city-input" class="tt-input"
								   value="{{ request('city') }}"
								   placeholder="e.g. Tunis" autocomplete="off">
							<div id="city-suggestions" class="autocomplete-suggestions" style="display: none;"></div>
						</div>
					</div>
					<div class="col-lg-2 col-md-6">
						<div class="tt-form-group">
							<label><i class="fas fa-flag"></i> Country</label>
							<input type="text" name="country" id="country-input" class="tt-input"
								   value="{{ request('country') }}"
								   placeholder="e.g. Tunisia" autocomplete="off">
							<div id="country-suggestions" class="autocomplete-suggestions" style="display: none;"></div>
						</div>
					</div>
					<div class="col-lg-1 col-md-3">
						<div class="tt-form-group">
							<label><i class="fas fa-star"></i> Min Stars</label>
							<select name="min_stars" class="tt-select">
								<option value="">Any</option>
								@for($i = 1; $i <= 5; $i++)
									<option value="{{ $i }}" {{ request('min_stars') == $i ? 'selected' : '' }}>{{ $i }}+</option>
								@endfor
							</select>
						</div>
					</div>
					<div class="col-lg-1 col-md-3">
						<div class="tt-form-group">
							<label><i class="fas fa-star-half-alt"></i> Max Stars</label>
							<select name="max_stars" class="tt-select">
								<option value="">Any</option>
								@for($i = 1; $i <= 5; $i++)
									<option value="{{ $i }}" {{ request('max_stars') == $i ? 'selected' : '' }}>{{ $i }}-</option>
								@endfor
							</select>
						</div>
					</div>
					<div class="col-lg-1 col-md-3">
						<div class="tt-form-group">
							<label><i class="fas fa-dollar-sign"></i> Min Price</label>
							<input type="number" name="min_price" class="tt-input" 
								   value="{{ request('min_price') }}"
								   placeholder="Min" min="0">
						</div>
					</div>
					<div class="col-lg-1 col-md-3">
						<div class="tt-form-group">
							<label><i class="fas fa-dollar-sign"></i> Max Price</label>
							<input type="number" name="max_price" class="tt-input" 
								   value="{{ request('max_price') }}"
								   placeholder="Max" min="0">
						</div>
					</div>
					<div class="col-lg-1 col-md-12">
						<div class="tt-form-group">
							<label>&nbsp;</label>
							<button type="submit" class="btn-tt-primary w-100 tt-btn-icon-top">
								<i class="fas fa-search"></i>
								<span>Search</span>
							</button>
						</div>
					</div>
				</div>
				@if(!empty(request()->except('page')))
					<div class="mt-3 pt-3 border-top">
						<a href="{{ route('hotels.index') }}" class="btn btn-sm btn-outline-danger">
							<i class="fas fa-times me-1 small"></i> Clear All Filters
						</a>
					</div>
				@endif
			</form>
		</div>

		<!-- Results Header -->
		<div class="d-flex justify-content-between align-items-center mb-4">
			<div>
				<h5 class="mb-1 fw-semibold">
					<span class="tt-text-primary">{{ $hotels->total() }}</span> Hotel{{ $hotels->total() !== 1 ? 's' : '' }} Found
				</h5>
				<p class="text-muted small mb-0">
					@if(request()->hasAny(['name', 'city', 'country', 'min_stars', 'max_stars', 'min_price', 'max_price']))
						<span class="badge bg-primary">Active Filters</span>
					@else
						Showing all available hotels
					@endif
				</p>
			</div>
		</div>

		<!-- Results -->
		@if($hotels->count())
			<div class="row g-4">
				@foreach($hotels as $hotel)
					<div class="col-md-6 col-lg-4">
						<div class="card h-100 border-0 shadow-sm hotel-card">
							<div class="position-relative">
								<img src="{{ $hotel->thumbnail }}" alt="{{ $hotel->name }}" 
									 class="card-img-top hotel-card-img" loading="lazy"
									 style="height: 220px; object-fit: cover;">
								<div class="position-absolute top-0 end-0 p-3">
									<span class="badge bg-white text-dark shadow-sm">
										<i class="fas fa-star text-warning me-1 small"></i>{{ $hotel->stars }}
									</span>
								</div>
								@if($hotel->destination)
									<div class="position-absolute bottom-0 start-0 p-3">
										<a href="{{ route('desti.show', $hotel->destination) }}" 
										   class="badge bg-primary text-decoration-none">
											{{ $hotel->destination->title }}
										</a>
									</div>
								@endif
							</div>
							<div class="card-body">
								<h5 class="card-title fw-bold mb-2 text-truncate">{{ $hotel->name }}</h5>
								<p class="text-muted small mb-3">
									<i class="fas fa-map-marker-alt me-1 text-danger small"></i> 
									{{ $hotel->city }}, {{ $hotel->country }}
								</p>
								<div class="mb-3">
									<div class="text-warning mb-1">
										@for($i = 1; $i <= 5; $i++)
											@if($i <= $hotel->stars)
												<i class="fas fa-star small"></i>
											@else
												<i class="far fa-star small"></i>
											@endif
										@endfor
									</div>
								</div>
								@if($hotel->amenities_list)
									<div class="mb-3">
										@foreach(array_slice($hotel->amenities_list, 0, 4) as $amenity)
											<span class="badge bg-light text-dark me-1 small">{{ $amenity }}</span>
										@endforeach
										@if(count($hotel->amenities_list) > 4)
											<span class="badge bg-light text-dark small">+{{ count($hotel->amenities_list) - 4 }}</span>
										@endif
									</div>
								@endif
								<div class="d-flex justify-content-between align-items-center mt-auto">
									<div>
										<span class="h5 mb-0 text-primary fw-bold">{{ $hotel->converted_price['formatted'] }}</span>
										<span class="text-muted small">/night</span>
									</div>
									<a href="{{ route('hotels.show', $hotel) }}" class="btn-tt-primary btn-sm py-1 px-3 text-decoration-none">
										View Details <i class="fas fa-arrow-right ms-1"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			</div>

			<!-- Pagination -->
			<div class="mt-5">
				{{ $hotels->appends(request()->except('page'))->links() }}
			</div>
		@else
			<div class="text-center py-5">
				<div class="mb-4">
					<i class="fas fa-hotel fa-3x text-muted opacity-50"></i>
				</div>
				<h4 class="fw-semibold mb-2">No Hotels Found</h4>
				<p class="text-muted mb-4">
					@if(!empty(request()->except('page')))
						We couldn't find any hotels matching your criteria. 
						<a href="{{ route('hotels.index') }}" class="text-primary">Clear all filters</a> to see all hotels.
					@else
						No hotels are currently available. Check back later for new listings.
					@endif
				</p>
				@if(!empty(request()->except('page')))
					<a href="{{ route('home') }}" class="btn btn-outline-primary">
						<i class="fas fa-home me-1 small"></i> Back to Home
					</a>
				@endif
			</div>
		@endif
	</div>
</section>

<style>
.tt-btn-icon-top {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	padding: .5rem 1rem;
}
.tt-btn-icon-top i {
	font-size: 1.25rem;
	margin-bottom: .15rem;
}
.hotel-card {
	transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hotel-card:hover {
	transform: translateY(-5px);
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

.hotel-card-img {
	transition: transform 0.5s ease;
}

.hotel-card:hover .hotel-card-img {
	transform: scale(1.05);
}

.card-img-top {
	border-top-left-radius: calc(0.375rem - 1px);
	border-top-right-radius: calc(0.375rem - 1px);
}

.card-img-top {
	border-top-left-radius: calc(0.375rem - 1px);
	border-top-right-radius: calc(0.375rem - 1px);
}

.autocomplete-suggestions {
	position: absolute;
	top: 100%;
	left: 0;
	right: 0;
	background: white;
	border: 1px solid #ddd;
	border-radius: 0.375rem;
	max-height: 200px;
	overflow-y: auto;
	z-index: 1000;
	box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.autocomplete-suggestions div {
	padding: 8px 12px;
	cursor: pointer;
	transition: background 0.2s;
}

.autocomplete-suggestions div:hover {
	background: #f0f0f0;
}

.tt-form-group {
	position: relative;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cityInput = document.getElementById('city-input');
    const citySuggestions = document.getElementById('city-suggestions');
    const countryInput = document.getElementById('country-input');
    const countrySuggestions = document.getElementById('country-suggestions');

    if (!cityInput || !citySuggestions || !countryInput || !countrySuggestions) {
        return;
    }

    let cityTimeout;
    let countryTimeout;

    // City autocomplete
    cityInput.addEventListener('input', function() {
        clearTimeout(cityTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            citySuggestions.style.display = 'none';
            return;
        }

        cityTimeout = setTimeout(() => {
            fetch(`{{ route('hotels.autocomplete') }}?query=${encodeURIComponent(query)}&type=city`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        citySuggestions.innerHTML = data.map(item => `<div>${item}</div>`).join('');
                        citySuggestions.style.display = 'block';
                    } else {
                        citySuggestions.style.display = 'none';
                    }
                })
                .catch(error => {
                    citySuggestions.style.display = 'none';
                });
        }, 300);
    });

    citySuggestions.addEventListener('click', function(e) {
        if (e.target.tagName === 'DIV') {
            cityInput.value = e.target.textContent;
            citySuggestions.style.display = 'none';
        }
    });

    // Country autocomplete
    countryInput.addEventListener('input', function() {
        clearTimeout(countryTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            countrySuggestions.style.display = 'none';
            return;
        }

        countryTimeout = setTimeout(() => {
            fetch(`{{ route('hotels.autocomplete') }}?query=${encodeURIComponent(query)}&type=country`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        countrySuggestions.innerHTML = data.map(item => `<div>${item}</div>`).join('');
                        countrySuggestions.style.display = 'block';
                    } else {
                        countrySuggestions.style.display = 'none';
                    }
                })
                .catch(error => {
                    countrySuggestions.style.display = 'none';
                });
        }, 300);
    });

    countrySuggestions.addEventListener('click', function(e) {
        if (e.target.tagName === 'DIV') {
            countryInput.value = e.target.textContent;
            countrySuggestions.style.display = 'none';
        }
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!cityInput.contains(e.target) && !citySuggestions.contains(e.target)) {
            citySuggestions.style.display = 'none';
        }
        if (!countryInput.contains(e.target) && !countrySuggestions.contains(e.target)) {
            countrySuggestions.style.display = 'none';
        }
    });
});
</script>

@include('partials.footer')

@endsection