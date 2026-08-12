import { NextRequest, NextResponse } from "next/server";
import { searchFlights, normaliseOffer } from "@/lib/amadeus";
import { sortByBest, sortByPrice, sortByDuration } from "@/lib/sort";
import { getCacheStore, buildSearchCacheKey, DEFAULT_TTL_SECONDS } from "@/lib/cache";
import { z } from "zod";

const querySchema = z.object({
  origin: z.string().length(3),
  destination: z.string().length(3),
  deptDate: z.string().regex(/^\d{4}-\d{2}-\d{2}$/),
  returnDate: z.string().regex(/^\d{4}-\d{2}-\d{2}$/).optional(),
  adults: z.coerce.number().int().min(1).max(9).default(1),
  sort: z.enum(["best", "price", "duration"]).default("best"),
  max: z.coerce.number().int().min(1).max(250).default(50),
});

export async function GET(req: NextRequest) {
  const { searchParams } = new URL(req.url);
  const raw = Object.fromEntries(searchParams);

  const parsed = querySchema.safeParse(raw);
  if (!parsed.success) {
    return NextResponse.json(
      { error: "Invalid parameters", details: parsed.error.flatten() },
      { status: 400 }
    );
  }

  const { origin, destination, deptDate, returnDate, adults, sort, max } = parsed.data;

  // ---- Cache check ----
  const cacheKey = buildSearchCacheKey({
    origin, destination, deptDate, returnDate, adults: String(adults), max: String(max),
  });
  const cache = getCacheStore();

  const cached = await cache.get<{ offers: unknown[] }>(cacheKey);
  if (cached?.offers) {
    const normalised = cached.offers.map((o: any) => normaliseOffer(o, origin, destination));
    const sortFn = sort === "price" ? sortByPrice : sort === "duration" ? sortByDuration : sortByBest;
    const sorted = sortFn(normalised);

    return NextResponse.json({
      meta: { origin, destination, deptDate, returnDate: returnDate ?? null, adults, sort, total: sorted.length, cached: true },
      flights: sorted,
    });
  }

  try {
    const offers = await searchFlights({ origin, destination, deptDate, returnDate, adults, max });

    // ---- Write-through cache ----
    await cache.set(cacheKey, { offers }, DEFAULT_TTL_SECONDS);

    const normalised = offers.map((offer) => normaliseOffer(offer, origin, destination));
    const sortFn = sort === "price" ? sortByPrice : sort === "duration" ? sortByDuration : sortByBest;
    const sorted = sortFn(normalised);

    return NextResponse.json({
      meta: { origin, destination, deptDate, returnDate: returnDate ?? null, adults, sort, total: sorted.length, cached: false },
      flights: sorted,
    });
  } catch (error: any) {
    console.error("Flight search failed:", error);
    return NextResponse.json(
      { error: "Failed to search flights", message: error.message },
      { status: 502 }
    );
  }
}
