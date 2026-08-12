<template>
    <AppLayout>
        <section class="tt-page-hero">
            <div class="tt-page-hero-bg" :style="{ backgroundImage: `url(${destination.image_url})` }"></div>
            <div class="container" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><Link href="/"><i class="fas fa-home me-1"></i>Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/destinations">Destinations</Link></li>
                        <li class="breadcrumb-item active">{{ destination.title }}</li>
                    </ol>
                </nav>
                <h1 class="tt-page-title">{{ destination.title }}</h1>
                <p class="tt-page-subtitle">{{ destination.description }}</p>
            </div>
        </section>

        <section class="tt-section">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="tt-detail-gallery mb-4" data-aos="fade-up">
                            <img :src="destination.image_url" :alt="destination.title"
                                 class="img-fluid rounded-3 w-100" loading="lazy">
                            <span class="tt-detail-badge"><i class="fas fa-map-marker-alt me-1"></i> Featured</span>
                        </div>

                        <div class="tt-detail-content" data-aos="fade-up">
                            <h2>{{ destination.title }}</h2>
                            <p class="lead">{{ destination.description }}</p>
                            <h4>About This Destination</h4>
                            <p>{{ destination.content }}</p>
                        </div>

                        <div class="tt-detail-booking" data-aos="fade-up">
                            <div class="row g-0 align-items-center">
                                <div class="col-md-8 p-4">
                                    <h4>Ready to Book Your Adventure?</h4>
                                    <p>Experience the beauty and culture of {{ destination.title }}. Our expert guides will ensure an unforgettable journey.</p>
                                    <div class="d-flex flex-wrap gap-3">
                                        <button class="btn-tt-primary" @click="addToCart">
                                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                        </button>
                                        <Link href="/contact" class="btn-tt-outline">
                                            <i class="fas fa-phone me-2"></i> Contact Us
                                        </Link>
                                    </div>
                                </div>
                                <div class="col-md-4 tt-detail-booking-accent text-center p-4">
                                    <i class="fas fa-plane fa-3x mb-2"></i>
                                    <h5 class="mb-1">Book Now</h5>
                                    <small>Best Rates Guaranteed</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-4" data-aos="fade-up">
                            <div class="tt-sidebar-card">
                                <h4 class="mb-1"><i class="fas fa-star me-2"></i> Reviews</h4>

                                <template v-if="canReview">
                                    <form @submit.prevent="submitReview" class="tt-form mt-3 mb-4 p-3 rounded-3"
                                          style="background:var(--tt-light);">
                                        <div class="mb-2">
                                            <label class="tt-label">Your Rating</label>
                                            <select v-model="reviewForm.rating" class="form-select" required>
                                                <option value="">Select...</option>
                                                <option v-for="r in [5,4,3,2,1]" :key="r" :value="r">{{ r }} star{{ r > 1 ? 's' : '' }}</option>
                                            </select>
                                            <div v-if="reviewErrors.rating" class="text-danger small mt-1">{{ reviewErrors.rating }}</div>
                                        </div>
                                        <div class="mb-2">
                                            <label class="tt-label">Your Review</label>
                                            <textarea v-model="reviewForm.comment" class="tt-input" rows="3"
                                                      placeholder="Share your experience..." maxlength="1000"></textarea>
                                        </div>
                                        <button type="submit" class="btn-tt-primary" :disabled="reviewSubmitting">
                                            <span v-if="reviewSubmitting" class="spinner-border spinner-border-sm me-1"></span>
                                            <i v-else class="fas fa-paper-plane me-1"></i>
                                            {{ reviewSubmitting ? 'Submitting...' : 'Submit Review' }}
                                        </button>
                                    </form>
                                </template>
                                <p v-else class="text-muted mt-3">
                                    <Link href="/login" class="fw-semibold" style="color:var(--tt-primary);">Sign in</Link> to leave a review.
                                </p>

                                <div v-if="reviews.length">
                                    <div v-for="review in reviews" :key="review.id" class="py-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>{{ review.user_name }}</strong>
                                                <div class="text-warning small">
                                                    <i v-for="s in 5" :key="s"
                                                       :class="s <= review.rating ? 'fas fa-star' : 'far fa-star text-muted'"></i>
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ review.created_at }}</small>
                                        </div>
                                        <p v-if="review.comment" class="mt-2 mb-0 text-muted">{{ review.comment }}</p>
                                        <button v-if="review.can_delete" class="btn btn-sm btn-outline-danger mt-2"
                                                @click="deleteReview(review.id)">
                                            Delete
                                        </button>
                                    </div>
                                    <Pagination v-if="paginationLinks.length > 3" :links="paginationLinks" class="mt-3" />
                                </div>
                                <p v-else class="text-muted">No reviews yet. Be the first to share your experience!</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="tt-sidebar-card mb-4" data-aos="fade-left">
                            <div class="text-center mb-4">
                                <div class="tt-price-label">Starting From</div>
                                <div class="tt-price-amount">{{ destination.pricing }}</div>
                                <small class="text-muted">per person</small>
                            </div>
                            <div class="tt-info-list">
                                <div class="tt-info-row">
                                    <div class="tt-info-icon"><i class="fas fa-clock"></i></div>
                                    <div>
                                        <div class="tt-info-label">Duration</div>
                                        <div class="tt-info-value">{{ destination.duration }}</div>
                                    </div>
                                </div>
                                <div class="tt-info-row">
                                    <div class="tt-info-icon"><i class="fas fa-users"></i></div>
                                    <div>
                                        <div class="tt-info-label">Group Size</div>
                                        <div class="tt-info-value">{{ destination.group_size }}</div>
                                    </div>
                                </div>
                                <div class="tt-info-row">
                                    <div class="tt-info-icon"><i class="fas fa-map-marked-alt"></i></div>
                                    <div>
                                        <div class="tt-info-label">Tour Type</div>
                                        <div class="tt-info-value">{{ destination.tour_type }}</div>
                                    </div>
                                </div>
                                <div class="tt-info-row">
                                    <div class="tt-info-icon"><i class="fas fa-star"></i></div>
                                    <div>
                                        <div class="tt-info-label">Rating</div>
                                        <div class="tt-info-value">
                                            <template v-if="destination.reviews_count > 0">
                                                {{ destination.average_rating }}/5 ({{ destination.reviews_count }} review{{ destination.reviews_count > 1 ? 's' : '' }})
                                            </template>
                                            <template v-else>No reviews yet</template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tt-sidebar-card mb-4" data-aos="fade-left">
                            <h5>Find Destinations</h5>
                            <form @submit.prevent="searchDestinations" class="input-group">
                                <input type="text" v-model="searchQuery" class="tt-input" placeholder="Search destinations...">
                                <button type="submit" class="btn-tt-primary"><i class="fas fa-search"></i></button>
                            </form>
                        </div>

                        <div v-if="allTags.length" class="tt-sidebar-card mb-4" data-aos="fade-left">
                            <h5>Popular Tags</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <span v-for="tag in allTags" :key="tag.name" class="tt-tag">{{ tag.name }}</span>
                            </div>
                        </div>

                        <div v-if="allCategories.length" class="tt-sidebar-card mb-4" data-aos="fade-left">
                            <h5>Categories</h5>
                            <ul class="tt-sidebar-list">
                                <li v-for="cat in allCategories" :key="cat.id">
                                    <Link :href="`/destinations?category=${cat.id}`">
                                        {{ cat.name }} <i class="fas fa-chevron-right"></i>
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        <div class="tt-sidebar-card text-center" data-aos="fade-left">
                            <div class="tt-info-icon mx-auto mb-3"><i class="fas fa-headset"></i></div>
                            <h5>Need Help?</h5>
                            <p class="text-muted">Our travel experts are here 24/7</p>
                            <a href="tel:+1234567890" class="btn-tt-primary w-100 mb-2">
                                <i class="fas fa-phone me-2"></i> +1 234 567 890
                            </a>
                            <a href="mailto:info@travelia.com" class="btn-tt-outline w-100">
                                <i class="fas fa-envelope me-2"></i> Get Quote
                            </a>
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
import Pagination from '@/Components/Pagination.vue'

const page = usePage()
const destination = computed(() => page.props.destination)
const reviews = computed(() => page.props.reviews?.data ?? [])
const paginationLinks = computed(() => page.props.reviews?.links ?? [])
const allTags = computed(() => page.props.tags ?? [])
const allCategories = computed(() => page.props.categories ?? [])
const canReview = computed(() => page.props.can_review)

const searchQuery = ref('')
const reviewForm = reactive({ rating: '', comment: '' })
const reviewErrors = ref({})
const reviewSubmitting = ref(false)

function addToCart() {
    router.post(`/cart/add/${destination.value.id}`, {}, { preserveScroll: true })
}

function searchDestinations() {
    if (searchQuery.value.trim()) {
        router.get('/destinations', { search: searchQuery.value })
    }
}

function submitReview() {
    reviewSubmitting.value = true
    reviewErrors.value = {}

    router.post(`/destinations/${destination.value.id}/reviews`, { ...reviewForm }, {
        preserveScroll: true,
        onSuccess: () => {
            reviewForm.rating = ''
            reviewForm.comment = ''
            reviewSubmitting.value = false
        },
        onError: (errs) => {
            reviewErrors.value = errs
            reviewSubmitting.value = false
        },
        onFinish: () => { reviewSubmitting.value = false },
    })
}

function deleteReview(id) {
    if (!confirm('Delete this review?')) return
    router.delete(`/reviews/${id}`, { preserveScroll: true })
}
</script>
