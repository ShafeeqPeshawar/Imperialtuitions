"use client";

import { useState } from "react";

type Inquiry = {
  id: number;
  course_title: string;
  name: string;
  email: string;
  phone: string | null;
  message: string;
  level: string | null;
  launch_date: string | null;
  is_viewed: number;
  reply_status: "pending" | "replied";
  created_at: string;
};

type Pagination = { page: number; totalPages: number };

export function AdminInquiriesManager({ initialInquiries, initialPagination }: { initialInquiries: Inquiry[]; initialPagination: Pagination }) {
  const [inquiries, setInquiries] = useState(initialInquiries);
  const [pagination, setPagination] = useState(initialPagination);
  const [selected, setSelected] = useState<Inquiry | null>(null);
  const [replyMessage, setReplyMessage] = useState("");
  const [message, setMessage] = useState("");

  async function reload(page = pagination.page) {
    const res = await fetch(`/api/admin/course-inquiries?page=${page}`);
    const body = (await res.json()) as { inquiries: Inquiry[]; pagination: Pagination };
    setInquiries(body.inquiries ?? []);
    setPagination(body.pagination ?? { page: 1, totalPages: 1 });
  }

  async function openModal(id: number) {
    const res = await fetch(`/api/admin/course-inquiries/${id}`);
    const body = (await res.json()) as { inquiry?: Inquiry };
    setSelected(body.inquiry ?? null);
    setReplyMessage("");
    await reload();
  }

  async function removeItem(id: number) {
    if (!window.confirm("Delete this inquiry?")) return;
    const res = await fetch(`/api/admin/course-inquiries/${id}`, { method: "DELETE" });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Deleted." : "Delete failed."));
    if (res.ok) await reload();
  }

  async function sendReply() {
    if (!selected || !replyMessage.trim()) return;
    const res = await fetch(`/api/admin/course-inquiries/${selected.id}/reply`, {
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
      <div className="admin-page-header"><h2 className="admin-page-title">Course Inquiries</h2></div>
      {message ? <p style={{ color: "#334155", fontSize: 14 }}>{message}</p> : null}
      <div className="admin-card">
        <table className="admin-table">
          <thead><tr><th>For Course</th><th>Name</th><th>Email</th><th>Submitted</th><th>Replied</th><th>Viewed</th><th>Action</th></tr></thead>
          <tbody>
            {inquiries.map((i) => (
              <tr key={i.id}>
                <td>{i.course_title}</td>
                <td>{i.name}</td>
                <td>{i.email}</td>
                <td>{new Date(i.created_at).toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" })}</td>
                <td>{i.reply_status === "replied" ? "Replied" : "Pending"}</td>
                <td>{i.is_viewed ? "Viewed" : "—"}</td>
                <td>
                  <div className="admin-actions">
                    <button className="admin-btn-sm admin-btn-edit" onClick={() => openModal(i.id)}>View</button>
                    <button className="admin-btn-sm admin-btn-delete" onClick={() => removeItem(i.id)}>Delete</button>
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
              <h3>Inquiry Details</h3>
              <p><strong>Course:</strong> {selected.course_title}</p>
              <p><strong>Level:</strong> {selected.level ?? "—"}</p>
              <p><strong>Name:</strong> {selected.name}</p>
              <p><strong>Email:</strong> {selected.email}</p>
              <p><strong>Phone:</strong> {selected.phone ?? "—"}</p>
              <p><strong>Message:</strong> {selected.message}</p>
              <textarea className="admin-modal-textarea" rows={4} placeholder="Reply message..." value={replyMessage} onChange={(e) => setReplyMessage(e.target.value)} />
              <div className="admin-modal-actions">
                <button className="admin-btn-sm" style={{ background: "#facc15" }} onClick={sendReply}>Send Reply</button>
              </div>
            </div>
          </div>
        </div>
      ) : null}
    </div>
  );
}
