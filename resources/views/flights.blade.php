@extends('layouts.front')

@section('title', 'Search Flights - Travelia')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('seo')
	<title>Search Flights ✈️ | Travelia</title>
	<meta name="description" content="Search and compare flight prices worldwide. Find the best deals on flights from Tunis, Paris, London and more with Travelia.">
@endsection

@section('page')
@include('partials.navbar')

<!-- Page Hero -->
<section class="tt-page-hero">
	<div class="tt-page-hero-bg" style="background-image: url('{{ asset('images/flight.png') }}');"></div>
	<div class="container" data-aos="fade-up">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb justify-content-center">
				<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Home</a></li>
				<li class="breadcrumb-item active">Flights</li>
			</ol>
		</nav>
		<h1 class="tt-page-title">Search <span class="accent">Flights</span></h1>
		<p class="tt-page-subtitle">Find the best flight deals worldwide</p>
	</div>
</section>

<!-- Search Form -->
<section class="tt-section-sm">
	<div class="container">
		<div class="tt-search-bar" data-aos="fade-up">
			<form id="flightSearchForm">

				{{-- Flight Type Tabs --}}
				<div class="tt-flight-tabs mb-3 d-flex gap-2">
					<button type="button" class="tt-flight-tab active" data-type="one_way">
						<i class="fas fa-arrow-right me-1"></i> One-Way
					</button>
					<button type="button" class="tt-flight-tab" data-type="return">
						<i class="fas fa-exchange-alt me-1"></i> Return
					</button>
					<button type="button" class="tt-flight-tab" data-type="multi_city">
						<i class="fas fa-layer-group me-1"></i> Multi-City
					</button>
					<input type="hidden" name="flight_type" value="one_way">
				</div>

				{{-- Single-leg form (one-way / return) --}}
				<div id="singleLegFields">
					<div class="row g-3 align-items-end">
						<div class="col-lg-2">
							<div class="tt-form-group">
								<label><i class="fas fa-plane-departure"></i> From</label>
								<input type="text" class="tt-input" name="originLocationCode" id="originInput"
									   placeholder="City or IATA" value="TUN" required>
								<div id="originSuggestions" class="tt-search-suggestions" style="display:none;"></div>
							</div>
						</div>
						<div class="col-lg-2">
							<div class="tt-form-group">
								<label><i class="fas fa-plane-arrival"></i> To</label>
								<input type="text" class="tt-input" name="destinationLocationCode" id="destInput"
									   placeholder="City or IATA" value="CDG" required>
								<div id="destSuggestions" class="tt-search-suggestions" style="display:none;"></div>
							</div>
						</div>
						<div class="col-lg-2">
							<div class="tt-form-group">
								<label><i class="fas fa-calendar"></i> Departure</label>
								<input type="date" class="tt-input" name="departureDate" id="departureDate"
									   value="{{ date('Y-m-d') }}" required>
							</div>
						</div>
						<div class="col-lg-2" id="returnDateGroup">
							<div class="tt-form-group">
								<label><i class="fas fa-calendar-check"></i> Return</label>
								<input type="date" class="tt-input" name="returnDate" id="returnDate" placeholder="Optional">
							</div>
						</div>
						<div class="col-lg-1">
							<div class="tt-form-group">
								<label><i class="fas fa-user"></i> Adults</label>
								<select class="tt-select" name="adults">
									@for($i = 1; $i <= 9; $i++)
										<option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
									@endfor
								</select>
							</div>
						</div>
						<div class="col-lg-1">
							<div class="tt-form-group">
								<label><i class="fas fa-child"></i> Kids</label>
								<select class="tt-select" name="children">
									@for($i = 0; $i <= 9; $i++)
										<option value="{{ $i }}" {{ $i == 0 ? 'selected' : '' }}>{{ $i }}</option>
									@endfor
								</select>
							</div>
						</div>
					</div>
					<div class="row g-3 mt-1">
						<div class="col-lg-2">
							<select class="tt-select" name="travelClass">
								<option value="ECONOMY">Economy</option>
								<option value="BUSINESS">Business</option>
								<option value="FIRST">First</option>
							</select>
						</div>
					</div>
				</div>

				{{-- Multi-city form (hidden by default) --}}
				<div id="multiCityFields" style="display:none;">
					<div id="multiCityLegs">
						<div class="tt-multi-leg" data-leg-index="0">
							<div class="row g-3 align-items-end mb-2">
								<div class="col-lg-3">
									<div class="tt-form-group">
										<label><i class="fas fa-plane-departure"></i> Flight 1 From</label>
										<input type="text" class="tt-input" name="legs[0][origin]" placeholder="City or IATA" required>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="tt-form-group">
										<label><i class="fas fa-plane-arrival"></i> Flight 1 To</label>
										<input type="text" class="tt-input" name="legs[0][destination]" placeholder="City or IATA" required>
									</div>
								</div>
								<div class="col-lg-3">
									<div class="tt-form-group">
										<label><i class="fas fa-calendar"></i> Flight 1 Date</label>
										<input type="date" class="tt-input" name="legs[0][departure_date]" value="{{ date('Y-m-d') }}" required>
									</div>
								</div>
								<div class="col-lg-3 text-end">
									<button type="button" class="btn btn-outline-danger btn-sm tt-remove-leg" style="display:none;" title="Remove leg">
										<i class="fas fa-times"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row g-3 align-items-end mb-3">
						<div class="col-lg-12">
							<button type="button" class="btn-tt-outline btn-sm" id="addLegBtn">
								<i class="fas fa-plus me-1"></i> Add Flight (max 3)
							</button>
							<small class="text-muted ms-2" id="legCounter">1 of 3 legs</small>
						</div>
					</div>
					<div class="row g-3 mt-1">
						<div class="col-lg-2">
							<label><i class="fas fa-user"></i> Adults</label>
							<select class="tt-select" name="adults">
								@for($i = 1; $i <= 9; $i++)
									<option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
								@endfor
							</select>
						</div>
						<div class="col-lg-2">
							<label><i class="fas fa-child"></i> Kids</label>
							<select class="tt-select" name="children">
								@for($i = 0; $i <= 9; $i++)
									<option value="{{ $i }}" {{ $i == 0 ? 'selected' : '' }}>{{ $i }}</option>
								@endfor
							</select>
						</div>
						<div class="col-lg-2">
							<select class="tt-select" name="travelClass">
								<option value="ECONOMY">Economy</option>
								<option value="BUSINESS">Business</option>
								<option value="FIRST">First</option>
							</select>
						</div>
					</div>
				</div>

				{{-- Baggage Selection --}}
				<div class="row g-3 mt-2 align-items-end">
					<div class="col-lg-2">
						<div class="tt-form-group">
							<label><i class="fas fa-suitcase me-1"></i> Cabin Bag</label>
							<div class="tt-baggage-selector">
								<select class="tt-select" name="cabin_bags">
									<option value="0">0 bags</option>
									<option value="1" selected>1 bag</option>
								</select>
								<small class="text-muted d-block" style="font-size:0.7rem;">Max 8kg, 55x40x20cm</small>
							</div>
						</div>
					</div>
					<div class="col-lg-2">
						<div class="tt-form-group">
							<label><i class="fas fa-suitcase-rolling me-1"></i> Checked Bag</label>
							<div class="tt-baggage-selector">
								<select class="tt-select" name="checked_bags">
									<option value="0">0 bags</option>
									<option value="1" selected>1 bag</option>
									<option value="2">2 bags</option>
									<option value="3">3 bags</option>
								</select>
								<small class="text-muted d-block" style="font-size:0.7rem;">Up to 23kg per bag</small>
							</div>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="tt-form-group">
							<label>&nbsp;</label>
							<button type="submit" class="btn-tt-primary w-100" id="searchBtn">
								<i class="fas fa-search me-1"></i> Search Flights
							</button>
						</div>
					</div>
				</div>

			</form>
		</div>
	</div>
</section>

<!-- Price Calendar -->
<section class="tt-section tt-section-light" id="calendarSection" style="display:none;">
	<div class="container">
		<div class="tt-section-header text-center" data-aos="fade-up">
			<div class="tt-pretitle">Best Deals</div>
			<h2 class="tt-title">Price <span class="accent">Calendar</span></h2>
			<p class="tt-subtitle">Cheapest fares for the next 30 days</p>
		</div>
		<div id="calendarLoader" class="text-center py-4" style="display:none;">
			<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
		</div>
		<div id="calendarGrid" class="row g-2 justify-content-center" data-aos="fade-up"></div>
	</div>
</section>

<!-- Results -->
<section class="tt-section" id="resultsSection" style="display:none;">
	<div class="container">
		<div class="tt-section-header text-center" data-aos="fade-up">
			<h2 class="tt-title">Available <span class="accent">Flights</span></h2>
			<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
				<p class="tt-subtitle mb-0" id="resultsCount"></p>
				<div class="d-flex gap-2" id="sortControls" style="display:none;">
					<select class="tt-select form-select-sm" id="sortSelect" style="width:auto;">
						<option value="price">Sort by Price</option>
						<option value="duration">Sort by Duration</option>
						<option value="departure">Sort by Departure</option>
					</select>
					<select class="tt-select form-select-sm" id="stopsFilter" style="width:auto;">
						<option value="">Any Stops</option>
						<option value="0">Direct Only</option>
						<option value="1">Max 1 Stop</option>
					</select>
				</div>
			</div>
		</div>
		<div id="resultsList"></div>
		<div id="resultsLoader" style="display:none;">
			<div class="tt-skeleton-grid">
				@for($i = 0; $i < 4; $i++)
				<div class="tt-skeleton-card">
					<div class="d-flex justify-content-between">
						<div class="skeleton-block" style="width:120px;height:40px;"></div>
						<div class="skeleton-block" style="width:80px;height:40px;"></div>
						<div class="skeleton-block" style="width:120px;height:40px;"></div>
					</div>
					<div class="d-flex gap-3 mt-3">
						<div class="skeleton-block" style="width:60%;height:16px;"></div>
						<div class="skeleton-block" style="width:20%;height:16px;"></div>
					</div>
					<div class="d-flex justify-content-between mt-3">
						<div class="skeleton-block" style="width:100px;height:14px;"></div>
						<div class="skeleton-block" style="width:140px;height:36px;"></div>
					</div>
				</div>
				@endfor
			</div>
		</div>
		<div id="resultsEmpty" class="tt-empty-state" style="display:none;" data-aos="fade-up">
			<div class="icon"><i class="fas fa-plane-slash"></i></div>
			<h3>No Flights Found</h3>
			<p id="emptyMessage">Try different dates or destinations.</p>
		</div>
	</div>
</section>

<!-- Flight Route Map -->
<section class="tt-section tt-section-light" id="mapSection" style="display:none;">
    <div class="container">
        <div class="tt-section-header text-center" data-aos="fade-up">
            <div class="tt-pretitle">Route</div>
            <h2 class="tt-title">Flight <span class="accent">Map</span></h2>
            <p class="tt-subtitle">Visual overview of your journey</p>
        </div>
        <div id="flightMap" style="height:400px;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1);" data-aos="fade-up"></div>
    </div>
</section>

@include('partials.footer')
@endsection

@push('styles')
<style>
.tt-flight-card {
	background: #fff;
	border-radius: 16px;
	overflow: hidden;
	box-shadow: 0 2px 12px rgba(0,0,0,0.08);
	transition: transform 0.2s, box-shadow 0.2s;
	padding: 1.5rem;
	margin-bottom: 1rem;
}
.tt-flight-card:hover {
	transform: translateY(-2px);
	box-shadow: 0 8px 32px rgba(0,0,0,0.12);
}
.tt-flight-route { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
.tt-flight-time { font-size: 1.5rem; font-weight: 700; color: #1a1a2e; }
.tt-flight-airport { font-size: 0.85rem; color: #6c757d; }
.tt-flight-duration { text-align: center; font-size: 0.8rem; color: #6c757d; }
.tt-flight-line { display: flex; align-items: center; gap: 0.5rem; }
.tt-flight-line hr { flex: 1; border-top: 2px dashed #dee2e6; margin: 0; min-width: 60px; }
.tt-flight-stops { font-size: 0.75rem; color: #dc3545; font-weight: 600; }
.tt-flight-carrier { font-size: 0.85rem; color: #495057; margin-top: 0.5rem; }
.tt-flight-price { font-size: 1.4rem; font-weight: 800; color: var(--tt-primary, #0d6efd); }
.tt-flight-leg { border-bottom: 1px solid #f0f0f0; padding-bottom: 1rem; margin-bottom: 1rem; }
.tt-flight-leg:last-child { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
.tt-flight-type {
	font-size: 0.75rem;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	padding: 2px 10px;
	border-radius: 20px;
	display: inline-block;
	margin-bottom: 0.75rem;
}
.tt-flight-type.outbound { background: #e8f4fd; color: #0d6efd; }
.tt-flight-type.return { background: #e8fde8; color: #198754; }
.tt-search-suggestions {
	position: absolute; top: 100%; left: 0; right: 0;
	background: #fff; border: 1px solid #ddd; border-radius: 8px;
	box-shadow: 0 4px 20px rgba(0,0,0,0.12); z-index: 1000;
	max-height: 240px; overflow-y: auto; margin-top: 4px;
}
.tt-form-group { position: relative; }

/* Skeleton */
.tt-skeleton-grid { display: grid; gap: 1rem; }
.tt-skeleton-card {
	background: #fff; border-radius: 16px; padding: 1.5rem;
	box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}
.skeleton-block {
	background: linear-gradient(90deg, #e9ecef 25%, #f8f9fa 50%, #e9ecef 75%);
	background-size: 200% 100%;
	animation: shimmer 1.5s infinite;
	border-radius: 8px;
}
@keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

.calendar-day { transition: transform 0.15s; }
.calendar-day:hover { transform: scale(1.1); }

.flight-tag {
	display: inline-block;
	padding: 2px 10px;
	border-radius: 20px;
	font-size: 0.75rem;
	font-weight: 600;
}
.flight-tag.direct { background: #d1fae5; color: #065f46; }
.flight-tag.stops { background: #fee2e2; color: #991b1b; }

/* Flight Type Tabs */
.tt-flight-tabs { border-bottom: 2px solid #e9ecef; padding-bottom: 0.5rem; }
.tt-flight-tab {
	background: none; border: none; padding: 0.5rem 1.25rem;
	font-size: 0.85rem; font-weight: 600; color: #6c757d;
	border-radius: 8px 8px 0 0; cursor: pointer; transition: all 0.2s;
}
.tt-flight-tab:hover { color: var(--tt-primary); background: #f8f9fa; }
.tt-flight-tab.active {
	color: var(--tt-primary); background: #e8f4fd;
	box-shadow: 0 -2px 0 var(--tt-primary) inset;
}
.tt-baggage-selector select { margin-bottom: 0; }
.tt-multi-leg { background: #f8f9fa; border-radius: 8px; padding: 1rem; margin-bottom: 0.5rem; }
.tt-multi-leg:first-child { background: transparent; padding: 0; }
.tt-remove-leg { font-size: 0.75rem; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/tt-map.js') }}"></script>
<script>
const apiBase = '/api/v1/flights';
window.userCurrency = '{{ $userCurrency ?? "TND" }}';

let allAirportsCache = [];

async function fetchAllAirports() {
	// Load popular airports/cities on page load for quick suggestions
	const result = await ttFetch(`${apiBase}/airports?keyword=a`, {}, { showToast: false });
	if (result.success) {
		allAirportsCache = result.data?.data || [];
	}
}

async function searchAirports(query, inputId, suggestionId) {
	const dropdown = document.getElementById(suggestionId);
	if (!dropdown) return;
	if (query.length < 1) {
		showAllAirports(inputId, suggestionId, 50);
		return;
	}
	dropdown.innerHTML = '<div style="padding:12px 16px;color:#999;font-size:14px;text-align:center;"><div class="spinner-border spinner-border-sm me-1" role="status"></div> Searching...</div>';
	dropdown.style.display = 'block';
	const result = await ttFetch(`${apiBase}/airports?keyword=${encodeURIComponent(query)}`, {}, { showToast: false });
	if (!result.success || !result.data?.data?.length) {
		dropdown.innerHTML = '<div style="padding:12px 16px;color:#999;font-size:14px;">No airports found</div>';
		dropdown.style.display = 'block';
		return;
	}
	const airports = result.data.data;
	dropdown.innerHTML = airports.map(a => renderSuggestion(a, inputId)).join('');
	dropdown.style.display = 'block';
}

function getAirportDisplayName(a) {
	if (a.subType === 'CITY' || a.code?.startsWith('city:')) {
		return a.city || a.name;
	}
	return a.code + ' - ' + a.name;
}

function renderSuggestion(a, inputId) {
	const isCity = a.subType === 'CITY' || a.code?.startsWith('city:');
	if (isCity) {
		return `
		<div style="padding:10px 16px;cursor:pointer;border-bottom:1px solid #f0f0f0;transition:background 0.2s;background:#f0f7ff;"
			 onmouseenter="this.style.background='#dcecff'" onmouseleave="this.style.background='#f0f7ff'"
			 onclick="selectAirport('${inputId}', '${a.code}')">
			<div style="font-weight:700;font-size:14px;color:#1a1a2e;">📍 ${a.city || a.name}</div>
			<div style="font-size:12px;color:#666;">✈️ All airports in ${a.subtitle || a.city || ''}</div>
		</div>`;
	}
	return `
		<div style="padding:10px 16px;cursor:pointer;border-bottom:1px solid #f0f0f0;transition:background 0.2s;"
			 onmouseenter="this.style.background='#f8f9fa'" onmouseleave="this.style.background=''"
			 onclick="selectAirport('${inputId}', '${a.code}')">
			<div style="font-weight:600;font-size:14px;">${a.code} - ${a.name}</div>
			<div style="font-size:12px;color:#999;">${a.subtitle || ''}</div>
		</div>`;
}

function showAllAirports(inputId, suggestionId, maxItems) {
	const dropdown = document.getElementById(suggestionId);
	if (!dropdown || !allAirportsCache.length) return;
	// Show cities first, then airports
	const cities = allAirportsCache.filter(a => a.subType === 'CITY' || a.code?.startsWith('city:'));
	const airports = allAirportsCache.filter(a => a.subType !== 'CITY' && !a.code?.startsWith('city:'));
	const combined = [...cities, ...airports];
	const items = maxItems ? combined.slice(0, maxItems) : combined;
	dropdown.innerHTML = items.map(a => renderSuggestion(a, inputId)).join('');
	if (combined.length > items.length) {
		dropdown.innerHTML += '<div style="padding:8px 16px;color:#999;font-size:12px;text-align:center;">Type to see more results...</div>';
	}
	dropdown.style.display = 'block';
}

function setupAirportAutocomplete(inputId, suggestionId) {
	const input = document.getElementById(inputId);
	const dropdown = document.getElementById(suggestionId);
	if (!input || !dropdown) return;
	let timer;

	input.addEventListener('focus', function() {
		if (!this.value.trim() && allAirportsCache.length) {
			showAllAirports(inputId, suggestionId, 50);
		}
	});

	input.addEventListener('input', function() {
		clearTimeout(timer);
		const q = this.value.trim();
		if (q.length < 1) { showAllAirports(inputId, suggestionId, 50); return; }
		timer = setTimeout(() => searchAirports(q, inputId, suggestionId), 300);
	});
	document.addEventListener('click', e => {
		if (!input.parentElement.contains(e.target)) dropdown.style.display = 'none';
	});
}

function selectAirport(inputId, code) {
	const input = document.getElementById(inputId);
	if (code.startsWith('city:')) {
		const cityName = code.replace('city:', '');
		input.value = code;
		input.dataset.cityCode = code;
		// Show the city name to the user
		input.placeholder = '📍 ' + cityName;
	} else {
		input.value = code;
		delete input.dataset.cityCode;
	}
	const d = document.getElementById(inputId === 'originInput' ? 'originSuggestions' : 'destSuggestions');
	if (d) d.style.display = 'none';
}

// ── Flight Type Tabs ────────────────────────────────────────────────────
const flightTypeInput = document.querySelector('input[name="flight_type"]');
const tabBtns = document.querySelectorAll('.tt-flight-tab');
const singleLegFields = document.getElementById('singleLegFields');
const multiCityFields = document.getElementById('multiCityFields');
const returnDateGroup = document.getElementById('returnDateGroup');

function toggleFields(type) {
	const isMulti = type === 'multi_city';
	singleLegFields.style.display = isMulti ? 'none' : 'block';
	multiCityFields.style.display = isMulti ? 'block' : 'none';
	returnDateGroup.style.display = (type === 'return') ? 'block' : 'none';

	// Disable hidden fields so they don't submit empty values
	singleLegFields.querySelectorAll('input, select').forEach(el => el.disabled = isMulti);
	multiCityFields.querySelectorAll('input, select').forEach(el => el.disabled = !isMulti);
}

tabBtns.forEach(btn => {
	btn.addEventListener('click', function() {
		tabBtns.forEach(b => b.classList.remove('active'));
		this.classList.add('active');
		const type = this.dataset.type;
		flightTypeInput.value = type;
		toggleFields(type);
	});
});
// Init: hide return date on one-way default
toggleFields('one_way');

// ── Multi-City Leg Management ──────────────────────────────────────────
const legsContainer = document.getElementById('multiCityLegs');
const addLegBtn = document.getElementById('addLegBtn');
const legCounter = document.getElementById('legCounter');
const MAX_LEGS = 3;

function updateLegIndices() {
	const legs = legsContainer.querySelectorAll('.tt-multi-leg');
	legs.forEach((leg, i) => {
		leg.dataset.legIndex = i;
		leg.querySelectorAll('input').forEach(input => {
			const name = input.name.replace(/\[\d+\]/g, `[${i}]`);
			input.name = name;
		});
		const label = leg.querySelector('label');
		if (label) label.textContent = `Flight ${i + 1} ${label.textContent.includes('From') ? 'From' : 'To'}`;
		const dateLabel = leg.querySelectorAll('label')[2];
		if (dateLabel) dateLabel.textContent = `Flight ${i + 1} Date`;
		const removeBtn = leg.querySelector('.tt-remove-leg');
		if (removeBtn) removeBtn.style.display = legs.length > 1 ? 'inline-block' : 'none';
	});
	legCounter.textContent = `${legs.length} of ${MAX_LEGS} legs`;
	if (addLegBtn) addLegBtn.style.display = legs.length >= MAX_LEGS ? 'none' : 'inline-block';
}

function addLeg(origin, destination, date) {
	const legs = legsContainer.querySelectorAll('.tt-multi-leg');
	const idx = legs.length;
	if (idx >= MAX_LEGS) return;

	const template = legs[0].cloneNode(true);
	template.querySelector('input[name^="legs"]').value = origin || '';
	template.querySelectorAll('input').forEach((input, i) => {
		const names = ['legs[0][origin]', 'legs[0][destination]', 'legs[0][departure_date]'];
		if (i < names.length) input.name = names[i].replace('[0]', `[${idx}]`);
		if (i === 0) input.value = origin || '';
		if (i === 1) input.value = destination || '';
		if (i === 2) input.value = date || '{{ date('Y-m-d') }}';
	});
	legsContainer.appendChild(template);
	updateLegIndices();
}

legsContainer.addEventListener('click', function(e) {
	const btn = e.target.closest('.tt-remove-leg');
	if (!btn) return;
	const leg = btn.closest('.tt-multi-leg');
	if (leg && legsContainer.querySelectorAll('.tt-multi-leg').length > 1) {
		leg.remove();
		updateLegIndices();
	}
});

addLegBtn?.addEventListener('click', function() {
	addLeg('', '', '{{ date('Y-m-d') }}');
});

// ── Airport Autocomplete ────────────────────────────────────────────────
fetchAllAirports().then(() => {
	setupAirportAutocomplete('originInput', 'originSuggestions');
	setupAirportAutocomplete('destInput', 'destSuggestions');
});

// Sync return date min when departure changes
const depInput = document.getElementById('departureDate');
const retInput = document.getElementById('returnDate');
depInput.addEventListener('change', function() {
	retInput.min = this.value;
	if (retInput.value && retInput.value < this.value) {
		retInput.value = this.value;
	}
});
if (depInput.value) retInput.min = depInput.value;

// ── Flight Route Map Renderer ────────────────────────────────────────────
let flightMapInstance = null;

function renderFlightMap(coordinates, flightType) {
    const container = document.getElementById('flightMap');
    if (!container) return;

    if (flightMapInstance) {
        flightMapInstance.destroy();
    }

    flightMapInstance = new TraveliaMap('flightMap', {
        center: { lat: 34.0, lng: 9.0 },
        zoom: 3,
    });

    // Initialize without fetching destination markers
    flightMapInstance.container = container;
    flightMapInstance.map = L.map(container, {
        center: [flightMapInstance.centerLat, flightMapInstance.centerLng],
        zoom: flightMapInstance.defaultZoom,
        minZoom: 2,
        maxZoom: 18,
        zoomControl: true,
        scrollWheelZoom: true,
    });

    L.tileLayer(flightMapInstance.options.tileUrl, {
        attribution: flightMapInstance.options.tileAttribution,
        maxZoom: 18,
    }).addTo(flightMapInstance.map);

    flightMapInstance.flightRouteGroup = L.layerGroup().addTo(flightMapInstance.map);

    setTimeout(() => flightMapInstance.map.invalidateSize(), 300);
    flightMapInstance.renderFlightRoutes(coordinates, flightType);
}

// ── Form Submission ──────────────────────────────────────────────────────
document.getElementById('flightSearchForm').addEventListener('submit', async function(e) {
	e.preventDefault();

	const flightType = flightTypeInput.value;
	const btn = document.getElementById('searchBtn');
	const loader = document.getElementById('resultsLoader');
	const section = document.getElementById('resultsSection');
	const list = document.getElementById('resultsList');
	const count = document.getElementById('resultsCount');
	const empty = document.getElementById('resultsEmpty');
	const msg = document.getElementById('emptyMessage');
	const mapSection = document.getElementById('mapSection');
		// Validate multi-city legs
	if (flightType === 'multi_city') {
		const legs = legsContainer.querySelectorAll('.tt-multi-leg');
		if (legs.length < 2) {
			ttToast.show('Multi-city requires at least 2 flight legs.', 'error');
			return;
		}
		let valid = true;
		legs.forEach((leg, i) => {
			const inputs = leg.querySelectorAll('input');
			inputs.forEach(inp => { if (!inp.value.trim()) valid = false; });
		});
		if (!valid) {
			ttToast.show('Please fill in all fields for each flight leg.', 'error');
			return;
		}
	}

	// Validate return date
	if (flightType === 'return') {
		const depVal = depInput.value;
		const retVal = retInput.value;
		if (retVal && retVal < depVal) {
			ttToast.show('The return date must be on or after the departure date.', 'error');
			return;
		}
	}

	btn.disabled = true;
	btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Searching...';
	empty.style.display = 'none';
	list.innerHTML = '';
	section.style.display = 'block';
	loader.style.display = 'block';
	mapSection.style.display = 'none';

	// Build POST body
	const formData = new FormData(this);
	formData.set('flight_type', flightType);
	formData.append('currency', window.userCurrency || 'TND');

	const result = await ttFetch('/flights/search', {
		method: 'POST',
		body: formData,
	}, {
		button: btn,
		buttonHtml: '<i class="fas fa-search me-1"></i> Search',
		spinner: loader,
	});

	if (!result.success || !result.data?.success || !result.data?.count) {
		empty.style.display = 'block';
		msg.textContent = result.data?.message || 'No flights found for this route. Try different dates.';
		loader.style.display = 'none';
		btn.disabled = false;
		btn.innerHTML = '<i class="fas fa-search me-1"></i> Search';
		return;
	}

	count.textContent = `Found ${result.data.count} flight options`;
	list.innerHTML = result.data.html;
	loader.style.display = 'none';

	// Render flight route map
	if (result.data.coordinates && result.data.coordinates.length) {
		mapSection.style.display = 'block';
		renderFlightMap(result.data.coordinates, flightType);
	}
});

// ── Price Calendar ───────────────────────────────────────────────────────
async function fetchPriceCalendar(origin, dest, fromDate) {
	const section = document.getElementById('calendarSection');
	const loader = document.getElementById('calendarLoader');
	const grid = document.getElementById('calendarGrid');
	if (!origin || !dest || !fromDate) return;
	section.style.display = 'block';
	loader.style.display = 'block';
	grid.innerHTML = '';
	const result = await ttFetch(`${apiBase}/price-calendar?originLocationCode=${encodeURIComponent(origin)}&destinationLocationCode=${encodeURIComponent(dest)}&fromDate=${fromDate}`, {}, { showToast: false });
	loader.style.display = 'none';
	if (!result.success || !result.data?.data?.length) { section.style.display = 'none'; return; }
	const minPrice = Math.min(...result.data.data.map(d => d.converted || Infinity));
	grid.innerHTML = result.data.data.map(d => {
		const day = new Date(d.date + 'T12:00:00');
		const dayName = day.toLocaleDateString('en', { weekday: 'short' });
		const dayNum = day.getDate();
		const isCheapest = d.converted && d.converted === minPrice;
		return `<div class="col-2 col-md-1">
			<div class="calendar-day ${isCheapest ? 'cheapest' : ''}" style="text-align:center;padding:6px 2px;border-radius:8px;background:${isCheapest ? 'var(--tt-accent,#f59e0b)' : '#fff'};color:${isCheapest ? '#fff' : '#333'};font-size:0.75rem;box-shadow:0 1px 4px rgba(0,0,0,0.06);cursor:pointer;" title="${d.formatted || ''}">
				<div style="font-weight:600;">${dayName}</div>
				<div style="font-size:0.9rem;font-weight:700;">${dayNum}</div>
				<div style="font-size:0.65rem;opacity:0.8;">${d.formatted || ''}</div>
				${isCheapest ? '<span style="font-size:0.6rem;background:rgba(255,255,255,0.3);padding:1px 6px;border-radius:10px;">Best</span>' : ''}
			</div>
		</div>`;
	}).join('');
}

// ── Flight Booking (Stripe Checkout) ──────────────────────────────────
document.addEventListener('click', async function(e) {
	const btn = e.target.closest('.btn-book-flight');
	if (!btn) return;

	e.preventDefault();
	btn.disabled = true;
	btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Redirecting...';

	let flightData;
	try {
		flightData = JSON.parse(btn.getAttribute('data-flight'));
	} catch {
		showToast('Invalid flight data.', 'danger');
		btn.disabled = false;
		btn.innerHTML = '<i class="fas fa-lock me-1"></i> Book Now';
		return;
	}

	// Read current baggage selections from the form
	const cabinBagsEl = document.querySelector('select[name="cabin_bags"]');
	const checkedBagsEl = document.querySelector('select[name="checked_bags"]');
	const flightTypeEl = document.querySelector('input[name="flight_type"]');

	const firstLeg = flightData.legs?.[0] || {};
	const payload = {
		flight_details: {
			originCode:      firstLeg.originCode || '',
			origin:          firstLeg.origin || '',
			destinationCode: firstLeg.destinationCode || '',
			destination:     firstLeg.destination || '',
			departure:       firstLeg.departure || '',
			arrival:         firstLeg.arrival || '',
			duration:        firstLeg.duration || 0,
			stops:           firstLeg.stops ?? 0,
			carrier:         firstLeg.carrier || '',
			bookingUrl:      flightData.bookingUrl || '',
		},
		legs:            flightData.legs || null,
		flight_type:     flightTypeEl?.value || 'one_way',
		original_price:  flightData.priceUsd || flightData.price || 0,
		converted_price: flightData.price || 0,
		currency_code:   flightData.currency || window.userCurrency || 'TND',
		currency_symbol: flightData.currency || '',
		cabin_bags:      parseInt(cabinBagsEl?.value || '1'),
		checked_bags:    parseInt(checkedBagsEl?.value || '0'),
	};

	const result = await ttFetch('/flights/checkout', {
		method: 'POST',
		body: payload,
	}, {
		button: btn,
		buttonHtml: '<i class="fas fa-lock me-1"></i> Book Now',
	});

	if (result.success && result.data?.success && result.data?.url) {
		window.location.href = result.data.url;
	} else {
		ttToast.show(result.data?.message || 'Failed to create payment session.', 'error');
	}
});

function showToast(message, type) {
	var t = type === 'danger' ? 'error' : type;
	ttToast.show(message, t, 4000);
}
</script>
@endpush
