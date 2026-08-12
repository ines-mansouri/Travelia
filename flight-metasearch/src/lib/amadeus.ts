/**
 * Amadeus Self-Service API Client
 *
 * Flow:
 *   1. POST /v1/security/oauth2/token  →  access_token
 *   2. GET  /v2/shopping/flight-offers  →  search flights
 *
 * Includes:
 *   - OAuth token caching (90 % of expiry window)
 *   - Token refresh on 401 (stale token recovery)
 *   - Retry with exponential backoff for 429 / 5xx
 *   - Rate-limit gating (~1 req/s for test tier)
 */

import { FlightOffer } from "@/types";

const AMADEUS_BASE = "https://test.api.amadeus.com";
const TOKEN_URL = `${AMADEUS_BASE}/v1/security/oauth2/token`;
const FLIGHT_SEARCH_URL = `${AMADEUS_BASE}/v2/shopping/flight-offers`;

const MAX_RETRIES = 3;
const BASE_DELAY_MS = 1000;

// --------------- Rate-Limit Gate ---------------

let lastRequestTime = 0;

async function rateLimitGate(): Promise<void> {
  const now = Date.now();
  const elapsed = now - lastRequestTime;
  const minInterval = 1100; // slightly > 1 req/s to stay under Amadeus test tier limit
  if (elapsed < minInterval) {
    await new Promise((r) => setTimeout(r, minInterval - elapsed));
  }
  lastRequestTime = Date.now();
}

// --------------- Token Management ---------------

let cachedToken: { access_token: string; expires_at: number } | null = null;

async function getAccessToken(): Promise<string> {
  const now = Date.now();
  if (cachedToken && cachedToken.expires_at > now) {
    return cachedToken.access_token;
  }

  const apiKey = process.env.AMADEUS_API_KEY;
  const apiSecret = process.env.AMADEUS_API_SECRET;

  if (!apiKey || !apiSecret) {
    throw new Error("Missing Amadeus credentials. Set AMADEUS_API_KEY and AMADEUS_API_SECRET in .env");
  }

  const body = new URLSearchParams({
    grant_type: "client_credentials",
    client_id: apiKey,
    client_secret: apiSecret,
  });

  const res = await fetch(TOKEN_URL, {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: body.toString(),
  });

  if (!res.ok) {
    // Clear stale token so next call retries fresh
    cachedToken = null;
    const text = await res.text();
    throw new Error(`Amadeus token error ${res.status}: ${text}`);
  }

  const data = await res.json();
  cachedToken = {
    access_token: data.access_token,
    expires_at: now + (data.expires_in - 60) * 1000,
  };

  return data.access_token;
}

async function getValidToken(): Promise<string> {
  try {
    return await getAccessToken();
  } catch (err) {
    // Force re-auth on next call
    cachedToken = null;
    throw err;
  }
}

// --------------- Flight Search ---------------

export interface AmadeusSearchOptions {
  origin: string;
  destination: string;
  deptDate: string;
  returnDate?: string;
  adults?: number;
  currency?: string;
  max?: number;
  nonStop?: boolean;
}

/**
 * Search flight offers via the Amadeus API with retry and rate-limit gating.
 */
export async function searchFlights(opts: AmadeusSearchOptions): Promise<FlightOffer[]> {
  let lastError: Error | null = null;

  for (let attempt = 0; attempt < MAX_RETRIES; attempt++) {
    try {
      await rateLimitGate();
      const token = await getValidToken();

      const params = new URLSearchParams({
        originLocationCode: opts.origin,
        destinationLocationCode: opts.destination,
        departureDate: opts.deptDate,
        adults: String(opts.adults ?? 1),
        currencyCode: opts.currency ?? "EUR",
      });

      if (opts.returnDate) params.set("returnDate", opts.returnDate);
      if (opts.max) params.set("max", String(opts.max));
      if (opts.nonStop) params.set("nonStop", "true");

      const url = `${FLIGHT_SEARCH_URL}?${params.toString()}`;

      const res = await fetch(url, {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      });

      if (res.status === 401) {
        cachedToken = null; // force token refresh on next attempt
        lastError = new Error("Token expired, retrying...");
        continue;
      }

      if (res.status === 429) {
        const retryAfter = parseInt(res.headers.get("Retry-After") ?? "2", 10);
        await sleep(retryAfter * 1000);
        lastError = new Error(`Rate limited, retry after ${retryAfter}s`);
        continue;
      }

      if (!res.ok) {
        const text = await res.text();
        throw new Error(`Amadeus search error ${res.status}: ${text}`);
      }

      const data = await res.json();
      return (data.data ?? []) as FlightOffer[];

    } catch (err: any) {
      lastError = err;
      if (attempt < MAX_RETRIES - 1) {
        const delay = BASE_DELAY_MS * Math.pow(2, attempt);
        console.warn(`Amadeus retry ${attempt + 1}/${MAX_RETRIES} after ${delay}ms: ${err.message}`);
        await sleep(delay);
      }
    }
  }

  throw lastError ?? new Error("Amadeus search failed after retries");
}

// --------------- Normalisation ---------------

export function normaliseOffer(
  offer: FlightOffer,
  origin: string,
  destination: string
): import("@/types").NormalisedFlight {
  const itinerary = offer.itineraries[0];
  const totalMinutes = parseDuration(itinerary.duration);
  const segments = itinerary.segments;
  const stops = segments.length - 1;

  return {
    id: offer.id,
    price: parseFloat(offer.price.grandTotal ?? offer.price.total),
    totalDurationMinutes: totalMinutes,
    stops,
    airline: segments[0]?.carrierCode ?? "Unknown",
    departureTime: segments[0]?.departure.at ?? "",
    arrivalTime: segments[segments.length - 1]?.arrival.at ?? "",
    origin,
    destination,
    segments,
    bestScore: 0,
  };
}

/**
 * Parse ISO 8601 duration to total minutes.
 * Handles: PT2H30M, PT45M, P1DT5H30M
 */
export function parseDuration(duration: string): number {
  const match = duration.match(/P(?:(\d+)D)?T(?:(\d+)H)?(?:(\d+)M)?/);
  if (!match) return 0;
  const days = parseInt(match[1] ?? "0", 10);
  const hours = parseInt(match[2] ?? "0", 10);
  const minutes = parseInt(match[3] ?? "0", 10);
  return days * 1440 + hours * 60 + minutes;
}

// --------------- Helpers ---------------

function sleep(ms: number): Promise<void> {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
