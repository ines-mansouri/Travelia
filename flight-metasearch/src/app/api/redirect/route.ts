/**
 * Affiliate Redirect API Route
 *
 * GET /api/redirect?offerId=123&origin=JFK&destination=LAX&deptDate=2025-09-15&returnDate=2025-09-22&partner=trip
 *
 * Flow:
 *   1. Validate required params
 *   2. Fire-and-forget POST to Laravel backend to log the click
 *   3. 302 redirect to the affiliate partner URL
 *
 * The Laravel tracking call is non-blocking: the redirect fires immediately
 * and the tracking request completes in the background.
 */

import { NextRequest, NextResponse } from "next/server";
import { generateAffiliateLinks } from "@/lib/affiliate";

const LARAVEL_TRACKING_URL = process.env.LARAVEL_TRACKING_URL ?? "http://127.0.0.1:8000/api/v1/tracking/click";

type Partner = "trip" | "skyscanner" | "kiwi";

const VALID_PARTNERS = new Set<Partner>(["trip", "skyscanner", "kiwi"]);

export async function GET(req: NextRequest) {
  const { searchParams } = new URL(req.url);
  const offerId = searchParams.get("offerId");
  const origin = searchParams.get("origin");
  const destination = searchParams.get("destination");
  const deptDate = searchParams.get("deptDate");
  const returnDate = searchParams.get("returnDate");
  const partnerRaw = searchParams.get("partner")?.toLowerCase();
  const partner = VALID_PARTNERS.has(partnerRaw as Partner) ? (partnerRaw as Partner) : undefined;

  if (!origin || !destination || !deptDate) {
    return NextResponse.json(
      { error: "Missing required params: origin, destination, deptDate" },
      { status: 400 }
    );
  }

  if (origin.length !== 3 || destination.length !== 3 || !/^\d{4}-\d{2}-\d{2}$/.test(deptDate)) {
    return NextResponse.json(
      { error: "Invalid parameter format" },
      { status: 400 }
    );
  }

  // Generate affiliate links
  const links = generateAffiliateLinks({
    origin,
    destination,
    deptDate,
    returnDate: returnDate ?? undefined,
  });

  const chosenLink = partner
    ? links.find((l) => l.partner === partner)
    : links[0];

  const targetUrl = chosenLink?.url ?? "https://www.google.com/travel/flights";

  // ---- Fire-and-forget tracking to Laravel ----
  const ip = req.headers.get("x-forwarded-for")?.split(",")[0]?.trim()
             ?? req.headers.get("x-real-ip")
             ?? "unknown";

  const userAgent = req.headers.get("user-agent") ?? "";

  // Intentionally not awaited — we don't block the redirect on tracking
  trackClick({
    offerId: offerId ?? "",
    origin,
    destination,
    deptDate,
    returnDate: returnDate ?? null,
    partner: chosenLink?.partner ?? "direct",
    ip,
    userAgent,
  }).catch((err) => {
    console.error("[TRACKING] Failed to log click to Laravel:", err.message);
  });

  return NextResponse.redirect(targetUrl, 302);
}

// --------------- Tracking Helper ---------------

interface ClickEvent {
  offerId: string;
  origin: string;
  destination: string;
  deptDate: string;
  returnDate: string | null;
  partner: string;
  ip: string;
  userAgent: string;
}

async function trackClick(event: ClickEvent): Promise<void> {
  const payload = {
    offer_id: event.offerId,
    origin: event.origin,
    destination: event.destination,
    depart_date: event.deptDate,
    return_date: event.returnDate,
    partner: event.partner,
    ip_address: event.ip,
    user_agent: event.userAgent,
    timestamp: new Date().toISOString(),
  };

  await fetch(LARAVEL_TRACKING_URL, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(payload),
    // Short timeout — don't let tracking delay anything
    signal: AbortSignal.timeout(3000),
  });
}
