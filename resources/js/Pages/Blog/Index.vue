<template>
    <AppLayout>
        <section class="tt-page-hero">
            <div class="tt-page-hero-bg" style="background-image: url('/images/place-3.jpg');"></div>
            <div class="container" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><Link href="/"><i class="fas fa-home me-1"></i>Home</Link></li>
                        <li class="breadcrumb-item active">Blog</li>
                    </ol>
                </nav>
                <h1 class="tt-page-title">Travel <span class="accent">Stories</span></h1>
                <p class="tt-page-subtitle">Discover insider tips, travel stories, and hidden gems across the world's stunning landscapes and rich cultures.</p>
            </div>
        </section>

        <section class="tt-section">
            <div class="container">
                <div class="tt-section-header text-center" data-aos="fade-up">
                    <div class="tt-pretitle">Latest from Our Blog</div>
                    <h2 class="tt-title">Stories & <span class="accent">Insights</span></h2>
                    <p class="tt-subtitle">Get inspired by authentic travel experiences and expert tips from our global adventures.</p>
                </div>

                <form @submit.prevent="filterBlogs" class="tt-filter-bar mb-4" data-aos="fade-up">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" v-model="localFilters.search" placeholder="Search articles...">
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
                                <option value="created_at">Newest</option>
                                <option value="title">A-Z</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn-tt-primary w-100">Filter</button>
                        </div>
                    </div>
                </form>

                <div v-if="!blogs.data.length" class="tt-empty-state text-center py-5" data-aos="fade-up">
                    <div class="icon" style="font-size:3rem;color:var(--tt-primary);"><i class="fas fa-search"></i></div>
                    <h4 class="mt-3">No articles found</h4>
                    <p class="text-muted">Try adjusting your search or filter criteria.</p>
                    <Link href="/news" class="btn-tt-primary"><i class="fas fa-times me-1"></i> Clear Filters</Link>
                </div>

                <template v-else>
                    <div class="tt-blog-grid">
                        <article v-for="(blog, i) in blogs.data" :key="blog.id"
                                 class="tt-blog-card" data-aos="fade-up" :data-aos-delay="(i % 3) * 100">
                            <div class="tt-blog-card-img">
                                <img :src="blog.image_url" :alt="blog.title" loading="lazy">
                                <span class="badge-cat">{{ blog.category_name }}</span>
                                <span class="badge-read"><i class="fas fa-clock me-1"></i>{{ blog.read_time }}</span>
                            </div>
                            <div class="tt-blog-card-body">
                                <div class="tt-blog-card-meta">
                                    <span><i class="fas fa-calendar-alt"></i> {{ blog.published_at }}</span>
                                    <span><i class="fas fa-user"></i> Travelia Team</span>
                                </div>
                                <h3 class="tt-blog-card-title">
                                    <Link :href="`/news/${blog.id}`">{{ blog.title }}</Link>
                                </h3>
                                <p class="tt-blog-card-desc">{{ blog.description }}</p>
                                <Link :href="`/news/${blog.id}`" class="tt-blog-card-link">Read More <i class="fas fa-arrow-right"></i></Link>
                            </div>
                        </article>
                    </div>

                    <Pagination v-if="blogs.links?.length > 3" :links="blogs.links" class="mt-5 d-flex justify-content-center" />
                </template>
            </div>
        </section>

        <section class="tt-newsletter" data-aos="zoom-in">
            <div class="container">
                <div class="tt-newsletter-inner">
                    <div class="tt-newsletter-icon"><i class="fas fa-envelope-open-text"></i></div>
                    <h2>Stay Updated with Travel Tips</h2>
                    <p>Get the latest travel stories, tips, and exclusive offers delivered to your inbox.</p>
                    <form @submit.prevent="subscribeNewsletter" class="tt-newsletter-form">
                        <div class="tt-newsletter-input-group">
                            <input type="email" v-model="newsletterEmail" placeholder="Enter your email address" required>
                            <button type="submit" class="btn-tt-primary">Subscribe <i class="fas fa-paper-plane ms-1"></i></button>
                        </div>
                    </form>
                    <small class="text-muted mt-2 d-block">We respect your privacy. Unsubscribe at any time.</small>
                </div>
            </div>
        </section>

        <section v-if="categories.length" class="tt-section tt-section-light">
            <div class="container">
                <div class="tt-section-header text-center" data-aos="fade-up">
                    <div class="tt-pretitle">Browse by Topic</div>
                    <h2 class="tt-title">Popular <span class="accent">Categories</span></h2>
                </div>
                <div class="tt-cat-grid" data-aos="fade-up">
                    <Link v-for="cat in categories" :key="cat.id"
                          :href="`/news?category=${cat.id}`" class="tt-cat-card"
                          style="text-decoration:none;color:inherit;">
                        <div class="icon"><i :class="cat.icon"></i></div>
                        <h5>{{ cat.name }}</h5>
                        <span class="count">{{ cat.articles_count }} Articles</span>
                    </Link>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { reactive, ref, watch } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import Pagination from '@/Components/Pagination.vue'

const page = usePage()
const blogs = page.props.blogs
const categories = page.props.categories ?? []
const filters = page.props.filters ?? {}

const localFilters = reactive({ ...filters })
const newsletterEmail = ref('')

function filterBlogs() {
    router.get('/news', { ...localFilters }, { preserveState: true, preserveScroll: true })
}

function subscribeNewsletter() {
    router.post('/newsletter/subscribe', { email: newsletterEmail.value }, {
        preserveScroll: true,
        onSuccess: () => { newsletterEmail.value = '' },
    })
}

watch(() => page.props.filters, (f) => {
    Object.assign(localFilters, f)
}, { immediate: true })
</script>
