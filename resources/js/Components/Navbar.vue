<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <Link href="/" class="navbar-brand fw-bold">
                <i class="fas fa-compass text-primary me-2"></i>Travelia
            </Link>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <Link href="/destinations" class="nav-link">Destinations</Link>
                    </li>
                    <li class="nav-item">
                        <Link href="/flights" class="nav-link">Flights</Link>
                    </li>
                    <li class="nav-item">
                        <Link href="/hotels" class="nav-link">Hotels</Link>
                    </li>
                </ul>

                <ul class="navbar-nav align-items-center gap-2">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            {{ currencySymbol }} {{ currency }}
                        </a>
                        <ul class="dropdown-menu">
                            <li v-for="c in availableCurrencies" :key="c">
                                <Link :href="`/currency/${c}`" class="dropdown-item"
                                      :class="{ active: currency === c }">
                                    {{ currencySymbols[c] || c }} {{ c }}
                                </Link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            {{ locale === 'ar' ? 'العربية' : 'English' }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><Link href="/locale/en" class="dropdown-item" :class="{ active: locale === 'en' }">English</Link></li>
                            <li><Link href="/locale/ar" class="dropdown-item" :class="{ active: locale === 'ar' }">العربية</Link></li>
                        </ul>
                    </li>

                    <template v-if="auth.user">
                        <li class="nav-item">
                            <Link href="/dashboard" class="nav-link">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </Link>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fa-lg"></i>
                                <span class="d-none d-md-inline">{{ auth.user.name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><Link href="/profile" class="dropdown-item"><i class="fas fa-user me-2"></i>Profile</Link></li>
                                <li><Link href="/wishlist" class="dropdown-item"><i class="fas fa-heart me-2"></i>Wishlist</Link></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <Link href="/logout" method="post" as="button" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </Link>
                                </li>
                            </ul>
                        </li>
                    </template>
                    <template v-else>
                        <li class="nav-item">
                            <Link href="/login" class="nav-link">Login</Link>
                        </li>
                        <li class="nav-item">
                            <Link href="/register" class="btn btn-primary btn-sm">Sign Up</Link>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
import { Link } from '@inertiajs/vue3'
export default { components: { Link } }
</script>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const auth = computed(() => page.props.auth)
const locale = computed(() => page.props.locale)
const currency = computed(() => page.props.currency)
const currencySymbol = computed(() => page.props.currencySymbol)
const availableCurrencies = ['TND', 'EUR', 'USD', 'GBP']
const currencySymbols = { TND: 'د.ت', EUR: '€', USD: '$', GBP: '£' }
</script>
