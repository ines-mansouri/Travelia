import type { Metadata } from "next";

export const metadata: Metadata = {
  title: "Flight Metasearch — Compare & Book Flights",
  description: "Search hundreds of airlines for the best flight deals",
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en">
      <body style={{ margin: 0, fontFamily: "system-ui, sans-serif" }}>
        <nav style={navStyle}>
          <a href="/" style={{ fontWeight: 700, fontSize: 20, textDecoration: "none", color: "#000" }}>
            ✈ FlightMetasearch
          </a>
        </nav>
        <main style={{ maxWidth: 1200, margin: "0 auto", padding: "0 16px" }}>
          {children}
        </main>
      </body>
    </html>
  );
}

const navStyle: React.CSSProperties = {
  display: "flex",
  alignItems: "center",
  height: 56,
  padding: "0 24px",
  borderBottom: "1px solid #e5e7eb",
  marginBottom: 24,
  background: "#fff",
};
