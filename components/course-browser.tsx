"use client";

import { FormEvent, useMemo, useState } from "react";

type Course = {
  id: number;
  title: string;
  description?: string | null;
  level: string | null;
  duration: string | null;
  price: number | null;
  skills?: string | null;
  training_category_id: number | null;
};

type Category = { id: number; name: string };

type Props = {
  initialCourses: Course[];
  categories: Category[];
  catalogLoadError?: string | null;
};

export function CourseBrowser({ initialCourses, categories, catalogLoadError }: Props) {
  const courseList = useMemo(
    () => (Array.isArray(initialCourses) ? initialCourses : []),
    [initialCourses]
  );
  const categoryList = useMemo(() => (Array.isArray(categories) ? categories : []), [categories]);
  const [query, setQuery] = useState("");
  const [selectedCategory, setSelectedCategory] = useState("all");
  const [selectedLevel, setSelectedLevel] = useState("all");
  const [inquiryOpen, setInquiryOpen] = useState(false);
  const [contactOpen, setContactOpen] = useState(false);
  const [inquiryLoading, setInquiryLoading] = useState(false);
  const [contactLoading, setContactLoading] = useState(false);
  const [inquiryMsg, setInquiryMsg] = useState("");
  const [contactMsg, setContactMsg] = useState("");
  const [inquiryData, setInquiryData] = useState({
    course_id: "",
    course_title: "",
    level: "",
    name: "",
    email: "",
    phone: "",
    message: "",
  });
  const [contactData, setContactData] = useState({
    name: "",
    email: "",
    phone: "",
    message: "",
  });

  function shortDescription(input: string | null | undefined, max = 200) {
    const plain = (input ?? "").replace(/<[^>]+>/g, " ").replace(/\s+/g, " ").trim();
    if (!plain) return "No description available.";
    if (plain.length <= max) return plain;
    return `${plain.slice(0, max)}...`;
  }

  const visibleCourses = useMemo(() => {
    const normalizedQuery = query.trim().toLowerCase();
    return courseList.filter((course) => {
      const categoryMatch =
        selectedCategory === "all" ||
        String(course.training_category_id ?? "") === selectedCategory;

      const levelMatch =
        selectedLevel === "all" ||
        (course.level ?? "").toLowerCase() === selectedLevel.toLowerCase();

      const queryMatch =
        normalizedQuery.length === 0 ||
        course.title.toLowerCase().includes(normalizedQuery) ||
        (course.skills ?? "").toLowerCase().includes(normalizedQuery);

      return categoryMatch && levelMatch && queryMatch;
    });
  }, [courseList, selectedCategory, selectedLevel, query]);

  function openInquiry(course: Course) {
    setInquiryMsg("");
    setInquiryData((prev) => ({
      ...prev,
      course_id: String(course.id),
      course_title: course.title,
      level: course.level ?? "",
      message: `I want to know more about ${course.title}.`,
    }));
    setInquiryOpen(true);
  }

  async function submitInquiry(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setInquiryLoading(true);
    setInquiryMsg("");

    try {
      const res = await fetch("/api/course-inquiry", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(inquiryData),
      });

      const data = (await res.json()) as { success?: boolean; popup_message?: string; message?: string };
      if (res.ok && data.success) {
        setInquiryMsg(data.popup_message ?? "Inquiry sent.");
        setInquiryOpen(false);
        setInquiryData((prev) => ({
          ...prev,
          name: "",
          email: "",
          phone: "",
        }));
        return;
      }
      setInquiryMsg(data.message ?? "Unable to submit inquiry.");
    } catch {
      setInquiryMsg("Unable to submit inquiry.");
    } finally {
      setInquiryLoading(false);
    }
  }

  async function submitContact(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setContactLoading(true);
    setContactMsg("");

    try {
      const res = await fetch("/api/contact", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(contactData),
      });
      const data = (await res.json()) as { success?: boolean; message?: string };
      setContactMsg(data.message ?? (res.ok ? "Message sent." : "Unable to send message."));
      if (res.ok) {
        setContactData({ name: "", email: "", phone: "", message: "" });
      }
    } catch {
      setContactMsg("Unable to send message.");
    } finally {
      setContactLoading(false);
    }
  }

  return (
    <div className="courses-layout">
      <aside className="filters-sidebar">
        <h4>Filters</h4>
        <div className="filter-group">
          <h5>Filter by Category:</h5>
          <label className="filter-radio">
            <input
              type="radio"
              checked={selectedCategory === "all"}
              onChange={() => setSelectedCategory("all")}
            />
            <span>All Categories</span>
          </label>
          {categoryList.map((category) => (
            <label className="filter-radio" key={category.id}>
              <input
                type="radio"
                checked={selectedCategory === String(category.id)}
                onChange={() => setSelectedCategory(String(category.id))}
              />
              <span>{category.name}</span>
            </label>
          ))}
          <h5>Filter by Level:</h5>
          {["all", "beginner", "intermediate", "advanced"].map((level) => (
            <label className="filter-radio" key={level}>
              <input
                type="radio"
                checked={selectedLevel === level}
                onChange={() => setSelectedLevel(level)}
              />
              <span>{level === "all" ? "All Levels" : level[0].toUpperCase() + level.slice(1)}</span>
            </label>
          ))}
        </div>
        <div className="search-bar" style={{ marginTop: 16 }}>
          <input
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="Search by title or skills..."
          />
        </div>
        <button className="btn-outline" style={{ marginTop: 12 }} type="button" onClick={() => setContactOpen(true)}>
          Contact Us
        </button>
        <button className="btn-free-courses" type="button" onClick={() => setSelectedCategory("all")}>
          Free Courses
        </button>
      </aside>

      {visibleCourses.length === 0 ? (
        <div style={{ paddingTop: 40, color: "#6b7280", maxWidth: 520 }}>
          {catalogLoadError ? (
            <p style={{ margin: 0, fontSize: 15, lineHeight: 1.5 }}>
              Fix the database issue shown in the <strong>yellow alert</strong> above; then refresh this page.
            </p>
          ) : courseList.length === 0 ? (
            <p style={{ margin: 0 }}>No courses available yet. Add active courses in the admin panel.</p>
          ) : (
            <p style={{ margin: 0 }}>No courses match your filters. Try clearing category, level, or search.</p>
          )}
        </div>
      ) : (
        <div className="courses-grid">
          {visibleCourses.map((course) => (
            <article className="course-card home-course-card" key={course.id}>
              <h4>{course.title}</h4>
              <p className="course-excerpt">{shortDescription(course.description)}</p>
              <p className="level-text">
                <strong>{course.level ?? "N/A"}</strong>
              </p>
              <div className="home-course-card-expanded">
                <p className="course-info">
                  <strong>Duration:</strong> {course.duration ?? "N/A"}
                </p>
                <p className="course-info">
                  <strong>Charges:</strong>{" "}
                  {Number(course.price ?? 0) === 0 ? "Free" : `£${Number(course.price).toFixed(2)}`}
                </p>
                <p className="course-info">
                  <strong>Mode:</strong> Online / Virtual
                </p>
                <div className="course-actions">
                  <a href={`/courses/${course.id}`} className="btn-primary">
                    Details
                  </a>
                  <button className="btn-outline" type="button" onClick={() => openInquiry(course)}>
                    Inquiry
                  </button>
                </div>
              </div>
            </article>
          ))}
        </div>
      )}

      {inquiryOpen && (
        <div className="modal-overlay inquiry-modal-overlay" style={{ display: "flex" }}>
          <div className="inquiry-modal-box">
            <div className="inquiry-modal-header">
              <h3 className="inquiry-modal-title">Inquiry about &quot;{inquiryData.course_title}&quot;</h3>
              <p className="inquiry-modal-subtitle">We&apos;ll get back to you within 24 hours</p>
              <button className="inquiry-close-btn" type="button" onClick={() => setInquiryOpen(false)}>
                <i className="bi bi-x-lg" />
              </button>
            </div>
            <div className="inquiry-modal-body">
              <form onSubmit={submitInquiry} className="inquiry-form">
                <div className="inquiry-form-grid">
                  <div className="inquiry-field">
                    <label>Full Name</label>
                    <input
                      placeholder="Full Name"
                      value={inquiryData.name}
                      onChange={(e) => setInquiryData((prev) => ({ ...prev, name: e.target.value }))}
                      required
                    />
                  </div>
                  <div className="inquiry-field">
                    <label>Email</label>
                    <input
                      placeholder="Email"
                      type="email"
                      value={inquiryData.email}
                      onChange={(e) => setInquiryData((prev) => ({ ...prev, email: e.target.value }))}
                      required
                    />
                  </div>
                  <div className="inquiry-field inquiry-field-full">
                    <label>Phone (Optional)</label>
                    <input
                      placeholder="Phone (optional)"
                      value={inquiryData.phone}
                      onChange={(e) => setInquiryData((prev) => ({ ...prev, phone: e.target.value }))}
                    />
                  </div>
                  <div className="inquiry-field inquiry-field-full">
                    <label>Your Message</label>
                    <textarea
                      placeholder="Your message"
                      rows={3}
                      value={inquiryData.message}
                      onChange={(e) => setInquiryData((prev) => ({ ...prev, message: e.target.value }))}
                      required
                    />
                  </div>
                </div>
                {inquiryMsg ? <p className="success-text" style={{ marginBottom: 0 }}>{inquiryMsg}</p> : null}
                <button className="inquiry-submit-btn" type="submit" disabled={inquiryLoading}>
                  {inquiryLoading ? "Sending..." : "Send Inquiry"}
                </button>
                <p className="inquiry-footer">We usually respond within 24 hours.</p>
              </form>
            </div>
          </div>
        </div>
      )}

      {contactOpen && (
        <div className="modal-overlay inquiry-modal-overlay" style={{ display: "flex" }}>
          <div className="inquiry-modal-box contact-modal-box">
            <div className="inquiry-modal-header">
              <h3 className="inquiry-modal-title">Contact Us</h3>
              <p className="inquiry-modal-subtitle">Share your query and we&apos;ll get back soon.</p>
              <button className="inquiry-close-btn" type="button" onClick={() => setContactOpen(false)}>
                <i className="bi bi-x-lg" />
              </button>
            </div>
            <div className="inquiry-modal-body">
              <form onSubmit={submitContact} className="inquiry-form">
                <div className="inquiry-form-grid contact-form-grid">
                  <div className="inquiry-field">
                    <label>Full Name</label>
                    <input
                      placeholder="Full Name"
                      value={contactData.name}
                      onChange={(e) => setContactData((prev) => ({ ...prev, name: e.target.value }))}
                      required
                    />
                  </div>
                  <div className="inquiry-field">
                    <label>Email</label>
                    <input
                      placeholder="Email"
                      type="email"
                      value={contactData.email}
                      onChange={(e) => setContactData((prev) => ({ ...prev, email: e.target.value }))}
                      required
                    />
                  </div>
                  <div className="inquiry-field inquiry-field-full">
                    <label>Phone (Optional)</label>
                    <input
                      placeholder="Phone (optional)"
                      value={contactData.phone}
                      onChange={(e) => setContactData((prev) => ({ ...prev, phone: e.target.value }))}
                    />
                  </div>
                  <div className="inquiry-field inquiry-field-full">
                    <label>Your Message</label>
                    <textarea
                      placeholder="Your message"
                      rows={3}
                      value={contactData.message}
                      onChange={(e) => setContactData((prev) => ({ ...prev, message: e.target.value }))}
                      required
                    />
                  </div>
                </div>
                {contactMsg ? <p className="success-text" style={{ marginBottom: 0 }}>{contactMsg}</p> : null}
                <button className="inquiry-submit-btn" type="submit" disabled={contactLoading}>
                  {contactLoading ? "Sending..." : "Contact Us"}
                </button>
                <p className="inquiry-footer">We usually respond within 24 hours.</p>
              </form>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
