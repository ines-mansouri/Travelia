@extends('layouts.front')

@section('title', 'Checkout - Travelia')

@section('page')
@include('partials.navbar')

<!-- Page Hero -->
<section class="tt-page-hero tt-page-hero-sm">
	<div class="tt-page-hero-bg" style="background-image: url('{{ asset('images/place-1.jpg') }}');"></div>
	<div class="container" data-aos="fade-up">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb justify-content-center">
				<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('cart') }}">Cart</a></li>
				<li class="breadcrumb-item active">Checkout</li>
			</ol>
		</nav>
		<h1 class="tt-page-title">Secure <span class="accent">Checkout</span></h1>
	</div>
</section>

<!-- Checkout Content -->
<section class="tt-section">
	<div class="container">
		<div class="row g-5">
			<!-- Billing Info -->
			<div class="col-lg-7" data-aos="fade-up">
				<div class="tt-sidebar-card">
					<h4 class="mb-1"><i class="fas fa-user me-2"></i> Personal Information</h4>
					<p class="text-muted mb-4">Fill in your details to complete the booking</p>

					<form id="checkout-form" class="tt-form">
						@csrf
						<input type="hidden" name="destination_id" value="{{ $destinations->id }}">
						<input type="hidden" name="currency_code" value="{{ session('currency', config('currencies.default')) }}">
						<div class="row g-3">
							<div class="col-md-6">
								<div class="tt-form-group">
									<label class="tt-label">First Name *</label>
									<input type="text" name="firstname" class="tt-input" placeholder="John" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="tt-form-group">
									<label class="tt-label">Last Name *</label>
									<input type="text" name="lastname" class="tt-input" placeholder="Doe" required>
								</div>
							</div>
						</div>
						<div class="tt-form-group">
							<label class="tt-label">Phone Number *</label>
							<input type="tel" name="phone" class="tt-input" placeholder="+1 234 567 890" required>
						</div>
						<div class="tt-form-group">
							<label class="tt-label">Email Address *</label>
							<input type="email" name="email" class="tt-input" placeholder="you@example.com" required>
						</div>
					</form>
				</div>
			</div>

			<!-- Order Summary -->
			<div class="col-lg-5" data-aos="fade-left">
				<div class="tt-sidebar-card">
					<h4 class="mb-1"><i class="fas fa-shopping-bag me-2"></i> Your Package</h4>
					<p class="text-muted mb-4">Tour booking details</p>

					<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
						<strong>Tour</strong>
						<strong>Total</strong>
					</div>

					<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
						<span>{{ $destinations->title }}</span>
						<span>{{ $destinations->converted_pricing }}</span>
					</div>
					<div class="d-flex justify-content-between align-items-center py-3 border-bottom">
						<span>Subtotal</span>
						<span>{{ $destinations->converted_pricing }}</span>
					</div>
					<div class="d-flex justify-content-between align-items-center py-3 mb-4">
						<strong class="fs-5">Total</strong>
						<strong class="fs-5" style="color:var(--tt-primary);">{{ $destinations->converted_pricing }}</strong>
					</div>

					<p class="text-muted small mb-3"><i class="fas fa-info-circle me-1"></i> Can't wait to start your vacation?</p>

					<button type="button" id="checkout-submit" class="btn-tt-accent w-100 text-center d-block">
						<i class="fas fa-lock me-2"></i> Proceed to Pay
					</button>

					<div class="text-center mt-3">
						<small class="text-muted"><i class="fas fa-shield-alt me-1"></i> Secure payments powered by Stripe</small>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@include('partials.footer')
@endsection

@push('scripts')
<script>
(function() {
    var form = document.getElementById('checkout-form');
    var btn  = document.getElementById('checkout-submit');
    var btnHtml = '<i class="fas fa-lock me-2"></i> Proceed to Pay';

    btn.addEventListener('click', async function() {
        var data = {
            destination_id: form.querySelector('[name="destination_id"]').value,
            currency_code:  form.querySelector('[name="currency_code"]').value,
        };

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Redirecting...';

        var res = await ttFetch('{{ route('destinations.checkout') }}', {
            method: 'POST',
            body: data,
        }, {
            button: btn,
            buttonHtml: btnHtml,
        });

        if (res.success && res.data?.success && res.data?.url) {
            window.location.href = res.data.url;
        } else {
            ttToast.show(res.data?.message || 'Failed to initialise payment.', 'error');
        }
    });
})();
</script>
@endpush