@extends('layouts.front')

@section('title', 'Hajj & Umrah Packages - Travelia')

@section('seo')
	<title>Hajj & Umrah Packages | Travelia</title>
	<meta name="description" content="Explore our premium Hajj and Umrah packages with comfortable accommodations, guided tours, and spiritual experiences.">
@endsection

@section('page')
@include('partials.navbar')

<!-- Page Hero -->
<section class="tt-page-hero">
	<div class="tt-page-hero-bg" style="background-image: url('{{ asset('images/place-4.jpg') }}');"></div>
	<div class="container" data-aos="fade-up">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb justify-content-center">
				<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Home</a></li>
				<li class="breadcrumb-item active">Hajj & Umrah</li>
			</ol>
		</nav>
		<h1 class="tt-page-title">Hajj & <span class="accent">Umrah</span></h1>
		<p class="tt-page-subtitle">Sacred journeys with comfort and spiritual excellence</p>
	</div>
</section>

<!-- Search & Filter -->
<section class="tt-section-sm">
	<div class="container">
		<div class="tt-search-bar" data-aos="fade-up">
			<form action="{{ route('hajj') }}" method="GET">
				<div class="row g-3 align-items-end">
					<div class="col-lg-6">
						<div class="tt-form-group">
							<label><i class="fas fa-mosque"></i> Type</label>
							<select class="tt-select" name="type" onchange="this.form.submit()">
								<option value="">All Types</option>
								<option value="hajj" {{ request('type') == 'hajj' ? 'selected' : '' }}>Hajj</option>
								<option value="umrah" {{ request('type') == 'umrah' ? 'selected' : '' }}>Umrah</option>
							</select>
						</div>
					</div>
					<div class="col-lg-4">
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

<!-- Hajj/Umrah Listing -->
<section class="tt-section">
	<div class="container">
		<div class="tt-section-header text-center" data-aos="fade-up">
			<h2 class="tt-title">
				@if(request('search'))
					Results for "<span class="accent">{{ request('search') }}</span>"
				@else
					Our <span class="accent">Packages</span>
				@endif
			</h2>
			<p class="tt-subtitle">
				@if($hajjUmrahs->count() > 0)
					Showing {{ $hajjUmrahs->count() }} package{{ $hajjUmrahs->count() !== 1 ? 's' : '' }}
				@else
					No packages found matching your criteria
				@endif
			</p>
		</div>

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
							<a href="javascript:void(0)" class="tt-hajj-card-link" data-hajj-id="{{ $item->id }}">Details</a>
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
			<a href="{{ route('hajj') }}" class="btn-tt-primary">View All Packages</a>
		</div>
		@endif
	</div>
</section>

@include('partials.footer')
@include('partials.hajj-drawer')

@push('styles')
<style>
.hajj-drawer-link { color: var(--tt-primary, #1F3D39); text-decoration: none; }
.hajj-drawer-link:hover { color: var(--tt-primary-dark, #1a3a36); }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var links = document.querySelectorAll('a[data-hajj-id]');
        links.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                var hajjId = link.getAttribute('data-hajj-id');
                if (window.TraveliaDrawer && window.TraveliaDrawer.open) {
                    window.TraveliaDrawer.open(hajjId);
                } else {
                    // Fallback: attempt to fetch and open
                    var url = '/hajj/' + hajjId + '/details';
                    var req = window.ttFetch
                        ? window.ttFetch(url, {}, { showToast: false })
                        : fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                            .then(function (res) { return res.ok ? res.json() : Promise.reject(); });
                    Promise.resolve(req).then(function (res) {
                        var data = res && res.data ? res.data : res;
                        if (data && data.hajj) {
                            // Populate drawer manually or navigate
                            var content = document.getElementById('ttDrawerContent');
                            content.innerHTML = '<div class="tt-hajj-drawer-hero"><img src="' + (data.hajj.image_url || '/images/hajj-default.jpg') + '" alt="' + (data.hajj.title || 'Hajj Package') + '"></div><div class="tt-hajj-drawer-body"><h2>' + (data.hajj.title || 'Hajj Package') + '</h2><p>' + (data.hajj.description || '') + '</p></div>';
                            document.getElementById('ttDrawer').classList.add('is-open');
                            document.body.classList.add('tt-hajj-drawer-open');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush

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
</style>
@endpush
