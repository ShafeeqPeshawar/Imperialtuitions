"use client";

import { FormEvent, useMemo, useState } from "react";
import Image from "next/image";
import { useRouter } from "next/navigation";
import { RichTextEditor } from "@/components/rich-text-editor";

type Course = {
  id: number;
  title: string;
  description: string | null;
  image: string | null;
  level: string | null;
  duration: string | null;
  price: number | null;
  skills: string | null;
  sort_order: number;
  is_active: number;
  is_popular: number;
  training_category_id: number | null;
  category_name: string | null;
};

type Category = { id: number; name: string };

type FormState = {
  title: string;
  description: string;
  level: string;
  duration_value: string;
  duration_unit: string;
  price: string;
  skills: string;
  sort_order: string;
  training_category_id: string;
  is_active: boolean;
  image: File | null;
};

function emptyForm(): FormState {
  return {
    title: "",
    description: "",
    level: "",
    duration_value: "",
    duration_unit: "",
    price: "",
    skills: "",
    sort_order: "10",
    training_category_id: "",
    is_active: true,
    image: null,
  };
}

export function AdminCoursesManager({ initialCourses, categories }: { initialCourses: Course[]; categories: Category[] }) {
  const router = useRouter();
  const [courses, setCourses] = useState<Course[]>(initialCourses);
  const [selectedForPopular, setSelectedForPopular] = useState<number[]>([]);
  const [editing, setEditing] = useState<Course | null>(null);
  const [formOpen, setFormOpen] = useState(false);
  const [form, setForm] = useState<FormState>(emptyForm());
  const [message, setMessage] = useState("");

  const mode = useMemo(() => (editing ? "edit" : "create"), [editing]);

  function openCreate() {
    setEditing(null);
    setForm(emptyForm());
    setMessage("");
    setFormOpen(true);
  }

  function openEdit(course: Course) {
    setEditing(course);
    const [durationValue, durationUnit = ""] = (course.duration ?? "").split(" ");
    setForm({
      title: course.title,
      description: course.description ?? "",
      level: course.level ?? "",
      duration_value: durationValue ?? "",
      duration_unit: durationUnit.toLowerCase(),
      price: String(course.price ?? 0),
      skills: course.skills ?? "",
      sort_order: String(course.sort_order ?? 10),
      training_category_id: String(course.training_category_id ?? ""),
      is_active: course.is_active === 1,
      image: null,
    });
    setMessage("");
    setFormOpen(true);
  }

  function closeForm() {
    setFormOpen(false);
    setEditing(null);
    setForm(emptyForm());
    setMessage("");
  }

  async function refreshCourses() {
    const res = await fetch("/api/admin/courses");
    const data = (await res.json()) as { courses?: Course[] };
    setCourses(data.courses ?? []);
  }

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setMessage("");

    const data = new FormData();
    data.append("title", form.title);
    data.append("description", form.description);
    data.append("level", form.level);
    data.append("duration_value", form.duration_value);
    data.append("duration_unit", form.duration_unit);
    data.append("price", form.price);
    data.append("skills", form.skills);
    data.append("sort_order", form.sort_order);
    data.append("training_category_id", form.training_category_id);
    if (form.is_active) data.append("is_active", "1");
    if (form.image) data.append("image", form.image);

    const url = editing ? `/api/admin/courses/${editing.id}` : "/api/admin/courses";
    const method = editing ? "PUT" : "POST";
    const res = await fetch(url, { method, body: data });
    const body = (await res.json()) as { message?: string; success?: boolean };
    setMessage(body.message ?? (res.ok ? "Saved." : "Failed."));
    if (res.ok && body.success) {
      await refreshCourses();
      closeForm();
    }
  }

  async function removeCourse(id: number) {
    if (!window.confirm("Delete this course?")) return;
    const res = await fetch(`/api/admin/courses/${id}`, { method: "DELETE" });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Deleted." : "Delete failed."));
    if (res.ok) await refreshCourses();
  }

  function toggleSelected(id: number) {
    setSelectedForPopular((prev) => (prev.includes(id) ? prev.filter((x) => x !== id) : [...prev, id]));
  }

  async function makePopular() {
    const res = await fetch("/api/admin/courses/popular", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ selected_courses: selectedForPopular }),
    });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Updated." : "Failed."));
    if (res.ok) {
      setSelectedForPopular([]);
      await refreshCourses();
    }
  }

  return (
    <div style={{ display: "grid", gap: 20 }}>
      <div className="admin-page-header">
        <h2 className="admin-page-title">Courses</h2>
        <div style={{ display: "flex", gap: 8 }}>
          <button className="admin-btn-sm admin-btn-edit" type="button" onClick={() => router.push("/admin/courses/popular")}>Popular Courses</button>
          <button className="admin-btn-primary" type="button" onClick={openCreate}>+ Add Course</button>
        </div>
      </div>
      <button className="admin-btn-sm" type="button" style={{ width: "fit-content", background: "#f59e0b", color: "#111827" }} onClick={makePopular}>
        ⭐ Make Popular
      </button>

      <div className="admin-card">
        <table className="admin-table">
          <thead>
            <tr>
              <th>Image</th><th>Course</th><th>Level</th><th>Category</th><th>Duration</th><th>Price</th><th>Sort Order</th><th>Status</th><th>Select</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {courses.map((course) => (
              <tr key={course.id}>
                <td>
                  {course.image ? (
                    <Image src={`/images/${course.image}`} className="admin-course-img" alt="" width={56} height={56} />
                  ) : null}
                </td>
                <td><div className="admin-cell-title">{course.title}</div></td>
                <td>{course.level ?? "–"}</td>
                <td>{course.category_name ?? "Uncategorized"}</td>
                <td>{course.duration ?? "–"}</td>
                <td>{Number(course.price ?? 0) === 0 ? "Free" : `£${Number(course.price).toFixed(2)}`}</td>
                <td>{course.sort_order}</td>
                <td>
                  {course.is_active === 1 ? (
                    <span className="admin-badge admin-badge-active">Active</span>
                  ) : (
                    <span className="admin-badge admin-badge-inactive">Inactive</span>
                  )}
                </td>
                <td>
                  <input type="checkbox" checked={selectedForPopular.includes(course.id)} onChange={() => toggleSelected(course.id)} />
                </td>
                <td>
                  <div className="admin-actions">
                    <button className="admin-btn-sm admin-btn-edit" type="button" onClick={() => router.push(`/admin/courses/${course.id}/topics`)}>Topics</button>
                    <button className="admin-btn-sm admin-btn-edit" type="button" onClick={() => openEdit(course)}>Edit</button>
                    <button className="admin-btn-sm admin-btn-delete" type="button" onClick={() => removeCourse(course.id)}>Delete</button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {formOpen ? (
        <div className="modal-overlay admin-modal-shell">
          <div className="modal-box admin-modal-box" style={{ maxWidth: 760 }}>
            <button className="close-btn" type="button" onClick={closeForm} aria-label="Close">
              ×
            </button>
            <div className="registration-card admin-modal-content">
              <h3 className="reg-title">{mode === "create" ? "Add New Course" : `Edit Course: ${editing?.title}`}</h3>
              <p className="reg-subtitle">Fill in course details and save changes.</p>
              <form className="admin-course-form" onSubmit={onSubmit} style={{ display: "grid", gap: 12, maxHeight: "68vh", overflowY: "auto", paddingRight: 4 }}>
                <div style={{ display: "grid", gap: 6 }}>
                  <label style={{ fontWeight: 600, color: "#0f172a" }}>Course Title</label>
                  <input placeholder="Enter course title" value={form.title} onChange={(e) => setForm((p) => ({ ...p, title: e.target.value }))} required />
                </div>
                <div style={{ display: "grid", gap: 6 }}>
                  <label style={{ fontWeight: 600, color: "#0f172a" }}>Category</label>
                  <select value={form.training_category_id} onChange={(e) => setForm((p) => ({ ...p, training_category_id: e.target.value }))}>
                    <option value="">Select Category</option>
                    {categories.map((c) => <option key={c.id} value={c.id}>{c.name}</option>)}
                  </select>
                </div>
                <div style={{ display: "grid", gap: 6 }}>
                  <label style={{ fontWeight: 600, color: "#0f172a" }}>Course Description</label>
                  <RichTextEditor
                    value={form.description}
                    onChange={(value) => setForm((p) => ({ ...p, description: value }))}
                    placeholder="Enter course description"
                  />
                </div>
                <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 10 }}>
                  <div style={{ display: "grid", gap: 6 }}>
                    <label style={{ fontWeight: 600, color: "#0f172a" }}>Level</label>
                    <select value={form.level} onChange={(e) => setForm((p) => ({ ...p, level: e.target.value }))} required>
                      <option value="">Select Level</option>
                      <option value="Beginner">Beginner</option>
                      <option value="Intermediate">Intermediate</option>
                      <option value="Advanced">Advanced</option>
                    </select>
                  </div>
                  <div style={{ display: "grid", gap: 6 }}>
                    <label style={{ fontWeight: 600, color: "#0f172a" }}>Price</label>
                    <input type="number" step="0.01" placeholder="0.00" value={form.price} onChange={(e) => setForm((p) => ({ ...p, price: e.target.value }))} required />
                  </div>
                </div>
                <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 10 }}>
                  <div style={{ display: "grid", gap: 6 }}>
                    <label style={{ fontWeight: 600, color: "#0f172a" }}>Duration Value</label>
                    <input type="number" min={1} placeholder="e.g. 12" value={form.duration_value} onChange={(e) => setForm((p) => ({ ...p, duration_value: e.target.value }))} required />
                  </div>
                  <div style={{ display: "grid", gap: 6 }}>
                    <label style={{ fontWeight: 600, color: "#0f172a" }}>Duration Unit</label>
                    <select value={form.duration_unit} onChange={(e) => setForm((p) => ({ ...p, duration_unit: e.target.value }))} required>
                      <option value="">Select Unit</option>
                      <option value="hours">Hours</option><option value="days">Days</option><option value="weeks">Weeks</option><option value="months">Months</option>
                    </select>
                  </div>
                </div>
                <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 10 }}>
                  <div style={{ display: "grid", gap: 6 }}>
                    <label style={{ fontWeight: 600, color: "#0f172a" }}>Skills</label>
                    <input placeholder="Comma separated skills" value={form.skills} onChange={(e) => setForm((p) => ({ ...p, skills: e.target.value }))} />
                  </div>
                  <div style={{ display: "grid", gap: 6 }}>
                    <label style={{ fontWeight: 600, color: "#0f172a" }}>Sort Order</label>
                    <input type="number" placeholder="10" value={form.sort_order} onChange={(e) => setForm((p) => ({ ...p, sort_order: e.target.value }))} />
                  </div>
                </div>
                <div style={{ display: "grid", gap: 6 }}>
                  <label style={{ fontWeight: 600, color: "#0f172a" }}>Course Image</label>
                  <input type="file" accept=".png,.jpg,.jpeg,.webp" onChange={(e) => setForm((p) => ({ ...p, image: e.target.files?.[0] ?? null }))} />
                </div>
                <label style={{ display: "flex", alignItems: "center", gap: 8, fontWeight: 600, color: "#0f172a" }}>
                  <input type="checkbox" checked={form.is_active} onChange={(e) => setForm((p) => ({ ...p, is_active: e.target.checked }))} />
                  Active Course
                </label>
                {message ? <p style={{ color: "#334155", fontSize: 14 }}>{message}</p> : null}
                <div className="admin-modal-actions">
                  <button className="admin-btn-primary" type="submit">{mode === "create" ? "Save Course" : "Update Course"}</button>
                  <button className="admin-btn-sm admin-btn-delete" type="button" onClick={closeForm}>Cancel</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      ) : null}
    </div>
  );
}
