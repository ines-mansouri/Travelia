@extends('layouts.front')

@section('title', 'Testimonials - Travelia')

@section('page')
@include('partials.navbar')

<section class="tt-page-hero tt-page-hero-sm">
	<div class="tt-page-hero-bg" style="background-image: url('{{ asset('images/place-1.jpg') }}');"></div>
	<div class="container" data-aos="fade-up">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb justify-content-center">
				<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Home</a></li>
				<li class="breadcrumb-item active">Testimonials</li>
			</ol>
		</nav>
		<h1 class="tt-page-title">Manage <span class="accent">Testimonials</span></h1>
	</div>
</section>

<section class="tt-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">

				<div class="tt-sidebar-card mb-4" data-aos="fade-up">
					<h5><i class="fas fa-plus me-2"></i> Add Testimonial</h5>
					<form method="POST" action="{{ route('testimonials.store') }}" class="tt-form mt-3">
						@csrf
						<div class="row g-3">
							<div class="col-md-6">
								<div class="tt-form-group">
									<label class="tt-label">Name</label>
									<input type="text" name="name" class="tt-input" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="tt-form-group">
									<label class="tt-label">Location</label>
									<input type="text" name="location" class="tt-input" placeholder="e.g. United Kingdom">
								</div>
							</div>
						</div>
						<div class="tt-form-group">
							<label class="tt-label">Rating</label>
							<select name="rating" class="form-select" required>
								@for($r = 5; $r >= 1; $r--)
									<option value="{{ $r }}">{{ $r }} star{{ $r > 1 ? 's' : '' }}</option>
								@endfor
							</select>
						</div>
						<div class="tt-form-group">
							<label class="tt-label">Review Text</label>
							<textarea name="text" class="tt-input" rows="3" maxlength="1000" required></textarea>
						</div>
						<button type="submit" class="btn-tt-primary"><i class="fas fa-save me-1"></i> Save</button>
					</form>
				</div>

				<div class="tt-sidebar-card" data-aos="fade-up">
					<h5><i class="fas fa-list me-2"></i> All Testimonials</h5>
					@if($testimonials->isEmpty())
						<p class="text-muted mt-3">No testimonials yet.</p>
					@else
						<div class="mt-3">
							@foreach($testimonials as $t)
							<div class="d-flex justify-content-between align-items-start py-3 border-bottom">
								<div>
									<strong>{{ $t->name }}</strong>
									@if($t->location)<small class="text-muted ms-2">{{ $t->location }}</small>@endif
									<div class="text-warning small">
										@for($s = 1; $s <= 5; $s++)
											<i class="fas fa-star{{ $s <= $t->rating ? '' : '-o text-muted' }}"></i>
										@endfor
									</div>
									<p class="text-muted mt-1 mb-0">{{ Str::limit($t->text, 200) }}</p>
								</div>
								<form method="POST" action="{{ route('testimonials.destroy', $t) }}" onsubmit="return confirm('Delete this testimonial?');">
									@csrf @method('DELETE')
									<button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
								</form>
							</div>
							@endforeach
						</div>
						<div class="mt-3">{{ $testimonials->links('pagination::bootstrap-4') }}</div>
					@endif
				</div>

			</div>
		</div>
	</div>
</section>

@include('partials.footer')
@endsection
