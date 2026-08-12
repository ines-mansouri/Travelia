<template>
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-compass me-2"></i>Travelia</h5>
                    <p class="text-white-50 small">Discover amazing destinations worldwide with curated tours and unforgettable experiences.</p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <div class="d-flex flex-column gap-1">
                        <Link href="/destinations" class="text-white-50 text-decoration-none small">Destinations</Link>
                        <Link href="/flights" class="text-white-50 text-decoration-none small">Flights</Link>
                        <Link href="/hotels" class="text-white-50 text-decoration-none small">Hotels</Link>
                        <Link href="/about" class="text-white-50 text-decoration-none small">About</Link>
                        <Link href="/contact" class="text-white-50 text-decoration-none small">Contact</Link>
                    </div>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold mb-3">Contact</h6>
                    <p class="text-white-50 small mb-1"><i class="fas fa-envelope me-2"></i>info@travelia.tn</p>
                    <p class="text-white-50 small mb-1"><i class="fas fa-phone me-2"></i>+216 00 000 000</p>
                </div>
            </div>
            <hr class="my-4 border-white-50">
            <p class="text-center text-white-50 small mb-0">&copy; {{ new Date().getFullYear() }} Travelia. All rights reserved.</p>
        </div>
    </footer>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
