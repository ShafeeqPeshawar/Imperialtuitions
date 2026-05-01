import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";
import { sendMail } from "@/lib/mailer";
import { subscriberBroadcastEmail } from "@/lib/email-templates";

type SubscriberRow = { email: string };

async function ensureAuth() {
  const user = await getCurrentUser();
  if (!user) return null;
  return user;
}

export async function POST(request: Request) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const body = (await request.json()) as { emails?: string[]; message?: string };
  const emails = Array.isArray(body.emails) ? body.emails : [];
  const message = String(body.message ?? "").trim();
  if (emails.length === 0 || !message) {
    return NextResponse.json({ success: false, message: "Emails and message are required." }, { status: 422 });
  }

  const placeholders = emails.map(() => "?").join(",");
  const subscribers = await dbQuery<SubscriberRow[]>(
    `SELECT email FROM subscribers WHERE email IN (${placeholders})`,
    emails
  );

  await Promise.all(
    subscribers.map((s) =>
      sendMail({
        to: s.email,
        subject: "Message from Imperial Tuitions",
        html: subscriberBroadcastEmail("Subscriber", message),
      })
    )
  );

  return NextResponse.json({ success: true, message: "Message sent successfully" });
}
