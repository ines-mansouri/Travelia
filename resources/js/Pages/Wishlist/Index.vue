<template>
    <AppLayout>
        <section class="tt-page-hero tt-page-hero-sm">
            <div class="tt-page-hero-bg" :style="{ backgroundImage: `url('/images/place-1.jpg')` }"></div>
            <div class="container" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><Link href="/"><i class="fas fa-home me-1"></i>Home</Link></li>
                        <li class="breadcrumb-item active">Wishlist</li>
                    </ol>
                </nav>
                <h1 class="tt-page-title">My <span class="accent">Wishlist</span></h1>
                <p class="tt-page-subtitle">Your favorite destinations saved for later</p>
            </div>
        </section>

        <section class="tt-section">
            <div class="container">
                <div v-if="!items.length" class="tt-empty-state text-center" data-aos="fade-up">
                    <div class="icon" style="font-size:3rem;color:var(--tt-primary);"><i class="fas fa-heart"></i></div>
                    <h3 class="mt-3">Your wishlist is empty</h3>
                    <p class="text-muted">Start exploring destinations and add your favorites here!</p>
                    <Link href="/destinations" class="btn btn-primary">
                        <i class="fas fa-compass me-1"></i> Browse Destinations
                    </Link>
                </div>

                <div v-else class="row g-4">
                    <div v-for="(dest, index) in items" :key="dest.id" class="col-md-4"
                         data-aos="fade-up" :data-aos-delay="(index % 3) * 50">
                        <div class="tt-dest-card">
                            <div class="tt-dest-card-img">
                                <img :src="dest.image_url" :alt="dest.title"
                                     loading="lazy" style="height:200px;width:100%;object-fit:cover;">
                                <span class="badge-cat">{{ dest.category_name }}</span>
                            </div>
                            <div class="tt-dest-card-body">
                                <div class="tt-dest-card-meta">
                                    <span><i class="fas fa-map-marker-alt"></i> {{ dest.title }}</span>
                                    <span><i class="fas fa-clock"></i> {{ dest.duration }}</span>
                                </div>
                                <h3 class="tt-dest-card-title">
                                    <Link :href="`/destinations/destinations/${dest.id}`">{{ dest.title }}</Link>
                                </h3>
                                <p class="tt-dest-card-desc">{{ truncate(dest.description, 100) }}</p>
                                <div class="tt-dest-card-footer">
                                    <div>
                                        <div class="tt-dest-price-label">From</div>
                                        <div class="tt-dest-price-value">{{ dest.pricing }}</div>
                                    </div>
                                    <button class="btn btn-outline-danger btn-sm" @click="remove(dest.id)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <Pagination v-if="hasPagination" :links="paginationLinks" class="mt-4" />
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'

const page = usePage()
const items = computed(() => page.props.wishlisted?.data ?? [])
const paginationLinks = computed(() => page.props.wishlisted?.links ?? [])
const hasPagination = computed(() => paginationLinks.value.length > 3)

function truncate(text, max) {
    if (!text) return ''
    return text.length > max ? text.substring(0, max) + '...' : text
}

function remove(id) {
    router.delete(`/wishlist/${id}`, { preserveScroll: true })
}
</script>
