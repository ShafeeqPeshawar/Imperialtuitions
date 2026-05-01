"use client";

import Image from "next/image";
import { useRouter } from "next/navigation";
import { useState } from "react";

type TrainingImage = { id: number; image: string; training_category_id: number };
type Category = { id: number; name: string; images: TrainingImage[] };

export function AdminTrainingGalleryManager({ initialCategories }: { initialCategories: Category[] }) {
  const router = useRouter();
  const [categories, setCategories] = useState(initialCategories);

  async function removeImage(id: number) {
    if (!window.confirm("Delete this image?")) return;
    const res = await fetch(`/api/admin/training/images/${id}`, { method: "DELETE" });
    if (!res.ok) return;
    setCategories((prev) =>
      prev.map((c) => ({
        ...c,
        images: c.images.filter((i) => i.id !== id),
      }))
    );
  }

  return (
    <div style={{ display: "grid", gap: 20 }}>
      <div className="admin-page-header">
        <h2 className="admin-page-title">Your Gallery</h2>
        <div style={{ display: "flex", gap: 10 }}>
          <button className="admin-btn-primary" onClick={() => router.push("/admin/training/categories/create")}>+ Add Category</button>
          <button className="admin-btn-sm admin-btn-edit" onClick={() => router.push("/admin/training/images/create")}>+ Add Image</button>
        </div>
      </div>
      <div className="admin-card">
        <table className="admin-table">
          <thead>
            <tr><th>Category</th><th>Images</th><th>Actions</th></tr>
          </thead>
          <tbody>
            {categories.map((cat) => (
              <tr key={cat.id}>
                <td>{cat.name}</td>
                <td>
                  {cat.images.length ? (
                    <div className="admin-thumb-grid">
                      {cat.images.map((img) => (
                        <div key={img.id} className="admin-thumb-item">
                          <Image src={`/${img.image}`} alt="Training Image" width={80} height={64} style={{ objectFit: "cover", borderRadius: 6 }} />
                          <div className="admin-thumb-actions">
                            <button className="admin-btn-sm admin-btn-edit admin-thumb-btn" onClick={() => router.push(`/admin/training/images/${img.id}/edit`)}>Edit</button>
                            <button className="admin-btn-sm admin-btn-delete admin-thumb-btn" onClick={() => removeImage(img.id)}>Delete</button>
                          </div>
                        </div>
                      ))}
                    </div>
                  ) : (
                    <span style={{ color: "#64748b", fontSize: 14 }}>No images added</span>
                  )}
                </td>
                <td>
                  <button className="admin-btn-sm admin-btn-edit" onClick={() => router.push(`/admin/training/categories/${cat.id}/edit`)}>
                    Edit Category
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
