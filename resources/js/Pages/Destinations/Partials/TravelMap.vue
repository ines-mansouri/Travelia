<template>
    <section class="tt-section-sm">
        <div class="container">
            <div class="tt-map-container"
                 style="border-radius:var(--tt-radius-lg);overflow:hidden;box-shadow:var(--tt-shadow);">
                <div ref="mapEl" style="width:100%;height:400px;background:#f0f0f0;"></div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps({
    markers: { type: Array, default: () => [] },
})

const mapEl = ref(null)
let map = null
let markers = []

function initMap() {
    if (!mapEl.value) return

    map = L.map(mapEl.value, { zoomControl: true }).setView([20, 0], 2)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 18,
    }).addTo(map)

    updateMarkers()
}

function updateMarkers() {
    if (!map) return

    markers.forEach(m => map.removeLayer(m))
    markers = []

    if (!props.markers.length) return

    const bounds = []
    props.markers.forEach(m => {
        const marker = L.marker([m.lat, m.lng])
            .addTo(map)
            .bindPopup(`<b>${m.title}</b><br>From ${m.price}`)
        markers.push(marker)
        bounds.push([m.lat, m.lng])
    })

    if (bounds.length > 1) {
        map.fitBounds(bounds, { padding: [30, 30], maxZoom: 13 })
    } else if (bounds.length === 1) {
        map.setView(bounds[0], 13)
    }
}

watch(() => props.markers, updateMarkers, { deep: true })

onMounted(() => {
    setTimeout(initMap, 100)
})

onUnmounted(() => {
    if (map) {
        map.remove()
        map = null
    }
})
</script>
