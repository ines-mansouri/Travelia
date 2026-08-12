/**
 * Hand-off / Affiliate Link Generator
 *
 * For an MVP, you redirect users to the airline's website or an OTA
 * (Online Travel Agency) partner page with search params pre-filled.
 *
 * In production you would integrate with an affiliate network like:
 *   - Skyscanner Associates
 *   - Travelpayouts (post affiliate revolution)
 *   - Booking.com Affiliate Partner
 *   - AdmitAd / Kayak Partner Network
 *
 * The links below use deep-link formats for common OTAs.
 * Replace PARTNER_ID with your actual affiliate ID.
 */

import { FlightSearchParams } from "@/types";

export type AffiliatePartner = "direct" | "trip" | "skyscanner" | "kiwi";

interface AffiliateLink {
  partner: AffiliatePartner;
  label: string;
  url: string;
}

/**
 * Build affiliate outbound links for a given flight search.
 *
 * @param params  search parameters (origin, destination, dates)
 * @returns       array of affiliate link objects
 */
export function generateAffiliateLinks(
  params: FlightSearchParams
): AffiliateLink[] {
  const { origin, destination, deptDate, returnDate, adults } = params;
  const partnerId = process.env.AFFILIATE_PARTNER_ID ?? "YOUR_PARTNER_ID";

  return [
    {
      partner: "trip",
      label: "Book on Trip.com",
      url: buildTripUrl(origin, destination, deptDate, returnDate, adults),
    },
    {
      partner: "skyscanner",
      label: "Compare on Skyscanner",
      url: buildSkyscannerUrl(origin, destination, deptDate, returnDate),
    },
    {
      partner: "kiwi",
      label: "Search on Kiwi",
      url: buildKiwiUrl(origin, destination, deptDate, returnDate, adults),
    },
  ];
}

/**
 * Example: Generate a direct airline booking URL (no affiliate tracking).
 * Useful as a fallback or for MVP testing.
 */
export function buildDirectAirlineUrl(
  airlineCode: string,
  flightNumber: string,
  deptDate: string
): string {
  // This is a placeholder — real airline deep links vary wildly
  return `https://www.${airlineCode.toLowerCase()}.com/book?flight=${flightNumber}&date=${deptDate}`;
}

// --------------- Partner URL builders ---------------

function buildTripUrl(
  origin: string,
  dest: string,
  dept: string,
  ret?: string,
  adults: number = 1
): string {
  const base = "https://www.trip.com/flights/";
  const tripType = ret ? "round" : "oneway";
  return `${base}${origin}-${dest}?triptype=${tripType}&depdate=${dept}&arrdate=${ret ?? ""}&adults=${adults}`;
}

function buildSkyscannerUrl(
  origin: string,
  dest: string,
  dept: string,
  ret?: string
): string {
  const base = "https://www.skyscanner.net/transport/flights/";
  return `${base}${origin}/${dest}/${dept}/${ret ?? ""}`;
}

function buildKiwiUrl(
  origin: string,
  dest: string,
  dept: string,
  ret?: string,
  adults: number = 1
): string {
  const base = "https://www.kiwi.com/deep?from=";
  const retParam = ret ? `&to=${dest}&return=${ret}` : "";
  const adultsParam = adults > 1 ? `&adults=${adults}` : "";
  return `${base}${origin}&to=${dest}&departure=${dept}${retParam}${adultsParam}&currency=EUR&flightsId=TICKET&affilid=${process.env.AFFILIATE_PARTNER_ID ?? ""}&lang=en&passengers=${adults}`;
}

/**
 * Generate a booking hand-off link for a specific flight offer.
 * This would point to an OTA checkout page in production.
 */
export function generateBookingLink(
  offerId: string,
  validatingCarrier: string,
  params: FlightSearchParams
): string {
  const baseAffiliates = generateAffiliateLinks(params);
  // Return the first one as default; UI would let user pick
  return baseAffiliates[0]?.url ?? "#";
}
