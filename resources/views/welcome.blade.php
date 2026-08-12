@extends('layouts.front')

@section('og_image', asset('images/place-1.png'))

@section('page')
@include('partials.navbar')

<!-- Hero -->
<section class="tt-hero">
	<div class="tt-hero-bg"></div>
	<div class="tt-hero-brand">
    <div>TRAVELIA</div>
    <div>Your Gateway to the World</div>
</div>
	<div class="container tt-hero-content">
		<div class="row align-items-center">
			<div class="col-lg-7" data-aos="fade-right">
				<div style="margin-top: 30px; display: flex; flex-direction: column; align-items: flex-start; gap: .6rem;">
					<a href="{{ route('hajj') }}" class="btn-tt-accent" style="background: #c9a84c; border-color: #c9a84c; margin-left: 5rem;">
						<i class="fas fa-mosque me-1"></i> Hajj & Umrah
					</a>
					<div style="display: flex; gap: .75rem; flex-wrap: wrap;">
						<a href="{{ route('flights') }}" class="btn-tt-accent">
							<i class="fas fa-plane me-1"></i> {{ __('messages.hero.cta_flights') }}
						</a>
						<a href="{{ route('hotels.index') }}" class="btn-tt-accent">
							<i class="fas fa-hotel me-1"></i> Search Hotels
						</a>
					</div>
					<a href="{{ route('packages') }}" class="btn-tt-outline-white" style="margin-left: 5rem;">
						{{ __('messages.hero.cta_destinations') }} <i class="fas fa-arrow-right"></i>
					</a>
				</div>

				<div class="tt-trust-row" style="margin-top: 10rem;">
					<div class="tt-trust-item">
						<div class="tt-trust-icon"><i class="fas fa-shield-alt"></i></div>
						<div>
							<div class="tt-trust-value">100% Safe</div>
							<div class="tt-trust-label">Licensed Operator</div>
						</div>
					</div>
					<div class="tt-trust-item">
						<div class="tt-trust-icon"><i class="fas fa-star"></i></div>
						<div>
							<div class="tt-trust-value">4.9/5</div>
							<div class="tt-trust-label">2,400+ Reviews</div>
						</div>
					</div>
					<div class="tt-trust-item">
						<div class="tt-trust-icon"><i class="fas fa-award"></i></div>
						<div>
							<div class="tt-trust-value">8+ Years</div>
							<div class="tt-trust-label">Experience</div>
						</div>
					</div>
				</div>
			</div>

@php
$cards = [
    ['img' => 'destination-1.jpg', 'title' => 'Santorini Sunset', 'days' => '5 Days', 'people' => 'Max 8 People', 'price' => '€890'],
    ['img' => 'destination-2.jpg', 'title' => 'Maldives Escape', 'days' => '7 Days', 'people' => 'Max 6 People', 'price' => '€2,190'],
    ['img' => 'destination-3.jpg', 'title' => 'Bali Retreat', 'days' => '10 Days', 'people' => 'Max 12 People', 'price' => '€1,490'],
    ['img' => 'destination-4.jpg', 'title' => 'Paris Getaway', 'days' => '4 Days', 'people' => 'Max 10 People', 'price' => '€750'],
    ['img' => 'destination-5.jpg', 'title' => 'Tokyo Adventure', 'days' => '12 Days', 'people' => 'Max 8 People', 'price' => '€2,890'],
    ['img' => 'destination-6.jpg', 'title' => 'Swiss Alps', 'days' => '8 Days', 'people' => 'Max 10 People', 'price' => '€2,450'],
];
@endphp
			<div class="col-lg-5 d-none d-lg-block" data-aos="fade-left" data-aos-delay="200">
				<div class="tt-slider">
					<div class="tt-slider-track" id="sliderTrack">
						@foreach($cards as $c)
						<div class="tt-slider-slide">
							<img src="{{ asset('images/' . $c['img']) }}" alt="{{ $c['title'] }}" loading="lazy">
							<div class="tt-slider-body">
								<span class="card-badge">Popular Choice</span>
								<h4>{{ $c['title'] }}</h4>
								<div class="tt-hero-card-meta">
									<span><i class="fas fa-clock"></i> {{ $c['days'] }}</span>
									<span><i class="fas fa-users"></i> {{ $c['people'] }}</span>
								</div>
								<div class="tt-slider-footer">
									<div>
										<div class="price-from">From</div>
										<div class="price-value">{{ $c['price'] }}</div>
									</div>
									<a href="{{ route('packages') }}" class="tt-hero-card-link">
										View Details <i class="fas fa-arrow-right"></i>
									</a>
								</div>
							</div>
						</div>
						@endforeach
					</div>
					<div class="tt-slider-dots" id="sliderDots"></div>
				</div>
			</div>
			<script>
			const track = document.getElementById('sliderTrack');
			const dots = document.getElementById('sliderDots');
			const slides = track.querySelectorAll('.tt-slider-slide');
			let idx = 0;
			slides.forEach((_, i) => {
				const dot = document.createElement('span');
				dot.addEventListener('click', () => { idx = i; update(); });
				dots.appendChild(dot);
			});
			const dotEls = dots.querySelectorAll('span');
			function update() {
				track.style.transform = 'translateX(-' + (idx * 100) + '%)';
				dotEls.forEach((d, i) => d.classList.toggle('active', i === idx));
			}
			update();
			setInterval(() => { idx = (idx + 1) % slides.length; update(); }, 5000);
			</script>
	</div>

	<div class="tt-hero-scroll">
		<span>Scroll to Explore</span>
		<i class="fas fa-chevron-down"></i>
	</div>
</section>

<!-- Featured Destinations -->
<section class="tt-section">
	<div class="container">
		<div class="tt-section-header text-center" data-aos="fade-up">
			<div class="tt-pretitle">Handpicked Experiences</div>
			<h2 class="tt-title">Featured <span class="accent">Destinations</span></h2>
			<p class="tt-subtitle">
				Explore the world's most captivating locations, curated by our local experts
				who know every hidden gem and breathtaking vista.
			</p>
		</div>

		<div class="tt-dest-grid">
			@foreach ($destinations as $destination)
			<article class="tt-dest-card" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
				<div class="tt-dest-card-img">
					<img src="{{ $destination->image_url }}"
						 alt="{{ $destination->title }}" loading="lazy">
					<span class="badge-cat">{{ $destination->category->name ?? 'Travel' }}</span>
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
							<div class="tt-dest-price-value">
								{{ $destination->converted_pricing }}
							</div>
						</div>
						<a href="{{ route('desti.show', $destination->id) }}" class="tt-dest-card-link">
							Explore <i class="fas fa-arrow-right"></i>
						</a>
					</div>
				</div>
			</article>
			@endforeach
		</div>

		<div class="text-center mt-5" data-aos="fade-up">
			<a href="{{ route('packages') }}" class="btn-tt-primary">
				View All Destinations <i class="fas fa-globe"></i>
			</a>
		</div>
	</div>
</section>

<!-- Why Choose Us -->
<section class="tt-section tt-section-light">
	<div class="container">
		<div class="row align-items-center g-5">
			<div class="col-lg-6" data-aos="fade-right">
				<div class="tt-features-img">
					<img src="{{ asset('images/travelia-about.png') }}" alt="Travel" loading="lazy">
				</div>
			</div>

			<div class="col-lg-6" data-aos="fade-left">
				<div class="tt-section-header">
					<div class="tt-pretitle">Why Choose Travelia</div>
					<h2 class="tt-title">Experience the World with <span class="accent">Local Experts</span></h2>
					<p class="tt-subtitle">
						We don't just show you destinations — we share our world with you.
					</p>
				</div>

				<div class="tt-feature-item">
					<div class="tt-feature-icon"><i class="fas fa-map-marked-alt"></i></div>
					<div>
						<h4>Local Expertise</h4>
						<p>We know every hidden gem, cultural nuance, and breathtaking vista across the globe.</p>
					</div>
				</div>
				<div class="tt-feature-item">
					<div class="tt-feature-icon"><i class="fas fa-shield-alt"></i></div>
					<div>
						<h4>Safety First</h4>
						<p>Licensed operator with comprehensive insurance and 24/7 support throughout your journey.</p>
					</div>
				</div>
				<div class="tt-feature-item">
					<div class="tt-feature-icon"><i class="fas fa-leaf"></i></div>
					<div>
						<h4>Sustainable Tourism</h4>
						<p>We partner with local communities and support conservation efforts worldwide.</p>
					</div>
				</div>
				<div class="tt-feature-item">
					<div class="tt-feature-icon"><i class="fas fa-star"></i></div>
					<div>
						<h4>Personalized Service</h4>
						<p>Every journey is tailored to your preferences, creating memories that last a lifetime.</p>
					</div>
				</div>

			</div>
		</div>
	</div>
</section>

<!-- Testimonials -->
<section class="tt-section">
	<div class="container">
		<div class="tt-section-header text-center" data-aos="fade-up">
			<div class="tt-pretitle">What Our Travelers Say</div>
			<h2 class="tt-title">Stories from the <span class="accent">Road</span></h2>
		</div>

		<div class="tt-testimonials-grid">
			@forelse($testimonials as $i => $testimonial)
			<div class="tt-testimonial-card" data-aos="fade-up" data-aos-delay="{{ ($i % 3) * 100 }}">
				<div class="tt-testimonial-stars">
					@for($s = 1; $s <= 5; $s++)
						<i class="fas fa-star{{ $s <= $testimonial->rating ? '' : '-o text-muted' }}"></i>
					@endfor
				</div>
				<p class="tt-testimonial-text">"{{ $testimonial->text }}"</p>
				<div class="tt-testimonial-author">
					<img src="{{ asset($testimonial->image ?? 'images/place-1.jpg') }}" alt="{{ $testimonial->name }}" class="tt-testimonial-avatar" loading="lazy">
					<div>
						<div class="tt-testimonial-name">{{ $testimonial->name }}</div>
						<div class="tt-testimonial-location">{{ $testimonial->location }}</div>
					</div>
				</div>
			</div>
			@empty
			<div class="text-center py-4 text-muted">No testimonials yet.</div>
			@endforelse
		</div>
	</div>
</section>

<!-- CTA -->
<section class="tt-cta">
	<div class="container" data-aos="zoom-in">
		<div class="icon-lg"><i class="fas fa-paper-plane"></i></div>
		<h2>Ready to Begin Your Next Adventure?</h2>
		<p>
			Let our local experts craft your perfect journey. From cultural immersions to coastal escapes,
			mountain treks to city adventures — your dream experience awaits.
		</p>
		<div class="tt-cta-actions">
			<a href="{{ route('packages') }}" class="btn-tt-white">
				Explore Destinations <i class="fas fa-compass"></i>
			</a>
			<a href="{{ route('contact') }}" class="btn-tt-outline-white">
				Contact Us <i class="fas fa-phone"></i>
			</a>
		</div>
	</div>
</section>

@include('partials.footer')
@endsection
