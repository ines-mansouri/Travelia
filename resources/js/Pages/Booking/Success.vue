<template>
    <AppLayout>
        <section class="tt-section py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center" data-aos="fade-up">
                        <div class="mb-4">
                            <div style="width:80px;height:80px;border-radius:50%;background:#d1fae5;
                                        display:flex;align-items:center;justify-content:center;margin:0 auto;">
                                <i class="fas fa-check-circle" style="font-size:2.5rem;color:#065f46;"></i>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-2">Payment Successful!</h2>
                        <p class="text-muted mb-1">Thank you for your booking, <strong>{{ booking.customer_name }}</strong>.</p>
                        <p class="text-muted mb-4">A confirmation email will be sent to <strong>{{ booking.customer_email }}</strong>.</p>

                        <div class="tt-sidebar-card text-start mb-4">
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted">Invoice</span>
                                <strong>#{{ booking.invoice_number }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted">Destination</span>
                                <strong>{{ booking.destination_title }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted">Travel Date</span>
                                <strong>{{ booking.travel_date }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted">Status</span>
                                <span class="badge bg-success">{{ booking.status }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted">Payment</span>
                                <span class="badge bg-success">{{ booking.payment_status }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between py-2">
                                <strong class="fs-5">Total Paid</strong>
                                <strong class="fs-5" style="color:var(--tt-primary);">
                                    {{ booking.currency_symbol }}{{ booking.total_price }}
                                </strong>
                            </div>
                        </div>

                        <div class="d-flex gap-3 justify-content-center">
                            <Link href="/dashboard" class="btn btn-primary">
                                <i class="fas fa-tachometer-alt me-1"></i> Go to Dashboard
                            </Link>
                            <Link href="/destinations" class="btn btn-outline-secondary">
                                <i class="fas fa-compass me-1"></i> Browse More
                            </Link>
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
</script>
