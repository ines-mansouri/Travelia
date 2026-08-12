/**
 * Airport Static Data Loader
 *
 * Loads airport data from the OurAirports / OpenFlights dataset.
 *
 * Data source: https://davidmegginson.github.io/ourairports-data/airports.csv
 *
 * Dual-mode: runs on both server (API routes) and client (browser).
 * - Server: reads from public/data/airports.json via fs
 * - Client: fetches via HTTP /data/airports.json
 *
 * For MVP scale (~5k IATA airports) we load the full JSON into memory.
 */

import { Airport } from "@/types";

const isServer = typeof window === "undefined";

let airports: Airport[] | null = null;
let airportsIndex: Map<string, Airport> | null = null;

async function loadAirports(): Promise<Airport[]> {
  if (airports) return airports;

  let data: Airport[];

  if (isServer) {
    // Running on the server (API routes, SSR) — read from filesystem
    const fs = await import("fs");
    const path = await import("path");
    const filePath = path.default.join(process.cwd(), "public", "data", "airports.json");
    const raw = fs.readFileSync(filePath, "utf-8");
    data = JSON.parse(raw) as Airport[];
  } else {
    // Running in the browser — fetch via HTTP
    const res = await fetch("/data/airports.json");
    if (!res.ok) throw new Error("Failed to load airport data");
    data = (await res.json()) as Airport[];
  }

  airports = data;
  return data;
}

function buildIndex(data: Airport[]): Map<string, Airport> {
  const idx = new Map<string, Airport>();
  for (const apt of data) {
    if (apt.iata_code && apt.iata_code.length === 3) {
      idx.set(apt.iata_code.toUpperCase(), apt);
    }
  }
  return idx;
}

export async function lookupAirport(iata: string): Promise<Airport | null> {
  if (!airportsIndex) {
    const data = await loadAirports();
    airportsIndex = buildIndex(data);
  }
  return airportsIndex.get(iata.toUpperCase()) ?? null;
}

export async function searchAirports(
  query: string,
  limit: number = 10
): Promise<Airport[]> {
  if (!query || query.length < 2) return [];

  const data = await loadAirports();
  const q = query.toLowerCase().trim();

  const scored = data
    .filter((a) => a.iata_code && a.iata_code !== "\\N")
    .map((a) => {
      let score = 0;
      if (a.iata_code.toLowerCase() === q) score += 100;
      else if (a.iata_code.toLowerCase().startsWith(q)) score += 50;
      if (a.municipality?.toLowerCase().includes(q)) score += 20;
      if (a.name?.toLowerCase().includes(q)) score += 10;
      if (a.type === "large_airport") score += 5;
      else if (a.type === "medium_airport") score += 3;
      return { airport: a, score };
    })
    .filter((a) => a.score > 0)
    .sort((a, b) => b.score - a.score)
    .slice(0, limit);

  return scored.map((s) => s.airport);
}

export function expandMetroAirports(
  data: Airport[],
  iata: string
): string[] {
  const metroMap: Record<string, string[]> = {
    PAR: ["CDG", "ORY", "BVA"],
    LON: ["LHR", "LGW", "STN", "LCY", "LTN", "SEN"],
    NYC: ["JFK", "LGA", "EWR"],
    TYO: ["NRT", "HND"],
    CHI: ["ORD", "MDW"],
    WAS: ["DCA", "IAD", "BWI"],
    MOW: ["SVO", "DME", "VKO"],
    SAO: ["GRU", "CGH", "VCP"],
  };

  const codes = metroMap[iata.toUpperCase()] ?? [iata];
  const existing = new Set(
    data.filter((a) => a.iata_code).map((a) => a.iata_code.toUpperCase())
  );
  return codes.filter((c) => existing.has(c));
}
