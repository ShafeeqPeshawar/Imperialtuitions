"use client";

import { FormEvent, useState } from "react";
import { useRouter } from "next/navigation";

type Props = {
  initialName: string;
  initialEmail: string;
};

export function ProfileForms({ initialName, initialEmail }: Props) {
  const router = useRouter();
  const [profile, setProfile] = useState({ name: initialName, email: initialEmail });
  const [password, setPassword] = useState({
    current_password: "",
    password: "",
    password_confirmation: "",
  });
  const [deletePassword, setDeletePassword] = useState("");
  const [profileMsg, setProfileMsg] = useState("");
  const [passwordMsg, setPasswordMsg] = useState("");
  const [deleteMsg, setDeleteMsg] = useState("");

  async function submitProfile(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const res = await fetch("/api/profile/update", {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(profile),
    });
    const body = (await res.json()) as { message?: string };
    setProfileMsg(body.message ?? (res.ok ? "Saved." : "Unable to update profile."));
    router.refresh();
  }

  async function submitPassword(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const res = await fetch("/api/profile/password", {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(password),
    });
    const body = (await res.json()) as { message?: string };
    setPasswordMsg(body.message ?? (res.ok ? "Saved." : "Unable to update password."));
    if (res.ok) setPassword({ current_password: "", password: "", password_confirmation: "" });
  }

  async function submitDelete(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const res = await fetch("/api/profile/delete", {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ password: deletePassword }),
    });
    const body = (await res.json()) as { success?: boolean; message?: string };
    setDeleteMsg(body.message ?? (res.ok ? "Account deleted." : "Unable to delete account."));
    if (res.ok && body.success) {
      router.push("/login");
      router.refresh();
    }
  }

  return (
    <div style={{ display: "grid", gap: 20 }}>
      <div className="success-box" style={{ textAlign: "left" }}>
        <h3 style={{ marginBottom: 10 }}>Profile Information</h3>
        <form onSubmit={submitProfile} style={{ display: "grid", gap: 10 }}>
          <input value={profile.name} onChange={(e) => setProfile((p) => ({ ...p, name: e.target.value }))} required />
          <input type="email" value={profile.email} onChange={(e) => setProfile((p) => ({ ...p, email: e.target.value }))} required />
          {profileMsg ? <p style={{ fontSize: 14, color: "#334155" }}>{profileMsg}</p> : null}
          <button className="btn-primary">Save</button>
        </form>
      </div>

      <div className="success-box" style={{ textAlign: "left" }}>
        <h3 style={{ marginBottom: 10 }}>Update Password</h3>
        <form onSubmit={submitPassword} style={{ display: "grid", gap: 10 }}>
          <input type="password" placeholder="Current Password" value={password.current_password} onChange={(e) => setPassword((p) => ({ ...p, current_password: e.target.value }))} required />
          <input type="password" placeholder="New Password" value={password.password} onChange={(e) => setPassword((p) => ({ ...p, password: e.target.value }))} required />
          <input type="password" placeholder="Confirm Password" value={password.password_confirmation} onChange={(e) => setPassword((p) => ({ ...p, password_confirmation: e.target.value }))} required />
          {passwordMsg ? <p style={{ fontSize: 14, color: "#334155" }}>{passwordMsg}</p> : null}
          <button className="btn-primary">Save</button>
        </form>
      </div>

      <div className="success-box" style={{ textAlign: "left" }}>
        <h3 style={{ marginBottom: 10 }}>Delete Account</h3>
        <form onSubmit={submitDelete} style={{ display: "grid", gap: 10 }}>
          <input type="password" placeholder="Password" value={deletePassword} onChange={(e) => setDeletePassword(e.target.value)} required />
          {deleteMsg ? <p style={{ fontSize: 14, color: "#334155" }}>{deleteMsg}</p> : null}
          <button className="btn-outline">Delete Account</button>
        </form>
      </div>
    </div>
  );
}
