"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";
import { RichTextEditor } from "@/components/rich-text-editor";

export function AdminTopicEditForm({
  topic,
}: {
  topic: { id: number; course_id: number; title: string; description: string | null; sort_order: number; is_active: number };
}) {
  const router = useRouter();
  const [title, setTitle] = useState(topic.title);
  const [description, setDescription] = useState(topic.description ?? "");
  const [sortOrder, setSortOrder] = useState(String(topic.sort_order));
  const [isActive, setIsActive] = useState(topic.is_active === 1);
  const [message, setMessage] = useState("");

  async function save() {
    const res = await fetch(`/api/admin/topics/${topic.id}`, {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        title,
        description,
        sort_order: Number(sortOrder || 0),
        is_active: isActive,
      }),
    });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Updated." : "Failed."));
    if (res.ok) {
      router.push(`/admin/courses/${topic.course_id}/topics`);
      router.refresh();
    }
  }

  return (
    <div className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <div className="admin-card" style={{ padding: 24, maxWidth: 760, display: "grid", gap: 12 }}>
        <h2 className="admin-page-title">Edit Topic</h2>
        <input value={title} onChange={(e) => setTitle(e.target.value)} />
        <RichTextEditor value={description} onChange={setDescription} />
        <input type="number" value={sortOrder} onChange={(e) => setSortOrder(e.target.value)} />
        <label style={{ display: "flex", gap: 8, alignItems: "center" }}>
          <input type="checkbox" checked={isActive} onChange={(e) => setIsActive(e.target.checked)} />
          Active
        </label>
        {message ? <p style={{ color: "#334155", fontSize: 14 }}>{message}</p> : null}
        <div style={{ display: "flex", gap: 8 }}>
          <button className="admin-btn-primary" onClick={save}>Update Topic</button>
          <button className="admin-btn-sm admin-btn-edit" onClick={() => router.push(`/admin/courses/${topic.course_id}/topics`)}>
            Cancel
          </button>
        </div>
      </div>
    </div>
  );
}
