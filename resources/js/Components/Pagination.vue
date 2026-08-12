<template>
    <nav v-if="links.length > 3" class="mt-3">
        <ul class="pagination pagination-sm justify-content-center mb-0">
            <li v-for="link in links" :key="link.label"
                class="page-item"
                :class="{ disabled: !link.url, active: link.active }">
                <Link v-if="link.url"
                      :href="link.url"
                      class="page-link"
                      v-html="link.label"
                      preserve-scroll />
                <span v-else class="page-link" v-html="link.label"></span>
            </li>
        </ul>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    links: { type: Array, required: true },
})
</script>
