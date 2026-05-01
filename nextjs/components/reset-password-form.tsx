"use client";

import { FormEvent, useState } from "react";
import { useRouter } from "next/navigation";

type Props = {
  token: string;
  email: string;
};

export function ResetPasswordForm({ token, email }: Props) {
  const router = useRouter();
  const [password, setPassword] = useState("");
  const [passwordConfirmation, setPasswordConfirmation] = useState("");
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setLoading(true);
    setMessage("");
    try {
      const res = await fetch("/api/auth/reset-password", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          token,
          email,
          password,
          password_confirmation: passwordConfirmation,
        }),
      });
      const body = (await res.json()) as { success?: boolean; message?: string };
      setMessage(body.message ?? (res.ok ? "Password reset successfully." : "Unable to reset password."));
      if (res.ok && body.success) {
        setTimeout(() => router.push("/login"), 1000);
      }
    } catch {
      setMessage("Unable to reset password.");
    } finally {
      setLoading(false);
    }
  }

  return (
    <form onSubmit={onSubmit} style={{ display: "grid", gap: 12 }}>
      <input type="email" value={email} disabled />
      <input type="password" placeholder="Password" required value={password} onChange={(e) => setPassword(e.target.value)} />
      <input
        type="password"
        placeholder="Confirm Password"
        required
        value={passwordConfirmation}
        onChange={(e) => setPasswordConfirmation(e.target.value)}
      />
      {message ? <p style={{ color: "#334155", fontSize: 14 }}>{message}</p> : null}
      <button className="btn-primary" disabled={loading}>
        {loading ? "Resetting..." : "Reset Password"}
      </button>
    </form>
  );
}
