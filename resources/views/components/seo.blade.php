@props([
    'title' => 'Travelia - Discover Amazing Destinations',
    'description' => 'Explore the world with Travelia. Curated destinations, expert guides, and unforgettable experiences await.',
    'image' => asset('images/og-image.jpg'),
    'type' => 'website',
    'url' => url()->current(),
])

<!-- Primary Meta Tags -->
<title>{{ $title }}</title>
<meta name="title" content="{{ $title }}">
<meta name="description" content="{{ $description }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:site_name" content="Travelia">
<meta property="og:locale" content="en_US">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $url }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

<!-- Additional SEO -->
<meta name="robots" content="index, follow">
<meta name="author" content="Travelia">
<link rel="canonical" href="{{ $url }}">

<!-- JSON-LD Structured Data -->
@if($type === 'website')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "TravelAgency",
    "name": "Travelia",
    "description": "{{ $description }}",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/logo.png') }}",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "123 Travel Street",
        "addressLocality": "City Center",
        "addressRegion": "",
        "postalCode": "",
        "addressCountry": ""
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+1234567890",
        "contactType": "customer service"
    },
    "sameAs": [
        "#"
    ]
}
</script>
@endif
