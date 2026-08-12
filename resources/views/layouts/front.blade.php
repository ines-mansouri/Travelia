<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Travelia - Discover Amazing Destinations Worldwide')</title>
    <meta name="description" content="@yield('meta_description', 'Explore the world with Travelia. Curated destinations, expert guides, and unforgettable experiences await.')">

    <meta property="og:title" content="@yield('og_title', 'Travelia - Discover Amazing Destinations Worldwide')">
    <meta property="og:description" content="@yield('og_description', 'Explore the world with Travelia. Curated destinations, expert guides, and unforgettable experiences await.')">
    <meta property="og:image" content="@yield('og_image', asset('images/place-1.jpg'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Travelia - Discover Amazing Destinations Worldwide')">
    <meta name="twitter:description" content="@yield('og_description', 'Explore the world with Travelia. Curated destinations, expert guides, and unforgettable experiences await.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/place-1.jpg'))">

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "TravelAgency",
        "name": "Travelia",
        "url": "{{ url('/') }}",
        "logo": "{{ asset('images/place-1.jpg') }}",
        "description": "Explore the world with Travelia. Curated destinations, expert guides, and unforgettable experiences await.",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "City Center",
            "addressCountry": "US"
        }
    }
    </script>

    @hasSection('seo')
        @yield('seo')
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@400;600;700&family=Cinzel+Decorative:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- AOS (animations) -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <!-- Unified Theme -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">

    @livewireStyles

    @stack('styles')
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <x-flash-messages />

    @yield('page')

    <main id="main-content">
        @yield('content')
    </main>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/1234567890" class="tt-whatsapp-float" target="_blank" aria-label="Chat on WhatsApp" title="Chat on WhatsApp" id="whatsapp-float-btn">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="tt-scroll-top" aria-label="Scroll to top">
        <i class="fas fa-chevron-up"></i>
    </button>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Travelia Request Interceptor & Error Handler -->
    <script src="{{ asset('js/tt-interceptor.js') }}"></script>

    <!-- Destination Details Slide-Over Drawer -->
    @include('partials.destination-drawer')

    <script>
        AOS.init({ duration: 800, easing: 'ease-out', once: true, offset: 60 });

        window.addEventListener('scroll', () => {
            const nav = document.querySelector('.tt-navbar');
            if (nav) nav.classList.toggle('scrolled', window.scrollY > 40);

            const btn = document.getElementById('scrollToTop');
            if (btn) btn.classList.toggle('visible', window.scrollY > 400);
        });

        document.getElementById('scrollToTop')?.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>

    @livewireScripts

    @stack('scripts')
</body>
</html>
