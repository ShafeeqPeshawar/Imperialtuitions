import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";
import { sendMail } from "@/lib/mailer";
import { enrollmentReplyEmail } from "@/lib/email-templates";

type TargetRow = { name: string; email: string; course_name: string };

async function ensureAuth() {
  const user = await getCurrentUser();
  if (!user) return null;
  return user;
}

export async function POST(request: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;
  const body = (await request.json()) as { reply_message?: string };
  const replyMessage = String(body.reply_message ?? "").trim();
  if (!replyMessage) return NextResponse.json({ success: false, message: "Reply message is required." }, { status: 422 });

  const rows = await dbQuery<TargetRow[]>("SELECT name, email, course_name FROM course_enrollments WHERE id = ? LIMIT 1", [id]);
  const row = rows[0];
  if (!row) return NextResponse.json({ success: false, message: "Enrollment not found." }, { status: 404 });

  await sendMail({
    to: row.email,
    subject: "Imperial Tuitions Training - Enrollment Update",
    html: enrollmentReplyEmail(row.name, row.course_name, replyMessage),
  });
  return NextResponse.json({ success: true, message: "Reply sent successfully." });
}
