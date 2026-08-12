<template>
    <AppLayout>
        <section class="tt-section" style="padding-top:120px;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center" data-aos="zoom-in">
                        <div class="tt-success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h1 class="display-5 fw-bold mt-3 tt-display">
                            {{ isPaid ? 'Booking Confirmed!' : 'Booking Received' }}
                        </h1>
                        <p class="text-muted fs-5">
                            <template v-if="isPaid">
                                Your payment was successful. A confirmation email is on its way.
                            </template>
                            <template v-else>
                                We're verifying your payment. You'll receive a confirmation shortly.
                            </template>
                        </p>
                        <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
                            <Link href="/flights" class="btn-tt-outline">
                                <i class="fas fa-arrow-left me-1"></i> Back to Flights
                            </Link>
                            <a :href="`/flights/booking/${booking.id}/invoice`" class="btn-tt-accent" target="_blank">
                                <i class="fas fa-download me-1"></i> Download Receipt
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="tt-section-sm">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="tt-sidebar-card">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="tt-icon-box">
                                    <i class="fas fa-plane"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ itineraryLabel }}</h5>
                                    <small class="text-muted">{{ carrier }}</small>
                                </div>
                            </div>

                            <template v-if="isMultiCity && legs.length">
                                <div v-for="(leg, i) in legs" :key="i"
                                     class="tt-flight-route py-3 px-3 rounded-3 mb-3" style="background:var(--tt-light);">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <span class="tt-leg-number">{{ i + 1 }}</span>
                                        <strong style="font-size:0.85rem;">Flight {{ i + 1 }}</strong>
                                    </div>
                                    <div class="row align-items-center text-center">
                                        <div class="col-4">
                                            <div class="tt-time">{{ formatTime(leg.departure) }}</div>
                                            <div class="text-muted small">{{ leg.originCode || leg.origin }}</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="small text-muted mb-1">{{ formatDuration(leg.duration) }}</div>
                                            <div class="tt-flight-line">
                                                <hr class="tt-dash">
                                                <i class="fas fa-plane tt-plane-icon"></i>
                                                <hr class="tt-dash">
                                            </div>
                                            <span class="tt-flight-tag" :class="(leg.stops ?? 0) === 0 ? 'direct' : 'stops'">
                                                {{ (leg.stops ?? 0) === 0 ? 'Direct' : (leg.stops ?? 0) + ' stop' + ((leg.stops ?? 0) > 1 ? 's' : '') }}
                                            </span>
                                        </div>
                                        <div class="col-4">
                                            <div class="tt-time">{{ formatTime(leg.arrival) }}</div>
                                            <div class="text-muted small">{{ leg.destinationCode || leg.destination }}</div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template v-else>
                                <div class="tt-flight-route py-3 px-3 rounded-3" style="background:var(--tt-light);">
                                    <div class="row align-items-center text-center">
                                        <div class="col-4">
                                            <div class="tt-time">{{ formatTime(departure) }}</div>
                                            <div class="text-muted small">{{ origin }}</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="small text-muted mb-1">{{ formatDuration(firstLegDuration) }}</div>
                                            <div class="tt-flight-line">
                                                <hr class="tt-dash">
                                                <i class="fas fa-plane tt-plane-icon"></i>
                                                <hr class="tt-dash">
                                            </div>
                                            <span class="tt-flight-tag" :class="firstLeg.stops === 0 ? 'direct' : 'stops'">
                                                {{ firstLeg.stops === 0 ? 'Direct' : firstLeg.stops + ' stop' + (firstLeg.stops > 1 ? 's' : '') }}
                                            </span>
                                        </div>
                                        <div class="col-4">
                                            <div class="tt-time">{{ formatTime(arrival) }}</div>
                                            <div class="text-muted small">{{ destination }}</div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div class="row g-3 mt-3">
                                <div class="col-6">
                                    <small class="text-muted d-block">Airline</small>
                                    <span class="fw-semibold">{{ carrier }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Flight Date</small>
                                    <span class="fw-semibold">{{ formatDate(departure) }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Booking Reference</small>
                                    <span class="fw-semibold">#{{ booking.id }}</span>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block">Status</small>
                                    <span class="badge" :class="isPaid ? 'bg-success' : 'bg-warning'" style="font-size:0.85rem;">
                                        {{ isPaid ? 'Paid' : 'Pending' }}
                                    </span>
                                </div>
                            </div>

                            <div class="row g-3 mt-3 pt-3" style="border-top:1px solid #eee;">
                                <div class="col-6">
                                    <small class="text-muted d-block"><i class="fas fa-suitcase me-1"></i> Cabin Bag</small>
                                    <span class="fw-semibold">{{ cabinBags }} bag{{ cabinBags !== 1 ? 's' : '' }}</span>
                                    <small class="text-muted d-block" style="font-size:0.7rem;">Up to 8kg, 55x40x20cm</small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block"><i class="fas fa-suitcase-rolling me-1"></i> Checked Bag</small>
                                    <span class="fw-semibold">{{ checkedBags }} bag{{ checkedBags !== 1 ? 's' : '' }}</span>
                                    <small class="text-muted d-block" style="font-size:0.7rem;">Up to 23kg per bag</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="tt-section-sm">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="tt-sidebar-card">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="tt-icon-box tt-accent-box">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Invoice</h5>
                                    <small class="text-muted">Receipt #INV-FL-{{ String(booking.id).padStart(6, '0') }}</small>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="ps-0 text-muted">{{ invoiceItemLabel }}</td>
                                            <td class="text-end">{{ booking.currency_symbol }}{{ booking.converted_price }}</td>
                                        </tr>
                                        <tr v-if="checkedBags > 0 || cabinBags > 0">
                                            <td class="ps-0 text-muted">
                                                Baggage ({{ cabinBags }} cabin + {{ checkedBags }} checked)
                                            </td>
                                            <td class="text-end">{{ booking.currency_symbol }}{{ booking.baggage_converted_price }}</td>
                                        </tr>
                                        <tr><td colspan="2"><hr class="my-1"></td></tr>
                                        <tr>
                                            <td class="ps-0 fw-semibold">
                                                Total Charged
                                                <small class="text-muted d-block fw-normal">({{ booking.currency_code }})</small>
                                            </td>
                                            <td class="text-end fw-bold fs-5" style="color:var(--tt-primary);">
                                                {{ booking.currency_symbol }}{{ totalCharged }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0 text-muted small">Original amount (USD)</td>
                                            <td class="text-end text-muted small" style="text-decoration:line-through;">
                                                ${{ originalTotalUsd }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="ps-0 text-muted small">Booking ID</td>
                                            <td class="text-end"><code>#{{ booking.id }}</code></td>
                                        </tr>
                                        <tr v-if="booking.stripe_payment_intent_id">
                                            <td class="ps-0 text-muted small">Transaction ID</td>
                                            <td class="text-end"><code>{{ booking.stripe_payment_intent_id }}</code></td>
                                        </tr>
                                    </tbody>
                                </table>
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
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const page = usePage()
const booking = computed(() => page.props.booking)

const isPaid = computed(() => booking.value.status === 'paid')
const isMultiCity = computed(() => booking.value.flight_type === 'multi_city')
const legs = computed(() => booking.value.legs ?? [])
const firstLeg = computed(() => (booking.value.legs ?? [])[0] ?? booking.value.flight_details ?? {})
const carrier = computed(() => booking.value.carrier)
const origin = computed(() => booking.value.origin)
const destination = computed(() => booking.value.destination)
const departure = computed(() => booking.value.departure)
const arrival = computed(() => booking.value.arrival)
const cabinBags = computed(() => booking.value.cabin_bags)
const checkedBags = computed(() => booking.value.checked_bags)

const firstLegDuration = computed(() => firstLeg.value.duration ?? 0)
const totalCharged = computed(() => {
    const price = parseFloat(booking.value.converted_price || 0)
    const baggage = parseFloat(booking.value.baggage_converted_price || 0)
    return (price + baggage).toFixed(2)
})
const originalTotalUsd = computed(() => {
    const price = parseFloat(booking.value.original_price_usd || 0)
    const baggage = parseFloat(booking.value.baggage_original_price || 0)
    return (price + baggage).toFixed(2)
})

const itineraryLabel = computed(() => {
    if (booking.value.flight_type === 'multi_city') return 'Multi-City Itinerary'
    if (booking.value.flight_type === 'return') return 'Return Itinerary'
    return 'Flight Itinerary'
})

const invoiceItemLabel = computed(() => {
    if (booking.value.flight_type === 'multi_city') return 'Multi-City Flight'
    return `Flight (${origin.value} → ${destination.value})`
})

function formatTime(dt) {
    if (!dt) return '--:--'
    const d = new Date(dt)
    return String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0')
}

function formatDate(dt) {
    if (!dt) return '-'
    const d = new Date(dt)
    return d.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: '2-digit', year: 'numeric' })
}

function formatDuration(minutes) {
    if (!minutes) return ''
    const h = Math.ceil(minutes / 60)
    const m = Math.round(minutes % 60)
    return h + 'h ' + m + 'm'
}
</script>

<style scoped>
.tt-success-icon {
    font-size: 5rem;
    color: #198754;
    animation: popIn 0.6s cubic-bezier(0.68, -0.55, 0.27, 1.55);
}
@keyframes popIn {
    0% { transform: scale(0); opacity: 0; }
    60% { transform: scale(1.2); }
    100% { transform: scale(1); opacity: 1; }
}
.tt-display {
    font-family: var(--tt-font-display);
}
.tt-icon-box {
    width: 48px; height: 48px; border-radius: 12px;
    background: var(--tt-primary-light);
    display: flex; align-items: center; justify-content: center;
    color: var(--tt-primary); font-size: 1.25rem;
}
.tt-accent-box {
    background: var(--tt-accent-light);
    color: var(--tt-accent);
}
.tt-leg-number {
    background: var(--tt-primary); color: #fff;
    border-radius: 50%; width: 24px; height: 24px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem; font-weight: 700;
}
.tt-time {
    font-weight: 700; font-size: 1.5rem;
    color: var(--tt-primary);
}
.tt-flight-line {
    display: flex; align-items: center; gap: 6px; justify-content: center;
}
.tt-dash {
    flex: 1; border-top: 2px dashed var(--tt-border); margin: 0;
}
.tt-plane-icon {
    color: var(--tt-primary); font-size: 0.85rem;
}
.tt-flight-tag {
    display: inline-block; margin-top: 4px;
    font-size: 0.7rem; padding: 2px 8px; border-radius: 4px;
}
.tt-flight-tag.direct {
    background: #d1fae5; color: #065f46;
}
.tt-flight-tag.stops {
    background: #fef3c7; color: #92400e;
}
@media print {
    :deep(.tt-navbar), :deep(.tt-footer), .btn-tt-accent, .btn-tt-outline { display: none !important; }
    .tt-section { padding: 1rem 0 !important; }
    body { background: #fff !important; }
    .tt-sidebar-card { box-shadow: none !important; border: 1px solid #ddd !important; break-inside: avoid; }
}
</style>
