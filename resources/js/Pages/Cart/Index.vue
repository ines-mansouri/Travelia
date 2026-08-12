<template>
    <AppLayout>
        <section class="tt-page-hero tt-page-hero-sm">
            <div class="tt-page-hero-bg" :style="{ backgroundImage: `url('/images/place-1.jpg')` }"></div>
            <div class="container" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><Link href="/"><i class="fas fa-home me-1"></i>Home</Link></li>
                        <li class="breadcrumb-item active">Cart</li>
                    </ol>
                </nav>
                <h1 class="tt-page-title">Your <span class="accent">Cart</span></h1>
            </div>
        </section>

        <section class="tt-section">
            <div class="container">
                <div v-if="!destination" class="text-center py-5" data-aos="fade-up">
                    <div class="icon" style="font-size:3rem;color:var(--tt-primary);"><i class="fas fa-shopping-cart"></i></div>
                    <h3 class="mt-3">Your cart is empty</h3>
                    <p class="text-muted">Browse destinations and add your next adventure!</p>
                    <Link href="/destinations" class="btn btn-primary mt-2">
                        <i class="fas fa-compass me-1"></i> Browse Destinations
                    </Link>
                </div>

                <div v-else class="row g-5">
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="tt-cart-table">
                            <div class="tt-cart-header d-none d-md-flex">
                                <div class="tt-cart-col-product">Product</div>
                                <div class="tt-cart-col-price">Price</div>
                                <div class="tt-cart-col-qty">Quantity</div>
                                <div class="tt-cart-col-total">Total</div>
                            </div>

                            <div class="tt-cart-item">
                                <div class="tt-cart-col-product">
                                    <div class="d-flex align-items-center gap-3">
                                        <img :src="destination.image_url" :alt="destination.title"
                                             class="tt-cart-img" loading="lazy">
                                        <div>
                                            <h6 class="mb-1">{{ destination.title }}</h6>
                                            <small class="text-muted">{{ destination.category_name }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="tt-cart-col-price" data-label="Price">{{ destination.pricing }}</div>
                                <div class="tt-cart-col-qty" data-label="Quantity">
                                    <div class="tt-qty-control">
                                        <button class="tt-qty-btn" @click="decrement"><i class="fas fa-minus"></i></button>
                                        <input type="number" class="tt-qty-input" v-model.number="quantity" min="1" max="10">
                                        <button class="tt-qty-btn" @click="increment"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="tt-cart-col-total" data-label="Total">{{ destination.pricing }}</div>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center mt-4 gap-3">
                            <Link href="/destinations" class="btn-tt-outline">
                                <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                            </Link>
                            <div class="d-flex gap-2">
                                <button class="btn-tt-outline" style="border-color:#dc3545;color:#dc3545;" @click="clearCart">
                                    <i class="fas fa-trash me-1"></i> Clear Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4" data-aos="fade-left">
                        <div class="tt-sidebar-card">
                            <h5 class="mb-4"><i class="fas fa-receipt me-2"></i> Order Summary</h5>

                            <div class="mb-4">
                                <label class="tt-label mb-2">Have a coupon?</label>
                                <div class="input-group">
                                    <input type="text" class="tt-input" placeholder="Enter code">
                                    <button class="btn-tt-primary">Apply</button>
                                </div>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <strong>{{ destination.pricing }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Taxes & Fees</span>
                                <span class="text-muted">Included</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-4">
                                <strong class="fs-5">Total</strong>
                                <strong class="fs-5" style="color:var(--tt-primary);">{{ destination.pricing }}</strong>
                            </div>

                            <Link href="/checkout" class="btn-tt-primary w-100 text-center d-block">
                                Proceed to Checkout <i class="fas fa-arrow-right ms-1"></i>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const page = usePage()
const destination = computed(() => page.props.destination ?? null)
const quantity = ref(1)

function increment() { if (quantity.value < 10) quantity.value++ }
function decrement() { if (quantity.value > 1) quantity.value-- }

function clearCart() {
    router.delete('/cart/remove/0', { preserveScroll: true })
}
</script>
