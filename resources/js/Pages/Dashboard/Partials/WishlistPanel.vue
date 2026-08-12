<template>
    <div class="tt-sidebar-card" data-aos="fade-left">
        <h4 class="mb-1"><i class="fas fa-heart me-2"></i> Wishlist</h4>
        <p class="text-muted mb-4">Your saved destinations</p>

        <div v-if="!items.length" class="tt-empty-state text-center py-4">
            <div class="icon" style="font-size:2rem;color:var(--tt-primary);">
                <i class="fas fa-heart"></i>
            </div>
            <p class="text-muted mt-2">No saved destinations yet</p>
        </div>

        <div v-else class="d-flex flex-column gap-2">
            <Link v-for="item in items" :key="item.id"
                  :href="`/destinations/${item.id}`"
                  class="d-flex align-items-center gap-3 p-2 rounded-3 text-decoration-none"
                  style="background:var(--tt-light);">
                <img :src="item.image_url" :alt="item.title"
                     style="width:50px;height:50px;border-radius:8px;object-fit:cover;" loading="lazy">
                <div>
                    <div class="fw-semibold" style="color:var(--tt-text);">{{ item.title }}</div>
                    <small class="text-muted">{{ item.pricing }}</small>
                </div>
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    items: { type: Array, default: () => [] },
})
</script>
