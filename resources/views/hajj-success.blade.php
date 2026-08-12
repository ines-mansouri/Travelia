@extends('layouts.front')

@section('title', 'Booking Received - Travelia')

@section('page')
@include('partials.navbar')

{{-- ─── Success Hero ──────────────────────────────────────────────────── --}}
<section class="tt-section" style="padding-top:120px;">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8 text-center" data-aos="zoom-in">
				<div style="font-size:5rem;color:var(--tt-primary,#1F3D39);animation:popIn 0.6s cubic-bezier(0.68,-0.55,0.27,1.55);">
					<i class="fas fa-mosque"></i>
				</div>
				<h1 class="display-5 fw-bold mt-3" style="font-family:var(--tt-font-display);">Booking Received!</h1>
				<p class="text-muted fs-5">
					Thank you, {{ $booking->customer_name }}. Your pilgrimage request has been received.
					Our team will contact you within 24 hours to confirm your package.
				</p>
				<div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
					<a href="{{ route('dashboard') }}" class="btn-tt-outline">
						<i class="fas fa-arrow-left me-1"></i> My Dashboard
					</a>
					<a href="{{ route('hajj') }}" class="btn-tt-accent">
						<i class="fas fa-compass me-1"></i> Explore More Packages
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

{{-- ─── Booking Summary ──────────────────────────────────────────────── --}}
<section class="tt-section-sm">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8" data-aos="fade-up">
				<div class="tt-sidebar-card">
					<div class="d-flex align-items-center gap-3 mb-4">
						<div style="width:48px;height:48px;border-radius:12px;background:var(--tt-primary-light);display:flex;align-items:center;justify-content:center;color:var(--tt-primary);font-size:1.25rem;">
							<i class="fas fa-suitcase"></i>
						</div>
						<div>
							<h5 class="mb-0">{{ $booking->hajjUmrah?->title ?? 'Hajj & Umrah Package' }}</h5>
							<small class="text-muted">Booking #{{ $booking->id }}</small>
						</div>
					</div>

					<div class="row g-3">
						<div class="col-6">
							<small class="text-muted d-block">Travel Date</small>
							<span class="fw-semibold">{{ $booking->travel_date?->format('M d, Y') ?? 'TBD' }}</span>
						</div>
						<div class="col-6">
							<small class="text-muted d-block">Guests</small>
							<span class="fw-semibold">{{ $booking->guests }}</span>
						</div>
						<div class="col-6">
							<small class="text-muted d-block">Invoice</small>
							<span class="fw-semibold">{{ $booking->invoice_number ?? '—' }}</span>
						</div>
						<div class="col-6">
							<small class="text-muted d-block">Status</small>
							<span class="badge bg-warning" style="font-size:0.85rem;">{{ ucfirst($booking->status ?? 'pending') }}</span>
						</div>
					</div>

					<hr class="my-3">
					<div class="d-flex justify-content-between align-items-center">
						<span class="fw-semibold">Estimated Total</span>
						<div class="fw-bold fs-5" style="color:var(--tt-primary);">
							${{ number_format($booking->total_price, 2) }}
						</div>
					</div>
					<div class="d-flex justify-content-between align-items-center mt-1">
						<small class="text-muted">{{ $booking->customer_email }}</small>
						<small class="text-muted">{{ $booking->customer_name }}</small>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

{{-- ─── Animation ────────────────────────────────────────────────────── --}}
<style>
	@keyframes popIn {
		0% { transform:scale(0); opacity:0; }
		60% { transform:scale(1.2); }
		100% { transform:scale(1); opacity:1; }
	}
</style>

@include('partials.footer')
@endsection