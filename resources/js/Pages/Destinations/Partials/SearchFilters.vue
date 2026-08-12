<template>
    <section class="tt-section-sm">
        <div class="container">
            <div class="tt-search-bar" data-aos="fade-up">
                <form @submit.prevent="submit">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4">
                            <div class="tt-form-group" style="position:relative;">
                                <label><i class="fas fa-search"></i> Search Destinations</label>
                                <input type="text" class="tt-input" v-model="local.search"
                                       placeholder="Where do you want to go?">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="tt-form-group">
                                <label><i class="fas fa-tag"></i> Category</label>
                                <select class="tt-select" v-model="local.category" @change="submit">
                                    <option value="">All</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                        {{ cat.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="tt-form-group">
                                <label><i class="fas fa-dollar-sign"></i> Budget</label>
                                <select class="tt-select" v-model="local.price_range" @change="submit">
                                    <option value="">Any</option>
                                    <option value="0-500">Under $500</option>
                                    <option value="500-2000">$500 – $2,000</option>
                                    <option value="2000-5000">$2,000 – $5,000</option>
                                    <option value="5000+">Over $5,000</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="tt-form-group">
                                <label><i class="fas fa-sort"></i> Sort by</label>
                                <select class="tt-select" v-model="sortKey" @change="updateSort">
                                    <option value="title-asc">Name</option>
                                    <option value="pricing-asc">Price (low)</option>
                                    <option value="pricing-desc">Price (high)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="tt-form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn-tt-primary w-100">
                                    Search <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>

<script setup>
import { reactive, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    filters: { type: Object, default: () => ({}) },
    categories: { type: Array, default: () => [] },
})

const local = reactive({ ...props.filters })

watch(() => props.filters, (f) => {
    Object.assign(local, f)
}, { immediate: true })

const sortKey = computed({
    get: () => `${local.sort ?? 'title'}-${local.order ?? 'asc'}`,
    set: () => {},
})

function updateSort(e) {
    const [sort, order] = e.target.value.split('-')
    local.sort = sort
    local.order = order
    submit()
}

function submit() {
    router.get('/destinations', { ...local }, {
        preserveState: true,
        preserveScroll: true,
    })
}
</script>
