@extends('layouts.front')

@section('title', 'My Profile - Travelia')

@section('page')
@include('partials.navbar')

<section class="tt-page-hero tt-page-hero-sm">
	<div class="tt-page-hero-bg" style="background-image: url('{{ asset('images/place-1.jpg') }}');"></div>
	<div class="container" data-aos="fade-up">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb justify-content-center">
				<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home me-1"></i>Home</a></li>
				<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
				<li class="breadcrumb-item active">Profile</li>
			</ol>
		</nav>
		<h1 class="tt-page-title">My <span class="accent">Profile</span></h1>
		<p class="tt-page-subtitle">Manage your personal information and avatar</p>
	</div>
</section>

<section class="tt-section">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-8">
				{{-- ─── Avatar Card ─────────────────────────────────────── --}}
				<div class="tt-sidebar-card text-center" data-aos="fade-up">
					<div class="tt-avatar-wrapper">
						<img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}"
							 id="profileAvatar" class="tt-avatar-image">
						<label for="avatarUpload" class="tt-avatar-overlay" title="Change photo">
							<i class="fas fa-camera"></i>
							<span>Change Photo</span>
						</label>
						<input type="file" id="avatarUpload" accept="image/jpeg,image/png,image/jpg" style="display:none;">
					</div>
					<h4 class="mt-3 mb-0" id="profileName">{{ Auth::user()->name }}</h4>
					<p class="text-muted" id="profileEmail">{{ Auth::user()->email }}</p>
				</div>

				{{-- ─── Account Details ────────────────────────────────── --}}
				<div class="tt-sidebar-card mt-4" data-aos="fade-up">
					<h4 class="mb-1"><i class="fas fa-user me-2"></i> Account Details</h4>
					<p class="text-muted mb-4">Update your name and email address</p>

					<form id="profileForm" class="tt-form">
						@csrf @method('PUT')
						<div class="row g-3">
							<div class="col-md-6">
								<div class="tt-form-group">
									<label class="tt-label">Full Name</label>
									<input type="text" id="inputName" name="name" class="tt-input"
										   value="{{ Auth::user()->name }}" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="tt-form-group">
									<label class="tt-label">Email Address</label>
									<input type="email" id="inputEmail" name="email" class="tt-input"
										   value="{{ Auth::user()->email }}" required>
								</div>
							</div>
						</div>
						<button type="submit" id="profileSubmitBtn" class="btn-tt-primary mt-3">
							<i class="fas fa-save me-1"></i> Save Changes
						</button>
					</form>
				</div>

				{{-- ─── Change Password ────────────────────────────────── --}}
				<div class="tt-sidebar-card mt-4" data-aos="fade-up">
					<h4 class="mb-1"><i class="fas fa-lock me-2"></i> Change Password</h4>
					<p class="text-muted mb-4">Update your password</p>

					<form id="passwordForm" class="tt-form">
						@csrf @method('PUT')
						<div class="row g-3">
							<div class="col-md-4">
								<div class="tt-form-group">
									<label class="tt-label">Current Password</label>
									<input type="password" name="current_password" class="tt-input" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="tt-form-group">
									<label class="tt-label">New Password</label>
									<input type="password" name="password" class="tt-input" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="tt-form-group">
									<label class="tt-label">Confirm New Password</label>
									<input type="password" name="password_confirmation" class="tt-input" required>
								</div>
							</div>
						</div>
						<button type="submit" id="passwordSubmitBtn" class="btn-tt-accent mt-3">
							<i class="fas fa-key me-1"></i> Change Password
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

@include('partials.footer')
@endsection

@push('styles')
<style>
.tt-avatar-wrapper {
	position: relative;
	width: 140px;
	height: 140px;
	margin: 0 auto;
	border-radius: 50%;
	overflow: hidden;
	box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}
.tt-avatar-image {
	width: 100%;
	height: 100%;
	object-fit: cover;
	display: block;
}
.tt-avatar-overlay {
	position: absolute;
	inset: 0;
	background: rgba(44, 81, 76, 0.75);
	color: #fff;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	gap: 4px;
	opacity: 0;
	transition: opacity 0.25s ease;
	cursor: pointer;
	font-size: 0.75rem;
	font-weight: 600;
}
.tt-avatar-overlay i {
	font-size: 1.2rem;
}
.tt-avatar-wrapper:hover .tt-avatar-overlay {
	opacity: 1;
}
</style>
@endpush

@push('scripts')
<script>
(function() {
	// ── Avatar Upload ──────────────────────────────────────────────
	document.getElementById('avatarUpload').addEventListener('change', async function() {
		var file = this.files[0];
		if (!file) return;

		var formData = new FormData();
		formData.append('avatar', file);
		formData.append('_token', ttCsrfToken());

		var btnHtml = '<i class="fas fa-camera"></i><span>Change Photo</span>';
		var label = document.querySelector('.tt-avatar-overlay');
		label.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';

		var res = await ttFetch('{{ route("profile.avatar") }}', {
			method: 'POST',
			body: formData,
		}, {
			showToast: false,
		});

		label.innerHTML = btnHtml;

		if (res.success && res.data?.success && res.data?.avatar_url) {
			document.getElementById('profileAvatar').src = res.data.avatar_url;
			ttToast.show(res.data.message, 'success');
		} else {
			ttToast.show(res.data?.message || 'Failed to upload avatar.', 'error');
		}

		this.value = '';
	});

	// ── Profile Update ──────────────────────────────────────────────
	document.getElementById('profileForm').addEventListener('submit', async function(e) {
		e.preventDefault();
		var btn = document.getElementById('profileSubmitBtn');
		var btnHtml = '<i class="fas fa-save me-1"></i> Save Changes';

		btn.disabled = true;
		btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Saving...';

		var res = await ttFetch('{{ route("profile.update") }}', {
			method: 'PUT',
			body: {
				name: document.getElementById('inputName').value,
				email: document.getElementById('inputEmail').value,
			},
		}, {
			button: btn,
			buttonHtml: btnHtml,
			showToast: false,
		});

		if (res.success && res.data?.success) {
			document.getElementById('profileName').textContent = res.data.user.name;
			document.getElementById('profileEmail').textContent = res.data.user.email;
			ttToast.show(res.data.message, 'success');
		} else {
			var msg = res.data?.message || 'Failed to update profile.';
			if (res.data?.errors) {
				var keys = Object.keys(res.data.errors);
				msg = res.data.errors[keys[0]][0] || msg;
			}
			ttToast.show(msg, 'error');
		}
	});

	// ── Password Update ─────────────────────────────────────────────
	document.getElementById('passwordForm').addEventListener('submit', async function(e) {
		e.preventDefault();
		var btn = document.getElementById('passwordSubmitBtn');
		var btnHtml = '<i class="fas fa-key me-1"></i> Change Password';

		btn.disabled = true;
		btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Updating...';

		var res = await ttFetch('{{ route("profile.password") }}', {
			method: 'PUT',
			body: {
				current_password: this.querySelector('[name="current_password"]').value,
				password: this.querySelector('[name="password"]').value,
				password_confirmation: this.querySelector('[name="password_confirmation"]').value,
			},
		}, {
			button: btn,
			buttonHtml: btnHtml,
			showToast: false,
		});

		if (res.success && res.data?.success) {
			ttToast.show(res.data.message, 'success');
			this.reset();
		} else {
			var msg = res.data?.message || 'Failed to update password.';
			if (res.data?.errors) {
				var keys = Object.keys(res.data.errors);
				msg = res.data.errors[keys[0]][0] || msg;
			}
			ttToast.show(msg, 'error');
		}
	});
})();
</script>
@endpush
