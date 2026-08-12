<template>
    <AppLayout>
        <section class="tt-page-hero">
            <div class="tt-page-hero-bg" style="background-image: url('/images/place-4.jpg');"></div>
            <div class="container" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><Link href="/"><i class="fas fa-home me-1"></i>Home</Link></li>
                        <li class="breadcrumb-item active">Destinations</li>
                    </ol>
                </nav>
                <h1 class="tt-page-title">Discover the World's <span class="accent">Wonders</span></h1>
                <p class="tt-page-subtitle">
                    From tropical beaches to mountain peaks, ancient cities to wild frontiers,
                    explore our handpicked destinations that showcase our planet's natural beauty.
                </p>
            </div>
        </section>

        <section class="tt-section">
            <div class="container">
                <div class="tt-section-header text-center" data-aos="fade-up">
                    <div class="tt-pretitle">Explore the World</div>
                    <h2 class="tt-title">
                        <template v-if="filters.search">
                            Results for "<span class="accent">{{ filters.search }}</span>"
                        </template>
                        <template v-else>
                            All <span class="accent">Destinations</span>
                        </template>
                    </h2>
                    <p class="tt-subtitle">
                        <template v-if="destinations.data.length">
                            Showing {{ destinations.data.length }} amazing destinations
                        </template>
                        <template v-else>
                            No destinations found matching your criteria
                        </template>
                    </p>
                </div>

                <form @submit.prevent="filterDestinations" class="tt-filter-bar mb-4" data-aos="fade-up">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" v-model="localFilters.search" placeholder="Search destinations...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" v-model="localFilters.category">
                                <option value="">All Categories</option>
                                <option v-for="cat in categories" :key="cat.id" :value="String(cat.id)">{{ cat.name }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" v-model="localFilters.sort">
                                <option value="title">A-Z</option>
                                <option value="pricing">Price</option>
                                <option value="duration">Duration</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn-tt-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <div v-if="!destinations.data.length" class="tt-empty-state text-center py-5" data-aos="fade-up">
                    <div class="icon" style="font-size:3rem;color:var(--tt-primary);"><i class="fas fa-search"></i></div>
                    <h4 class="mt-3">No Destinations Found</h4>
                    <p class="text-muted">We couldn't find any destinations matching your search criteria. Try adjusting your filters.</p>
                    <Link href="/destinations" class="btn-tt-primary"><i class="fas fa-times me-1"></i> Clear Filters</Link>
                </div>

                <template v-else>
                    <div class="tt-dest-grid">
                        <div v-for="(dest, i) in destinations.data" :key="dest.id"
                             class="tt-dest-card" data-aos="fade-up" :data-aos-delay="(i % 3) * 100">
                            <div class="tt-dest-card-img">
                                <img :src="dest.image_url" :alt="dest.title" loading="lazy">
                                <span class="badge-cat">{{ dest.category_name }}</span>
                                <button v-if="isLoggedIn" class="tt-wishlist-btn"
                                        :class="{ active: wishlistIds.includes(dest.id) }"
                                        @click="toggleWishlist(dest.id)">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="tt-dest-card-body">
                                <div class="tt-dest-card-meta">
                                    <span><i class="fas fa-clock"></i> {{ dest.duration }}</span>
                                    <span><i class="fas fa-users"></i> {{ dest.group_size }}</span>
                                </div>
                                <h3 class="tt-dest-card-title">
                                    <Link :href="`/destinations/${dest.id}`">{{ dest.title }}</Link>
                                </h3>
                                <p class="tt-dest-card-desc">{{ dest.description }}</p>
                                <div class="tt-dest-card-footer">
                                    <div>
                                        <div class="tt-price">{{ dest.pricing }}</div>
                                        <small class="text-muted">per person</small>
                                    </div>
                                    <div class="text-end">
                                        <div v-if="dest.average_rating" class="tt-rating">
                                            <i class="fas fa-star text-warning"></i>
                                            {{ dest.average_rating }}
                                        </div>
                                        <small v-if="dest.tour_type" class="text-muted">{{ dest.tour_type }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <Pagination v-if="destinations.links?.length > 3" :links="destinations.links" class="mt-5 d-flex justify-content-center" />
                </template>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { reactive, watch } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'

const page = usePage()
const destinations = page.props.destinations
const categories = page.props.categories ?? []
const wishlistIds = page.props.wishlistIds ?? []
const filters = page.props.filters ?? {}
const isLoggedIn = page.props.auth?.user != null

const localFilters = reactive({ ...filters })

function filterDestinations() {
    router.get('/destinations', { ...localFilters }, { preserveState: true, preserveScroll: true })
}

function toggleWishlist(destinationId) {
    router.post(`/wishlist/${destinationId}/toggle`, {}, {
        preserveScroll: true,
        preserveState: true,
    })
}

watch(() => page.props.filters, (f) => {
    Object.assign(localFilters, f)
}, { immediate: true })
</script>

<style scoped>
.tt-dest-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 1.5rem;
}

.tt-dest-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
}

.tt-dest-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

.tt-dest-card-img {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.tt-dest-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}

.tt-dest-card:hover .tt-dest-card-img img {
    transform: scale(1.05);
}

.tt-dest-card-body {
    padding: 1.25rem;
}

.tt-dest-card-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.tt-dest-card-meta span i {
    margin-right: 0.35rem;
}

.tt-dest-card-title {
    font-size: 1.15rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.tt-dest-card-title a {
    color: #1a1a2e;
    text-decoration: none;
}

.tt-dest-card-title a:hover {
    color: var(--tt-primary);
}

.tt-dest-card-desc {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.tt-dest-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    padding-top: 0.75rem;
    border-top: 1px solid #f0f0f0;
}

.tt-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--tt-primary);
}

.tt-rating {
    font-size: 0.9rem;
    font-weight: 600;
}
</style>
