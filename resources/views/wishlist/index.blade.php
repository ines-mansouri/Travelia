@extends('layouts.front')

@section('title', 'My Wishlist - Travelia')

@section('page')
@include('partials.navbar')

<section class="tt-page-hero tt-page-hero-sm">
	<div class="tt-page-hero-bg" style="background-image: url('{{ asset('images/place-1.jpg') }}');"></div>
	<div class="container" data-aos="fade-up">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb justify-content-center">
				<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Home</a></li>
				<li class="breadcrumb-item active">Wishlist</li>
			</ol>
		</nav>
		<h1 class="tt-page-title">My <span class="accent">Wishlist</span></h1>
		<p class="tt-page-subtitle">Your favorite destinations saved for later</p>
	</div>
</section>

<section class="tt-section">
	<div class="container">
		@if(session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>
		@endif

		@if($wishlisted->isEmpty())
			<div class="tt-empty-state text-center" data-aos="fade-up">
				<div class="icon" style="font-size:3rem;color:var(--tt-primary);"><i class="fas fa-heart"></i></div>
				<h3 class="mt-3">Your wishlist is empty</h3>
				<p class="text-muted">Start exploring destinations and add your favorites here!</p>
				<a href="{{ route('packages') }}" class="btn-tt-primary">
					<i class="fas fa-compass me-1"></i> Browse Destinations
				</a>
			</div>
		@else
			<div class="row g-4">
				@foreach($wishlisted as $destination)
					<div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 3) * 50 }}">
						<div class="tt-dest-card">
							<div class="tt-dest-card-img">
								<img src="{{ $destination->image_url }}" alt="{{ $destination->title }}" loading="lazy" style="height:200px;width:100%;object-fit:cover;">
								<span class="badge-cat">{{ $destination->category->name ?? 'Tour' }}</span>
							</div>
							<div class="tt-dest-card-body">
								<div class="tt-dest-card-meta">
									<span><i class="fas fa-map-marker-alt"></i> {{ $destination->title }}</span>
									<span><i class="fas fa-clock"></i> {{ $destination->duration ?? '7 Days' }}</span>
								</div>
								<h3 class="tt-dest-card-title">
									<a href="{{ route('desti.show', $destination->id) }}">{{ $destination->title }}</a>
								</h3>
								<p class="tt-dest-card-desc">{{ Str::limit($destination->description, 100) }}</p>
								<div class="tt-dest-card-footer">
									<div>
										<div class="tt-dest-price-label">From</div>
										<div class="tt-dest-price-value">{{ $destination->converted_pricing }}</div>
									</div>
									<form action="{{ route('wishlist.destroy', $destination) }}" method="POST" class="d-inline">
										@csrf @method('DELETE')
										<button type="submit" class="btn-tt-outline" style="border-color:#dc3545;color:#dc3545;padding:0.4rem 0.8rem;">
											<i class="fas fa-trash"></i>
										</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			</div>

			<div class="mt-4 d-flex justify-content-center" data-aos="fade-up">
				{{ $wishlisted->links('pagination::bootstrap-4') }}
			</div>
		@endif
	</div>
</section>

@include('partials.footer')
@endsection
