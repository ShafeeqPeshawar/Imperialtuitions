"use client";

import { useState } from "react";

type Course = { id: number; title: string; is_popular: number };

export function AdminPopularCoursesManager({ initialCourses }: { initialCourses: Course[] }) {
  const [courses, setCourses] = useState(initialCourses);
  const [selected, setSelected] = useState<number[]>([]);
  const [message, setMessage] = useState("");

  function toggle(id: number) {
    setSelected((prev) => (prev.includes(id) ? prev.filter((x) => x !== id) : [...prev, id]));
  }

  async function removeSelected() {
    const res = await fetch("/api/admin/courses/popular", {
      method: "DELETE",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ selected_courses: selected }),
    });
    const body = (await res.json()) as { message?: string };
    setMessage(body.message ?? (res.ok ? "Updated." : "Failed."));
    if (res.ok) {
      setCourses((prev) => prev.filter((c) => !selected.includes(c.id)));
      setSelected([]);
    }
  }

  return (
    <div style={{ display: "grid", gap: 20 }}>
      <h2 className="admin-page-title">Popular Courses</h2>
      {message ? <p style={{ color: "#334155", fontSize: 14 }}>{message}</p> : null}
      <button className="admin-btn-primary" style={{ width: "fit-content" }} onClick={removeSelected}>
        ⭐ Remove From Popular
      </button>
      <div className="admin-card">
        <table className="admin-table">
          <thead>
            <tr>
              <th>Course</th>
              <th>Select</th>
            </tr>
          </thead>
          <tbody>
            {courses.map((course) => (
              <tr key={course.id}>
                <td>{course.title}</td>
                <td>
                  <input type="checkbox" checked={selected.includes(course.id)} onChange={() => toggle(course.id)} />
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
