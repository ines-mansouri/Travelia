<template>
    <article class="tt-dest-card" :data-aos="'fade-up'" :data-aos-delay="delay">
        <div class="tt-dest-card-img">
            <img :src="destination.image_url" :alt="destination.title" loading="lazy">
            <span class="badge-cat">{{ destination.category_name }}</span>
            <button class="btn-fav" :class="{ active: isWishlisted }"
                    aria-label="Add to favorites"
                    @click.stop="$emit('toggle-wishlist', destination.id)">
                <i :class="(isWishlisted ? 'fas' : 'far') + ' fa-heart'"></i>
            </button>
        </div>
        <div class="tt-dest-card-body">
            <div class="tt-dest-card-meta">
                <span><i class="fas fa-map-marker-alt"></i> {{ destination.title }}</span>
                <span><i class="fas fa-clock"></i> {{ destination.duration }}</span>
            </div>
            <h3 class="tt-dest-card-title">
                <Link :href="`/destinations/${destination.id}`">{{ destination.title }}</Link>
            </h3>
            <p class="tt-dest-card-desc">{{ truncate(destination.description, 120) }}</p>
            <div v-if="destination.tour_type || destination.average_rating" class="mb-2 d-flex flex-wrap gap-1">
                <span v-if="destination.tour_type" class="tt-tag"><i class="fas fa-tag me-1"></i>{{ destination.tour_type }}</span>
                <span v-if="destination.group_size" class="tt-tag"><i class="fas fa-users me-1"></i>{{ destination.group_size }}</span>
                <span v-if="destination.average_rating" class="tt-tag"><i class="fas fa-star text-warning me-1"></i>{{ destination.average_rating }}</span>
            </div>
            <div class="tt-dest-card-footer">
                <div>
                    <div class="tt-dest-price-label">From</div>
                    <div class="tt-dest-price-value">{{ destination.pricing }}</div>
                </div>
                <Link :href="`/destinations/${destination.id}`" class="tt-dest-card-link">
                    Explore <i class="fas fa-arrow-right"></i>
                </Link>
            </div>
        </div>
    </article>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    destination: { type: Object, required: true },
    isWishlisted: { type: Boolean, default: false },
    delay: { type: Number, default: 0 },
})

defineEmits(['toggle-wishlist'])

function truncate(text, max) {
    if (!text) return ''
    return text.length > max ? text.substring(0, max) + '...' : text
}
</script>
