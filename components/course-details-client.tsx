"use client";

import { FormEvent, useState } from "react";
import { EnrollModal } from "@/components/enroll-modal";

type Topic = {
  id: number;
  title: string;
  description: string | null;
};

type Course = {
  id: number;
  title: string;
  description: string | null;
  level: string | null;
  duration: string | null;
  price: number | null;
  topics: Topic[];
};

type RelatedCourse = {
  id: number;
  title: string;
  description: string | null;
  level: string | null;
  duration: string | null;
  price: number | null;
};

type Props = {
  course: Course;
  relatedCourses: RelatedCourse[];
};

type Html2PdfInstance = {
  set: (options: unknown) => Html2PdfInstance;
  from: (source: HTMLElement) => Html2PdfInstance;
  save: () => Promise<void>;
};

type Html2PdfFactory = () => Html2PdfInstance;

function cleanRichText(html: string | null | undefined) {
  if (!html) return "";
  return html
    .replace(/<script[\s\S]*?>[\s\S]*?<\/script>/gi, "")
    .replace(/<style[\s\S]*?>[\s\S]*?<\/style>/gi, "")
    .replace(/\son\w+="[^"]*"/gi, "")
    .replace(/\son\w+='[^']*'/gi, "");
}

function textOnly(html: string | null | undefined) {
  if (!html) return "";
  return cleanRichText(html).replace(/<[^>]+>/g, " ").replace(/\s+/g, " ").trim();
}

function buildPrintableCourseHtml(course: Course) {
  const topicsHtml = course.topics.length
    ? course.topics
        .map((topic, index) => {
          const desc = cleanRichText(topic.description);
          return `
            <div style="margin-bottom: 16px;">
              <h3 style="font-size: 20px; margin: 0 0 8px;">${index + 1}. ${topic.title}</h3>
              <div class="pdf-rich" style="font-size: 14px; color: #334155;">${desc || "<p>No description</p>"}</div>
            </div>
          `;
        })
        .join("")
    : "<p>No topics added yet.</p>";

  return `
    <style>
      .pdf-root { padding: 26px; background: #ffffff; color: #0f172a; font-family: Arial, sans-serif; line-height: 1.5; width: 100%; max-width: 760px; box-sizing: border-box; }
      .pdf-rich, .pdf-rich * {
        max-width: 100% !important;
        box-sizing: border-box !important;
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: anywhere !important;
      }
      .pdf-rich p { margin: 0 0 10px; line-height: 1.55; }
      .pdf-rich ul, .pdf-rich ol { margin: 0 0 10px 20px; padding: 0; }
      .pdf-rich img, .pdf-rich table { max-width: 100% !important; height: auto !important; }
      .pdf-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 12px 0 20px;
      }
      .pdf-pill {
        display: inline-flex;
        align-items: center;
        border: 1px solid #86efac;
        border-radius: 999px;
        background: linear-gradient(180deg, #ffffff, #f0fdf4);
        color: #14532d;
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.1px;
        line-height: 1.2;
        box-shadow: 0 1px 0 rgba(255, 255, 255, 0.8) inset, 0 3px 8px rgba(21, 128, 61, 0.12);
      }
      @media print {
        body { margin: 0; }
        .pdf-root { max-width: 100%; padding: 0; }
      }
    </style>
    <div class="pdf-root">
      <h1 style="font-size: 34px; margin: 0 0 14px;">${course.title}</h1>
      <div class="pdf-rich" style="font-size: 15px; color: #334155; margin-bottom: 18px;">${cleanRichText(course.description) || "<p>No description available.</p>"}</div>
      <div class="pdf-meta">
        <span class="pdf-pill">Level: ${course.level ?? "N/A"}</span>
        <span class="pdf-pill">Duration: ${course.duration ?? "N/A"}</span>
        <span class="pdf-pill">Mode: Online / Virtual</span>
        <span class="pdf-pill">Charges: ${Number(course.price ?? 0) === 0 ? "Free" : `£${Number(course.price).toFixed(2)}`}</span>
      </div>
      <h2 style="font-size: 26px; margin: 0 0 12px;">What you will learn?</h2>
      ${topicsHtml}
    </div>
  `;
}

export function CourseDetailsClient({ course, relatedCourses }: Props) {
  const selectedLevel = (course.level ?? "").toLowerCase();
  const [inquiryOpen, setInquiryOpen] = useState(false);
  const [enrollOpen, setEnrollOpen] = useState(false);
  const [inquiryLoading, setInquiryLoading] = useState(false);
  const [enrollLoading, setEnrollLoading] = useState(false);
  const [status, setStatus] = useState("");

  const [inquiryData, setInquiryData] = useState({
    name: "",
    email: "",
    phone: "",
    message: `I want to know more about ${course.title}.`,
  });

  const [enrollData, setEnrollData] = useState({
    name: "",
    email: "",
    phone: "",
    preferred_date: "",
    preferred_time: "",
    message: "",
  });

  async function submitInquiry(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setInquiryLoading(true);
    setStatus("");
    try {
      const res = await fetch("/api/course-inquiry", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          course_id: course.id,
          course_title: course.title,
          level: selectedLevel || course.level || "",
          ...inquiryData,
        }),
      });
      const data = (await res.json()) as { success?: boolean; popup_message?: string; message?: string };
      setStatus(data.popup_message ?? data.message ?? (res.ok ? "Inquiry sent." : "Unable to send inquiry."));
      if (res.ok && data.success) {
        setInquiryOpen(false);
        setInquiryData((prev) => ({
          ...prev,
          name: "",
          email: "",
          phone: "",
        }));
      }
    } catch {
      setStatus("Unable to send inquiry.");
    } finally {
      setInquiryLoading(false);
    }
  }

  async function submitEnroll(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setEnrollLoading(true);
    setStatus("");
    try {
      const res = await fetch("/api/course-enroll", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          course_id: course.id,
          course_name: course.title,
          level: selectedLevel || course.level || "",
          ...enrollData,
        }),
      });
      const data = (await res.json()) as { popup_message?: string; message?: string };
      setStatus(data.popup_message ?? data.message ?? (res.ok ? "Enrollment submitted." : "Unable to submit enrollment."));
      if (res.ok) {
        setEnrollOpen(false);
      }
    } catch {
      setStatus("Unable to submit enrollment.");
    } finally {
      setEnrollLoading(false);
    }
  }

  async function downloadCoursePdfOnly() {
    const container = document.createElement("div");
    container.innerHTML = buildPrintableCourseHtml(course);

    document.body.appendChild(container);

    try {
      const html2pdfModule = await import("html2pdf.js");
      const html2pdf = (html2pdfModule.default ?? html2pdfModule) as unknown as Html2PdfFactory;
      await html2pdf()
        .set({
          margin: [0.5, 0.4, 0.6, 0.4],
          filename: `${course.title.replace(/[\\/:*?"<>|]+/g, "").trim() || "course"}.pdf`,
          image: { type: "jpeg", quality: 0.98 },
          html2canvas: { scale: 1.4, useCORS: true, backgroundColor: "#ffffff" },
          jsPDF: { unit: "in", format: "a4", orientation: "portrait" },
          pagebreak: { mode: ["css", "legacy"] },
        })
        .from(container)
        .save();
    } finally {
      container.remove();
    }
  }

  function printCourseOnly() {
    const printWindow = window.open("", "_blank", "width=900,height=700");
    if (!printWindow) return;
    printWindow.document.open();
    printWindow.document.write(`
      <!doctype html>
      <html>
        <head>
          <meta charset="utf-8" />
          <title>${course.title}</title>
        </head>
        <body>
          ${buildPrintableCourseHtml(course)}
        </body>
      </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
  }

  async function shareCourse() {
    const url = typeof window !== "undefined" ? window.location.href : "";
    const shareData = {
      title: course.title,
      text: `Check out this course: ${course.title}`,
      url,
    };

    try {
      if (navigator.share) {
        await navigator.share(shareData);
        return;
      }
      if (navigator.clipboard?.writeText) {
        await navigator.clipboard.writeText(url);
        setStatus("Course link copied to clipboard.");
        return;
      }
      setStatus("Sharing is not supported on this device.");
    } catch {
      setStatus("Unable to share right now.");
    }
  }

  return (
    <>
      <section className="course-details-page">
        <div className="container">
          <div className="course-detail-hero">
            <h1>{course.title}</h1>
            <div className="course-hero-main">
              <div className="course-hero-left">
                <div className="course-hero-description">
                  {course.description ? (
                    <div
                      className="rich-content"
                      dangerouslySetInnerHTML={{ __html: cleanRichText(course.description) }}
                    />
                  ) : (
                    <p>No description available.</p>
                  )}
                </div>
                <div className="course-actions">
                  <button className="btn-primary course-register-btn" type="button" onClick={() => setEnrollOpen(true)}>Reigter</button>
                  <button className="btn-primary course-inquiry-btn" type="button" onClick={() => setInquiryOpen(true)}>Inquiry</button>
                  <button className="btn-primary" type="button" onClick={downloadCoursePdfOnly}>PDF</button>
                  <button className="btn-primary" type="button" onClick={printCourseOnly}>Print</button>
                  <button className="btn-primary" type="button" onClick={shareCourse}>Share</button>
                </div>
              </div>
              <aside className="course-snapshot-card">
                <h3>Course Snapshot</h3>
                <p className="course-snapshot-price">
                  {Number(course.price ?? 0) === 0 ? "Free" : `£${Number(course.price).toFixed(2)}`}
                </p>
                <div className="course-meta">
                  <span className="course-meta-pill"><i className="bi bi-bar-chart-steps" />Level: {course.level ?? "N/A"}</span>
                  <span className="course-meta-pill"><i className="bi bi-clock" />Duration: {course.duration ?? "N/A"}</span>
                  <span className="course-meta-pill"><i className="bi bi-laptop" />Mode: Online / Virtual</span>
                </div>
              </aside>
            </div>
          </div>

          <div className="course-detail-layout">
            <div className="course-main">
              <h2>What you will learn?</h2>
              <div className="course-topics">
                {course.topics.length === 0 ? (
                  <p>No topics added yet.</p>
                ) : (
                  course.topics.map((topic, index) => (
                    <article className="topic-card" key={topic.id}>
                      <h3>{index + 1}. {topic.title}</h3>
                      {topic.description ? (
                        <div
                          className="rich-content"
                          dangerouslySetInnerHTML={{ __html: cleanRichText(topic.description) }}
                        />
                      ) : (
                        <p>No description</p>
                      )}
                    </article>
                  ))
                )}
              </div>
            </div>

            <aside className="course-sidebar">
              <h3>Courses you may also like</h3>
              <div className="course-sidebar-grid">
                {relatedCourses.map((item) => (
                  <article className="course-card" key={item.id}>
                    <h4>{item.title}</h4>
                    <p className="course-excerpt">{textOnly(item.description).slice(0, 120) || "No description available."}</p>
                    <span className="course-level-badge">{item.level ?? "Beginner"}</span>
                    <p className="course-info"><strong>Level:</strong> {item.level ?? "N/A"}</p>
                    <p className="course-info"><strong>Duration:</strong> {item.duration ?? "N/A"}</p>
                    <p className="course-info"><strong>Charges:</strong> {Number(item.price ?? 0) === 0 ? "Free" : `£${Number(item.price).toFixed(2)}`}</p>
                    <div className="course-actions">
                      <a href={`/courses/${item.id}`} className="btn-primary">Details</a>
                      <button className="btn-outline" type="button" onClick={() => setInquiryOpen(true)}>Inquiry</button>
                    </div>
                  </article>
                ))}
              </div>
            </aside>
          </div>
        </div>
      </section>

      {inquiryOpen && (
        <div className="modal-overlay inquiry-modal-overlay" style={{ display: "flex" }}>
          <div className="inquiry-modal-box">
            <div className="inquiry-modal-header">
              <h3 className="inquiry-modal-title">Inquiry about &quot;{course.title}&quot;</h3>
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
                      required
                      value={inquiryData.name}
                      onChange={(e) => setInquiryData((p) => ({ ...p, name: e.target.value }))}
                    />
                  </div>
                  <div className="inquiry-field">
                    <label>Email</label>
                    <input
                      type="email"
                      placeholder="Email"
                      required
                      value={inquiryData.email}
                      onChange={(e) => setInquiryData((p) => ({ ...p, email: e.target.value }))}
                    />
                  </div>
                  <div className="inquiry-field inquiry-field-full">
                    <label>Phone (Optional)</label>
                    <input
                      placeholder="Phone (optional)"
                      value={inquiryData.phone}
                      onChange={(e) => setInquiryData((p) => ({ ...p, phone: e.target.value }))}
                    />
                  </div>
                  <div className="inquiry-field inquiry-field-full">
                    <label>Your Message</label>
                    <textarea
                      rows={3}
                      placeholder="Your message"
                      required
                      value={inquiryData.message}
                      onChange={(e) => setInquiryData((p) => ({ ...p, message: e.target.value }))}
                    />
                  </div>
                </div>
                {status ? <p className="success-text" style={{ marginBottom: 0 }}>{status}</p> : null}
                <button className="inquiry-submit-btn" type="submit" disabled={inquiryLoading}>
                  {inquiryLoading ? "Sending..." : "Send Inquiry"}
                </button>
                <p className="inquiry-footer">We usually respond within 24 hours.</p>
              </form>
            </div>
          </div>
        </div>
      )}

      <EnrollModal
        open={enrollOpen}
        onClose={() => setEnrollOpen(false)}
        courseTitle={course.title}
        level={selectedLevel || course.level || ""}
        duration={course.duration || ""}
        loading={enrollLoading}
        message={status}
        data={enrollData}
        setData={setEnrollData}
        onSubmit={submitEnroll}
      />
    </>
  );
}
