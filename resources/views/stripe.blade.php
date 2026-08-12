@extends('layouts.front')

@section('title', 'Payment - Travelia')

@section('page')
@include('partials.navbar')

<section class="tt-page-hero tt-page-hero-sm">
	<div class="tt-page-hero-bg" style="background-image: url('{{ asset('images/place-1.jpg') }}');"></div>
	<div class="container" data-aos="fade-up">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb justify-content-center">
				<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('cart') }}">Cart</a></li>
				<li class="breadcrumb-item"><a href="{{ route('checkout') }}">Checkout</a></li>
				<li class="breadcrumb-item active">Payment</li>
			</ol>
		</nav>
		<h1 class="tt-page-title">Secure <span class="accent">Payment</span></h1>
		<p class="tt-page-subtitle">Complete your booking with confidence</p>
	</div>
</section>

<section class="tt-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<div class="tt-sidebar-card" data-aos="fade-up">

					@if (Session::has('success'))
					<div class="alert alert-success text-center">
						<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
						<p>{{ Session::get('success') }}</p>
					</div>
					@endif

					<h4 class="mb-1"><i class="fas fa-lock me-2"></i> Payment Details</h4>
					<p class="text-muted mb-4">Secure payments powered by Stripe</p>

					<div class="d-flex justify-content-between align-items-center py-3 border-bottom mb-4">
						<span class="fw-semibold">Total Amount</span>
						<span class="fs-5 fw-bold" style="color:var(--tt-primary);">{{ $destinations->converted_pricing }}</span>
					</div>

					<form role="form" action="{{ route('stripe.post') }}" method="post" class="require-validation tt-form"
						  data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
						@csrf

						<div class="tt-form-group">
							<label class="tt-label">Name on Card</label>
							<input class="tt-input" size="4" type="text" required>
						</div>

						<div class="tt-form-group">
							<label class="tt-label">Card Number</label>
							<input autocomplete="off" class="tt-input card-number" size="20" type="text" required>
						</div>

						<div class="row g-3">
							<div class="col-md-4">
								<div class="tt-form-group">
									<label class="tt-label">CVC</label>
									<input autocomplete="off" class="tt-input card-cvc" placeholder="311" size="4" type="text" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="tt-form-group">
									<label class="tt-label">Month</label>
									<input class="tt-input card-expiry-month" placeholder="MM" size="2" type="text" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="tt-form-group">
									<label class="tt-label">Year</label>
									<input class="tt-input card-expiry-year" placeholder="YYYY" size="4" type="text" required>
								</div>
							</div>
						</div>

						<div class="error form-group hide d-none">
							<div class="alert alert-danger mt-3">Please correct the errors and try again.</div>
						</div>

						<button class="btn-tt-accent w-100 mt-4" type="submit">
							<i class="fas fa-lock me-2"></i> Pay Now {{ $destinations->converted_pricing }}
						</button>

						<p class="text-center text-muted mt-3 small">
							<i class="fas fa-shield-alt me-1"></i> Your payment information is encrypted and secure.
						</p>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

@push('scripts')
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
$(function() {
	var $form = $(".require-validation");

	$('form.require-validation').bind('submit', function(e) {
		var inputSelector = ['input[type=email]', 'input[type=password]',
			'input[type=text]', 'input[type=file]',
			'textarea'].join(', ');
		var $inputs = $form.find('.tt-form-group').find(inputSelector);
		var $errorMessage = $form.find('div.error');
		var valid = true;
		$errorMessage.addClass('d-none');
		$inputs.parent().removeClass('has-error');
		$inputs.each(function(i, el) {
			var $input = $(el);
			if ($input.val() === '') {
				$input.parent().addClass('has-error');
				$errorMessage.removeClass('d-none');
				e.preventDefault();
			}
		});

		if (!$form.data('cc-on-file')) {
			e.preventDefault();
			Stripe.setPublishableKey($form.data('stripe-publishable-key'));
			Stripe.createToken({
				number: $('.card-number').val(),
				cvc: $('.card-cvc').val(),
				exp_month: $('.card-expiry-month').val(),
				exp_year: $('.card-expiry-year').val()
			}, stripeResponseHandler);
		}
	});

	function stripeResponseHandler(status, response) {
		if (response.error) {
			$('.error').removeClass('d-none').find('.alert').text(response.error.message);
		} else {
			var token = response['id'];
			$form.find('input[type=text]').empty();
			$form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
			$form.get(0).submit();
		}
	}
});
</script>
@endpush

@include('partials.footer')
@endsection
