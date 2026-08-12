/** Airport record from OpenFlights / OurAirports data */
export interface Airport {
  id: string;
  ident: string;
  type: string;
  name: string;
  latitude_deg: number | null;
  longitude_deg: number | null;
  elevation_ft: number | null;
  continent: string;
  iso_country: string;
  municipality: string;
  scheduled_service: string;
  gps_code: string;
  iata_code: string;
  local_code: string;
  home_link: string;
  wikipedia_link: string;
  keywords: string;
}

/** A single flight offer from Amadeus API */
export interface FlightOffer {
  id: string;
  price: {
    currency: string;
    total: number;
    grandTotal: number;
  };
  itineraries: FlightItinerary[];
  travelers: number;
  lastTicketingDate: string;
  validatingAirlineCodes: string[];
}

export interface FlightItinerary {
  duration: string;   // ISO 8601 duration: PT2H30M
  segments: FlightSegment[];
}

export interface FlightSegment {
  departure: { iataCode: string; at: string };
  arrival: { iataCode: string; at: string };
  carrierCode: string;
  number: string;
  aircraft: { code: string };
  duration: string;
  numberOfStops: number;
}

/** Normalised flight result used internally by the sort algorithm */
export interface NormalisedFlight {
  id: string;
  price: number;            // in EUR
  totalDurationMinutes: number;
  stops: number;
  airline: string;
  departureTime: string;
  arrivalTime: string;
  origin: string;
  destination: string;
  segments: FlightSegment[];
  /** Score used for "Best" sorting — lower is better */
  bestScore: number;
}

/** URL params parsed from /flights/:slug */
export interface FlightSearchParams {
  origin: string;
  destination: string;
  deptDate: string;         // YYYY-MM-DD
  returnDate?: string;      // YYYY-MM-DD (optional for one-way)
  adults?: number;
}
