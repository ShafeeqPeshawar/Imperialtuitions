import { NextResponse } from "next/server";
import { dbQuery } from "@/lib/db";
import { getCurrentUser } from "@/lib/auth";
import { sendMail } from "@/lib/mailer";

type TargetRow = { name: string; email: string; course_name: string };

async function ensureAuth() {
  const user = await getCurrentUser();
  if (!user) return null;
  return user;
}

export async function POST(_: Request, { params }: { params: Promise<{ id: string }> }) {
  const user = await ensureAuth();
  if (!user) return NextResponse.json({ success: false, message: "Unauthorized." }, { status: 401 });
  const { id } = await params;

  await dbQuery("UPDATE course_enrollments SET status = 'approved', updated_at = NOW() WHERE id = ?", [id]);
  const rows = await dbQuery<TargetRow[]>("SELECT name, email, course_name FROM course_enrollments WHERE id = ? LIMIT 1", [id]);
  const row = rows[0];
  if (row?.email) {
    await sendMail({
      to: row.email,
      subject: "Imperial Tuitions Training - Enrollment Approved",
      html: `<p>Dear ${row.name},</p><p>Your enrollment for <strong>${row.course_name}</strong> has been approved.</p>`,
    });
  }
  return NextResponse.json({ success: true, message: "Enrollment approved." });
}
