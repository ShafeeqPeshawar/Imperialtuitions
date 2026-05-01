"use client";

import { FormEvent, useState } from "react";
import { useRouter } from "next/navigation";

export function RegisterForm() {
  const router = useRouter();
  const [data, setData] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");
  const [errors, setErrors] = useState<string[]>([]);

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setLoading(true);
    setMessage("");
    setErrors([]);
    try {
      const res = await fetch("/api/auth/register", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });
      const body = (await res.json()) as { success?: boolean; message?: string; errors?: string[] };
      if (!res.ok || !body.success) {
        setMessage(body.message ?? "Registration failed.");
        if (Array.isArray(body.errors) && body.errors.length > 0) {
          setErrors(body.errors);
        }
        return;
      }
      router.push("/dashboard");
      router.refresh();
    } catch {
      setMessage("Registration failed.");
    } finally {
      setLoading(false);
    }
  }

  return (
    <form onSubmit={onSubmit} style={{ display: "grid", gap: 12 }}>
      <input placeholder="Name" required value={data.name} onChange={(e) => setData((p) => ({ ...p, name: e.target.value }))} />
      <input type="email" placeholder="Email" required value={data.email} onChange={(e) => setData((p) => ({ ...p, email: e.target.value }))} />
      <input type="password" placeholder="Password" required value={data.password} onChange={(e) => setData((p) => ({ ...p, password: e.target.value }))} />
      <input type="password" placeholder="Confirm Password" required value={data.password_confirmation} onChange={(e) => setData((p) => ({ ...p, password_confirmation: e.target.value }))} />
      {message ? <p style={{ color: "#dc2626", fontSize: 14 }}>{message}</p> : null}
      {errors.length > 0 ? (
        <ul style={{ color: "#dc2626", fontSize: 14, paddingLeft: 18, margin: 0, display: "grid", gap: 4 }}>
          {errors.map((error) => (
            <li key={error}>{error}</li>
          ))}
        </ul>
      ) : null}
      <button className="btn-primary" disabled={loading}>{loading ? "Registering..." : "Register"}</button>
    </form>
  );
}
