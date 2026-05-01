"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { RichTextEditor } from "@/components/rich-text-editor";

type Topic = {
  id: number;
  course_id: number;
  title: string;
  description: string | null;
  sort_order: number;
  is_active: number;
};

export function AdminCourseTopicsManager({
  course,
  initialTopics,
}: {
  course: { id: number; title: string };
  initialTopics: Topic[];
}) {
  const router = useRouter();
  const [topics, setTopics] = useState(initialTopics);
  const [title, setTitle] = useState("");
  const [description, setDescription] = useState("");
  const [sortOrder, setSortOrder] = useState(String((initialTopics.at(-1)?.sort_order ?? 0) + 10));
  const [isActive, setIsActive] = useState(true);
  const [message, setMessage] = useState("");

  async function addTopic() {
    const res = await fetch(`/api/admin/courses/${course.id}/topics`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        title,
        description,
        sort_order: Number(sortOrder || 0),
        is_active: isActive,
      }),
    });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Saved." : "Failed."));
    if (res.ok) {
      setTitle("");
      setDescription("");
      const list = await fetch(`/api/admin/courses/${course.id}/topics`).then((r) => r.json() as Promise<{ topics: Topic[] }>);
      setTopics(list.topics ?? []);
    }
  }

  async function deleteTopic(id: number) {
    if (!window.confirm("Delete this topic permanently?")) return;
    const res = await fetch(`/api/admin/topics/${id}`, { method: "DELETE" });
    if (res.ok) setTopics((prev) => prev.filter((t) => t.id !== id));
  }

  return (
    <div style={{ display: "grid", gap: 18 }}>
      <div className="admin-page-header">
        <h2 className="admin-page-title">
          Manage Topics
          <span style={{ display: "block", fontSize: 13, color: "#64748b", fontWeight: 400 }}>{course.title}</span>
        </h2>
        <button className="admin-btn-sm admin-btn-edit" onClick={() => router.push("/admin/courses")}>
          ← Back to Courses
        </button>
      </div>

      <div className="admin-card" style={{ padding: 20, display: "grid", gap: 10 }}>
        <h3 style={{ marginBottom: 6 }}>Add New Topic</h3>
        <input placeholder="Topic Title" value={title} onChange={(e) => setTitle(e.target.value)} />
        <RichTextEditor value={description} onChange={setDescription} placeholder="Description" />
        <div style={{ display: "grid", gridTemplateColumns: "1fr auto", gap: 10 }}>
          <input type="number" value={sortOrder} onChange={(e) => setSortOrder(e.target.value)} placeholder="Sort Order" />
          <label style={{ display: "flex", alignItems: "center", gap: 6 }}>
            <input type="checkbox" checked={isActive} onChange={(e) => setIsActive(e.target.checked)} /> Active
          </label>
        </div>
        <button className="admin-btn-primary" onClick={addTopic}>Add Topic</button>
        {message ? <p style={{ color: "#334155", fontSize: 14 }}>{message}</p> : null}
      </div>

      <div className="admin-card">
        <table className="admin-table">
          <thead>
            <tr><th>Title</th><th>Description</th><th>Sort</th><th>Status</th><th>Actions</th></tr>
          </thead>
          <tbody>
            {topics.map((topic) => (
              <tr key={topic.id}>
                <td>{topic.title}</td>
                <td>{topic.description?.replace(/<[^>]+>/g, "").slice(0, 120) || "No description"}</td>
                <td>{topic.sort_order}</td>
                <td>{topic.is_active ? "Active" : "Inactive"}</td>
                <td>
                  <div className="admin-actions">
                    <button className="admin-btn-sm admin-btn-edit" onClick={() => router.push(`/admin/topics/${topic.id}/edit`)}>
                      Edit
                    </button>
                    <button className="admin-btn-sm admin-btn-delete" onClick={() => deleteTopic(topic.id)}>
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
