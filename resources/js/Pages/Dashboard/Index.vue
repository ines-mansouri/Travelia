<template>
    <AppLayout>
        <section class="tt-page-hero tt-page-hero-sm">
            <div class="tt-page-hero-bg" :style="{ backgroundImage: `url('/images/place-1.jpg')` }"></div>
            <div class="container" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><Link href="/"><i class="fas fa-home me-1"></i>Home</Link></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </nav>
                <h1 class="tt-page-title">My <span class="accent">Dashboard</span></h1>
                <p class="tt-page-subtitle">Manage your bookings and wishlist</p>
            </div>
        </section>

        <section class="tt-section py-5">
            <div class="container">
                <div class="row g-4 mb-5" data-aos="fade-up">
                    <div class="col-md-4" v-for="card in statCards" :key="card.label">
                        <div class="tt-stat-card">
                            <div class="tt-stat-card-icon" :style="{ background: card.bg, color: card.color }">
                                <i :class="card.icon"></i>
                            </div>
                            <div class="tt-stat-card-body">
                                <div class="tt-stat-number">{{ card.value }}</div>
                                <div class="tt-stat-label">{{ card.label }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <DestinationsBookingsPanel
                            :bookings="bookings"
                            :filters="filters"
                            @update-filters="updateFilters"
                        />

                        <FlightBookingsPanel
                            :flight-bookings="flightBookings"
                            :filters="filters"
                            @update-filters="updateFilters"
                        />
                    </div>

                    <div class="col-lg-4">
                        <WishlistPanel :items="wishlisted" />

                        <div class="tt-sidebar-card mt-4" data-aos="fade-left">
                            <h5 class="mb-3"><i class="fas fa-cog me-2"></i> Quick Links</h5>
                            <div class="d-flex flex-column gap-2">
                                <Link href="/testimonials" class="btn-tt-outline w-100">
                                    <i class="fas fa-star me-1"></i> Manage Testimonials
                                </Link>
                                <Link href="/profile" class="btn-tt-outline w-100">
                                    <i class="fas fa-user me-1"></i> Edit Profile
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <ReviewModal ref="reviewModal" />
    </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import DestinationsBookingsPanel from './Partials/DestinationsBookingsPanel.vue'
import FlightBookingsPanel from './Partials/FlightBookingsPanel.vue'
import WishlistPanel from './Partials/WishlistPanel.vue'
import ReviewModal from './Partials/ReviewModal.vue'

const page = usePage()
const props = computed(() => page.props)

const stats = computed(() => props.value.stats)
const bookings = computed(() => props.value.bookings.data ?? [])
const flightBookings = computed(() => props.value.flightBookings.data ?? [])
const wishlisted = computed(() => props.value.wishlisted ?? [])
const filters = computed(() => props.value.filters ?? {})

const reviewModal = ref(null)

const statCards = computed(() => [
    {
        icon: 'fas fa-wallet',
        value: `${stats.value.totalSpentCurrency}${stats.value.totalSpent}`,
        label: 'Total Travel Investment',
        bg: '#e8f4fd',
        color: '#0d6efd',
    },
    {
        icon: 'fas fa-plane-departure',
        value: stats.value.upcomingTrips,
        label: 'Upcoming Active Trips',
        bg: '#d1fae5',
        color: '#065f46',
    },
    {
        icon: 'fas fa-globe-americas',
        value: stats.value.completedJourneys,
        label: 'Completed Journeys',
        bg: '#fef3c7',
        color: '#92400e',
    },
])

function updateFilters(newFilters) {
    router.get('/dashboard', { ...filters.value, ...newFilters }, {
        preserveState: true,
        preserveScroll: true,
    })
}

function openReviewModal(type, id, title) {
    reviewModal.value?.open(type, id, title)
}

defineExpose({ openReviewModal })
</script>
