/**
 * Flight Results Page
 *
 * URL format:  /flights/[origin]-[destination]/[deptDate]/[returnDate]
 *
 * This page:
 *   1. Parses the URL slug via parseFlightSlug()
 *   2. Calls the internal API route /api/search to fetch flight offers
 *   3. Allows the user to sort results by "Best", "Price", or "Duration"
 *   4. Displays affiliate booking links for each offer
 *
 * Rendering: Static shell with client-side data fetching (ISR-friendly).
 */

"use client";

import { useParams, useSearchParams } from "next/navigation";
import { useEffect, useState, useCallback } from "react";
import { parseFlightSlug } from "@/lib/parser";
import { sortByBest, sortByPrice, sortByDuration } from "@/lib/sort";
import type { FlightSearchParams, NormalisedFlight } from "@/types";

type SortMode = "best" | "price" | "duration";

const sortMap: Record<SortMode, typeof sortByBest> = {
  best: sortByBest,
  price: sortByPrice,
  duration: sortByDuration,
};

export default function FlightResultsPage() {
  const params = useParams();
  const searchParams = useSearchParams();

  // Parse the slug
  const slug = params.slug as string[];
  const search = parseFlightSlug(slug);
  const adults = parseInt(searchParams.get("adults") ?? "1", 10);

  const [results, setResults] = useState<NormalisedFlight[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [sortMode, setSortMode] = useState<SortMode>("best");

  useEffect(() => {
    if (!search) {
      setError("Invalid search URL. Use /flights/[ORIGIN]-[DEST]/[DATE]");
      setLoading(false);
      return;
    }

    const fetchResults = async () => {
      setLoading(true);
      setError(null);
      try {
        const query = new URLSearchParams({
          origin: search.origin,
          destination: search.destination,
          deptDate: search.deptDate,
          adults: String(adults),
        });
        if (search.returnDate) query.set("returnDate", search.returnDate);

        const res = await fetch(`/api/search?${query.toString()}`);
        if (!res.ok) {
          const err = await res.json().catch(() => ({}));
          throw new Error(err.error ?? `API error ${res.status}`);
        }
        const data = await res.json();
        setResults(data.flights ?? []);
      } catch (e: any) {
        setError(e.message);
      } finally {
        setLoading(false);
      }
    };

    fetchResults();
  }, [search?.origin, search?.destination, search?.deptDate, search?.returnDate, adults]);

  // Client-side re-sort when user changes sort mode
  const sorted = useCallback(() => {
    if (!results.length) return results;
    const copy = [...results];
    const sorter = sortMap[sortMode];
    return sorter(copy);
  }, [results, sortMode])();

  if (!search) {
    return <ErrorCard message="Invalid URL format" />;
  }

  return (
    <div>
      {/* Search summary header */}
      <div style={{ marginBottom: 24 }}>
        <h2 style={{ fontSize: 22, margin: 0 }}>
          {search.origin} → {search.destination}
        </h2>
        <p style={{ color: "#6b7280", margin: "4px 0 0" }}>
          {search.deptDate}
          {search.returnDate ? ` — ${search.returnDate}` : " (One-way)"}
          {adults > 1 ? ` · ${adults} adults` : ""}
        </p>
      </div>

      {/* Sort tabs */}
      <div style={{ display: "flex", gap: 8, marginBottom: 20 }}>
        {(["best", "price", "duration"] as SortMode[]).map((mode) => (
          <button
            key={mode}
            onClick={() => setSortMode(mode)}
            style={{
              padding: "8px 16px",
              border: `1px solid ${sortMode === mode ? "#2563eb" : "#d1d5db"}`,
              background: sortMode === mode ? "#2563eb" : "#fff",
              color: sortMode === mode ? "#fff" : "#374151",
              borderRadius: 8,
              cursor: "pointer",
              fontWeight: sortMode === mode ? 600 : 400,
              fontSize: 14,
            }}
          >
            {mode === "best" ? "Best" : mode === "price" ? "Cheapest" : "Fastest"}
          </button>
        ))}
      </div>

      {/* Results or loading/error state */}
      {loading && <p>Searching for the best flights...</p>}
      {error && <ErrorCard message={error} />}

      {!loading && !error && sorted.length === 0 && (
        <p style={{ color: "#6b7280" }}>No flights found for this route and date.</p>
      )}

      {sorted.map((flight) => (
        <FlightCard key={flight.id} flight={flight} search={search} />
      ))}
    </div>
  );
}

// ---------- Flight Card Component ----------

function FlightCard({
  flight,
  search,
}: {
  flight: NormalisedFlight;
  search: FlightSearchParams;
}) {
  const durationStr = formatMinutes(flight.totalDurationMinutes);

  return (
    <div style={cardStyle}>
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
        <div>
          <div style={{ fontSize: 14, color: "#6b7280" }}>
            {flight.airline} · {flight.stops === 0 ? "Non-stop" : `${flight.stops} stop${flight.stops > 1 ? "s" : ""}`}
          </div>
          <div style={{ fontSize: 18, fontWeight: 600, marginTop: 4 }}>
            {flight.origin} → {flight.destination}
          </div>
          <div style={{ fontSize: 13, color: "#6b7280", marginTop: 2 }}>
            {flight.departureTime} – {flight.arrivalTime} · {durationStr}
          </div>
        </div>
        <div style={{ textAlign: "right" }}>
          <div style={{ fontSize: 24, fontWeight: 700 }}>
            €{flight.price.toFixed(0)}
          </div>
          <a
            href={`/api/redirect?offerId=${flight.id}&origin=${search.origin}&destination=${search.destination}&deptDate=${search.deptDate}`}
            target="_blank"
            style={bookBtnStyle}
          >
            Book
          </a>
        </div>
      </div>

      {/* Score badge (debug/info) */}
      {flight.bestScore > 0 && (
        <div style={{ fontSize: 11, color: "#9ca3af", marginTop: 8 }}>
          Best score: {flight.bestScore.toFixed(3)}
        </div>
      )}
    </div>
  );
}

// ---------- Shared Components ----------

function ErrorCard({ message }: { message: string }) {
  return (
    <div style={{
      padding: 16,
      background: "#fef2f2",
      border: "1px solid #fecaca",
      borderRadius: 8,
      color: "#b91c1c",
    }}>
      {message}
    </div>
  );
}

// ---------- Helpers ----------

function formatMinutes(total: number): string {
  const h = Math.floor(total / 60);
  const m = total % 60;
  return `${h}h ${m}m`;
}

// ---------- Styles ----------

const cardStyle: React.CSSProperties = {
  padding: 16,
  marginBottom: 12,
  border: "1px solid #e5e7eb",
  borderRadius: 10,
  background: "#fff",
};

const bookBtnStyle: React.CSSProperties = {
  display: "inline-block",
  marginTop: 8,
  padding: "8px 20px",
  background: "#059669",
  color: "#fff",
  borderRadius: 6,
  textDecoration: "none",
  fontWeight: 600,
  fontSize: 14,
};
