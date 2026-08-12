/**
 * Aggregation & Sorting — "Best" Algorithm
 *
 * The "Best" sort balances price against travel duration (and optionally stops)
 * to produce a single normalised score. Lower score = better deal.
 *
 * Formula:
 *   bestScore = w_price * price_norm + w_duration * duration_norm + w_stops * stops_norm
 *
 * where each component is min-max normalised across the result set (0–1),
 * and the weights are tuned to give the user a balanced recommendation.
 *
 * Default weights (configurable):
 *   - Price:     50 %
 *   - Duration:  35 %
 *   - Stops:     15 %
 *
 * This surfaces the cheapest short flights while still ranking ultra-long
 * hauls fairly when price is significantly lower.
 */

import { NormalisedFlight } from "@/types";

export interface SortWeights {
  price: number;
  duration: number;
  stops: number;
}

const DEFAULT_WEIGHTS: SortWeights = { price: 0.50, duration: 0.35, stops: 0.15 };

/**
 * Score and sort an array of normalised flights using the "Best" algorithm.
 * The input array is mutated in place (sorted ascending by bestScore).
 *
 * @param flights   array of NormalisedFlight (without bestScore set)
 * @param weights   optional custom weights (must sum to 1.0)
 * @returns         same array, sorted, with bestScore populated
 */
export function sortByBest(
  flights: NormalisedFlight[],
  weights: SortWeights = DEFAULT_WEIGHTS
): NormalisedFlight[] {
  if (flights.length === 0) return flights;

  // 1. Extract raw values for min-max normalisation
  const prices = flights.map((f) => f.price);
  const durations = flights.map((f) => f.totalDurationMinutes);
  const stops = flights.map((f) => f.stops);

  const priceMin = Math.min(...prices);
  const priceMax = Math.max(...prices);
  const durMin = Math.min(...durations);
  const durMax = Math.max(...durations);
  const stopsMin = Math.min(...stops);
  const stopsMax = Math.max(...stops);

  const range = (min: number, max: number) => (max === min ? 1 : max - min);

  const priceRange = range(priceMin, priceMax);
  const durRange = range(durMin, durMax);
  const stopsRange = range(stopsMin, stopsMax);

  // 2. Compute weighted score for each flight
  for (const flight of flights) {
    const priceNorm = (flight.price - priceMin) / priceRange;
    const durNorm = (flight.totalDurationMinutes - durMin) / durRange;
    const stopsNorm = (flight.stops - stopsMin) / stopsRange;

    flight.bestScore =
      weights.price * priceNorm +
      weights.duration * durNorm +
      weights.stops * stopsNorm;
  }

  // 3. Sort ascending by bestScore
  flights.sort((a, b) => a.bestScore - b.bestScore);

  return flights;
}

/**
 * Sort by price only (cheapest first).
 */
export function sortByPrice(flights: NormalisedFlight[]): NormalisedFlight[] {
  return flights.sort((a, b) => a.price - b.price);
}

/**
 * Sort by total duration only (fastest first).
 */
export function sortByDuration(flights: NormalisedFlight[]): NormalisedFlight[] {
  return flights.sort((a, b) => a.totalDurationMinutes - b.totalDurationMinutes);
}

/**
 * Collection of available sort strategies.
 */
export const SortStrategies = {
  best: sortByBest,
  price: sortByPrice,
  duration: sortByDuration,
} as const;

export type SortStrategy = keyof typeof SortStrategies;
