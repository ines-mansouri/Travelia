<template>
    <AppLayout>
        <section class="tt-page-hero tt-page-hero-sm">
            <div class="tt-page-hero-bg" :style="{ backgroundImage: `url('/images/place-1.jpg')` }"></div>
            <div class="container" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><Link href="/"><i class="fas fa-home me-1"></i>Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/cart">Cart</Link></li>
                        <li class="breadcrumb-item active">Checkout</li>
                    </ol>
                </nav>
                <h1 class="tt-page-title">Secure <span class="accent">Checkout</span></h1>
            </div>
        </section>

        <section class="tt-section">
            <div class="container">
                <div v-if="!destination" class="text-center py-5">
                    <p class="text-muted">No destination selected.</p>
                    <Link href="/destinations" class="btn btn-primary">Browse Destinations</Link>
                </div>

                <div v-else class="row g-5">
                    <div class="col-lg-7" data-aos="fade-up">
                        <div class="tt-sidebar-card">
                            <h4 class="mb-1"><i class="fas fa-user me-2"></i> Personal Information</h4>
                            <p class="text-muted mb-4">Fill in your details to complete the booking</p>

                            <form @submit.prevent="submitCheckout" class="tt-form">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="tt-form-group">
                                            <label class="tt-label">First Name *</label>
                                            <input type="text" v-model="form.firstname" class="tt-input" placeholder="John" required>
                                            <div v-if="errors.firstname" class="text-danger small mt-1">{{ errors.firstname }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="tt-form-group">
                                            <label class="tt-label">Last Name *</label>
                                            <input type="text" v-model="form.lastname" class="tt-input" placeholder="Doe" required>
                                            <div v-if="errors.lastname" class="text-danger small mt-1">{{ errors.lastname }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tt-form-group">
                                    <label class="tt-label">Phone Number *</label>
                                    <input type="tel" v-model="form.phone" class="tt-input" placeholder="+1 234 567 890" required>
                                    <div v-if="errors.phone" class="text-danger small mt-1">{{ errors.phone }}</div>
                                </div>
                                <div class="tt-form-group">
                                    <label class="tt-label">Email Address *</label>
                                    <input type="email" v-model="form.email" class="tt-input" placeholder="you@example.com" required>
                                    <div v-if="errors.email" class="text-danger small mt-1">{{ errors.email }}</div>
                                </div>

                                <button type="submit" class="btn-tt-accent mt-3 w-100" :disabled="submitting">
                                    <span v-if="submitting" class="spinner-border spinner-border-sm me-1"></span>
                                    <i v-else class="fas fa-lock me-2"></i>
                                    {{ submitting ? 'Processing...' : 'Proceed to Pay' }}
                                </button>
                            </form>

                            <div class="text-center mt-3">
                                <small class="text-muted"><i class="fas fa-shield-alt me-1"></i> Secure payments powered by Stripe</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5" data-aos="fade-left">
                        <div class="tt-sidebar-card">
                            <h4 class="mb-1"><i class="fas fa-shopping-bag me-2"></i> Your Package</h4>
                            <p class="text-muted mb-4">Tour booking details</p>

                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                <strong>Tour</strong>
                                <strong>Total</strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                <span>{{ destination.title }}</span>
                                <span>{{ destination.pricing }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                <span>Subtotal</span>
                                <span>{{ destination.pricing }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center py-3 mb-4">
                                <strong class="fs-5">Total</strong>
                                <strong class="fs-5" style="color:var(--tt-primary);">{{ destination.pricing }}</strong>
                            </div>

                            <p class="text-muted small mb-0">
                                <i class="fas fa-info-circle me-1"></i> Can't wait to start your vacation?
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { reactive, ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const page = usePage()
const destination = computed(() => page.props.destination ?? null)
const submitting = ref(false)
const errors = ref({})

const form = reactive({
    firstname: '',
    lastname: '',
    phone: '',
    email: '',
})

function submitCheckout() {
    submitting.value = true
    errors.value = {}

    router.post('/checkout/store', { ...form }, {
        preserveScroll: true,
        onError: (errs) => {
            errors.value = errs
            submitting.value = false
        },
        onFinish: () => { submitting.value = false },
    })
}
</script>
