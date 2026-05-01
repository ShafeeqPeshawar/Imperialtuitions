import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { requireUser } from "@/lib/api-auth";
import { sendMail } from "@/lib/mailer";
import { inquiryReplyEmail } from "@/lib/email-templates";

type InquiryRow = { id: number; course_title: string; name: string; email: string };


export async function POST(request: Request, { params }: { params: Promise<{ id: string }> }) {
  const auth = await requireUser();
  if (!auth.ok) return auth.response;
  const { id } = await params;
  const body = (await request.json()) as { reply_message?: string };
  const replyMessage = String(body.reply_message ?? "").trim();
  if (!replyMessage) return NextResponse.json({ success: false, message: "Reply message is required." }, { status: 422 });

  const rows = await dbQuery<InquiryRow[]>("SELECT id, course_title, name, email FROM course_inquiries WHERE id = ? LIMIT 1", [id]);
  const row = rows[0];
  if (!row) return NextResponse.json({ success: false, message: "Inquiry not found." }, { status: 404 });

  await sendMail({
    to: row.email,
    subject: `Imperial Tuitions - Inquiry Response: ${row.course_title}`,
    html: inquiryReplyEmail(row.name, row.course_title, replyMessage),
  });

  await dbQuery("UPDATE course_inquiries SET reply_status = 'replied', updated_at = NOW() WHERE id = ?", [id]);
  return NextResponse.json({ success: true, message: "Reply sent successfully." });
}
