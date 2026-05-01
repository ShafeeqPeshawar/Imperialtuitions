"use client";

import { FormEvent, useState } from "react";

type Launch = { id: number; course_id: number; launch_date: string; course_title: string; level: string | null };
type Course = { id: number; title: string; level: string | null; price: number | null };

export function AdminCourseLaunchesManager({ initialLaunches, freeCourses }: { initialLaunches: Launch[]; freeCourses: Course[] }) {
  const [launches, setLaunches] = useState<Launch[]>(initialLaunches);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [courseId, setCourseId] = useState("");
  const [launchDate, setLaunchDate] = useState("");
  const [message, setMessage] = useState("");

  async function refresh() {
    const res = await fetch("/api/admin/course-launches");
    const body = (await res.json()) as { launches?: Launch[] };
    setLaunches(body.launches ?? []);
  }

  function startCreate() {
    setEditingId(null);
    setCourseId("");
    setLaunchDate("");
  }

  function startEdit(launch: Launch) {
    setEditingId(launch.id);
    setCourseId(String(launch.course_id));
    setLaunchDate(String(launch.launch_date).slice(0, 10));
  }

  async function submit(e: FormEvent) {
    e.preventDefault();
    setMessage("");
    const payload = { course_id: Number(courseId), launch_date: launchDate };
    const url = editingId ? `/api/admin/course-launches/${editingId}` : "/api/admin/course-launches";
    const method = editingId ? "PUT" : "POST";
    const res = await fetch(url, { method, headers: { "Content-Type": "application/json" }, body: JSON.stringify(payload) });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Saved." : "Failed."));
    if (res.ok) {
      await refresh();
      startCreate();
    }
  }

  async function removeLaunch(id: number) {
    if (!window.confirm("Delete this launch date?")) return;
    const res = await fetch(`/api/admin/course-launches/${id}`, { method: "DELETE" });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Deleted." : "Delete failed."));
    if (res.ok) await refresh();
  }

  return (
    <div style={{ display: "grid", gap: 20 }}>
      <div className="admin-page-header">
        <h2 className="admin-page-title">Course Launch Dates</h2>
        <button className="admin-btn-primary" type="button" onClick={startCreate}>+ Add</button>
      </div>

      <div className="admin-card">
        <table className="admin-table">
          <thead><tr><th>Course</th><th>Level</th><th>Launch Date</th><th>Actions</th></tr></thead>
          <tbody>
            {launches.map((l) => (
              <tr key={l.id}>
                <td>{l.course_title}</td>
                <td>{l.level ?? "–"}</td>
                <td>{new Date(l.launch_date).toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" })}</td>
                <td>
                  <div className="admin-actions">
                    <button className="admin-btn-sm admin-btn-edit" type="button" onClick={() => startEdit(l)}>Edit</button>
                    <button className="admin-btn-sm admin-btn-delete" type="button" onClick={() => removeLaunch(l.id)}>Delete</button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      <div className="admin-card" style={{ padding: 20 }}>
        <h3 style={{ marginBottom: 12 }}>{editingId ? "Edit Course Launch Date" : "Add Course Launch Date"}</h3>
        <form onSubmit={submit} style={{ display: "grid", gap: 10, maxWidth: 560 }}>
          <select value={courseId} onChange={(e) => setCourseId(e.target.value)} required>
            <option value="">Select Course</option>
            {freeCourses.map((c) => (
              <option key={c.id} value={c.id}>{c.title} ({c.level ?? "General"})</option>
            ))}
          </select>
          <input type="date" value={launchDate} onChange={(e) => setLaunchDate(e.target.value)} required />
          {message ? <p style={{ color: "#334155", fontSize: 14 }}>{message}</p> : null}
          <button className="admin-btn-primary">{editingId ? "Update" : "Save"}</button>
        </form>
      </div>
    </div>
  );
}
