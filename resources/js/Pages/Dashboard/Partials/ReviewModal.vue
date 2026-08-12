<template>
    <Teleport to="body">
        <div class="modal fade" ref="modalEl" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:var(--tt-radius-lg);border:none;box-shadow:var(--tt-shadow-lg);">
                    <div class="modal-header" style="border-bottom:1px solid var(--tt-border);padding:1.25rem 1.5rem;">
                        <h5 class="modal-title" style="font-family:var(--tt-font-display);">
                            <i class="fas fa-star text-warning me-2"></i>Review Your Journey
                        </h5>
                        <button type="button" class="btn-close" @click="hide"></button>
                    </div>

                    <div class="modal-body" style="padding:1.5rem;">
                        <p class="text-muted mb-3">Share your experience for "{{ bookingTitle }}"</p>

                        <div class="mb-4 text-center">
                            <label class="form-label fw-semibold mb-2" style="font-size:0.9rem;">Your Rating</label>
                            <div class="tt-star-rating" id="starRating">
                                <i v-for="i in 5" :key="i"
                                   class="tt-star"
                                   :class="[i <= selectedRating ? 'fas' : 'far', { hover: hoverRating >= i }]"
                                   :style="{ color: i <= (hoverRating || selectedRating) ? '#f59e0b' : '#d1d5db' }"
                                   @mouseenter="hoverRating = i"
                                   @mouseleave="hoverRating = 0"
                                   @click="selectedRating = i">
                                </i>
                            </div>
                            <div class="mt-2">
                                <span class="text-muted small">{{ ratingLabel }}</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="font-size:0.9rem;">
                                Your Review <span class="text-muted fw-normal">(optional)</span>
                            </label>
                            <textarea class="form-control" v-model="comment" rows="4"
                                      placeholder="Tell us about your experience..." maxlength="2000"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer" style="border-top:1px solid var(--tt-border);padding:1rem 1.5rem;">
                        <button type="button" class="btn btn-outline-secondary" @click="hide">Cancel</button>
                        <button type="button" class="btn btn-primary" :disabled="submitting || selectedRating < 1" @click="submit">
                            <span v-if="submitting" class="spinner-border spinner-border-sm me-1"></span>
                            <i v-else class="fas fa-paper-plane me-1"></i>
                            {{ submitting ? 'Submitting...' : 'Submit Review' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import { Modal } from 'bootstrap'

const labels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent']

const modalEl = ref(null)
let bsModal = null

const bookingType = ref('')
const bookingId = ref(null)
const bookingTitle = ref('')
const selectedRating = ref(0)
const hoverRating = ref(0)
const comment = ref('')
const submitting = ref(false)

const ratingLabel = computed(() => {
    return selectedRating.value > 0
        ? `${labels[selectedRating.value]} (${selectedRating.value}/5)`
        : 'Select your rating'
})

function open(type, id, title) {
    bookingType.value = type
    bookingId.value = id
    bookingTitle.value = title
    selectedRating.value = 0
    hoverRating.value = 0
    comment.value = ''
    if (!bsModal) bsModal = new Modal(modalEl.value)
    bsModal.show()
}

function hide() {
    bsModal?.hide()
}

function submit() {
    if (selectedRating.value < 1) return
    submitting.value = true

    router.post('/booking-reviews', {
        booking_type: bookingType.value,
        booking_id: bookingId.value,
        rating: selectedRating.value,
        comment: comment.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            hide()
            submitting.value = false
        },
        onError: () => {
            submitting.value = false
        },
    })
}

defineExpose({ open })
</script>

<style scoped>
.tt-star-rating {
    display: inline-flex;
    gap: 6px;
    direction: ltr;
}
.tt-star {
    font-size: 2rem;
    cursor: pointer;
    transition: transform 0.15s ease;
}
.tt-star:hover {
    transform: scale(1.2);
}
</style>
