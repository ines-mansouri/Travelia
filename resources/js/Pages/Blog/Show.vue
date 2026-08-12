<template>
    <AppLayout>
        <section class="tt-page-hero tt-page-hero-sm">
            <div class="tt-page-hero-bg" :style="{ backgroundImage: `url(${blog.image_url})` }"></div>
            <div class="container" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><Link href="/"><i class="fas fa-home me-1"></i>Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/news">Blog</Link></li>
                        <li class="breadcrumb-item active">{{ blog.title }}</li>
                    </ol>
                </nav>
                <h1 class="tt-page-title">{{ blog.title }}</h1>
                <div class="tt-page-meta text-center mt-3">
                    <span class="me-3"><i class="fas fa-calendar-alt me-1"></i> {{ blog.published_at }}</span>
                    <span><i class="fas fa-user me-1"></i> Travelia Team</span>
                </div>
            </div>
        </section>

        <section class="tt-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <article class="tt-blog-article" data-aos="fade-up">
                            <img :src="blog.image_url" :alt="blog.title"
                                 class="img-fluid rounded-3 w-100 mb-4" loading="lazy">
                            <div class="tt-blog-content" v-html="renderedContent"></div>
                        </article>

                        <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                            <Link href="/news" class="btn-tt-outline">
                                <i class="fas fa-arrow-left me-1"></i> Back to Blog
                            </Link>
                            <div class="d-flex gap-2">
                                <Link :href="`/news?category=${blog.category_id}`" class="btn-tt-outline">
                                    {{ blog.category_name }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const page = usePage()
const blog = computed(() => page.props.blog)

const renderedContent = computed(() => {
    if (!blog.value.content) return ''
    return blog.value.content.split('\n').map(p => p.trim() ? `<p>${escapeHtml(p)}</p>` : '').join('')
})

function escapeHtml(text) {
    const div = document.createElement('div')
    div.textContent = text
    return div.innerHTML
}
</script>
