/**
 * Airport Data Download Script
 *
 * Downloads the OurAirports dataset (CSV), filters to IATA-coded airports,
 * and outputs a compact JSON file for the metasearch engine.
 *
 * Usage:  npm run download-airports
 *
 * Source: https://davidmegginson.github.io/ourairports-data/airports.csv
 * License: Public Domain (OurAirports / OpenFlights data is CC-BY or Public Domain)
 */

const https = require("https");
const fs = require("fs");
const path = require("path");

const CSV_URL = "https://davidmegginson.github.io/ourairports-data/airports.csv";
const OUTPUT = path.join(__dirname, "..", "public", "data", "airports.json");

// CSV columns index (from OurAirports header)
const COLUMNS = [
  "id", "ident", "type", "name", "latitude_deg", "longitude_deg",
  "elevation_ft", "continent", "iso_country", "municipality",
  "scheduled_service", "gps_code", "iata_code", "local_code",
  "home_link", "wikipedia_link", "keywords",
];

function parseCSVLine(line) {
  const result = [];
  let current = "";
  let inQuotes = false;

  for (const char of line) {
    if (char === '"') {
      inQuotes = !inQuotes;
    } else if (char === "," && !inQuotes) {
      result.push(current.trim());
      current = "";
    } else {
      current += char;
    }
  }
  result.push(current.trim());
  return result;
}

function downloadCSV() {
  return new Promise((resolve, reject) => {
    console.log(`Downloading from ${CSV_URL}...`);
    const chunks = [];
    https
      .get(CSV_URL, (res) => {
        if (res.statusCode !== 200) {
          reject(new Error(`HTTP ${res.statusCode}`));
          return;
        }
        res.on("data", (chunk) => chunks.push(chunk));
        res.on("end", () => resolve(Buffer.concat(chunks).toString("utf-8")));
      })
      .on("error", reject);
  });
}

async function main() {
  const csv = await downloadCSV();
  const lines = csv.split("\n").filter(Boolean);
  const header = parseCSVLine(lines[0]);

  // Build column index map
  const colIndex = {};
  header.forEach((col, i) => {
    colIndex[col.replace(/^"|"$/g, "").trim()] = i;
  });

  const airports = [];

  for (let i = 1; i < lines.length; i++) {
    const parts = parseCSVLine(lines[i]);
    const get = (name) => (parts[colIndex[name]] ?? "").replace(/^"|"$/g, "").trim();

    const iata = get("iata_code");
    // Skip entries without a valid 3-letter IATA code
    if (!iata || iata === "\\N" || iata.length !== 3) continue;

    const type = get("type");
    // Keep only airports (skip heliports, closed, etc.)
    if (!/airport/.test(type)) continue;

    airports.push({
      id: get("id"),
      ident: get("ident"),
      type,
      name: get("name"),
      latitude_deg: parseFloat(get("latitude_deg")) || null,
      longitude_deg: parseFloat(get("longitude_deg")) || null,
      elevation_ft: parseInt(get("elevation_ft"), 10) || null,
      continent: get("continent"),
      iso_country: get("iso_country"),
      municipality: get("municipality"),
      scheduled_service: get("scheduled_service"),
      gps_code: get("gps_code"),
      iata_code: iata,
      local_code: get("local_code"),
      home_link: get("home_link"),
      wikipedia_link: get("wikipedia_link"),
      keywords: get("keywords"),
    });
  }

  // Sort by type priority: large > medium > small
  const typeOrder = { large_airport: 0, medium_airport: 1, small_airport: 2 };
  airports.sort((a, b) => (typeOrder[a.type] ?? 99) - (typeOrder[b.type] ?? 99));

  fs.mkdirSync(path.dirname(OUTPUT), { recursive: true });
  fs.writeFileSync(OUTPUT, JSON.stringify(airports, null, 2), "utf-8");

  console.log(`✓ Downloaded and filtered ${airports.length} airports`);
  console.log(`  Output: ${OUTPUT}`);
}

main().catch((err) => {
  console.error("Download failed:", err.message);
  process.exit(1);
});
