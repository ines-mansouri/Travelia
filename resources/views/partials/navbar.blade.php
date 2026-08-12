<!-- Navbar -->
<nav class="navbar navbar-expand-lg tt-navbar fixed-top">
	<div class="container">
		<a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
			<img src="{{ asset('images/logo.png') }}" alt="Travelia" height="40" style="max-height:40px;width:auto;">
			<span class="ms-2 fw-bold" style="font-size:1.5rem;letter-spacing:-0.5px;color:var(--tt-primary);font-family:'Poppins',sans-serif;">Travel<span style="color:var(--tt-accent);">ia</span></span>
		</a>

		<button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#ttNav"
				aria-controls="ttNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="ttNav">
			<ul class="navbar-nav mx-auto">
				<li class="nav-item">
					<a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">{{ __('messages.nav.home') }}</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ request()->is('flights') ? 'active' : '' }}" href="{{ route('flights') }}">{{ __('messages.nav.flights') }}</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ request()->is('hotels*') ? 'active' : '' }}" href="{{ route('hotels.index') }}">Hotels</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ request()->is('destinations*') ? 'active' : '' }}" href="{{ route('packages') }}">{{ __('messages.nav.destinations') }}</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ request()->is('hajj*') ? 'active' : '' }}" href="{{ route('hajj') }}">Hajj & Umrah</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ request()->is('contact*') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('messages.nav.contact') }}</a>
				</li>
			</ul>

			<div class="d-flex align-items-center gap-2">
				<a href="{{ route('packages') }}?search=" class="nav-link" title="Search">
					<i class="fas fa-search"></i>
				</a>

				@php
					$currentCurrency = session('currency', config('currencies.default'));
					$currencies = config('currencies.available', []);
				@endphp

				<div class="dropdown">
					<a class="nav-link dropdown-toggle d-flex align-items-center gap-1 fw-semibold small"
					   href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="fas fa-money-bill-wave"></i>
						{{ $currentCurrency }}
					</a>
					<ul class="dropdown-menu dropdown-menu-end shadow-sm border rounded-3 p-1" style="min-width: 200px; max-height: 300px; overflow-y: auto;">
						@foreach($currencies as $code)
							<li>
								<a class="dropdown-item rounded-2 py-2 {{ $code === $currentCurrency ? 'active' : '' }}"
								   href="{{ route('currency.switch', $code) }}">
									{{ config("currencies.symbols.$code", $code) }}
									<span class="ms-1">{{ $code }}</span>
									<small class="text-muted ms-1">{{ config("currencies.names.$code", '') }}</small>
								</a>
							</li>
						@endforeach
					</ul>
				</div>

				@auth
					<div class="dropdown">
						<a class="nav-link dropdown-toggle d-flex align-items-center gap-2 fw-semibold"
						   href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<div class="user-avatar">
								<i class="fas fa-user"></i>
							</div>
							{{ Auth::user()->name }}
						</a>
						<ul class="dropdown-menu dropdown-menu-end shadow-sm border rounded-3 p-1">
							<li class="px-3 py-2">
								<small class="text-muted">Welcome back!</small>
							</li>
							<li><hr class="dropdown-divider"></li>
							<li>
								<a class="dropdown-item rounded-2 py-2" href="{{ route('dashboard') }}">
									<i class="fas fa-tachometer-alt me-2 text-muted"></i>My Dashboard
								</a>
							</li>
							<li>
								<a class="dropdown-item rounded-2 py-2" href="{{ route('profile.index') }}">
									<i class="fas fa-user me-2 text-muted"></i>My Profile
								</a>
							</li>
							<li><hr class="dropdown-divider"></li>
							<li>
								<a class="dropdown-item rounded-2 py-2" href="{{ route('wishlist.index') }}">
									<i class="fas fa-heart me-2 text-muted"></i>My Wishlist
								</a>
							</li>
							<li><hr class="dropdown-divider"></li>
							<li>
								<a class="dropdown-item rounded-2 py-2 text-danger"
								   href="{{ route('logout') }}"
								   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
									<i class="fas fa-sign-out-alt me-2"></i>Logout
								</a>
							</li>
						</ul>
					</div>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
				@else
					<a href="{{ route('login') }}" class="btn btn-sign-in">Sign In</a>
					<a href="{{ route('register') }}" class="btn btn-get-started">Get Started</a>
				@endauth
			</div>
		</div>
	</div>
</nav>
