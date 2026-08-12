<template>
    <AppLayout>
        <section class="tt-page-hero tt-page-hero-sm">
            <div class="tt-page-hero-bg" style="background-image: url('/images/place-1.jpg');"></div>
            <div class="container" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><Link href="/"><i class="fas fa-home me-1"></i>Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/dashboard">Dashboard</Link></li>
                        <li class="breadcrumb-item active">Booking #{{ booking.id }}</li>
                    </ol>
                </nav>
                <h1 class="tt-page-title">Booking <span class="accent">#{{ booking.id }}</span></h1>
            </div>
        </section>

        <section class="tt-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="tt-sidebar-card" data-aos="fade-up">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h4 class="mb-1">{{ booking.destination_title }}</h4>
                                    <p class="text-muted">Booked on {{ booking.booked_on }}</p>
                                    <small v-if="booking.invoice_number" class="text-muted">
                                        Invoice: <strong>{{ booking.invoice_number }}</strong>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <span class="badge fs-6" :class="statusClass(booking.status)">
                                        {{ capitalize(booking.status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background:var(--tt-light);">
                                        <small class="text-muted">Travel Date</small>
                                        <div class="fw-bold">{{ booking.travel_date ?? 'TBD' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background:var(--tt-light);">
                                        <small class="text-muted">Guests</small>
                                        <div class="fw-bold">{{ booking.guests }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background:var(--tt-light);">
                                        <small class="text-muted">Total Price</small>
                                        <div class="fw-bold fs-5" style="color:var(--tt-primary);">${{ booking.total_price }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 rounded-3" style="background:var(--tt-light);">
                                        <small class="text-muted">Payment Status</small>
                                        <div class="fw-bold">{{ capitalize(booking.payment_status) }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <Link href="/dashboard" class="btn-tt-outline">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                                </Link>

                                <button v-if="booking.status !== 'cancelled'" type="button"
                                        class="btn-tt-outline" style="border-color:#dc3545;color:#dc3545;"
                                        @click="cancelBooking">
                                    <i class="fas fa-times me-1"></i> Cancel Booking
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const page = usePage()
const booking = computed(() => page.props.booking)

function statusClass(status) {
    return status === 'confirmed' ? 'bg-success' : status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark'
}

function capitalize(s) {
    return s ? s.charAt(0).toUpperCase() + s.slice(1) : ''
}

function cancelBooking() {
    if (!confirm('Are you sure you want to cancel this booking?')) return
    router.delete(`/bookings/${booking.value.id}/cancel`, { preserveScroll: true })
}
</script>
