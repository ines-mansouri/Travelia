<template>
    <div class="tt-sidebar-card mt-4" data-aos="fade-up">
        <h4 class="mb-1"><i class="fas fa-plane me-2"></i> My Flight Bookings</h4>
        <p class="text-muted mb-4">Your flight reservations</p>

        <form @submit.prevent="submitFilters" class="row g-2 mb-4">
            <div class="col-md-4">
                <select class="form-select" v-model="localFilters.flight_status">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </form>

        <div v-if="!flightBookings.length" class="tt-empty-state text-center py-4">
            <div class="icon" style="font-size:2.5rem;color:var(--tt-primary);">
                <i class="fas fa-plane"></i>
            </div>
            <h5 class="mt-3">No flight bookings yet</h5>
            <p class="text-muted">Search for flights and book your next trip!</p>
            <Link href="/flights" class="btn btn-primary">
                <i class="fas fa-search me-1"></i> Find Flights
            </Link>
        </div>

        <div v-else class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Route</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Review</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="fb in flightBookings" :key="fb.id">
                        <td>
                            <Link :href="fb.status === 'paid' ? `/flights/booking/${fb.id}/success` : '/flights'"
                                  class="fw-semibold tt-link">
                                {{ fb.origin }} → {{ fb.destination }}
                            </Link>
                        </td>
                        <td>{{ fb.departure }}</td>
                        <td>
                            <span class="badge" :class="statusBadge(fb.status)">
                                {{ capitalize(fb.status) }}
                            </span>
                        </td>
                        <td>{{ fb.currency_symbol }}{{ fb.converted_price }}</td>
                        <td>
                            <span v-if="fb.has_review" class="badge bg-secondary">
                                <i class="fas fa-check me-1"></i>Reviewed
                            </span>
                            <button v-else-if="fb.can_review"
                                class="btn btn-sm btn-outline-warning"
                                @click="$emit('open-review', 'flight', fb.id, `${fb.origin} → ${fb.destination}`)">
                                <i class="fas fa-star me-1"></i> Review
                            </button>
                            <span v-else class="text-muted small">—</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a v-if="['paid', 'refunding', 'cancelled'].includes(fb.status)"
                                   :href="`/flights/booking/${fb.id}/invoice`"
                                   class="btn btn-sm btn-outline-primary" title="Invoice PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <button v-if="['pending', 'paid'].includes(fb.status)"
                                    class="btn btn-sm btn-outline-danger"
                                    @click="cancelFlight(fb.id)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
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
    flightBookings: { type: Array, required: true },
    filters: { type: Object, default: () => ({}) },
})

const emit = defineEmits(['update-filters', 'open-review'])

const page = usePage()
const pagination = computed(() => page.props.flightBookings)
const hasPagination = computed(() => pagination.value?.links?.length > 3)
const paginationLinks = computed(() => pagination.value?.links ?? [])

const loading = ref(false)
const localFilters = reactive({ flight_status: props.filters.flight_status ?? '' })

function submitFilters() {
    loading.value = true
    emit('update-filters', { flight_status: localFilters.flight_status })
}

watch(() => props.filters, (f) => {
    localFilters.flight_status = f.flight_status ?? ''
    loading.value = false
}, { immediate: true })

function statusBadge(status) {
    return {
        paid: 'bg-success',
        cancelled: 'bg-danger',
        pending: 'bg-warning text-dark',
    }[status] ?? 'bg-secondary'
}

function capitalize(s) {
    return s ? s.charAt(0).toUpperCase() + s.slice(1) : ''
}

function cancelFlight(id) {
    const msg = 'Are you sure you want to cancel this flight booking?'
    if (!confirm(msg)) return
    router.delete(`/flights/booking/${id}/cancel`, { preserveScroll: true })
}
</script>
