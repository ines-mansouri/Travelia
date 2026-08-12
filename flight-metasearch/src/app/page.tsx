"use client";

import { FormEvent, useState } from "react";
import { buildFlightUrl } from "@/lib/parser";

export default function HomePage() {
  const [origin, setOrigin] = useState("");
  const [destination, setDestination] = useState("");
  const [deptDate, setDeptDate] = useState("");
  const [returnDate, setReturnDate] = useState("");

  function handleSubmit(e: FormEvent) {
    e.preventDefault();
    if (!origin || !destination || !deptDate) return;
    const url = buildFlightUrl({
      origin: origin.toUpperCase(),
      destination: destination.toUpperCase(),
      deptDate,
      returnDate: returnDate || undefined,
    });
    window.location.href = url;
  }

  return (
    <div style={{ maxWidth: 640, margin: "80px auto" }}>
      <h1 style={{ fontSize: 32, marginBottom: 8 }}>Search Flights</h1>
      <p style={{ color: "#6b7280", marginBottom: 32 }}>
        Compare prices across hundreds of airlines
      </p>

      <form onSubmit={handleSubmit} style={formStyle}>
        <div style={{ display: "flex", gap: 12, marginBottom: 16 }}>
          <Input label="From" value={origin} onChange={setOrigin} placeholder="JFK" />
          <Input label="To" value={destination} onChange={setDestination} placeholder="LAX" />
        </div>
        <div style={{ display: "flex", gap: 12, marginBottom: 16 }}>
          <Input label="Depart" type="date" value={deptDate} onChange={setDeptDate} />
          <Input label="Return (optional)" type="date" value={returnDate} onChange={setReturnDate} />
        </div>
        <button type="submit" style={btnStyle}>
          Search Flights
        </button>
      </form>
    </div>
  );
}

function Input({
  label, type = "text", value, onChange, placeholder,
}: {
  label: string;
  type?: string;
  value: string;
  onChange: (v: string) => void;
  placeholder?: string;
}) {
  return (
    <div style={{ flex: 1 }}>
      <label style={labelStyle}>{label}</label>
      <input
        type={type}
        value={value}
        onChange={(e) => onChange(e.target.value)}
        placeholder={placeholder}
        style={inputStyle}
        required={type === "date" && label === "Depart"}
      />
    </div>
  );
}

// ---------- Styles ----------

const formStyle: React.CSSProperties = {
  background: "#f9fafb",
  padding: 24,
  borderRadius: 12,
  border: "1px solid #e5e7eb",
};

const labelStyle: React.CSSProperties = {
  display: "block",
  fontSize: 12,
  fontWeight: 600,
  marginBottom: 4,
  color: "#374151",
};

const inputStyle: React.CSSProperties = {
  width: "100%",
  padding: "10px 12px",
  fontSize: 14,
  border: "1px solid #d1d5db",
  borderRadius: 8,
  boxSizing: "border-box",
};

const btnStyle: React.CSSProperties = {
  width: "100%",
  padding: 12,
  background: "#2563eb",
  color: "#fff",
  border: "none",
  borderRadius: 8,
  fontSize: 16,
  fontWeight: 600,
  cursor: "pointer",
};
