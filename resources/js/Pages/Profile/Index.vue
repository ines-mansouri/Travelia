<template>
    <AppLayout>
        <section class="tt-page-hero tt-page-hero-sm">
            <div class="tt-page-hero-bg" :style="{ backgroundImage: `url('/images/place-1.jpg')` }"></div>
            <div class="container" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><Link href="/"><i class="fas fa-home me-1"></i>Home</Link></li>
                        <li class="breadcrumb-item"><Link href="/dashboard">Dashboard</Link></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </nav>
                <h1 class="tt-page-title">My <span class="accent">Profile</span></h1>
                <p class="tt-page-subtitle">Manage your personal information and avatar</p>
            </div>
        </section>

        <section class="tt-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">

                        <div class="tt-sidebar-card text-center" data-aos="fade-up">
                            <div class="tt-avatar-wrapper">
                                <img :src="user.avatar_url" :alt="user.name"
                                     id="profileAvatar" class="tt-avatar-image">
                                <label for="avatarUpload" class="tt-avatar-overlay" title="Change photo">
                                    <i class="fas fa-camera"></i>
                                    <span>Change Photo</span>
                                </label>
                                <input type="file" id="avatarUpload" accept="image/jpeg,image/png,image/jpg"
                                       style="display:none;" @change="uploadAvatar">
                            </div>
                            <h4 class="mt-3 mb-0">{{ user.name }}</h4>
                            <p class="text-muted">{{ user.email }}</p>
                        </div>

                        <div class="tt-sidebar-card mt-4" data-aos="fade-up">
                            <h4 class="mb-1"><i class="fas fa-user me-2"></i> Account Details</h4>
                            <p class="text-muted mb-4">Update your name and email address</p>

                            <form @submit.prevent="updateProfile" class="tt-form">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="tt-form-group">
                                            <label class="tt-label">Full Name</label>
                                            <input type="text" v-model="form.name" class="tt-input" required>
                                            <div v-if="errors.name" class="text-danger small mt-1">{{ errors.name }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="tt-form-group">
                                            <label class="tt-label">Email Address</label>
                                            <input type="email" v-model="form.email" class="tt-input" required>
                                            <div v-if="errors.email" class="text-danger small mt-1">{{ errors.email }}</div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn-tt-primary mt-3" :disabled="saving">
                                    <span v-if="saving" class="spinner-border spinner-border-sm me-1"></span>
                                    <i v-else class="fas fa-save me-1"></i>
                                    {{ saving ? 'Saving...' : 'Save Changes' }}
                                </button>
                            </form>
                        </div>

                        <div class="tt-sidebar-card mt-4" data-aos="fade-up">
                            <h4 class="mb-1"><i class="fas fa-lock me-2"></i> Change Password</h4>
                            <p class="text-muted mb-4">Update your password</p>

                            <form @submit.prevent="updatePassword" class="tt-form">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="tt-form-group">
                                            <label class="tt-label">Current Password</label>
                                            <input type="password" v-model="passwordForm.current_password" class="tt-input" required>
                                            <div v-if="errors.current_password" class="text-danger small mt-1">{{ errors.current_password }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="tt-form-group">
                                            <label class="tt-label">New Password</label>
                                            <input type="password" v-model="passwordForm.password" class="tt-input" required>
                                            <div v-if="errors.password" class="text-danger small mt-1">{{ errors.password }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="tt-form-group">
                                            <label class="tt-label">Confirm New Password</label>
                                            <input type="password" v-model="passwordForm.password_confirmation" class="tt-input" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn-tt-accent mt-3" :disabled="savingPassword">
                                    <span v-if="savingPassword" class="spinner-border spinner-border-sm me-1"></span>
                                    <i v-else class="fas fa-key me-1"></i>
                                    {{ savingPassword ? 'Updating...' : 'Change Password' }}
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'

const page = usePage()
const user = computed(() => page.props.user)

const form = reactive({
    name: user.value.name,
    email: user.value.email,
})
const passwordForm = reactive({
    current_password: '',
    password: '',
    password_confirmation: '',
})
const saving = ref(false)
const savingPassword = ref(false)
const errors = ref({})

function updateProfile() {
    saving.value = true
    errors.value = {}

    router.put('/profile', { ...form }, {
        preserveScroll: true,
        onSuccess: () => {
            saving.value = false
            form.name = page.props.user.name
            form.email = page.props.user.email
        },
        onError: (errs) => {
            errors.value = errs
            saving.value = false
        },
        onFinish: () => { saving.value = false },
    })
}

function updatePassword() {
    savingPassword.value = true
    errors.value = {}

    router.put('/profile/password', { ...passwordForm }, {
        preserveScroll: true,
        onSuccess: () => {
            passwordForm.current_password = ''
            passwordForm.password = ''
            passwordForm.password_confirmation = ''
            savingPassword.value = false
        },
        onError: (errs) => {
            errors.value = errs
            savingPassword.value = false
        },
        onFinish: () => { savingPassword.value = false },
    })
}

function uploadAvatar(event) {
    const file = event.target.files[0]
    if (!file) return

    router.post('/profile/avatar', {
        avatar: file,
    }, {
        preserveScroll: true,
        onSuccess: () => {},
        onError: (errs) => {
            errors.value = errs
        },
    })

    event.target.value = ''
}
</script>

<style scoped>
.tt-avatar-wrapper {
    position: relative;
    width: 140px;
    height: 140px;
    margin: 0 auto;
    border-radius: 50%;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.12);
}
.tt-avatar-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.tt-avatar-overlay {
    position: absolute;
    inset: 0;
    background: rgba(44, 81, 76, 0.75);
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    opacity: 0;
    transition: opacity 0.25s ease;
    cursor: pointer;
    font-size: 0.75rem;
    font-weight: 600;
}
.tt-avatar-overlay i { font-size: 1.2rem; }
.tt-avatar-wrapper:hover .tt-avatar-overlay { opacity: 1; }
</style>
