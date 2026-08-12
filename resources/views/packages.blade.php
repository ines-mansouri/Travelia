@extends('layouts.front')

@section('title', 'Tour Destinations - Travelia')
@section('og_title', 'Explore Destinations - Travelia')
@section('og_description', 'Browse our curated list of amazing travel destinations worldwide.')

@section('page')
@include('partials.navbar')

<!-- Page Hero -->
<section class="tt-page-hero">
	<div class="tt-page-hero-bg" style="background-image: url('{{ asset('images/place-4.jpg') }}');"></div>
	<div class="container" data-aos="fade-up">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb justify-content-center">
				<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Home</a></li>
				<li class="breadcrumb-item active">Destinations</li>
			</ol>
		</nav>
		<h1 class="tt-page-title">Discover the World's <span class="accent">Wonders</span></h1>
		<p class="tt-page-subtitle">
			From tropical beaches to mountain peaks, ancient cities to wild frontiers,
			explore our handpicked destinations that showcase our planet's natural beauty.
		</p>
	</div>
</section>

<!-- Search & Filter -->
<section class="tt-section-sm">
	<div class="container">
		<div class="tt-search-bar" data-aos="fade-up">
			<form action="{{ route('packages') }}" method="GET" id="tt-dest-search-form">
				<div class="row g-3 align-items-end">
					<div class="col-lg-4">
						<div class="tt-form-group" style="position:relative;">
							<label><i class="fas fa-search"></i> Search Destinations</label>
							<input type="text" class="tt-input" name="search"
								   placeholder="Where do you want to go?" value="{{ request('search') }}">
						</div>
					</div>
					<div class="col-lg-2">
						<div class="tt-form-group">
							<label><i class="fas fa-tag"></i> Category</label>
							<select class="tt-select" name="category">
								<option value="">All</option>
								@foreach($categories as $category)
									<option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
										{{ $category->name }}
									</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-2">
						<div class="tt-form-group">
							<label><i class="fas fa-dollar-sign"></i> Budget</label>
							<select class="tt-select" name="price_range">
								<option value="">Any</option>
								<option value="0-500" {{ request('price_range') == '0-500' ? 'selected' : '' }}>Under $500</option>
								<option value="500-2000" {{ request('price_range') == '500-2000' ? 'selected' : '' }}>$500 - $2,000</option>
								<option value="2000-5000" {{ request('price_range') == '2000-5000' ? 'selected' : '' }}>$2,000 - $5,000</option>
								<option value="5000+" {{ request('price_range') == '5000+' ? 'selected' : '' }}>Over $5,000</option>
							</select>
						</div>
					</div>
					<div class="col-lg-2">
						<div class="tt-form-group">
							<label><i class="fas fa-sort"></i> Sort by</label>
							<select class="tt-select" name="sort">
								<option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Name</option>
								<option value="pricing" {{ request('sort') == 'pricing' ? 'selected' : '' }}>Price (low)</option>
								<option value="pricing" {{ request('sort') == 'pricing' && request('order') == 'desc' ? 'selected' : '' }} data-order="desc">Price (high)</option>
							</select>
							<input type="hidden" name="order" value="{{ request('order', 'asc') }}">
						</div>
					</div>
					<div class="col-lg-2">
						<div class="tt-form-group">
							<label>&nbsp;</label>
							<button type="submit" class="btn-tt-primary w-100">
								Search <i class="fas fa-arrow-right"></i>
							</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>

<!-- Live Interactive Map -->
<section class="tt-section-sm">
	<div class="container">
		<x-travel-map />
	</div>
</section>

<!-- Destinations Listing -->
<section class="tt-section">
	<div class="container" id="tt-dest-results">
		<div class="tt-section-header text-center" data-aos="fade-up">
			<h2 class="tt-title">
				@if(isset($hajjUmrahs))
					Hajj & <span class="accent">Umrah</span>
				@elseif(request('search'))
					Results for "<span class="accent">{{ request('search') }}</span>"
				@else
					All <span class="accent">Destinations</span>
				@endif
			</h2>
			<p class="tt-subtitle">
				@if(isset($hajjUmrahs))
					@if($hajjUmrahs->count() > 0)
						Showing {{ $hajjUmrahs->count() }} package{{ $hajjUmrahs->count() !== 1 ? 's' : '' }}
					@else
						No packages found matching your criteria
					@endif
				@elseif($destinations->count() > 0)
					Showing {{ $destinations->count() }} amazing destinations
				@else
					No destinations found matching your criteria
				@endif
			</p>
		</div>

		@if(isset($hajjUmrahs))
			@if($hajjUmrahs->count() > 0)
			<div class="row g-4">
				@foreach ($hajjUmrahs as $item)
				<div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 3) * 100 }}">
					<article class="tt-hajj-card">
						<div class="tt-hajj-card-img">
							<img src="{{ $item->image ?? 'https://picsum.photos/seed/hajj' . $item->id . '/800/500' }}"
								 alt="{{ $item->title }}" loading="lazy">
							@if($item->category)
							<span class="badge-cat">{{ $item->category->name }}</span>
							@endif
							<span class="badge-type badge-{{ $item->type }}">
								<i class="fas fa-{{ $item->type === 'hajj' ? 'kaaba' : 'mosque' }} me-1"></i>
								{{ ucfirst($item->type) }}
							</span>
						</div>
						<div class="tt-hajj-card-body">
							<div class="tt-hajj-card-meta">
								<span><i class="far fa-calendar-alt"></i> {{ $item->published_at?->format('M d, Y') ?? 'Draft' }}</span>
								@if($item->duration_days)
								<span><i class="far fa-clock"></i> {{ $item->duration_days }} days</span>
								@endif
							</div>
							<h3 class="tt-hajj-card-title">
								<a href="{{ route('hajj.show', $item->id) }}">{{ $item->title }}</a>
							</h3>
							<p class="tt-hajj-card-desc">{{ Str::limit($item->description, 150) }}</p>
							<div class="tt-hajj-card-footer">
								@if($item->price)
								<span class="tt-hajj-price">${{ number_format($item->price, 2) }}</span>
								@endif
								<a href="{{ route('hajj.show', $item->id) }}" class="tt-hajj-card-link">
									Details <i class="fas fa-arrow-right"></i>
								</a>
							</div>
						</div>
					</article>
				</div>
				@endforeach
			</div>

			<div class="tt-pagination mt-5 d-flex justify-content-center" data-aos="fade-up">
				{{ $hajjUmrahs->appends(request()->query())->links('pagination::bootstrap-4') }}
			</div>
			@else
			<div class="tt-empty-state" data-aos="fade-up">
				<div class="icon"><i class="fas fa-mosque"></i></div>
				<h3>No Packages Found</h3>
				<p>We couldn't find any packages matching your criteria. Try adjusting your filters.</p>
				<a href="{{ route('packages', ['category' => request('category')]) }}" class="btn-tt-primary">View All Packages</a>
			</div>
			@endif
		@elseif($destinations->count() > 0)
		<div class="tt-dest-grid">
			@foreach ($destinations as $destination)
			<article class="tt-dest-card" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 3) * 100 }}">
				<div class="tt-dest-card-img">
					<img src="{{ $destination->image_url }}"
						 alt="{{ $destination->title }}" loading="lazy">
					<span class="badge-cat">{{ $destination->category->name ?? 'Safari' }}</span>
					<button class="btn-fav" aria-label="Add to favorites" data-id="{{ $destination->id }}">
						<i class="{{ in_array($destination->id, $wishlistIds ?? []) ? 'fas' : 'far' }} fa-heart"></i>
					</button>
				</div>
				<div class="tt-dest-card-body">
					<div class="tt-dest-card-meta">
						<span><i class="fas fa-map-marker-alt"></i> {{ $destination->title }}</span>
						<span><i class="fas fa-clock"></i> {{ $destination->duration ?? '7 Days' }}</span>
					</div>
					<h3 class="tt-dest-card-title">
						<a href="{{ route('desti.show', $destination->id) }}">{{ $destination->title }}</a>
					</h3>
					<p class="tt-dest-card-desc">{{ Str::limit($destination->description, 120) }}</p>
					<div class="tt-dest-card-footer">
						<div>
							<div class="tt-dest-price-label">From</div>
							<div class="tt-dest-price-value">{{ $destination->converted_pricing }}</div>
						</div>
						<a href="{{ route('desti.show', $destination->id) }}" class="tt-dest-card-link">
							Explore <i class="fas fa-arrow-right"></i>
						</a>
					</div>
				</div>
			</article>
			@endforeach
		</div>

		<div class="tt-pagination mt-5 d-flex justify-content-center" data-aos="fade-up">
			{{ $destinations->appends(request()->query())->links('pagination::bootstrap-4') }}
		</div>
		@else
		<div class="tt-empty-state" data-aos="fade-up">
			<div class="icon"><i class="fas fa-search"></i></div>
			<h3>No Destinations Found</h3>
			<p>We couldn't find any destinations matching your search criteria. Try adjusting your filters.</p>
			<a href="{{ route('packages') }}" class="btn-tt-primary">View All Destinations</a>
		</div>
		@endif
	</div>
</section>

<!-- Categories -->
<section class="tt-section tt-section-light">
	<div class="container">
		<div class="tt-section-header text-center" data-aos="fade-up">
			<div class="tt-pretitle">Explore by Type</div>
			<h2 class="tt-title">Popular <span class="accent">Categories</span></h2>
		</div>
		<div class="tt-cat-grid" data-aos="fade-up">
			@foreach($categories as $cat)
			<a href="{{ route('packages', ['category' => $cat->id]) }}" class="tt-cat-card" style="text-decoration:none;color:inherit;">
				<div class="icon">
					<i class="{{ $cat->icon ?? 'fas fa-globe' }}"></i>
				</div>
				<h5>{{ $cat->name }}</h5>
				<span class="count">
					@if($cat->name === 'Pilgrimage')
						{{ $cat->hajj_umrahs_count ?? 0 }} Package{{ ($cat->hajj_umrahs_count ?? 0) !== 1 ? 's' : '' }}
					@else
						{{ $cat->destinations_count ?? $cat->destinations?->count() ?? '—' }} Tours
					@endif
				</span>
			</a>
			@endforeach
		</div>
	</div>
</section>

@include('partials.footer')



@push('styles')
<style>
.tt-hajj-card {
	background: #fff;
	border-radius: 16px;
	overflow: hidden;
	box-shadow: 0 2px 12px rgba(0,0,0,0.08);
	transition: transform 0.2s, box-shadow 0.2s;
	height: 100%;
	display: flex;
	flex-direction: column;
}
.tt-hajj-card:hover {
	transform: translateY(-4px);
	box-shadow: 0 8px 32px rgba(0,0,0,0.12);
}
.tt-hajj-card-img {
	position: relative;
	height: 220px;
	overflow: hidden;
	flex-shrink: 0;
}
.tt-hajj-card-img img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 0.4s;
}
.tt-hajj-card:hover .tt-hajj-card-img img {
	transform: scale(1.05);
}
.tt-hajj-card-img .badge-cat {
	position: absolute;
	top: 12px;
	left: 12px;
	background: var(--tt-primary, #0d6efd);
	color: #fff;
	padding: 4px 14px;
	border-radius: 20px;
	font-size: 0.75rem;
	font-weight: 600;
	letter-spacing: 0.3px;
}
.tt-hajj-card-img .badge-type {
	position: absolute;
	top: 12px;
	right: 12px;
	color: #fff;
	padding: 4px 14px;
	border-radius: 20px;
	font-size: 0.75rem;
	font-weight: 600;
	letter-spacing: 0.3px;
}
.badge-type.badge-hajj { background: #8b5cf6; }
.badge-type.badge-umrah { background: #10b981; }
.tt-hajj-card-body {
	padding: 1.25rem;
	flex: 1;
	display: flex;
	flex-direction: column;
}
.tt-hajj-card-meta {
	font-size: 0.8rem;
	color: #6c757d;
	margin-bottom: 0.5rem;
}
.tt-hajj-card-meta span { margin-right: 1rem; }
.tt-hajj-card-title {
	font-family: 'Playfair Display', serif;
	font-size: 1.15rem;
	font-weight: 700;
	margin-bottom: 0.5rem;
	line-height: 1.35;
}
.tt-hajj-card-title a {
	color: #1a1a2e;
	text-decoration: none;
	transition: color 0.2s;
}
.tt-hajj-card-title a:hover { color: var(--tt-primary, #0d6efd); }
.tt-hajj-card-desc {
	font-size: 0.85rem;
	color: #6c757d;
	flex: 1;
	margin-bottom: 1rem;
	line-height: 1.6;
}
.tt-hajj-card-footer {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding-top: 0.75rem;
	border-top: 1px solid #eee;
}
.tt-hajj-price {
	font-size: 1.1rem;
	font-weight: 700;
	color: var(--tt-primary, #0d6efd);
}
.tt-hajj-card-link {
	color: var(--tt-primary, #0d6efd);
	font-weight: 600;
	font-size: 0.85rem;
	text-decoration: none;
	display: inline-flex;
	align-items: center;
	gap: 0.35rem;
	transition: gap 0.2s;
}
.tt-hajj-card-link:hover { gap: 0.6rem; color: #0b5ed7; }
.tt-search-suggestion-item {
	display: flex;
	align-items: center;
	gap: 12px;
	width: 100%;
	padding: 10px 16px;
	background: #fff;
	border: 0;
	border-bottom: 1px solid #f0f0f0;
	text-align: left;
	cursor: pointer;
	font: inherit;
	color: #333;
	transition: background 0.2s;
}
.tt-search-suggestion-item:last-child { border-bottom: 0; }
.tt-search-suggestion-item:hover { background: #f8f9fa; }
</style>
@endpush

@push('scripts')
<script>
const destResults = document.getElementById('tt-dest-results');
const searchForm = document.getElementById('tt-dest-search-form');
const searchInput = document.querySelector('input[name="search"]');
const sortSelect = document.querySelector('select[name="sort"]');
const orderInput = document.querySelector('input[name="order"]');

// Wishlist toggles — re-bound after every results refresh
function bindWishlist() {
	document.querySelectorAll('.btn-fav').forEach(btn => {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			@auth
			const destinationId = this.closest('.tt-dest-card').querySelector('a[href*="destinations/"]')?.href?.split('/').pop();
			if (destinationId) {
				(async () => {
					const res = await ttFetch(`/wishlist/${destinationId}/toggle`, {
						method: 'POST',
					}, { showToast: false });
					if (res.success && res.data) {
						const icon = this.querySelector('i');
						if (res.data.wishlisted) {
							icon.classList.remove('far'); icon.classList.add('fas'); this.classList.add('active');
							showToast('Added to wishlist', 'heart');
						} else {
							icon.classList.remove('fas'); icon.classList.add('far'); this.classList.remove('active');
							showToast('Removed from wishlist', 'heart');
						}
					}
				})();
			}
			@else
			const icon = this.querySelector('i');
			icon.classList.toggle('far');
			icon.classList.toggle('fas');
			this.classList.toggle('active');
			showToast('Sign in to save to wishlist', 'info-circle');
			@endauth
		});
	});
}

// Fetch filtered results and swap the grid in place — never navigate away
async function refreshDestinations(url, scroll) {
	if (!destResults) return;
	const submitBtn = searchForm ? searchForm.querySelector('button[type="submit"]') : null;
	const btnHtml = submitBtn ? submitBtn.innerHTML : '';
	if (submitBtn) {
		submitBtn.disabled = true;
		submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Searching...';
	}

	let query;
	if (url) {
		query = url.split('?')[1] || '';
	} else if (searchForm) {
		query = new URLSearchParams(new FormData(searchForm)).toString();
	}

	const res = await ttFetch(`/destinations${query ? '?' + query : ''}`, {}, { showToast: true });

	if (res.success && res.data) {
		destResults.innerHTML = res.data;
		bindWishlist();
		if (typeof AOS !== 'undefined' && typeof AOS.refresh === 'function') AOS.refresh();
		if (scroll && destResults.scrollIntoView) {
			destResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
		}
	}

	if (submitBtn) {
		submitBtn.disabled = false;
		submitBtn.innerHTML = btnHtml;
	}
}

// Main "Search" button + Enter key → filter the grid in place
if (searchForm) {
	searchForm.addEventListener('submit', function(e) {
		e.preventDefault();
		refreshDestinations(null, true);
	});
}

// Sort by — "Price (high)" maps to order=desc, filters in place
if (sortSelect && orderInput) {
	sortSelect.addEventListener('change', function() {
		const opt = this.options[this.selectedIndex];
		orderInput.value = opt.dataset.order || 'asc';
		refreshDestinations(null, true);
	});
}

// Category & Budget — filter in place on change
document.querySelectorAll('select[name="category"], select[name="price_range"]').forEach(sel => {
	sel.addEventListener('change', () => refreshDestinations(null, true));
});

// Real-time filtering as the user types
if (searchInput) {
	let searchTimer;
	searchInput.addEventListener('input', function() {
		clearTimeout(searchTimer);
		searchTimer = setTimeout(() => refreshDestinations(), 500);
	});
}

// Search suggestions — selecting one only filters the grid, never navigates
if (searchInput) {
	const wrapper = document.createElement('div');
	wrapper.style.position = 'relative';
	searchInput.parentNode.appendChild(wrapper);
	wrapper.appendChild(searchInput);

	const dropdown = document.createElement('div');
	dropdown.className = 'tt-search-suggestions';
	dropdown.style.cssText = 'display:none;position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid #ddd;border-radius:8px;box-shadow:0 4px 20px rgba(0,0,0,0.12);z-index:1000;max-height:320px;overflow-y:auto;margin-top:4px;';
	wrapper.appendChild(dropdown);

	let timer;

	searchInput.addEventListener('input', function() {
		clearTimeout(timer);
		const q = this.value.trim();
		if (q.length < 2) { dropdown.style.display = 'none'; return; }

		timer = setTimeout(async () => {
			const res = await ttFetch(`/api/v1/destinations/search?q=${encodeURIComponent(q)}&limit=6`, {}, { showToast: false });
			if (!res.success || !res.data?.data?.length) {
				dropdown.innerHTML = '<div style="padding:12px 16px;color:#999;font-size:14px;">No destinations found</div>';
				dropdown.style.display = 'block';
				return;
			}
			dropdown.innerHTML = res.data.data.map(d => `
				<button type="button" class="tt-search-suggestion-item"
				   data-title="${(d.title || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;')}"
				   onmouseenter="this.style.background='#f8f9fa'" onmouseleave="this.style.background=''">
					<img src="${d.image_url || '/images/destination-1.jpg'}" alt="" style="width:40px;height:40px;border-radius:6px;object-fit:cover;pointer-events:none;">
					<div>
						<div style="font-weight:600;font-size:14px;">${d.title}</div>
						<div style="font-size:12px;color:#999;">${d.category?.name || 'Destination'} · ${d.duration || ''}</div>
					</div>
					<div style="margin-left:auto;font-weight:700;color:var(--tt-primary);font-size:14px;">${d.converted_pricing || ''}</div>
				</button>
			`).join('');
			dropdown.style.display = 'block';
		}, 300);
	});

	dropdown.addEventListener('click', function(e) {
		const item = e.target.closest('.tt-search-suggestion-item');
		if (!item) return;
		searchInput.value = item.dataset.title || '';
		dropdown.style.display = 'none';
		refreshDestinations(null, true);
	});

	document.addEventListener('click', e => {
		if (!wrapper.contains(e.target)) dropdown.style.display = 'none';
	});
}

// Pagination — swap results in place
if (destResults) {
	destResults.addEventListener('click', function(e) {
		const link = e.target.closest('.tt-pagination a');
		if (!link) return;
		e.preventDefault();
		refreshDestinations(link.getAttribute('href'), true);
	});
}

// Toast notification — delegates to global ttToast
function showToast(message, icon) {
	ttToast.show(message, 'success', 3000);
}

bindWishlist();
</script>
@endpush
@endsection
