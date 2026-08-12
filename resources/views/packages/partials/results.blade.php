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
