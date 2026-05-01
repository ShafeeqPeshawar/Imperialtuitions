"use client";

import { FormEvent } from "react";

type EnrollData = {
  name: string;
  email: string;
  phone: string;
  preferred_date: string;
  preferred_time: string;
  message: string;
};

type Props = {
  open: boolean;
  onClose: () => void;
  courseTitle: string;
  level?: string;
  duration?: string;
  loading: boolean;
  message: string;
  data: EnrollData;
  setData: (updater: (prev: EnrollData) => EnrollData) => void;
  onSubmit: (event: FormEvent<HTMLFormElement>) => void;
};

export function EnrollModal({
  open,
  onClose,
  courseTitle,
  level,
  duration,
  loading,
  message,
  data,
  setData,
  onSubmit,
}: Props) {
  if (!open) return null;

  return (
    <div className="modal-overlay enroll-modal-overlay" style={{ display: "flex" }}>
      <div className="modal-box enroll-modal-box">
        <button type="button" className="enroll-close-btn" onClick={onClose} aria-label="Close">
          <i className="bi bi-x-lg" />
        </button>
        <div className="enroll-modal-header">
          <h3 className="enroll-modal-title">Enroll in &quot;{courseTitle}&quot;</h3>
          <p className="enroll-modal-subtitle">A coordinator will confirm schedule and payment details</p>
          <div className="enroll-info enroll-info-pills" style={{ display: "flex" }}>
            {duration ? (
              <span className="info-pill">
                <i className="bi bi-clock" />
                <span>{duration}</span>
              </span>
            ) : null}
            {level ? (
              <span className="info-pill">
                <i className="bi bi-bar-chart-steps" />
                <span>{level}</span>
              </span>
            ) : null}
          </div>
        </div>
        <div className="enroll-modal-body">
          <form className="enroll-form" onSubmit={onSubmit}>
            <div className="enroll-form-grid">
              <div className="enroll-field">
                <label>Full Name</label>
                <input
                  value={data.name}
                  onChange={(e) => setData((prev) => ({ ...prev, name: e.target.value }))}
                  placeholder="Your full name"
                  required
                />
              </div>
              <div className="enroll-field">
                <label>Email</label>
                <input
                  type="email"
                  value={data.email}
                  onChange={(e) => setData((prev) => ({ ...prev, email: e.target.value }))}
                  placeholder="name@email.com"
                  required
                />
              </div>
              <div className="enroll-field">
                <label>Phone (Optional)</label>
                <input
                  value={data.phone}
                  onChange={(e) => setData((prev) => ({ ...prev, phone: e.target.value }))}
                  placeholder="+1 (___) ___-____"
                />
              </div>
              <div className="enroll-field">
                <label>Preferred Date</label>
                <input
                  type="date"
                  value={data.preferred_date}
                  onChange={(e) => setData((prev) => ({ ...prev, preferred_date: e.target.value }))}
                />
              </div>
              <div className="enroll-field">
                <label>Preferred Time</label>
                <input
                  type="time"
                  value={data.preferred_time}
                  onChange={(e) => setData((prev) => ({ ...prev, preferred_time: e.target.value }))}
                />
              </div>
              <div className="enroll-field enroll-field-full">
                <label>Message (Optional)</label>
                <textarea
                  rows={3}
                  value={data.message}
                  onChange={(e) => setData((prev) => ({ ...prev, message: e.target.value }))}
                  placeholder="Any questions, goals, or corporate training details..."
                />
              </div>
            </div>
            <div className="enroll-consent">
              <label className="enroll-consent-label">
                <input type="checkbox" required className="enroll-consent-checkbox" />
                <span className="enroll-consent-text">
                  I confirm the information provided is accurate and agree that Imperial Tuitions may use it for educational and enrollment purposes.
                </span>
              </label>
            </div>
            {message ? <p className="success-text" style={{ marginBottom: 0 }}>{message}</p> : null}
            <button type="submit" className="enroll-submit-btn" disabled={loading}>
              <i className="bi bi-pencil-square" />
              {loading ? "Submitting..." : "Submit Registration"}
            </button>
            <p className="enroll-footer">By submitting, you agree to be contacted for scheduling and payment coordination.</p>
          </form>
        </div>
      </div>
    </div>
  );
}
