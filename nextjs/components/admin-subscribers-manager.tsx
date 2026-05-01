"use client";

import { useMemo, useState } from "react";

export type Subscriber = { id: number; email: string; created_at: string };

export function AdminSubscribersManager({ initialSubscribers }: { initialSubscribers: Subscriber[] }) {
  const [subscribers, setSubscribers] = useState(initialSubscribers);
  const [selectedEmails, setSelectedEmails] = useState<string[]>([]);
  const [isMessageOpen, setIsMessageOpen] = useState(false);
  const [mailHtml, setMailHtml] = useState("");
  const [message, setMessage] = useState("");

  const allSelected = useMemo(
    () => subscribers.length > 0 && selectedEmails.length === subscribers.length,
    [subscribers.length, selectedEmails.length]
  );

  async function refresh() {
    const res = await fetch("/api/admin/subscribers");
    const body = (await res.json()) as { subscribers?: Subscriber[] };
    setSubscribers(body.subscribers ?? []);
  }

  function toggleSelect(email: string) {
    setSelectedEmails((prev) => (prev.includes(email) ? prev.filter((e) => e !== email) : [...prev, email]));
  }

  function toggleSelectAll() {
    if (allSelected) setSelectedEmails([]);
    else setSelectedEmails(subscribers.map((s) => s.email));
  }

  async function sendMessage() {
    if (selectedEmails.length === 0) {
      setMessage("Please select at least one subscriber.");
      return;
    }
    if (!mailHtml.trim()) {
      setMessage("Please enter a message first.");
      return;
    }

    const res = await fetch("/api/admin/subscribers/send-message", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ emails: selectedEmails, message: mailHtml }),
    });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Message sent successfully." : "Failed to send message."));
    if (res.ok) {
      setIsMessageOpen(false);
      setMailHtml("");
      setSelectedEmails([]);
      await refresh();
    }
  }

  return (
    <div style={{ display: "grid", gap: 20 }}>
      <div className="admin-page-header">
        <h2 className="admin-page-title">Subscribers</h2>
        <button className="admin-btn-primary" onClick={() => setIsMessageOpen(true)}>Message</button>
      </div>
      {message ? <p style={{ color: "#334155", fontSize: 14 }}>{message}</p> : null}
      <div className="admin-card">
        <table className="admin-table">
          <thead>
            <tr>
              <th>Email</th>
              <th>Status</th>
              <th>Select</th>
              <th>
                Select All
                <input type="checkbox" style={{ marginLeft: 8 }} checked={allSelected} onChange={toggleSelectAll} />
              </th>
            </tr>
          </thead>
          <tbody>
            {subscribers.map((s) => (
              <tr key={s.id}>
                <td>{s.email}</td>
                <td><span className="status-ok">Active</span></td>
                <td>
                  <input type="checkbox" checked={selectedEmails.includes(s.email)} onChange={() => toggleSelect(s.email)} />
                </td>
                <td>—</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {isMessageOpen ? (
        <div className="modal-overlay admin-modal-shell">
          <div className="modal-box admin-modal-box">
            <button className="close-btn" onClick={() => setIsMessageOpen(false)}>×</button>
            <div className="registration-card admin-modal-content">
              <h3>Send Message</h3>
              <p className="admin-modal-note">Send to selected subscribers</p>
              <textarea
                className="admin-modal-textarea"
                rows={8}
                value={mailHtml}
                onChange={(e) => setMailHtml(e.target.value)}
                placeholder="Write your message here..."
              />
              <button className="admin-btn-primary" style={{ marginTop: 12 }} onClick={sendMessage}>
                Send Message
              </button>
            </div>
          </div>
        </div>
      ) : null}
    </div>
  );
}
