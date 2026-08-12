/**
 * Cache Abstraction Layer
 *
 * Provides a unified cache interface backed by an in-memory Map.
 * Swap the store for Redis (ioredis) or Next.js unstable_cache as needed.
 *
 * For single-instance deployments, the in-memory Map is sufficient.
 * For multi-instance (serverless), use Redis via the same interface below.
 *
 * Key format:  flight:search:{SHA256(serializedParams)}
 * TTL:         300s (5 min) — Amadeus data is stable at this granularity
 */

export interface CacheStore {
  get<T>(key: string): Promise<T | null>;
  set<T>(key: string, value: T, ttlSeconds: number): Promise<void>;
  del(key: string): Promise<void>;
}

// --------------- In-Memory Store (default) ---------------

interface CacheEntry {
  data: unknown;
  expiresAt: number;
}

class MemoryStore implements CacheStore {
  private store = new Map<string, CacheEntry>();

  async get<T>(key: string): Promise<T | null> {
    const entry = this.store.get(key);
    if (!entry) return null;
    if (Date.now() > entry.expiresAt) {
      this.store.delete(key);
      return null;
    }
    return entry.data as T;
  }

  async set<T>(key: string, value: T, ttlSeconds: number): Promise<void> {
    this.store.set(key, {
      data: value,
      expiresAt: Date.now() + ttlSeconds * 1000,
    });
  }

  async del(key: string): Promise<void> {
    this.store.delete(key);
  }
}

// --------------- Singleton ---------------

let store: CacheStore = new MemoryStore();

export function setCacheStore(customStore: CacheStore): void {
  store = customStore;
}

export function getCacheStore(): CacheStore {
  return store;
}

// --------------- Helpers ---------------

/**
 * Build a deterministic cache key from search parameters.
 * Order of keys is normalised so the same query always produces the same key.
 */
export function buildSearchCacheKey(params: Record<string, string | undefined>): string {
  const sorted = Object.keys(params)
    .filter((k) => params[k] !== undefined)
    .sort()
    .map((k) => `${k}=${params[k]}`)
    .join("&");

  // Simple hash function for cache key (avoid exposing raw params in key)
  let hash = 0;
  for (let i = 0; i < sorted.length; i++) {
    const chr = sorted.charCodeAt(i);
    hash = ((hash << 5) - hash) + chr;
    hash |= 0;
  }
  return `flight:search:${Math.abs(hash).toString(36)}`;
}

export const DEFAULT_TTL_SECONDS = 300; // 5 minutes
