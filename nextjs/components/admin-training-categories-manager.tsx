"use client";

import { useRouter } from "next/navigation";
import { useState } from "react";

type Category = { id: number; name: string; slug: string; sort_order: number };

export function AdminTrainingCategoriesManager({ initialCategories }: { initialCategories: Category[] }) {
  const router = useRouter();
  const [categories, setCategories] = useState(initialCategories);

  async function removeCategory(id: number) {
    if (!window.confirm("Delete this category permanently?")) return;
    const res = await fetch(`/api/admin/training/categories/${id}`, { method: "DELETE" });
    if (res.ok) {
      setCategories((prev) => prev.filter((c) => c.id !== id));
      router.refresh();
    }
  }

  return (
    <div style={{ display: "grid", gap: 20 }}>
      <div className="admin-page-header">
        <h2 className="admin-page-title">Training Categories</h2>
        <button className="admin-btn-primary" onClick={() => router.push("/admin/training/categories/create")}>
          Add Category
        </button>
      </div>
      <div className="admin-card">
        <table className="admin-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Order</th>
              <th>Slug</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            {categories.map((c) => (
              <tr key={c.id}>
                <td>{c.name}</td>
                <td>{c.sort_order}</td>
                <td>{c.slug}</td>
                <td>
                  <div className="admin-actions">
                    <button className="admin-btn-sm admin-btn-edit" onClick={() => router.push(`/admin/training/categories/${c.id}/edit`)}>
                      Edit
                    </button>
                    <button className="admin-btn-sm admin-btn-delete" onClick={() => removeCategory(c.id)}>
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
