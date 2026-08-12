/**
 * TraveliaMap — Global Leaflet interactive map module.
 *
 * Features:
 *   - Destination hotspot markers fetched from the Travelia API
 *   - Flight route visualisation: curved geodesic lines + leg markers
 *     for one-way, return, and multi-city itineraries
 *
 * Usage:
 *   const map = new TraveliaMap('tt-live-map');
 *   await map.init();                          // destination markers
 *   map.renderFlightRoutes(legs, 'one_way');   // flight route overlay
 *
 * Dependencies:
 *   - Leaflet (loaded via CDN in the travel-map component)
 *   - ttFetch() from tt-interceptor.js
 *   - window.ttToast from tt-interceptor.js
 */

class TraveliaMap {

    // ── Defaults ──────────────────────────────────────────────────────────
    static DEFAULTS = {
        center: { lat: 34.0, lng: 9.0 },
        zoom: 5,
        minZoom: 2,
        maxZoom: 18,
        tileUrl: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        tileAttribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    };

    // ── Colour palette for multi-leg routes ───────────────────────────────
    static LEG_COLORS = ['#0d6efd', '#198754', '#dc3545'];

    // ── Constructor ────────────────────────────────────────────────────────
    constructor(containerId, options = {}) {
        this.containerId = containerId;
        this.container   = document.getElementById(containerId);
        if (!this.container) {
            console.warn(`TraveliaMap: container #${containerId} not found.`);
            return;
        }

        this.options = { ...TraveliaMap.DEFAULTS, ...options };
        this.map     = null;
        this.markers = [];
        this.markerGroup = null;
        this.flightRouteGroup = null;

        this.apiEndpoint = this.container.dataset.api || '/api/v1/destinations/coordinates';
        this.centerLat   = parseFloat(this.container.dataset.centerLat) || this.options.center.lat;
        this.centerLng   = parseFloat(this.container.dataset.centerLng) || this.options.center.lng;
        this.defaultZoom = parseInt(this.container.dataset.zoom, 10) || this.options.zoom;

        // Locale & translations from data attributes
        this.locale = this.container.dataset.locale || 'en';
        this.txt = {
            viewDetails:   this.container.dataset.textViewDetails || 'View Details',
            from:          this.container.dataset.textFrom || 'From',
            noResults:     this.container.dataset.textNoResults || 'No destinations found for ":query"',
            origin:        this.container.dataset.textOrigin || 'Origin',
            destination:   this.container.dataset.textDestination || 'Destination',
            outbound:      this.container.dataset.textOutbound || 'Outbound',
            return:        this.container.dataset.textReturn || 'Return',
            leg:           this.container.dataset.textLeg || 'Leg :number',
        };
    }

    // ── Initialise: create map, load tiles, fetch destinations ──────────
    async init() {
        if (!this.container) return;

        const loader = this.container.querySelector('.tt-map-loading');
        if (loader) loader.classList.add('loaded');

        this.map = L.map(this.containerId, {
            center: [this.centerLat, this.centerLng],
            zoom: this.defaultZoom,
            minZoom: this.options.minZoom,
            maxZoom: this.options.maxZoom,
            zoomControl: true,
            scrollWheelZoom: true,
        });

        L.tileLayer(this.options.tileUrl, {
            attribution: this.options.tileAttribution,
            maxZoom: this.options.maxZoom,
        }).addTo(this.map);

        this.markerGroup = L.layerGroup().addTo(this.map);
        this.flightRouteGroup = L.layerGroup().addTo(this.map);

        setTimeout(() => this.map.invalidateSize(), 300);

        await this.fetchAndRender();
    }

    // ── Fetch destination markers from API ──────────────────────────────
    async fetchAndRender() {
        const result = await ttFetch(this.apiEndpoint, {}, { showToast: false });
        if (result.success && result.data?.data?.length) {
            this.renderMarkers(result.data.data);
        }
    }

    // ── Render destination markers ──────────────────────────────────────
    renderMarkers(locationsArray) {
        if (!this.markerGroup || !Array.isArray(locationsArray)) return;

        this.markerGroup.clearLayers();
        this.markers = [];

        locationsArray.forEach(loc => {
            if (!loc.latitude || !loc.longitude) return;

            const lat = parseFloat(loc.latitude);
            const lng = parseFloat(loc.longitude);
            if (isNaN(lat) || isNaN(lng)) return;

            const popupHtml = this.buildPopupContent(loc);
            const icon = L.divIcon({
                className: 'tt-marker-wrapper',
                html: `<div class="tt-marker-icon"><i class="fas fa-map-marker-alt"></i></div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -38],
            });

            const marker = L.marker([lat, lng], { icon })
                .bindPopup(popupHtml, { maxWidth: 280, minWidth: 220, className: '', closeButton: true });

            this.markerGroup.addLayer(marker);
            this.markers.push(marker);
        });

        this.fitToMarkers();
        setTimeout(() => this.map.invalidateSize(), 100);
    }

    // ── Render flight routes (curved lines + airport markers) ───────────
    //
    // legs: array of { leg: number, origin: {lat,lng,name,city}, destination: {lat,lng,name,city} }
    // flightType: 'one_way' | 'return' | 'multi_city'
    //
    renderFlightRoutes(legs, flightType) {
        if (!this.flightRouteGroup || !Array.isArray(legs) || !legs.length) return;

        this.clearFlightRoutes();

        const allPoints = [];

        legs.forEach((leg, i) => {
            const color = TraveliaMap.LEG_COLORS[i % TraveliaMap.LEG_COLORS.length];
            const label = flightType === 'multi_city'
                ? this.txt.leg.replace(':number', i + 1)
                : (i === 0 ? this.txt.outbound : this.txt.return);

            const org = leg.origin;
            const dst = leg.destination;
            if (!org || !dst) return;

            const orgLatLng = [org.lat, org.lng];
            const dstLatLng = [dst.lat, dst.lng];

            // Draw curved arc between origin and destination
            const arcPoints = this.generateArcPoints(orgLatLng, dstLatLng, 60);
            const polyline = L.polyline(arcPoints, {
                color: color,
                weight: 3,
                opacity: 0.8,
                dashArray: i === 0 ? null : '8, 12',
                lineCap: 'round',
                lineJoin: 'round',
            }).addTo(this.flightRouteGroup);

            // Add label at midpoint
            const midIdx = Math.floor(arcPoints.length / 2);
            const midPoint = arcPoints[midIdx];
            L.marker(midPoint, {
                icon: L.divIcon({
                    className: 'tt-flight-label',
                    html: `<div style="background:${color};color:#fff;padding:2px 10px;border-radius:12px;font-size:0.75rem;font-weight:600;white-space:nowrap;border:2px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.15);">${label}</div>`,
                    iconSize: [0, 0],
                    iconAnchor: [0, 0],
                }),
                interactive: false,
            }).addTo(this.flightRouteGroup);

            // Origin marker
            const orgIcon = L.divIcon({
                className: 'tt-airport-marker',
                html: `<div style="background:${color};width:14px;height:14px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.25);"></div>`,
                iconSize: [14, 14],
                iconAnchor: [7, 7],
            });

            const orgPopup = `
                <div style="padding:4px 0;min-width:160px;">
                    <div style="font-weight:700;font-size:0.95rem;color:#1a1a2e;">${org.city || org.name || ''}</div>
                    <div style="font-size:0.8rem;color:#6c757d;">${org.name || ''}</div>
                    <div style="font-size:0.8rem;color:${color};font-weight:600;margin-top:4px;">
                        <i class="fas fa-plane-departure"></i> ${label} ${this.txt.origin}
                    </div>
                </div>
            `;

            L.marker(orgLatLng, { icon: orgIcon })
                .bindPopup(orgPopup, { maxWidth: 260, className: '', closeButton: true })
                .addTo(this.flightRouteGroup);

            // Destination marker
            const dstIcon = L.divIcon({
                className: 'tt-airport-marker',
                html: `<div style="background:${color};width:14px;height:14px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.25);"></div>`,
                iconSize: [14, 14],
                iconAnchor: [7, 7],
            });

            const dstPopup = `
                <div style="padding:4px 0;min-width:160px;">
                    <div style="font-weight:700;font-size:0.95rem;color:#1a1a2e;">${dst.city || dst.name || ''}</div>
                    <div style="font-size:0.8rem;color:#6c757d;">${dst.name || ''}</div>
                    <div style="font-size:0.8rem;color:${color};font-weight:600;margin-top:4px;">
                        <i class="fas fa-plane-arrival"></i> ${label} ${this.txt.destination}
                    </div>
                </div>
            `;

            L.marker(dstLatLng, { icon: dstIcon })
                .bindPopup(dstPopup, { maxWidth: 260, className: '', closeButton: true })
                .addTo(this.flightRouteGroup);

            allPoints.push(orgLatLng, dstLatLng);
        });

        // Fit bounds to include all route points
        if (allPoints.length) {
            const bounds = L.latLngBounds(allPoints);
            this.map.fitBounds(bounds.pad(0.2));
            setTimeout(() => this.map.invalidateSize(), 100);
        }
    }

    // ── Generate arc points for a curved flight path ────────────────────
    // Uses a sinusoidal deviation proportional to the great-circle distance
    generateArcPoints(latlng1, latlng2, steps = 60) {
        const p1 = L.latLng(latlng1);
        const p2 = L.latLng(latlng2);
        const points = [];

        const dist = p1.distanceTo(p2);
        const deviation = Math.min(dist * 0.06, 300000); // max ~300km arc height

        for (let i = 0; i <= steps; i++) {
            const t = i / steps;
            const lat = p1.lat + (p2.lat - p1.lat) * t;
            const lng = p1.lng + (p2.lng - p1.lng) * t;

            // Add a sinusoidal arc (peak at midpoint)
            const arc = Math.sin(t * Math.PI) * deviation / 111320;
            points.push([lat + arc, lng]);
        }

        return points;
    }

    // ── Clear all flight route layers ───────────────────────────────────
    clearFlightRoutes() {
        if (this.flightRouteGroup) {
            this.flightRouteGroup.clearLayers();
        }
    }

    // ── Build destination popup content ─────────────────────────────────
    buildPopupContent(loc) {
        const title       = loc.title || 'Destination';
        const imgUrl      = loc.image_url || '/images/destination-1.jpg';
        const price       = loc.converted_pricing || '';
        const duration    = loc.duration || '';
        const rating      = loc.average_rating
            ? '<span style="color:#f59e0b;">' + '★'.repeat(Math.round(loc.average_rating)) + '</span>'
            : '';
        const detailUrl   = `/destinations/${loc.id}`;

        return `
            <div class="tt-map-popup">
                <img src="${imgUrl}" alt="${title}" loading="lazy">
                <div class="tt-map-popup-body">
                    <div class="tt-map-popup-title">${this.escapeHtml(title)}</div>
                    <div class="tt-map-popup-meta">
                        ${duration ? '<i class="fas fa-clock me-1"></i>' + duration : ''}
                        ${rating ? ' &middot; ' + rating : ''}
                    </div>
                    <div class="tt-map-popup-price">${this.txt.from} ${price}</div>
                    <a href="${detailUrl}" class="tt-map-popup-btn">
                        <i class="fas fa-eye me-1"></i>${this.txt.viewDetails}
                    </a>
                </div>
            </div>
        `;
    }

    // ── Utility: basic HTML escaping ───────────────────────────────────
    escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }

    // ── Fit the map to show all current markers ────────────────────────
    fitToMarkers(padding = 0.15) {
        if (!this.markers.length) return;
        if (this.markers.length === 1) {
            this.map.setView(this.markers[0].getLatLng(), 8);
            return;
        }
        const group = L.featureGroup(this.markers);
        this.map.fitBounds(group.getBounds().pad(padding));
    }

    // ── Fly to a specific location ─────────────────────────────────────
    flyTo(lat, lng, zoom = 10) {
        if (!this.map) return;
        this.map.flyTo([lat, lng], zoom, { duration: 1.2 });
    }

    // ── Cleanup ────────────────────────────────────────────────────────
    destroy() {
        if (this.map) {
            this.map.remove();
            this.map = null;
        }
        this.markers = [];
        this.markerGroup = null;
        this.flightRouteGroup = null;
    }
}

// ── Auto-initialise on DOM ready ─────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('tt-live-map');
    if (!el) return;

    window.traveliaMap = new TraveliaMap('tt-live-map');

    const searchInput = document.getElementById('tt-map-search');
    const searchBtn   = document.getElementById('tt-map-search-btn');
    let searchTimer;

    const txtNoResults = el.dataset.textNoResults || 'No destinations found for ":query"';

    function doSearch() {
        const q = (searchInput?.value || '').trim();
        if (!q) {
            window.traveliaMap.clearFlightRoutes();
            window.traveliaMap.fetchAndRender();
            return;
        }

        ttFetch(`/api/v1/destinations/coordinates/search?q=${encodeURIComponent(q)}`, {}, { showToast: false })
            .then(result => {
                if (result.success && result.data?.data?.length) {
                    window.traveliaMap.clearFlightRoutes();
                    window.traveliaMap.renderMarkers(result.data.data);
                } else {
                    const msg = txtNoResults.replace(':query', q);
                    ttToast.show(msg, 'info');
                }
            });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(doSearch, 400);
        });
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') { e.preventDefault(); doSearch(); }
        });
    }
    if (searchBtn) {
        searchBtn.addEventListener('click', doSearch);
    }

    setTimeout(() => {
        window.traveliaMap.init();
    }, 200);
});