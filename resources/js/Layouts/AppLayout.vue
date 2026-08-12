<template>
    <div :dir="locale === 'ar' ? 'rtl' : 'ltr'">
        <Navbar />

        <main>
            <slot />
        </main>

        <Footer />
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'

const page = usePage()
const locale = computed(() => page.props.locale)
</script>
