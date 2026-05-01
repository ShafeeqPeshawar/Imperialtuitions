"use client";

import { useState } from "react";

type Contact = {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  message: string;
  reply_status: "pending" | "replied";
  is_viewed: number;
  created_at: string;
};

type Pagination = { page: number; totalPages: number };

export function AdminContactsManager({ initialContacts, initialPagination }: { initialContacts: Contact[]; initialPagination: Pagination }) {
  const [contacts, setContacts] = useState(initialContacts);
  const [pagination, setPagination] = useState(initialPagination);
  const [selected, setSelected] = useState<Contact | null>(null);
  const [replyMessage, setReplyMessage] = useState("");
  const [message, setMessage] = useState("");

  async function reload(page = pagination.page) {
    const res = await fetch(`/api/admin/contacts?page=${page}`);
    const body = (await res.json()) as { contacts: Contact[]; pagination: Pagination };
    setContacts(body.contacts ?? []);
    setPagination(body.pagination ?? { page: 1, totalPages: 1 });
  }

  async function openModal(id: number) {
    const res = await fetch(`/api/admin/contacts/${id}`);
    const body = (await res.json()) as { contact?: Contact };
    setSelected(body.contact ?? null);
    setReplyMessage("");
    await reload();
  }

  async function removeItem(id: number) {
    if (!window.confirm("Delete this contact?")) return;
    const res = await fetch(`/api/admin/contacts/${id}`, { method: "DELETE" });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Deleted." : "Delete failed."));
    if (res.ok) await reload();
  }

  async function sendReply() {
    if (!selected || !replyMessage.trim()) return;
    const res = await fetch(`/api/admin/contacts/${selected.id}/reply`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ reply_message: replyMessage }),
    });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Reply sent." : "Reply failed."));
    if (res.ok) {
      setSelected(null);
      await reload();
    }
  }

  return (
    <div style={{ display: "grid", gap: 20 }}>
      <div className="admin-page-header"><h2 className="admin-page-title">Contact Submissions</h2></div>
      {message ? <p style={{ color: "#334155", fontSize: 14 }}>{message}</p> : null}
      <div className="admin-card">
        <table className="admin-table">
          <thead><tr><th>Name</th><th>Email</th><th>Date</th><th>Replied</th><th>Viewed</th><th>Action</th></tr></thead>
          <tbody>
            {contacts.map((c) => (
              <tr key={c.id}>
                <td>{c.name}</td>
                <td>{c.email}</td>
                <td>{new Date(c.created_at).toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" })}</td>
                <td>{c.reply_status === "replied" ? "Replied" : "Pending"}</td>
                <td>{c.is_viewed ? "Viewed" : "—"}</td>
                <td>
                  <div className="admin-actions">
                    <button className="admin-btn-sm admin-btn-edit" onClick={() => openModal(c.id)}>View Details</button>
                    <button className="admin-btn-sm admin-btn-delete" onClick={() => removeItem(c.id)}>Delete</button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <div style={{ display: "flex", gap: 8 }}>
        <button className="admin-btn-sm admin-btn-edit" disabled={pagination.page <= 1} onClick={() => reload(pagination.page - 1)}>Previous</button>
        <button className="admin-btn-sm admin-btn-edit" disabled={pagination.page >= pagination.totalPages} onClick={() => reload(pagination.page + 1)}>Next</button>
      </div>

      {selected ? (
        <div className="modal-overlay admin-modal-shell">
          <div className="modal-box admin-modal-box">
            <button className="close-btn" onClick={() => setSelected(null)}>×</button>
            <div className="registration-card admin-modal-content">
              <h3>Contact Details</h3>
              <p><strong>Name:</strong> {selected.name}</p>
              <p><strong>Email:</strong> {selected.email}</p>
              <p><strong>Phone:</strong> {selected.phone ?? "—"}</p>
              <p><strong>Message:</strong> {selected.message}</p>
              <textarea className="admin-modal-textarea" rows={4} placeholder="Write your reply..." value={replyMessage} onChange={(e) => setReplyMessage(e.target.value)} />
              <button className="admin-btn-sm" style={{ marginTop: 12, background: "#facc15", color: "#111827" }} onClick={sendReply}>Send Reply</button>
            </div>
          </div>
        </div>
      ) : null}
    </div>
  );
}
