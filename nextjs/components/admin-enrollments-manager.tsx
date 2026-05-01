"use client";

import { useState } from "react";

type Enrollment = {
  id: number;
  course_name: string;
  registration_type: string | null;
  name: string;
  email: string;
  phone: string | null;
  message: string | null;
  status: "pending" | "approved" | "rejected";
  level: string | null;
  preferred_date: string | null;
  preferred_time: string | null;
  created_at: string;
};

type Pagination = { page: number; totalPages: number };

export function AdminEnrollmentsManager({
  initialEnrollments,
  initialPagination,
}: {
  initialEnrollments: Enrollment[];
  initialPagination: Pagination;
}) {
  const [enrollments, setEnrollments] = useState(initialEnrollments);
  const [pagination, setPagination] = useState(initialPagination);
  const [selected, setSelected] = useState<Enrollment | null>(null);
  const [replyMessage, setReplyMessage] = useState("");
  const [message, setMessage] = useState("");

  async function reload(page = pagination.page) {
    const res = await fetch(`/api/admin/course-enrollments?page=${page}`);
    const body = (await res.json()) as { enrollments: Enrollment[]; pagination: Pagination };
    setEnrollments(body.enrollments ?? []);
    setPagination(body.pagination ?? { page: 1, totalPages: 1 });
  }

  async function openModal(id: number) {
    const res = await fetch(`/api/admin/course-enrollments/${id}`);
    const body = (await res.json()) as { enrollment?: Enrollment };
    setSelected(body.enrollment ?? null);
    setReplyMessage("");
  }

  async function removeItem(id: number) {
    if (!window.confirm("Delete this enrollment?")) return;
    const res = await fetch(`/api/admin/course-enrollments/${id}`, { method: "DELETE" });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Deleted." : "Delete failed."));
    if (res.ok) await reload();
  }

  async function submitReply() {
    if (!selected) return;
    const res = await fetch(`/api/admin/course-enrollments/${selected.id}/reply`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ reply_message: replyMessage }),
    });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Reply sent." : "Reply failed."));
  }

  async function changeStatus(action: "approve" | "reject") {
    if (!selected) return;
    const res = await fetch(`/api/admin/course-enrollments/${selected.id}/${action}`, { method: "POST" });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Updated." : "Update failed."));
    if (res.ok) {
      setSelected(null);
      await reload();
    }
  }

  return (
    <div style={{ display: "grid", gap: 20 }}>
      <div className="admin-page-header"><h2 className="admin-page-title">Course Enrollments</h2></div>
      {message ? <p style={{ color: "#334155", fontSize: 14 }}>{message}</p> : null}
      <div className="admin-card">
        <table className="admin-table">
          <thead><tr><th>#</th><th>For Course</th><th>Type</th><th>Name</th><th>Submitted</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
            {enrollments.map((e, i) => (
              <tr key={e.id}>
                <td>{(pagination.page - 1) * 20 + i + 1}</td>
                <td>{e.course_name}</td>
                <td>{e.registration_type ?? "Individual"}</td>
                <td>{e.name}</td>
                <td>{new Date(e.created_at).toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" })}</td>
                <td>
                  <span className={`admin-badge ${e.status === "approved" ? "admin-badge-active" : e.status === "rejected" ? "admin-badge-inactive" : ""}`}>
                    {e.status}
                  </span>
                </td>
                <td>
                  <div className="admin-actions">
                    <button className="admin-btn-sm admin-btn-edit" onClick={() => openModal(e.id)}>View Details</button>
                    <button className="admin-btn-sm admin-btn-delete" onClick={() => removeItem(e.id)}>Delete</button>
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
              <h3>Enrollment Details</h3>
              <p><strong>Name:</strong> {selected.name}</p>
              <p><strong>Email:</strong> {selected.email}</p>
              <p><strong>Phone:</strong> {selected.phone ?? "—"}</p>
              <p><strong>Course:</strong> {selected.course_name}</p>
              <p><strong>Level:</strong> {selected.level ?? "—"}</p>
              <p><strong>Preferred Date:</strong> {selected.preferred_date ?? "—"}</p>
              <p><strong>Preferred Time:</strong> {selected.preferred_time ?? "—"}</p>
              <p><strong>Message:</strong> {selected.message ?? "—"}</p>
              <textarea className="admin-modal-textarea" rows={4} placeholder="Reply message..." value={replyMessage} onChange={(e) => setReplyMessage(e.target.value)} />
              <div className="admin-modal-actions">
                <button className="admin-btn-sm admin-btn-edit" onClick={submitReply}>Send Reply</button>
                <button className="admin-btn-sm" style={{ background: "#facc15" }} onClick={() => changeStatus("approve")}>Approve</button>
                <button className="admin-btn-sm admin-btn-delete" onClick={() => changeStatus("reject")}>Reject</button>
              </div>
            </div>
          </div>
        </div>
      ) : null}
    </div>
  );
}
