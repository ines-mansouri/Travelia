import { FlightSearchParams } from "@/types";
import { z } from "zod";

const DATE_REGEX = /^\d{4}-\d{2}-\d{2}$/;

const paramsSchema = z.object({
  origin: z.string().length(3, "Origin must be a 3-letter IATA code"),
  destination: z.string().length(3, "Destination must be a 3-letter IATA code"),
  deptDate: z
    .string()
    .regex(DATE_REGEX, "Departure date must be YYYY-MM-DD")
    .refine((v) => !Number.isNaN(Date.parse(v)), "Departure date is not a real date"),
  returnDate: z
    .string()
    .regex(DATE_REGEX, "Return date must be YYYY-MM-DD")
    .refine((v) => !Number.isNaN(Date.parse(v)), "Return date is not a real date")
    .optional(),
  adults: z.coerce.number().int().min(1).max(9).default(1),
});

/**
 * Parse a Next.js catch-all slug array into typed FlightSearchParams.
 *
 * Accepts:  /flights/JFK-LAX/2025-09-15/2025-09-22
 *           /flights/JFK-LAX/2025-09-15
 *
 * Airport codes are strictly alphabetic (IATA standard).
 * Metro codes like PAR are NOT expanded here — expandMetroCode()
 * should be called separately before hitting the API.
 */
export function parseFlightSlug(slug: string[]): FlightSearchParams | null {
  if (!slug || slug.length < 2) return null;

  const route = slug[0];
  // IATA codes are exactly 3 uppercase letters (no digits)
  const match = route.match(/^([A-Za-z]{3})-([A-Za-z]{3})$/);
  if (!match) return null;

  const raw: Record<string, string | undefined> = {
    origin: match[1].toUpperCase(),
    destination: match[2].toUpperCase(),
    deptDate: slug[1],
    returnDate: slug[2],
  };

  const parsed = paramsSchema.safeParse(raw);
  return parsed.success ? parsed.data : null;
}

/**
 * Build a canonical URL for a flight search.
 */
export function buildFlightUrl(params: FlightSearchParams): string {
  const base = `/flights/${params.origin}-${params.destination}/${params.deptDate}`;
  const suffix = params.returnDate ? `/${params.returnDate}` : "";
  const query = params.adults && params.adults !== 1 ? `?adults=${params.adults}` : "";
  return `${base}${suffix}${query}`;
}

/**
 * Expand metro/city codes to concrete IATA airport codes.
 * Must be called explicitly in the search flow before passing to Amadeus.
 *
 * Example: "PAR" → ["CDG", "ORY", "BVA"]
 *          "JFK" → ["JFK"]  (not a metro code, returned as-is)
 */
export function expandMetroCode(iata: string): string[] {
  const metroMap: Record<string, string[]> = {
    PAR: ["CDG", "ORY", "BVA"],
    LON: ["LHR", "LGW", "STN", "LCY", "LTN", "SEN"],
    NYC: ["JFK", "LGA", "EWR"],
    TYO: ["NRT", "HND"],
    CHI: ["ORD", "MDW"],
    WAS: ["DCA", "IAD", "BWI"],
    MOW: ["SVO", "DME", "VKO"],
    SAO: ["GRU", "CGH", "VCP"],
    BER: ["BER"],
    MIL: ["MXP", "LIN", "BGY"],
    ROM: ["FCO", "CIA"],
    STO: ["ARN", "NYO"],
  };
  return metroMap[iata.toUpperCase()] ?? [iata];
}

/**
 * Validate that a date string represents a real calendar date in the future
 * (or up to 365 days ahead, per Amadeus booking window limits).
 */
export function isDateInRange(dateStr: string, maxDaysAhead: number = 365): boolean {
  const d = new Date(dateStr + "T00:00:00");
  if (Number.isNaN(d.getTime())) return false;
  const now = new Date();
  const diffDays = (d.getTime() - now.getTime()) / 86_400_000;
  return diffDays >= -1 && diffDays <= maxDaysAhead; // -1 allows today
}
