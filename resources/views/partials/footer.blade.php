<!-- Footer -->
<footer class="tt-footer">
	<div class="container">
		<div class="row g-4">
			<!-- Brand -->
			<div class="col-lg-4">
				<div class="d-flex align-items-center gap-2 mb-3">
					<div class="brand-icon"><i class="fas fa-globe"></i></div>
					<img src="{{ asset('images/logo.png') }}" alt="Travelia" height="35" style="max-height:35px;width:auto;">
					<span class="ms-2 fw-bold" style="font-size:1.25rem;letter-spacing:-0.5px;color:var(--tt-primary);font-family:'Poppins',sans-serif;">Travel<span style="color:var(--tt-accent);">ia</span></span>
				</div>
				<p class="mb-3">
					Your trusted local guide to the world's most incredible destinations.
					We create authentic experiences that connect you with every beautiful corner of our planet.
				</p>
				<div class="social-links">
					<a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
					<a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
					<a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
					<a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
				</div>
			</div>

			<!-- Quick Links -->
			<div class="col-lg-2 col-md-6">
				<h5>{{ __('messages.footer.quick_links') }}</h5>
				<a href="{{ url('/') }}" class="tt-footer-link">{{ __('messages.nav.home') }}</a>
				<a href="{{ route('flights') }}" class="tt-footer-link">{{ __('messages.nav.flights') }}</a>
				<a href="{{ route('packages') }}" class="tt-footer-link">{{ __('messages.nav.destinations') }}</a>
				<a href="{{ route('hajj') }}" class="tt-footer-link">Hajj & Umrah</a>
			</div>

			<!-- Services -->
			<div class="col-lg-3 col-md-6">
				<h5>{{ __('messages.footer.services') }}</h5>
				@php
					$footerPackages = \App\Destinations::published()->latest()->take(5)->get();
					if ($footerPackages->isEmpty()) {
						$footerPackages = \App\Destinations::latest()->take(5)->get();
					}
				@endphp
				@foreach($footerPackages as $footerPkg)
					<a href="{{ route('desti.show', $footerPkg->id) }}" class="tt-footer-link">{{ $footerPkg->title }}</a>
				@endforeach
			</div>

			<!-- Contact Info -->
			<div class="col-lg-3 col-md-6">
				<h5>{{ __('messages.footer.contact_info') }}</h5>
				<div class="contact-row">
					<i class="fas fa-map-marker-alt"></i>
					<span>123 Travel Street<br>City Center</span>
				</div>
				<div class="contact-row">
					<i class="fas fa-phone"></i>
					<span>
						<a href="tel:+1234567890" style="color: inherit; text-decoration: none;">+1 234 567 890</a>
						<a href="https://wa.me/1234567890" target="_blank" class="ms-2 text-success" title="Chat on WhatsApp" id="whatsapp-footer-link" style="font-size: 1.15rem; vertical-align: middle;">
							<i class="fab fa-whatsapp"></i>
						</a>
					</span>
				</div>
				<div class="contact-row">
					<i class="fas fa-envelope"></i>
					<span>info@travelia.com</span>
				</div>
			</div>
		</div>

		<!-- Bottom bar -->
		<div class="tt-footer-bottom">
			<div class="row align-items-center">
				<div class="col-md-6">
					<p>&copy; {{ date('Y') }} Travelia. {{ __('messages.footer.rights') }}</p>
				</div>
				<div class="col-md-6 text-md-end">
					<a href="#" class="me-3">Privacy Policy</a>
					<a href="#">Terms of Service</a>
				</div>
			</div>
		</div>
	</div>
</footer>
