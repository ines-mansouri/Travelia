<template>
    <div class="tt-sidebar-card" data-aos="fade-up">
        <h4 class="mb-1"><i class="fas fa-suitcase me-2"></i> My Bookings</h4>
        <p class="text-muted mb-4">Your recent tour bookings</p>

        <form @submit.prevent="submitFilters" class="row g-2 mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" v-model="localFilters.search"
                           placeholder="Search destinations...">
                </div>
            </div>
            <div class="col-md-4">
                <select class="form-select" v-model="localFilters.status">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </form>

        <div v-if="!bookings.length" class="tt-empty-state text-center py-4">
            <div class="icon" style="font-size:2.5rem;color:var(--tt-primary);">
                <i class="fas fa-suitcase"></i>
            </div>
            <h5 class="mt-3">No bookings yet</h5>
            <p class="text-muted">Start exploring destinations and book your next adventure!</p>
            <Link href="/destinations" class="btn btn-primary">
                <i class="fas fa-compass me-1"></i> Explore Destinations
            </Link>
        </div>

        <div v-else class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Destination</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Review</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="booking in bookings" :key="booking.id">
                        <td>
                            <Link :href="`/bookings/${booking.id}`" class="fw-semibold tt-link">
                                {{ booking.destination_title }}
                            </Link>
                        </td>
                        <td>{{ booking.travel_date }}</td>
                        <td>
                            <span class="badge" :class="statusBadge(booking.status)">
                                {{ capitalize(booking.status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge" :class="paymentBadge(booking.payment_status)">
                                {{ capitalize(booking.payment_status) }}
                            </span>
                        </td>
                        <td>${{ booking.total_price }}</td>
                        <td>
                            <span v-if="booking.has_review" class="badge bg-secondary">
                                <i class="fas fa-check me-1"></i>Reviewed
                            </span>
                            <button v-else-if="booking.can_review"
                                class="btn btn-sm btn-outline-warning"
                                @click="$emit('open-review', 'destination', booking.id, booking.destination_title)">
                                <i class="fas fa-star me-1"></i> Review
                            </button>
                            <span v-else class="text-muted small">—</span>
                        </td>
                        <td>
                            <button v-if="booking.status !== 'cancelled'"
                                class="btn btn-sm btn-outline-danger"
                                @click="cancelBooking(booking.id)">
                                Cancel
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Pagination v-if="hasPagination" :links="paginationLinks" />
    </div>
</template>

<script setup>
import { ref, reactive, watch, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import Pagination from '@/Components/Pagination.vue'

const props = defineProps({
    bookings: { type: Array, required: true },
    filters: { type: Object, default: () => ({}) },
})

const emit = defineEmits(['update-filters', 'open-review'])

const page = usePage()
const pagination = computed(() => page.props.bookings)
const hasPagination = computed(() => pagination.value?.links?.length > 3)
const paginationLinks = computed(() => pagination.value?.links ?? [])

const loading = ref(false)
const localFilters = reactive({ ...props.filters })

function submitFilters() {
    loading.value = true
    emit('update-filters', { ...localFilters })
}

watch(() => props.filters, (f) => {
    Object.assign(localFilters, f)
    loading.value = false
}, { immediate: true })

function statusBadge(status) {
    return {
        confirmed: 'bg-success',
        cancelled: 'bg-danger',
        pending: 'bg-warning text-dark',
    }[status] ?? 'bg-secondary'
}

function paymentBadge(status) {
    return status === 'paid' ? 'bg-success' : 'bg-secondary'
}

function capitalize(s) {
    return s ? s.charAt(0).toUpperCase() + s.slice(1) : ''
}

function cancelBooking(id) {
    if (!confirm('Cancel this booking?')) return
    router.delete(`/bookings/${id}/cancel`, { preserveScroll: true })
}
</script>
