@extends('layouts.front')

@section('title', $hotel->name . ' - Travelia')
@section('og_title', $hotel->name . ' - Travelia')
@section('og_description', Str::limit($hotel->city . ', ' . $hotel->country, 160))

@section('page')
@include('partials.navbar')

<section class="tt-page-hero">
	<div class="tt-page-hero-bg" style="background-image: url('{{ $hotel->thumbnail }}');"></div>
	<div class="container" data-aos="fade-up">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb justify-content-center">
				<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-1 small"></i>Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('hotels.index') }}">Hotels</a></li>
				<li class="breadcrumb-item active">{{ $hotel->name }}</li>
			</ol>
		</nav>
		<h1 class="tt-page-title">{{ $hotel->name }}</h1>
		<p class="tt-page-subtitle">
			<i class="fas fa-map-marker-alt me-1 small"></i> {{ $hotel->city }}, {{ $hotel->country }}
		</p>
	</div>
</section>

<section class="tt-section py-5">
	<div class="container">
		<div class="row g-4">
			<div class="col-lg-8">
				<!-- Image Gallery -->
				<div class="card border-0 shadow-sm mb-4">
					<div class="card-body p-0">
						@if($hotel->images && count($hotel->images) > 1)
							<div id="hotelGallery" class="carousel slide" data-bs-ride="carousel">
								<div class="carousel-indicators">
									@foreach($hotel->images as $idx => $img)
										<button type="button" data-bs-target="#hotelGallery"
												data-bs-slide-to="{{ $idx }}" {{ $idx === 0 ? 'class="active"' : '' }}></button>
									@endforeach
								</div>
								<div class="carousel-inner">
									@foreach($hotel->images as $idx => $img)
										<div class="carousel-item {{ $idx === 0 ? 'active' : }}">
											<img src="{{ $img }}" alt="{{ $hotel->name }}" class="d-block w-100" 
												 style="height: 450px; object-fit: cover;">
										</div>
									@endforeach
								</div>
								<button class="carousel-control-prev" type="button" data-bs-target="#hotelGallery" data-bs-slide="prev">
									<span class="carousel-control-prev-icon"></span>
								</button>
								<button class="carousel-control-next" type="button" data-bs-target="#hotelGallery" data-bs-slide="next">
									<span class="carousel-control-next-icon"></span>
								</button>
							</div>
						@elseif($hotel->images && count($hotel->images) === 1)
							<img src="{{ $hotel->images[0] }}" alt="{{ $hotel->name }}" class="d-block w-100" 
								 style="height: 450px; object-fit: cover;">
						@else
							<img src="{{ $hotel->thumbnail }}" alt="{{ $hotel->name }}" class="d-block w-100" 
								 style="height: 450px; object-fit: cover;">
						@endif
					</div>
				</div>

				<!-- Hotel Details -->
				<div class="card border-0 shadow-sm mb-4">
					<div class="card-body p-4">
						<div class="d-flex justify-content-between align-items-start mb-3">
							<div>
								<h2 class="fw-bold mb-2">{{ $hotel->name }}</h2>
								<p class="text-muted mb-2">
									<i class="fas fa-map-marker-alt me-1 text-danger small"></i> {{ $hotel->city }}, {{ $hotel->country }}
								</p>
								<div class="text-warning mb-3">
									@for($i = 1; $i <= 5; $i++)
										@if($i <= $hotel->stars)
											<i class="fas fa-star small"></i>
										@else
											<i class="far fa-star small"></i>
										@endif
									@endfor
									<span class="text-muted ms-2" style="font-size: 0.875rem;">{{ $hotel->stars }} Star Hotel</span>
								</div>
							</div>
							<div class="text-end">
								<div class="h3 mb-0 text-primary fw-bold">${{ number_format($hotel->price_per_night_usd, 0) }}</div>
								<small class="text-muted">per night</small>
							</div>
						</div>

						@if($hotel->destination)
							<div class="alert alert-info d-flex align-items-center mb-4">
								<i class="fas fa-globe me-3 text-primary"></i>
								<div>
									<strong>Part of Destination:</strong> {{ $hotel->destination->title }}
									<br>
									<a href="{{ route('desti.show', $hotel->destination) }}" class="text-primary text-decoration-none">
										View destination details <i class="fas fa-arrow-right ms-1 small"></i>
									</a>
								</div>
							</div>
						@endif
					</div>
				</div>

				<!-- Amenities -->
				@if($hotel->amenities_list)
					<div class="card border-0 shadow-sm mb-4">
						<div class="card-body p-4">
							<h4 class="fw-semibold mb-4">
								<i class="fas fa-concierge-bell me-2 text-primary small"></i> Amenities
							</h4>
							<div class="row g-3">
								@foreach($hotel->amenities_list as $amenity)
									<div class="col-md-6 col-lg-4">
										<div class="d-flex align-items-center p-3 bg-light rounded">
											<i class="fas fa-check-circle text-success me-3 small"></i>
											<span class="fw-medium">{{ $amenity }}</span>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				@endif
			</div>

			<!-- Sidebar -->
			<div class="col-lg-4">
				<div class="card border-0 shadow-sm position-sticky" style="top: 100px;">
					<div class="card-body p-4">
						<h4 class="fw-semibold mb-3">Book This Hotel</h4>
						<div class="mb-4">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<span class="text-muted">Price per night</span>
								<span class="h5 mb-0 text-primary fw-bold">${{ number_format($hotel->price_per_night_usd, 0) }}</span>
							</div>
							<div class="d-flex justify-content-between align-items-center">
								<span class="text-muted">Rating</span>
								<span class="text-warning">
									@for($i = 1; $i <= 5; $i++)
										@if($i <= $hotel->stars)
											<i class="fas fa-star small"></i>
										@else
											<i class="far fa-star small"></i>
										@endif
									@endfor
								</span>
							</div>
						</div>
						<hr class="my-4">
						<p class="text-muted small mb-4">
							Contact us to book this hotel at the best available rates. We'll help you find the perfect dates and room for your stay.
						</p>
						<a href="{{ route('contact') }}" class="btn btn-primary w-100 mb-2">
							<i class="fas fa-envelope me-2 small"></i> Inquire Now
						</a>
						<a href="{{ route('hotels.index') }}" class="btn btn-outline-secondary w-100">
							<i class="fas fa-arrow-left me-2 small"></i> Back to Hotels
						</a>
					</div>
				</div>

				@if($hotel->destination)
					<div class="card border-0 shadow-sm mt-4">
						<div class="card-body p-4">
							<h5 class="fw-semibold mb-3">
								<i class="fas fa-map-marked-alt me-2 text-primary small"></i> Related Destination
							</h5>
							<p class="text-muted mb-3">{{ $hotel->destination->title }}</p>
							<a href="{{ route('desti.show', $hotel->destination) }}" class="btn btn-outline-primary w-100 btn-sm">
								View Destination <i class="fas fa-arrow-right ms-1 small"></i>
							</a>
						</div>
					</div>
				@endif
			</div>
		</div>
	</div>
</section>

@include('partials.footer')

@endsection
