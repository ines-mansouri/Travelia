@extends('layouts.front')

@section('title', $hajjUmrah->title . ' - Hajj & Umrah Packages')

@section('seo')
	<title>{{ $hajjUmrah->title }} | Travelia</title>
	<meta name="description" content="{{ Str::limit($hajjUmrah->description, 160) }}">
	<meta property="og:title" content="{{ $hajjUmrah->title }} | Travelia">
	<meta property="og:description" content="{{ Str::limit($hajjUmrah->description, 200) }}">
	<meta property="og:image" content="{{ $hajjUmrah->image ?? asset('images/place-4.jpg') }}">
	<meta property="og:type" content="article">
@endsection

@section('page')
@include('partials.navbar')

<!-- Page Hero -->
<section class="tt-page-hero tt-page-hero-sm">
	<div class="tt-page-hero-bg" style="background-image: url('{{ $hajjUmrah->image ?? asset('images/place-4.jpg') }}');"></div>
	<div class="container" data-aos="fade-up">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb justify-content-center">
				<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('hajj') }}">Hajj & Umrah</a></li>
				<li class="breadcrumb-item active">{{ Str::limit($hajjUmrah->title, 40) }}</li>
			</ol>
		</nav>
		<h1 class="tt-page-title" style="font-size:2rem;">{{ $hajjUmrah->title }}</h1>
		<div class="tt-page-meta d-flex justify-content-center gap-3 mt-2">
			@if($hajjUmrah->category)
			<span class="badge bg-primary bg-opacity-75 rounded-pill px-3 py-1">
				<i class="fas fa-tag me-1"></i>{{ $hajjUmrah->category->name }}
			</span>
			@endif
			<span class="badge {{ $hajjUmrah->type === 'hajj' ? 'bg-purple' : 'bg-success' }} bg-opacity-75 rounded-pill px-3 py-1">
				<i class="fas fa-{{ $hajjUmrah->type === 'hajj' ? 'kaaba' : 'mosque' }} me-1"></i>{{ ucfirst($hajjUmrah->type) }}
			</span>
			<span class="text-white-50" style="font-size:0.85rem;">
				<i class="far fa-calendar-alt me-1"></i>{{ $hajjUmrah->published_at?->format('F d, Y') ?? 'Draft' }}
			</span>
		</div>
	</div>
</section>

<!-- Content -->
<section class="tt-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8" data-aos="fade-up">
				<article class="tt-article">
					@if($hajjUmrah->image)
					<img src="{{ $hajjUmrah->image }}" alt="{{ $hajjUmrah->title }}" class="tt-article-hero-img img-fluid rounded-4 mb-4" loading="lazy">
					@endif

					<div class="tt-hajj-info-bar d-flex flex-wrap gap-3 mb-4 p-3 bg-light rounded-3">
						@if($hajjUmrah->price)
						<div class="d-flex align-items-center gap-2">
							<i class="fas fa-dollar-sign text-primary"></i>
							<span><strong>Price:</strong> ${{ number_format($hajjUmrah->price, 2) }}</span>
						</div>
						@endif
						@if($hajjUmrah->duration_days)
						<div class="d-flex align-items-center gap-2">
							<i class="far fa-clock text-primary"></i>
							<span><strong>Duration:</strong> {{ $hajjUmrah->duration_days }} days</span>
						</div>
						@endif
						<div class="d-flex align-items-center gap-2">
							<i class="fas fa-mosque text-primary"></i>
							<span><strong>Type:</strong> {{ ucfirst($hajjUmrah->type) }}</span>
						</div>
					</div>

					<div class="tt-article-body">
						{!! nl2br(e($hajjUmrah->content)) !!}
					</div>

					<div class="tt-article-footer mt-5 pt-4 border-top">
						<div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
							<div>
								<small class="text-muted">Published {{ $hajjUmrah->published_at?->format('F d, Y') ?? 'N/A' }}</small>
							</div>
							<a href="{{ route('hajj') }}" class="btn-tt-outline">
								<i class="fas fa-arrow-left me-1"></i> Back to Packages
							</a>
						</div>
					</div>
				</article>
			</div>
		</div>
	</div>
</section>

<!-- Booking -->
<section class="tt-section-sm">
	<div class="container">
		<div class="row g-5">
			<!-- Personal Information -->
			<div class="col-lg-7" data-aos="fade-up">
				<div class="tt-sidebar-card">
					<h4 class="mb-1"><i class="fas fa-user me-2"></i> Personal Information</h4>
					<p class="text-muted mb-4">Fill in your details to complete the booking</p>

					@if (isset($errors) && $errors->any())
					<div class="alert alert-danger mb-4">
						<ul class="mb-0">
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif

					<form id="hajj-booking-form" class="tt-form" method="POST" action="{{ route('hajj.book', $hajjUmrah->id) }}">
						@csrf
						<div class="row g-3">
							<div class="col-md-6">
								<div class="tt-form-group">
									<label class="tt-label">First Name *</label>
									<input type="text" name="firstname" class="tt-input" placeholder="John" value="{{ old('firstname') }}" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="tt-form-group">
									<label class="tt-label">Last Name *</label>
									<input type="text" name="lastname" class="tt-input" placeholder="Doe" value="{{ old('lastname') }}" required>
								</div>
							</div>
						</div>
						<div class="tt-form-group">
							<label class="tt-label">Phone Number *</label>
							<input type="tel" name="phone" class="tt-input" placeholder="+1 234 567 890" value="{{ old('phone') }}" required>
						</div>
						<div class="tt-form-group">
							<label class="tt-label">Email Address *</label>
							<input type="email" name="email" class="tt-input" placeholder="you@example.com" value="{{ old('email') }}" required>
						</div>
					</form>
				</div>
			</div>

			<!-- Order Summary -->
			<div class="col-lg-5" data-aos="fade-left">
				<div class="tt-sidebar-card">
					<h4 class="mb-1"><i class="fas fa-shopping-bag me-2"></i> Your Package</h4>
					<p class="text-muted mb-4">Pilgrimage booking details</p>

					<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
						<strong>Package</strong>
						<strong>Total</strong>
					</div>

					<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
						<span>{{ $hajjUmrah->title }}</span>
						<span>${{ number_format($hajjUmrah->price, 2) }}</span>
					</div>

					@if($hajjUmrah->duration_days)
					<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
						<span>Duration</span>
						<span>{{ $hajjUmrah->duration_days }} days</span>
					</div>
					@endif

					<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
						<span>Travel Date</span>
						<input type="date" name="travel_date" form="hajj-booking-form" class="tt-input" style="max-width:180px;" min="{{ now()->toDateString() }}" value="{{ old('travel_date') }}">
					</div>

					<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
						<span>Guests</span>
						<div class="tt-stepper">
							<button type="button" class="tt-step-btn" data-dir="-1" tabindex="-1" aria-label="Fewer guests"><i class="fas fa-minus"></i></button>
							<input type="number" name="guests" form="hajj-booking-form" class="tt-guest-count" id="hajj-guest-count" value="1" min="1" max="20" readonly aria-label="Guests">
							<button type="button" class="tt-step-btn" data-dir="1" tabindex="-1" aria-label="More guests"><i class="fas fa-plus"></i></button>
						</div>
					</div>
					<div class="d-flex justify-content-between align-items-center py-3 mb-4">
						<strong class="fs-5">Total</strong>
						<strong class="fs-5" style="color:var(--tt-primary);" id="hajj-booking-total">${{ number_format($hajjUmrah->price, 2) }}</strong>
					</div>

					<p class="text-muted small mb-3"><i class="fas fa-info-circle me-1"></i> Our team will contact you within 24 hours to confirm your pilgrimage.</p>

					<button type="submit" form="hajj-booking-form" id="hajj-booking-submit" class="btn-tt-accent w-100 text-center d-block">
						<i class="fas fa-mosque me-2"></i> Confirm Booking
					</button>

					<div class="text-center mt-3">
						<small class="text-muted"><i class="fas fa-shield-alt me-1"></i> No payment required to reserve</small>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@include('partials.footer')
@endsection

@push('styles')
<style>
.tt-page-hero-sm {
	min-height: 320px;
	padding: 80px 0 40px;
}
.tt-page-hero-sm .tt-page-title {
	font-family: 'Playfair Display', serif;
	font-size: 2rem;
}
.tt-page-meta .badge { font-weight: 500; letter-spacing: 0.3px; }
.tt-page-meta .bg-purple { background: #8b5cf6 !important; }

.tt-article-hero-img {
	width: 100%;
	max-height: 420px;
	object-fit: cover;
	box-shadow: 0 4px 24px rgba(0,0,0,0.1);
}
.tt-article-body {
	font-size: 1.05rem;
	line-height: 1.8;
	color: #334155;
}
.tt-article-body p { margin-bottom: 1.25rem; }
.tt-hajj-info-bar { font-size: 0.9rem; }
.tt-article-footer small { font-size: 0.8rem; }
.tt-stepper { display: flex; align-items: center; gap: .4rem; }
.tt-step-btn {
	width: 38px;
	height: 38px;
	flex-shrink: 0;
	border: 1px solid var(--tt-border, #e5e7eb);
	border-radius: 10px;
	background: #ffffff;
	color: var(--tt-primary, #1F3D39);
	font-size: .8rem;
	cursor: pointer;
	display: grid;
	place-items: center;
	transition: background .2s ease, color .2s ease, border-color .2s ease;
}
.tt-step-btn:hover { background: var(--tt-primary, #1F3D39); color: #fff; border-color: var(--tt-primary, #1F3D39); }
.tt-guest-count {
	width: 46px;
	text-align: center;
	border: 1px solid var(--tt-border, #e5e7eb);
	border-radius: 10px;
	background: #ffffff;
	color: var(--tt-dark, #1a1a2e);
	font-family: var(--tt-font, sans-serif);
	font-weight: 800;
	font-size: 1rem;
	-moz-appearance: textfield;
	appearance: textfield;
}
.tt-guest-count::-webkit-outer-spin-button,
.tt-guest-count::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    'use strict';

    var form = document.getElementById('hajj-booking-form');
    var submit = document.getElementById('hajj-booking-submit');
    var count = document.getElementById('hajj-guest-count');
    var total = document.getElementById('hajj-booking-total');
    if (!form || !count) return;

    var price = {{ $hajjUmrah->price ? (float) $hajjUmrah->price : 0 }};

    function updateTotal() {
        var guests = Math.min(20, Math.max(1, parseInt(count.value, 10) || 1));
        count.value = guests;
        if (total) {
            total.textContent = '$' + (price * guests).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
    }

    form.addEventListener('click', function (e) {
        var dir = e.target.closest('[data-dir]');
        if (!dir) return;
        count.value = (parseInt(count.value, 10) || 1) + parseInt(dir.getAttribute('data-dir'), 10);
        updateTotal();
    });

    form.addEventListener('submit', function () {
        if (submit) {
            submit.disabled = true;
            submit.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Submitting...';
        }
    });

    updateTotal();
})();
</script>
@endpush
