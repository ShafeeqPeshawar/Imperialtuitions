"use client";

import { FormEvent, useState } from "react";

export function ForgotPasswordForm() {
  const [email, setEmail] = useState("");
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setLoading(true);
    setMessage("");
    try {
      const res = await fetch("/api/auth/forgot-password", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email }),
      });
      const body = (await res.json()) as { message?: string };
      setMessage(body.message ?? (res.ok ? "Reset link sent." : "Unable to send reset link."));
    } catch {
      setMessage("Unable to send reset link.");
    } finally {
      setLoading(false);
    }
  }

  return (
    <form onSubmit={onSubmit} style={{ display: "grid", gap: 12 }}>
      <input type="email" placeholder="Email" required value={email} onChange={(e) => setEmail(e.target.value)} />
      {message ? <p style={{ color: "#334155", fontSize: 14 }}>{message}</p> : null}
      <button className="btn-primary" disabled={loading}>
        {loading ? "Sending..." : "Email Password Reset Link"}
      </button>
    </form>
  );
}
