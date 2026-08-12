/**
 * Airport Autocomplete API Route
 *
 * GET /api/airports?q=lon
 *
 * Returns matching airports for the search-as-you-type input field.
 * Data is loaded from the OurAirports static JSON dataset.
 */

import { NextRequest, NextResponse } from "next/server";
import { searchAirports } from "@/lib/airports";

export async function GET(req: NextRequest) {
  const { searchParams } = new URL(req.url);
  const query = searchParams.get("q")?.trim();

  if (!query || query.length < 1) {
    return NextResponse.json({ airports: [] });
  }

  try {
    const results = await searchAirports(query, 15);
    return NextResponse.json({
      airports: results.map((a) => ({
        iata: a.iata_code,
        name: a.name,
        city: a.municipality,
        country: a.iso_country,
        type: a.type,
      })),
    });
  } catch (error: any) {
    console.error("Airport search failed:", error);
    return NextResponse.json(
      { error: "Failed to search airports" },
      { status: 500 }
    );
  }
}
