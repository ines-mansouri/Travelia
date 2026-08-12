@extends('layouts.front')

@section('title', 'My Dashboard - Travelia')

@section('page')
@include('partials.navbar')

<section class="tt-page-hero tt-page-hero-sm">
	<div class="tt-page-hero-bg" style="background-image: url('{{ asset('images/place-1.jpg') }}');"></div>
	<div class="container" data-aos="fade-up">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb justify-content-center">
				<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Home</a></li>
				<li class="breadcrumb-item active">Dashboard</li>
			</ol>
		</nav>
		<h1 class="tt-page-title">My <span class="accent">Dashboard</span></h1>
		<p class="tt-page-subtitle">Manage your bookings and wishlist</p>
	</div>
</section>

<section class="tt-section">
	<div class="container">

		{{-- ─── Premium Stats Cards ─────────────────────────────────── --}}
		<div class="row g-4 mb-5" data-aos="fade-up">
			<div class="col-md-4">
				<div class="tt-stat-card">
					<div class="tt-stat-card-icon" style="background:#e8f4fd;color:#0d6efd;">
						<i class="fas fa-wallet"></i>
					</div>
					<div class="tt-stat-card-body">
						<div class="tt-stat-number">{{ $totalSpentFormatted }}</div>
						<div class="tt-stat-label">Total Travel Investment</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="tt-stat-card">
					<div class="tt-stat-card-icon" style="background:#d1fae5;color:#065f46;">
						<i class="fas fa-plane-departure"></i>
					</div>
					<div class="tt-stat-card-body">
						<div class="tt-stat-number">{{ $upcomingTrips }}</div>
						<div class="tt-stat-label">Upcoming Active Trips</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="tt-stat-card">
					<div class="tt-stat-card-icon" style="background:#fef3c7;color:#92400e;">
						<i class="fas fa-globe-americas"></i>
					</div>
					<div class="tt-stat-card-body">
						<div class="tt-stat-number">{{ $completedJourneys }}</div>
						<div class="tt-stat-label">Completed Journeys</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row g-4">
			<div class="col-lg-8">
				<div class="tt-sidebar-card" data-aos="fade-up">
					<h4 class="mb-1"><i class="fas fa-suitcase me-2"></i> My Bookings</h4>
					<p class="text-muted mb-4">Your recent tour bookings</p>

					<form method="GET" action="{{ route('dashboard') }}" class="row g-2 mb-4">
						<div class="col-md-6">
							<div class="input-group">
								<span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
								<input type="text" class="form-control" name="search" placeholder="Search destinations..." value="{{ request('search') }}">
							</div>
						</div>
						<div class="col-md-4">
							<select class="form-select" name="status">
								<option value="">All Status</option>
								<option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
								<option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
								<option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
							</select>
						</div>
						<div class="col-md-2">
							<button type="submit" class="btn-tt-primary w-100"><i class="fas fa-filter"></i></button>
						</div>
					</form>

					@if($bookings->isEmpty())
					<div class="tt-empty-state text-center py-4">
						<div class="icon" style="font-size:2.5rem;color:var(--tt-primary);"><i class="fas fa-suitcase"></i></div>
						<h5 class="mt-3">No bookings yet</h5>
						<p class="text-muted">Start exploring destinations and book your next adventure!</p>
						<a href="{{ route('packages') }}" class="btn-tt-primary"><i class="fas fa-compass me-1"></i> Explore Destinations</a>
					</div>
					@else
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>Destination</th>
									<th>Date</th>
									<th>Status</th>
									<th>Payment</th>
									<th>Total</th>
									<th>Review</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($bookings as $booking)
								@php
									$reviewKey = \App\Booking::class . ':' . $booking->id;
									$hasReview = isset($bookingReviewLookup[$reviewKey]);
								@endphp
								<tr>
									<td><a href="{{ route('bookings.show', $booking) }}" class="fw-semibold" style="color:var(--tt-primary);text-decoration:none;">{{ $booking->destination->title ?? 'N/A' }}</a></td>
									<td>{{ $booking->travel_date ? $booking->travel_date->format('M d, Y') : 'TBD' }}</td>
									<td><span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : 'warning') }}">{{ ucfirst($booking->status) }}</span></td>
									<td><span class="badge bg-{{ $booking->payment_status === 'paid' ? 'success' : 'secondary' }}">{{ ucfirst($booking->payment_status) }}</span></td>
									<td>${{ number_format($booking->total_price, 2) }}</td>
									<td>
										@if($hasReview)
											<span class="badge bg-secondary"><i class="fas fa-check me-1"></i>Reviewed</span>
										@elseif(in_array($booking->status, ['paid', 'confirmed', 'completed']) && $booking->travel_date && $booking->travel_date->lt(\Carbon\Carbon::now()))
											<button type="button" class="btn btn-sm btn-outline-warning btn-write-review"
												data-booking-type="destination"
												data-booking-id="{{ $booking->id }}"
												data-booking-title="{{ $booking->destination->title ?? 'Booking' }}">
												<i class="fas fa-star me-1"></i> Review
											</button>
										@else
											<span class="text-muted small">—</span>
										@endif
									</td>
									<td>
										@if($booking->status !== 'cancelled')
										<form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking?');">
											@csrf @method('DELETE')
											<button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
										</form>
										@endif
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="mt-3">
						{{ $bookings->links('pagination::bootstrap-4') }}
					</div>
					@endif
				</div>

				{{-- ─── Flight Bookings ─────────────────────────────────── --}}
				<div class="tt-sidebar-card mt-4" data-aos="fade-up">
					<h4 class="mb-1"><i class="fas fa-plane me-2"></i> My Flight Bookings</h4>
					<p class="text-muted mb-4">Your flight reservations</p>

					<form method="GET" action="{{ route('dashboard') }}" class="row g-2 mb-4">
						<div class="col-md-4">
							<select class="form-select" name="flight_status">
								<option value="">All Status</option>
								<option value="pending" {{ request('flight_status') === 'pending' ? 'selected' : '' }}>Pending</option>
								<option value="paid" {{ request('flight_status') === 'paid' ? 'selected' : '' }}>Paid</option>
								<option value="cancelled" {{ request('flight_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
							</select>
						</div>
						<div class="col-md-2">
							<button type="submit" class="btn-tt-primary w-100"><i class="fas fa-filter"></i></button>
						</div>
					</form>

					@if($flightBookings->isEmpty())
					<div class="tt-empty-state text-center py-4">
						<div class="icon" style="font-size:2.5rem;color:var(--tt-primary);"><i class="fas fa-plane"></i></div>
						<h5 class="mt-3">No flight bookings yet</h5>
						<p class="text-muted">Search for flights and book your next trip!</p>
						<a href="{{ route('flights') }}" class="btn-tt-primary"><i class="fas fa-search me-1"></i> Find Flights</a>
					</div>
					@else
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>Route</th>
									<th>Date</th>
									<th>Status</th>
									<th>Total</th>
									<th>Review</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($flightBookings as $fb)
								@php
									$fbDetails = $fb->flight_details ?? [];
									$fbLeg = $fbDetails['legs'][0] ?? $fbDetails;
									$fbOrigin = $fbLeg['originCode'] ?? $fbLeg['origin'] ?? '—';
									$fbDest   = $fbLeg['destinationCode'] ?? $fbLeg['destination'] ?? '—';
									$fbDep    = $fbLeg['departure'] ?? null;
									$fbReviewKey = \App\FlightBooking::class . ':' . $fb->id;
									$fbHasReview = isset($bookingReviewLookup[$fbReviewKey]);
								@endphp
								<tr>
									<td>
										<a href="{{ $fb->status === 'paid' ? route('flights.booking.success', $fb) : route('flights') }}" class="fw-semibold" style="color:var(--tt-primary);text-decoration:none;">
											{{ $fbOrigin }} → {{ $fbDest }}
										</a>
									</td>
									<td>{{ $fbDep ? \Carbon\Carbon::parse($fbDep)->format('M d, Y') : '—' }}</td>
									<td>
										<span class="badge bg-{{ $fb->status === 'paid' ? 'success' : ($fb->status === 'cancelled' ? 'danger' : 'warning') }}">
											{{ ucfirst($fb->status) }}
										</span>
									</td>
									<td>
										{{ config("currencies.symbols.{$fb->currency_code}", $fb->currency_code) }}{{ number_format($fb->converted_price, 2) }}
									</td>
									<td>
										@if($fbHasReview)
											<span class="badge bg-secondary"><i class="fas fa-check me-1"></i>Reviewed</span>
										@elseif($fb->status === 'paid' && $fbDep && \Carbon\Carbon::parse($fbDep)->lt(\Carbon\Carbon::now()))
											<button type="button" class="btn btn-sm btn-outline-warning btn-write-review"
												data-booking-type="flight"
												data-booking-id="{{ $fb->id }}"
												data-booking-title="{{ $fbOrigin }} → {{ $fbDest }}">
												<i class="fas fa-star me-1"></i> Review
											</button>
										@else
											<span class="text-muted small">—</span>
										@endif
									</td>
									<td>
										<div class="d-flex gap-1">
											@if(in_array($fb->status, ['paid', 'refunding', 'cancelled']))
											<a href="{{ route('flights.booking.invoice', $fb) }}" class="btn btn-sm btn-outline-primary" title="Invoice PDF">
												<i class="fas fa-file-pdf"></i>
											</a>
											@endif
											@if(in_array($fb->status, ['pending', 'paid']))
											<button type="button"
												class="btn btn-sm btn-outline-danger btn-cancel-flight"
												data-url="{{ route('flights.booking.cancel', $fb) }}"
												data-status="{{ $fb->status }}"
												title="Cancel">
												<i class="fas fa-times"></i>
											</button>
											@endif
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="mt-3">
						{{ $flightBookings->links('pagination::bootstrap-4') }}
					</div>
					@endif
				</div>
			</div>

			<div class="col-lg-4">
				<div class="tt-sidebar-card" data-aos="fade-left">
					<h4 class="mb-1"><i class="fas fa-heart me-2"></i> Wishlist</h4>
					<p class="text-muted mb-4">Your saved destinations</p>

					@if($wishlisted->isEmpty())
					<div class="tt-empty-state text-center py-4">
						<div class="icon" style="font-size:2rem;color:var(--tt-primary);"><i class="fas fa-heart"></i></div>
						<p class="text-muted mt-2">No saved destinations yet</p>
					</div>
					@else
					<div class="d-flex flex-column gap-2">
						@foreach($wishlisted as $dest)
						<a href="{{ route('desti.show', $dest->id) }}" class="d-flex align-items-center gap-3 p-2 rounded-3 text-decoration-none" style="background:var(--tt-light);">
							<img src="{{ $dest->image_url }}" alt="{{ $dest->title }}" style="width:50px;height:50px;border-radius:8px;object-fit:cover;" loading="lazy">
							<div>
								<div class="fw-semibold" style="color:var(--tt-text);">{{ $dest->title }}</div>
								<small class="text-muted">{{ $dest->converted_pricing }}</small>
							</div>
						</a>
						@endforeach
					</div>
					@endif
				</div>

				<div class="tt-sidebar-card mt-4" data-aos="fade-left">
					<h5 class="mb-3"><i class="fas fa-cog me-2"></i> Quick Links</h5>
					<div class="d-flex flex-column gap-2">
						<a href="{{ route('testimonials.index') }}" class="btn-tt-outline w-100"><i class="fas fa-star me-1"></i> Manage Testimonials</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@include('partials.footer')
@endsection

{{-- ─── Review Modal ──────────────────────────────────────────────── --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content" style="border-radius:var(--tt-radius-lg);border:none;box-shadow:var(--tt-shadow-lg);">
			<div class="modal-header" style="border-bottom:1px solid var(--tt-border);padding:1.25rem 1.5rem;">
				<h5 class="modal-title" id="reviewModalLabel" style="font-family:var(--tt-font-display);">
					<i class="fas fa-star text-warning me-2"></i>Review Your Journey
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body" style="padding:1.5rem;">
				<p class="text-muted mb-3" id="reviewBookingTitle">Share your experience</p>

				<form id="reviewForm">
					<input type="hidden" name="booking_type" id="reviewBookingType" value="">
					<input type="hidden" name="booking_id" id="reviewBookingId" value="">

					<div class="mb-4 text-center">
						<label class="form-label fw-semibold mb-2" style="font-size:0.9rem;">Your Rating</label>
						<div class="tt-star-rating" id="starRating">
							@for($i = 1; $i <= 5; $i++)
							<i class="far fa-star tt-star" data-value="{{ $i }}"></i>
							@endfor
						</div>
						<div class="mt-2">
							<span id="ratingText" class="text-muted small">Select your rating</span>
						</div>
						<input type="hidden" name="rating" id="ratingValue" value="0">
					</div>

					<div class="mb-3">
						<label for="reviewComment" class="form-label fw-semibold" style="font-size:0.9rem;">
							Your Review <span class="text-muted fw-normal">(optional)</span>
						</label>
						<textarea class="tt-form-textarea" id="reviewComment" name="comment"
							rows="4" placeholder="Tell us about your experience..." maxlength="2000"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer" style="border-top:1px solid var(--tt-border);padding:1rem 1.5rem;">
				<button type="button" class="btn-tt-outline" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn-tt-primary" id="submitReviewBtn">
					<i class="fas fa-paper-plane me-1"></i> Submit Review
				</button>
			</div>
		</div>
	</div>
</div>

@push('styles')
<style>
.tt-star-rating {
    display: inline-flex;
    gap: 6px;
    direction: ltr;
}
.tt-star {
    font-size: 2rem;
    cursor: pointer;
    color: #d1d5db;
    transition: color 0.15s ease, transform 0.15s ease;
}
.tt-star.hover,
.tt-star.active {
    color: #f59e0b;
}
.tt-star:hover {
    transform: scale(1.2);
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    // ── Toast ───────────────────────────────────────────────────────
    function showToast(message, type) {
        var t = type === 'error' ? 'error' : (type || 'success');
        ttToast.show(message, t, 4000);
    }

    // ── Cancel flight booking handler ────────────────────────────────
    document.querySelectorAll('.btn-cancel-flight').forEach(function(btn) {
        btn.addEventListener('click', async function(e) {
            var url = btn.getAttribute('data-url');
            var status = btn.getAttribute('data-status');
            var isPaid = status === 'paid';
            var msg = isPaid
                ? 'Are you sure you want to cancel this booking? A refund will be processed back to your original payment method.'
                : 'Cancel this flight booking?';

            if (!confirm(msg)) return;

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

            var res = await ttFetch(url, {
                method: 'DELETE',
            }, {
                button: btn,
                buttonHtml: '<i class="fas fa-times"></i>',
                showToast: false,
            });

            if (res.success && res.data?.success) {
                showToast(res.data.message, 'success');
                setTimeout(function() { location.reload(); }, 1200);
            } else {
                ttToast.show(res.data?.message || 'Request failed.', 'error');
            }
        });
    });

    // ── Star Rating Interactivity ───────────────────────────────────
    var stars = document.querySelectorAll('.tt-star');
    var ratingValue = document.getElementById('ratingValue');
    var ratingText = document.getElementById('ratingText');
    var labels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

    function updateStars(value) {
        stars.forEach(function(s) {
            var v = parseInt(s.getAttribute('data-value'), 10);
            s.classList.toggle('active', v <= value);
            s.classList.toggle('far', v > value);
            s.classList.toggle('fas', v <= value);
        });
        ratingValue.value = value;
        ratingText.textContent = value > 0 ? labels[value] + ' (' + value + '/5)' : 'Select your rating';
    }

    stars.forEach(function(star) {
        star.addEventListener('mouseenter', function() {
            var v = parseInt(this.getAttribute('data-value'), 10);
            stars.forEach(function(s) {
                var sv = parseInt(s.getAttribute('data-value'), 10);
                s.classList.toggle('hover', sv <= v);
            });
        });
        star.addEventListener('mouseleave', function() {
            stars.forEach(function(s) { s.classList.remove('hover'); });
        });
        star.addEventListener('click', function() {
            updateStars(parseInt(this.getAttribute('data-value'), 10));
        });
    });

    // ── Open Review Modal ───────────────────────────────────────────
    var reviewModalEl = document.getElementById('reviewModal');
    var reviewModal = new bootstrap.Modal(reviewModalEl);

    document.querySelectorAll('.btn-write-review').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('reviewBookingType').value = this.getAttribute('data-booking-type');
            document.getElementById('reviewBookingId').value = this.getAttribute('data-booking-id');
            document.getElementById('reviewBookingTitle').textContent =
                'Share your experience for "' + this.getAttribute('data-booking-title') + '"';
            // Reset form
            document.getElementById('reviewComment').value = '';
            updateStars(0);
            reviewModal.show();
        });
    });

    // ── Submit Review ───────────────────────────────────────────────
    document.getElementById('submitReviewBtn').addEventListener('click', async function() {
        var btn = this;
        var rating = parseInt(ratingValue.value, 10);
        if (!rating || rating < 1) {
            showToast('Please select a rating.', 'error');
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Submitting...';

        var res = await ttFetch('{{ route("booking-reviews.store") }}', {
            method: 'POST',
            body: {
                rating: rating,
                comment: document.getElementById('reviewComment').value,
                booking_type: document.getElementById('reviewBookingType').value,
                booking_id: document.getElementById('reviewBookingId').value,
            },
        }, {
            button: btn,
            buttonHtml: '<i class="fas fa-paper-plane me-1"></i> Submit Review',
            showToast: false,
        });

        if (res.success && res.data?.success) {
            showToast(res.data.message, 'success');
            reviewModal.hide();
            // Reload to show "Reviewed" badge
            setTimeout(function() { location.reload(); }, 1000);
        } else {
            showToast(res.data?.message || 'Could not submit review.', 'error');
        }
    });

    // Clean up modal backdrop when hidden
    reviewModalEl.addEventListener('hidden.bs.modal', function() {
        document.querySelectorAll('.modal-backdrop').forEach(function(el) { el.remove(); });
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
    });
})();
</script>
@endpush
