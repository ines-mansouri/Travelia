@extends('layouts.front')

@section('title', 'Forbidden - Travelia')

@section('page')
@include('partials.navbar')

<section class="tt-section" style="min-height:60vh;display:flex;align-items:center;">
	<div class="container">
		<div class="tt-empty-state text-center" data-aos="fade-up">
			<div class="icon" style="font-size:5rem;color:var(--tt-primary);">
				<i class="fas fa-lock"></i>
			</div>
			<h1 class="display-4 fw-bold mt-4">403</h1>
			<h3 class="mt-2">Access Denied</h3>
			<p class="text-muted mt-2 mb-4">You don't have permission to access this page.</p>
			<a href="{{ url('/') }}" class="btn-tt-primary">
				<i class="fas fa-home me-1"></i> Back to Home
			</a>
		</div>
	</div>
</section>

@include('partials.footer')
@endsection
