"use client";

import { useRouter } from "next/navigation";
import { FormEvent, useState } from "react";
import Image from "next/image";

type Category = { id: number; name: string };

export function AdminTrainingImageForm({
  mode,
  categories,
  image,
}: {
  mode: "create" | "edit";
  categories: Category[];
  image?: { id: number; training_category_id: number; image: string };
}) {
  const router = useRouter();
  const [categoryId, setCategoryId] = useState(String(image?.training_category_id ?? ""));
  const [file, setFile] = useState<File | null>(null);
  const [message, setMessage] = useState("");

  async function onSubmit(e: FormEvent) {
    e.preventDefault();
    const fd = new FormData();
    fd.append("category_id", categoryId);
    if (file) fd.append("image", file);
    const url = mode === "create" ? "/api/admin/training/images" : `/api/admin/training/images/${image?.id}`;
    const method = mode === "create" ? "POST" : "PUT";
    const res = await fetch(url, { method, body: fd });
    const body = (await res.json()) as { message?: string };
    if (!res.ok) {
      setMessage(body.message ?? "Failed to save image.");
      return;
    }
    router.push("/admin/training");
    router.refresh();
  }

  return (
    <div className="container" style={{ paddingTop: 28, paddingBottom: 40 }}>
      <div className="admin-card" style={{ padding: 24, maxWidth: 760 }}>
        <h2 className="admin-page-title" style={{ marginBottom: 20 }}>
          {mode === "create" ? "Add Training Image" : "Edit Training Image"}
        </h2>
        <form onSubmit={onSubmit} style={{ display: "grid", gap: 12 }}>
          <label>
            <div style={{ marginBottom: 6, fontSize: 14, fontWeight: 600 }}>Training Category</div>
            <select value={categoryId} onChange={(e) => setCategoryId(e.target.value)} required style={{ width: "100%", padding: "10px 12px" }}>
              <option value="">-- Select Category --</option>
              {categories.map((c) => (
                <option key={c.id} value={c.id}>
                  {c.name}
                </option>
              ))}
            </select>
          </label>
          {mode === "edit" && image ? (
            <div>
              <div style={{ marginBottom: 6, fontSize: 14, fontWeight: 600 }}>Current Image</div>
              <Image src={`/${image.image}`} alt="Training Image" width={160} height={100} style={{ borderRadius: 8, border: "1px solid #e2e8f0", height: "auto" }} />
            </div>
          ) : null}
          <label>
            <div style={{ marginBottom: 6, fontSize: 14, fontWeight: 600 }}>{mode === "create" ? "Training Image" : "Change Image (optional)"}</div>
            <input type="file" accept="image/png,image/jpeg,image/webp" required={mode === "create"} onChange={(e) => setFile(e.target.files?.[0] ?? null)} />
          </label>
          {message ? <p style={{ color: "#b91c1c", fontSize: 14 }}>{message}</p> : null}
          <div style={{ display: "flex", gap: 10 }}>
            <button className="admin-btn-primary" type="submit">
              {mode === "create" ? "Upload Image" : "Update Image"}
            </button>
            <button className="admin-btn-sm admin-btn-edit" type="button" onClick={() => router.push("/admin/training")}>
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
