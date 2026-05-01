"use client";

import { FormEvent, useState } from "react";

export function ContactHeroButton() {
  const [open, setOpen] = useState(false);
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState("");
  const [data, setData] = useState({
    name: "",
    email: "",
    phone: "",
    message: "",
  });

  async function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setLoading(true);
    setMessage("");
    try {
      const res = await fetch("/api/contact", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });
      const body = (await res.json()) as { message?: string };
      setMessage(body.message ?? (res.ok ? "Message sent." : "Unable to send message."));
      if (res.ok) {
        setData({ name: "", email: "", phone: "", message: "" });
      }
    } catch {
      setMessage("Unable to send message.");
    } finally {
      setLoading(false);
    }
  }

  return (
    <>
      <button className="btn-outline" type="button" onClick={() => setOpen(true)}>
        Contact Us
      </button>

      {open && (
        <div id="contactModal" className="modal-overlay" style={{ display: "flex" }}>
          <div className="modal-box">
            <button className="close-btn" type="button" onClick={() => setOpen(false)}>×</button>
            <div className="registration-card">
              <h2 className="reg-title">Contact Us</h2>
              <p className="reg-subtitle">Leave us a message and our team will respond shortly.</p>
              <form onSubmit={submit}>
                <div className="reg-grid">
                  <div className="reg-group">
                    <label>Full Name</label>
                    <input
                      placeholder="Full Name"
                      required
                      value={data.name}
                      onChange={(e) => setData((prev) => ({ ...prev, name: e.target.value }))}
                    />
                  </div>
                  <div className="reg-group">
                    <label>Email</label>
                    <input
                      placeholder="Email"
                      type="email"
                      required
                      value={data.email}
                      onChange={(e) => setData((prev) => ({ ...prev, email: e.target.value }))}
                    />
                  </div>
                  <div className="reg-group">
                    <label>Phone (Optional)</label>
                    <input
                      placeholder="Phone (optional)"
                      value={data.phone}
                      onChange={(e) => setData((prev) => ({ ...prev, phone: e.target.value }))}
                    />
                  </div>
                  <div />
                  <div className="reg-group full">
                    <label>Message</label>
                    <textarea
                      rows={3}
                      placeholder="Your message"
                      required
                      value={data.message}
                      onChange={(e) => setData((prev) => ({ ...prev, message: e.target.value }))}
                    />
                  </div>
                </div>
                <div className="consent-box">
                  <label className="consent-label">
                    <input type="checkbox" required />
                    <span>
                      <strong>Consent &amp; Disclaimer</strong><br />
                      I confirm that all information provided is accurate.
                    </span>
                  </label>
                </div>
                {message ? <p className="success-text" style={{ marginTop: 8, marginBottom: 0 }}>{message}</p> : null}
                <button className="reg-submit" disabled={loading}>
                  {loading ? "Sending..." : "Contact Us"}
                </button>
                <p className="reg-footer">We usually respond within one business day.</p>
              </form>
            </div>
          </div>
        </div>
      )}
    </>
  );
}
