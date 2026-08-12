<template>
    <section class="tt-section tt-section-light">
        <div class="container">
            <div class="tt-section-header text-center" data-aos="fade-up">
                <div class="tt-pretitle">Explore by Type</div>
                <h2 class="tt-title">Popular <span class="accent">Categories</span></h2>
            </div>
            <div class="tt-cat-grid" data-aos="fade-up">
                <Link v-for="cat in categories" :key="cat.id"
                      :href="`/destinations?category=${cat.id}`"
                      class="tt-cat-card"
                      style="text-decoration:none;color:inherit;">
                    <div class="icon">
                        <i :class="cat.icon"></i>
                    </div>
                    <h5>{{ cat.name }}</h5>
                    <span class="count">{{ cat.count }} Tours</span>
                </Link>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    categories: { type: Array, default: () => [] },
})
</script>
