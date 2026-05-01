"use client";

import { FormEvent, useState } from "react";

type ResponseState = {
  open: boolean;
  title: string;
  message: string;
  isError: boolean;
};

const initialState: ResponseState = {
  open: false,
  title: "",
  message: "",
  isError: false,
};

export function NewsletterForm() {
  const [loading, setLoading] = useState(false);
  const [response, setResponse] = useState<ResponseState>(initialState);

  async function onSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    const form = event.currentTarget;
    const formData = new FormData(form);

    const payload = {
      email: String(formData.get("email") ?? ""),
      name: String(formData.get("name") ?? ""),
    };

    setLoading(true);

    try {
      const res = await fetch("/api/subscribe", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });

      if (res.ok) {
        form.reset();
        setResponse({
          open: true,
          title: "Subscription Successful",
          message:
            "Thank you for subscribing to Imperial Tuitions. Please check your email for updates and announcements.",
          isError: false,
        });
        return;
      }

      if (res.status === 409) {
        setResponse({
          open: true,
          title: "Already Subscribed",
          message: "This email is already subscribed to Imperial Tuitions.",
          isError: true,
        });
        return;
      }

      if (res.status === 422) {
        setResponse({
          open: true,
          title: "Invalid Email",
          message: "Please enter a valid email address.",
          isError: true,
        });
        return;
      }

      setResponse({
        open: true,
        title: "Something went wrong",
        message: "Please try again later.",
        isError: true,
      });
    } catch {
      setResponse({
        open: true,
        title: "Server Error",
        message: "Unable to process your request right now. Please try again later.",
        isError: true,
      });
    } finally {
      setLoading(false);
    }
  }

  return (
    <>
      <form onSubmit={onSubmit}>
        <input type="email" name="email" placeholder="Enter your email" required />
        <input type="text" name="name" placeholder="Your name" required />
        <button type="submit" className="btn-primary" disabled={loading}>
          {loading ? "Submitting..." : "Get Notified"}
        </button>
      </form>

      {response.open && (
        <div className="modal-overlay" style={{ display: "flex" }}>
          <div className="success-box" style={{ maxWidth: 460, width: "92vw" }}>
            <h3 className="success-title">{response.title}</h3>
            <p className="success-text">{response.message}</p>
            <button
              className={response.isError ? "btn-outline" : "btn-primary"}
              type="button"
              onClick={() => setResponse(initialState)}
            >
              OK
            </button>
          </div>
        </div>
      )}
    </>
  );
}
