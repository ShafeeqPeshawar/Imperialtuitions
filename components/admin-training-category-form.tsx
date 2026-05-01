"use client";

import { useRouter } from "next/navigation";
import { FormEvent, useState } from "react";

export function AdminTrainingCategoryForm({
  mode,
  category,
}: {
  mode: "create" | "edit";
  category?: { id: number; name: string; sort_order: number };
}) {
  const router = useRouter();
  const [name, setName] = useState(category?.name ?? "");
  const [sortOrder, setSortOrder] = useState(String(category?.sort_order ?? 10));
  const [message, setMessage] = useState("");

  async function onSubmit(e: FormEvent) {
    e.preventDefault();
    const url = mode === "create" ? "/api/admin/training/categories" : `/api/admin/training/categories/${category?.id}`;
    const method = mode === "create" ? "POST" : "PUT";
    const res = await fetch(url, {
      method,
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ name, sort_order: Number(sortOrder || 0) }),
    });
    const body = (await res.json()) as { message?: string };
    if (!res.ok) {
      setMessage(body.message ?? "Failed to save category.");
      return;
    }
    router.push("/admin/training/categories");
    router.refresh();
  }

  return (
    <div className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <div className="admin-card" style={{ padding: 24, maxWidth: 760 }}>
        <h2 className="admin-page-title" style={{ marginBottom: 20 }}>
          {mode === "create" ? "Add Training Category" : "Edit Training Category"}
        </h2>
        <form onSubmit={onSubmit} style={{ display: "grid", gap: 12 }}>
          <label>
            <div style={{ marginBottom: 6, fontSize: 14, fontWeight: 600 }}>Category Name</div>
            <input value={name} onChange={(e) => setName(e.target.value)} required className="w-full" style={{ width: "100%", padding: "10px 12px" }} />
          </label>
          <label>
            <div style={{ marginBottom: 6, fontSize: 14, fontWeight: 600 }}>Sort Order</div>
            <input type="number" min={0} value={sortOrder} onChange={(e) => setSortOrder(e.target.value)} style={{ width: "100%", padding: "10px 12px" }} />
          </label>
          {message ? <p style={{ color: "#b91c1c", fontSize: 14 }}>{message}</p> : null}
          <div style={{ display: "flex", gap: 10 }}>
            <button className="admin-btn-primary" type="submit">
              {mode === "create" ? "Save Category" : "Update Category"}
            </button>
            <button className="admin-btn-sm admin-btn-edit" type="button" onClick={() => router.push("/admin/training/categories")}>
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
