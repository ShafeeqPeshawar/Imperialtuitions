import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { requireUser } from "@/lib/api-auth";
import { isMailerConfigured, sendMail } from "@/lib/mailer";
import { subscriberBroadcastEmail } from "@/lib/email-templates";

type SubscriberRow = { email: string };


function normalizeEmails(raw: unknown): string[] {
  if (!Array.isArray(raw)) return [];
  const set = new Set<string>();
  for (const item of raw) {
    const e = typeof item === "string" ? item.trim().toLowerCase() : "";
    if (e) set.add(e);
  }
  return [...set];
}

export async function POST(request: Request) {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;

  if (!isMailerConfigured()) {
    return NextResponse.json(
      {
        success: false,
        message:
          "Email is not configured. Set MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD in .env at the project root and restart the server.",
      },
      { status: 503 }
    );
  }

  const body = (await request.json()) as { emails?: string[]; message?: string };
  const requestedKeys = normalizeEmails(body.emails);
  const message = String(body.message ?? "").trim();
  if (requestedKeys.length === 0 || !message) {
    return NextResponse.json({ success: false, message: "Emails and message are required." }, { status: 422 });
  }

  const placeholders = requestedKeys.map(() => "?").join(",");
  const subscribers = await dbQuery<SubscriberRow[]>(
    `SELECT email FROM subscribers WHERE LOWER(TRIM(email)) IN (${placeholders})`,
    requestedKeys
  );

  if (subscribers.length === 0) {
    return NextResponse.json(
      {
        success: false,
        message:
          "No matching subscribers found in the database. Try refreshing the page and selecting again.",
      },
      { status: 422 }
    );
  }

  let sent = 0;
  const errors: string[] = [];

  for (const row of subscribers) {
    try {
      const ok = await sendMail({
        to: row.email,
        subject: "Message from Imperial Tuitions",
        html: subscriberBroadcastEmail("Subscriber", message),
      });
      if (ok) sent++;
      else errors.push(`${row.email}: mail transport returned no send`);
    } catch (err) {
      const msg = err instanceof Error ? err.message : "send failed";
      console.error("Subscriber broadcast send error:", row.email, err);
      errors.push(`${row.email}: ${msg}`);
    }
  }

  if (sent === 0) {
    return NextResponse.json(
      {
        success: false,
        message: `Could not send any emails. ${errors.slice(0, 3).join("; ")}${errors.length > 3 ? "…" : ""}`,
      },
      { status: 502 }
    );
  }

  const partial =
    sent < subscribers.length
      ? ` Sent ${sent} of ${subscribers.length}. Some failed: ${errors.slice(0, 2).join("; ")}`
      : "";

  return NextResponse.json({
    success: true,
    message: `Message sent successfully to ${sent} subscriber${sent === 1 ? "" : "s"}.${partial}`,
    sent,
    attempted: subscribers.length,
  });
}
